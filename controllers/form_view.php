<?php
// Public form viewer — no login required
// $slug set by router

$stmt = $pdo->prepare("
    SELECT f.*, u.name AS owner_name
    FROM user_forms f
    INNER JOIN users u ON u.id = f.user_id
    WHERE f.slug = ? AND f.active = 1 AND f.deleted_at IS NULL
    LIMIT 1
");
$stmt->execute([$slug]);
$form = $stmt->fetch();

if (!$form) {
    http_response_code(404);
    require __DIR__ . '/../views/404.php';
    exit;
}

$stmtFields = $pdo->prepare("SELECT * FROM form_fields WHERE form_id = ? ORDER BY sort_order ASC, id ASC");
$stmtFields->execute([$form['id']]);
$fields = $stmtFields->fetchAll();

$submitted = false;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect field values
    $data = [];
    foreach ($fields as $field) {
        $key = 'field_' . $field['id'];
        $val = trim($_POST[$key] ?? '');
        if ($field['required'] && $val === '') {
            $formError = 'Por favor completa todos los campos obligatorios.';
            break;
        }
        $data[$field['label']] = $val;
    }

    if (!$formError) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $pdo->prepare("INSERT INTO form_responses (form_id, data, ip_address, created_at) VALUES (?,?,?,NOW())")
            ->execute([$form['id'], json_encode($data), $ip]);
        $submitted = true;
    }
}

require __DIR__ . '/../views/form_public.php';
