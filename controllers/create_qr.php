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

    $pdo->prepare("
        INSERT INTO qr_codes (user_id, name, short_code, target_url, type)
        VALUES (?, ?, ?, ?, ?)
    ")->execute([
        $_SESSION['user_id'],
        $name,
        $shortCode,
        $target_url,
        $type
    ]);

    header('Location: /qrs?created=1');
    exit;
}

require __DIR__ . '/../views/create_qr.php';

// ════════════════════════════════════════════════════════════════
// Helper: construir target_url según tipo de QR
// ════════════════════════════════════════════════════════════════

function qr_build_target_url(string $type, array $post): string
{
    switch ($type) {
        case 'url':
            return trim($post['url'] ?? '');
        case 'whatsapp': {
            $phone = preg_replace('/\D/', '', trim($post['wa_phone'] ?? ''));
            $msg   = trim($post['wa_message'] ?? '');
            if (empty($phone)) return '';
            return 'https://wa.me/' . $phone . ($msg ? '?text=' . urlencode($msg) : '');
        }
        case 'email': {
            $em   = trim($post['em_email']   ?? '');
            $subj = trim($post['em_subject'] ?? '');
            $body = trim($post['em_body']    ?? '');
            if (empty($em)) return '';
            $q = [];
            if ($subj) $q[] = 'subject=' . urlencode($subj);
            if ($body) $q[] = 'body='    . urlencode($body);
            return 'mailto:' . $em . ($q ? '?' . implode('&', $q) : '');
        }
        case 'phone':
            return ($ph = trim($post['phone_number'] ?? '')) ? 'tel:' . $ph : '';
        case 'sms': {
            $ph  = trim($post['sms_phone']   ?? '');
            $msg = trim($post['sms_message'] ?? '');
            if (empty($ph)) return '';
            return 'sms:' . $ph . ($msg ? '?body=' . urlencode($msg) : '');
        }
        case 'wifi': {
            $ssid = trim($post['wifi_ssid']     ?? '');
            $pass = trim($post['wifi_password'] ?? '');
            $sec  = trim($post['wifi_security'] ?? 'WPA');
            if (empty($ssid)) return '';
            return 'WIFI:S:' . $ssid . ';T:' . $sec . ';P:' . $pass . ';;';
        }
        case 'vcard': {
            $n = trim($post['vc_name'] ?? '');
            if (empty($n)) return '';
            return "BEGIN:VCARD\r\nVERSION:3.0\r\nFN:{$n}"
                . "\r\nTEL:"   . trim($post['vc_phone']   ?? '')
                . "\r\nEMAIL:" . trim($post['vc_email']   ?? '')
                . "\r\nORG:"   . trim($post['vc_company'] ?? '')
                . "\r\nURL:"   . trim($post['vc_website'] ?? '')
                . "\r\nEND:VCARD";
        }
        case 'pdf':
            return trim($post['pdf_url']    ?? '');
        case 'social':
            return trim($post['social_url'] ?? '');
        case 'event': {
            $title = trim($post['ev_title'] ?? '');
            if (empty($title)) return '';
            $start = trim($post['ev_start']       ?? '');
            $end   = trim($post['ev_end']         ?? '');
            $loc   = trim($post['ev_location']    ?? '');
            $desc  = trim($post['ev_description'] ?? '');
            $fmtDt = fn($dt) => str_replace(['-', ':', 'T'], '', $dt) . '00Z';
            return "BEGIN:VEVENT\r\nSUMMARY:{$title}"
                . "\r\nDTSTART:{$fmtDt($start)}"
                . "\r\nDTEND:{$fmtDt($end)}"
                . "\r\nLOCATION:{$loc}"
                . "\r\nDESCRIPTION:{$desc}"
                . "\r\nEND:VEVENT";
        }
        default:
            return trim($post['url'] ?? '');
    }
}
