<?php

require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: /forms'); exit; }

// Verify ownership
$stmt = $pdo->prepare("SELECT * FROM user_forms WHERE id = ? AND user_id = ? AND deleted_at IS NULL LIMIT 1");
$stmt->execute([$id, $_SESSION['user_id']]);
$form = $stmt->fetch();
if (!$form) { header('Location: /forms'); exit; }

// Load fields (for column headers)
$stmtFields = $pdo->prepare("SELECT * FROM form_fields WHERE form_id = ? ORDER BY sort_order ASC, id ASC");
$stmtFields->execute([$id]);
$fields = $stmtFields->fetchAll();

// Load responses
$stmtResp = $pdo->prepare("SELECT * FROM form_responses WHERE form_id = ? ORDER BY id DESC");
$stmtResp->execute([$id]);
$responses = $stmtResp->fetchAll();

// Decode JSON data
foreach ($responses as &$resp) {
    $resp['data_decoded'] = json_decode($resp['data'] ?? '{}', true) ?? [];
}
unset($resp);

require __DIR__ . '/../views/form_responses.php';
