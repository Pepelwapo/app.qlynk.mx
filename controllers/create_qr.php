<?php

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página e intenta de nuevo.';
        require __DIR__ . '/../views/create_qr.php';
        exit;
    }

    // Verificar límite del plan
    $stmt = $pdo->prepare("
        SELECT u.*, p.qr_dynamic_limit
        FROM users u
        INNER JOIN plans p ON p.id = u.plan_id
        WHERE u.id = ? LIMIT 1
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $userPlan = $stmt->fetch();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM qr_codes
        WHERE user_id = ? AND deleted_at IS NULL
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $currentCount = (int)$stmt->fetchColumn();

    if ($currentCount >= (int)$userPlan['qr_dynamic_limit']) {
        $error = 'Has alcanzado el límite de QR dinámicos de tu plan.';
        require __DIR__ . '/../views/create_qr.php';
        exit;
    }

    $type = trim($_POST['type'] ?? 'url');
    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        $error = 'El nombre del QR es obligatorio.';
        require __DIR__ . '/../views/create_qr.php';
        exit;
    }

    // ── PDF: subir archivo si fue provisto ──────────────────────────
    $pdfUploadUrl = '';
    if ($type === 'pdf' && !empty($_FILES['pdf_file']['name'])) {
        $file     = $_FILES['pdf_file'];
        $maxBytes = 800 * 1024; // 800 KB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Error al subir el archivo PDF. Intenta de nuevo.';
            require __DIR__ . '/../views/create_qr.php';
            exit;
        }
        if ($file['size'] > $maxBytes) {
            $error = 'El PDF supera el límite de 800 KB. Comprime el archivo e intenta de nuevo.';
            require __DIR__ . '/../views/create_qr.php';
            exit;
        }

        // Verificar tipo MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if ($mime !== 'application/pdf') {
            $error = 'El archivo debe ser un PDF válido.';
            require __DIR__ . '/../views/create_qr.php';
            exit;
        }

        // Límite de archivos PDF por usuario (máximo 10 por usuario)
        $uploadDir = __DIR__ . '/../../uploads/pdfs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $existingFiles = glob($uploadDir . 'u' . (int)$_SESSION['user_id'] . '_*.pdf');
        if (count($existingFiles) >= 10) {
            $error = 'Límite de 10 PDFs alcanzado. Elimina alguno antes de subir otro.';
            require __DIR__ . '/../views/create_qr.php';
            exit;
        }

        // Guardar con nombre único
        $newName = 'u' . (int)$_SESSION['user_id'] . '_' . bin2hex(random_bytes(8)) . '.pdf';
        $destPath = $uploadDir . $newName;
        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            $error = 'No se pudo guardar el archivo. Contacta soporte.';
            require __DIR__ . '/../views/create_qr.php';
            exit;
        }

        $pdfUploadUrl = APP_APP_URL . '/uploads/pdfs/' . $newName;
        // Inyectar en $_POST para que qr_build_target_url lo use
        $_POST['pdf_url'] = $pdfUploadUrl;
    }

    $target_url = qr_build_target_url($type, $_POST);

    if (empty($target_url)) {
        $error = 'Completa los campos requeridos para este tipo de QR.';
        require __DIR__ . '/../views/create_qr.php';
        exit;
    }

    // Short code único
    do {
        $shortCode = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $stmt = $pdo->prepare("SELECT id FROM qr_codes WHERE short_code = ? LIMIT 1");
        $stmt->execute([$shortCode]);
    } while ($stmt->fetch());

    $expires   = trim($_POST['expires_at'] ?? '');
    $expiresAt = (!empty($expires)) ? date('Y-m-d H:i:s', strtotime($expires)) : null;

    $pdo->prepare("
        INSERT INTO qr_codes (user_id, name, short_code, target_url, type, expires_at)
        VALUES (?, ?, ?, ?, ?, ?)
    ")->execute([
        $_SESSION['user_id'],
        $name,
        $shortCode,
        $target_url,
        $type,
        $expiresAt
    ]);

    header('Location: /qrs?created=1');
    exit;
}

require __DIR__ . '/../views/create_qr.php';

// ════════════════════════════════════════════════════════════════
// Helper: construir target_url según tipo de QR
// ════════════════════════════════════════════════════════════════

function qr_build_target_url($type, $post) {
    switch ($type) {
        case 'url':
            return trim($post['url'] ?? '');

        case 'whatsapp':
            $phone = preg_replace('/\D/', '', trim($post['wa_phone'] ?? ''));
            $msg   = trim($post['wa_message'] ?? '');
            if (empty($phone)) return '';
            return 'https://wa.me/' . $phone . ($msg ? '?text=' . urlencode($msg) : '');

        case 'email':
            $em   = trim($post['em_email']   ?? '');
            $subj = trim($post['em_subject'] ?? '');
            $body = trim($post['em_body']    ?? '');
            if (empty($em)) return '';
            $q = [];
            if ($subj) $q[] = 'subject=' . urlencode($subj);
            if ($body) $q[] = 'body='    . urlencode($body);
            return 'mailto:' . $em . ($q ? '?' . implode('&', $q) : '');

        case 'phone':
            $ph = trim($post['phone_number'] ?? '');
            return $ph ? 'tel:' . $ph : '';

        case 'sms':
            $ph  = trim($post['sms_phone']   ?? '');
            $msg = trim($post['sms_message'] ?? '');
            if (empty($ph)) return '';
            return 'sms:' . $ph . ($msg ? '?body=' . urlencode($msg) : '');

        case 'wifi':
            $ssid = trim($post['wifi_ssid']     ?? '');
            $pass = trim($post['wifi_password'] ?? '');
            $sec  = trim($post['wifi_security'] ?? 'WPA');
            if (empty($ssid)) return '';
            return 'WIFI:S:' . $ssid . ';T:' . $sec . ';P:' . $pass . ';;';

        case 'vcard':
            $n = trim($post['vc_name'] ?? '');
            if (empty($n)) return '';
            return "BEGIN:VCARD\r\nVERSION:3.0\r\nFN:{$n}"
                . "\r\nTEL:"   . trim($post['vc_phone']   ?? '')
                . "\r\nEMAIL:" . trim($post['vc_email']   ?? '')
                . "\r\nORG:"   . trim($post['vc_company'] ?? '')
                . "\r\nURL:"   . trim($post['vc_website'] ?? '')
                . "\r\nEND:VCARD";

        case 'pdf':
            return trim($post['pdf_url'] ?? '');

        case 'social':
            return trim($post['social_url'] ?? '');

        case 'event':
            $title = trim($post['ev_title'] ?? '');
            if (empty($title)) return '';
            $start = trim($post['ev_start']       ?? '');
            $end   = trim($post['ev_end']         ?? '');
            $loc   = trim($post['ev_location']    ?? '');
            $desc  = trim($post['ev_description'] ?? '');
            $fmtDt = function($dt) { return str_replace(['-', ':', 'T'], '', $dt) . '00Z'; };
            return "BEGIN:VEVENT\r\nSUMMARY:{$title}"
                . "\r\nDTSTART:{$fmtDt($start)}"
                . "\r\nDTEND:{$fmtDt($end)}"
                . "\r\nLOCATION:{$loc}"
                . "\r\nDESCRIPTION:{$desc}"
                . "\r\nEND:VEVENT";

        default:
            return trim($post['url'] ?? '');
    }
}
