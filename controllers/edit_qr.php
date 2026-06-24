<?php

require_login();

$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    header('Location: /qrs');
    exit;
}

// Buscar QR del usuario
$stmt = $pdo->prepare("
    SELECT * FROM qr_codes
    WHERE id = ? AND user_id = ? AND deleted_at IS NULL
    LIMIT 1
");
$stmt->execute([$id, $_SESSION['user_id']]);
$qr = $stmt->fetch();

if (!$qr) {
    header('Location: /qrs');
    exit;
}

// Parsear target_url → campos individuales (para pre-poblar el form)
$fields = qr_parse_target_url($qr['type'], $qr['target_url'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página.';
        require __DIR__ . '/../views/edit_qr.php';
        exit;
    }

    $type = trim($_POST['type'] ?? $qr['type']);
    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        $error = 'El nombre del QR es obligatorio.';
        require __DIR__ . '/../views/edit_qr.php';
        exit;
    }

    $target_url = qr_build_target_url($type, $_POST);

    if (empty($target_url)) {
        $error = 'Completa los campos requeridos para este tipo de QR.';
        require __DIR__ . '/../views/edit_qr.php';
        exit;
    }

    $pdo->prepare("
        UPDATE qr_codes
        SET name       = ?,
            target_url = ?,
            type       = ?,
            updated_at = NOW()
        WHERE id = ? AND user_id = ?
    ")->execute([$name, $target_url, $type, $id, $_SESSION['user_id']]);

    header('Location: /qrs/edit?id=' . $id . '&saved=1');
    exit;
}

if (!empty($_GET['saved'])) {
    $saved = true;
}

require __DIR__ . '/../views/edit_qr.php';

// ════════════════════════════════════════════════════════════════
// Helpers compartidos: build y parse de target_url por tipo
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
            $title = trim($post['ev_title']       ?? '');
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

function qr_parse_target_url(string $type, string $target_url): array
{
    $f = [];
    switch ($type) {
        case 'url':
            $f['url']        = $target_url; break;
        case 'pdf':
            $f['pdf_url']    = $target_url; break;
        case 'social':
            $f['social_url'] = $target_url; break;
        case 'whatsapp':
            if (preg_match('#wa\.me/(\d+)(?:\?text=(.*))?$#i', $target_url, $m)) {
                $f['wa_phone']   = '+' . $m[1];
                $f['wa_message'] = isset($m[2]) ? urldecode($m[2]) : '';
            }
            break;
        case 'email':
            if (preg_match('#^mailto:([^?]+)(?:\?(.*))?$#i', $target_url, $m)) {
                $f['em_email'] = $m[1];
                if (!empty($m[2])) {
                    parse_str($m[2], $p);
                    $f['em_subject'] = $p['subject'] ?? '';
                    $f['em_body']    = $p['body']    ?? '';
                }
            }
            break;
        case 'phone':
            $f['phone_number'] = str_replace('tel:', '', $target_url);
            break;
        case 'sms':
            if (preg_match('#^sms:([^?]+)(?:\?body=(.*))?$#i', $target_url, $m)) {
                $f['sms_phone']   = $m[1];
                $f['sms_message'] = isset($m[2]) ? urldecode($m[2]) : '';
            }
            break;
        case 'wifi':
            if (preg_match('#WIFI:S:(.*?);T:(.*?);P:(.*?);;#', $target_url, $m)) {
                $f['wifi_ssid']     = $m[1];
                $f['wifi_security'] = $m[2];
                $f['wifi_password'] = $m[3];
            }
            break;
        case 'vcard':
            foreach ([
                'vc_name'    => '/^FN:(.+)/m',
                'vc_phone'   => '/^TEL:(.+)/m',
                'vc_email'   => '/^EMAIL:(.+)/m',
                'vc_company' => '/^ORG:(.+)/m',
                'vc_website' => '/^URL:(.+)/m',
            ] as $key => $pat) {
                if (preg_match($pat, $target_url, $m)) $f[$key] = trim($m[1]);
            }
            break;
        case 'event':
            foreach ([
                'ev_title'       => '/^SUMMARY:(.+)/m',
                'ev_location'    => '/^LOCATION:(.+)/m',
                'ev_description' => '/^DESCRIPTION:(.+)/m',
            ] as $key => $pat) {
                if (preg_match($pat, $target_url, $m)) $f[$key] = trim($m[1]);
            }
            foreach (['ev_start' => '/^DTSTART:(\d{8})T(\d{6})/m',
                      'ev_end'   => '/^DTEND:(\d{8})T(\d{6})/m'] as $key => $pat) {
                if (preg_match($pat, $target_url, $m)) {
                    $d = $m[1]; $t = $m[2];
                    $f[$key] = substr($d,0,4).'-'.substr($d,4,2).'-'.substr($d,6,2)
                             . 'T'.substr($t,0,2).':'.substr($t,2,2);
                }
            }
            break;
    }
    return $f;
}
