<?php

require_login();

$uid = (int)$_SESSION['user_id'];

// User + plan
$stmt = $pdo->prepare("
    SELECT u.*, p.name AS plan_name, p.price AS plan_price,
           p.qr_dynamic_limit, p.qr_static_limit, p.scans_limit,
           p.collections_limit, p.forms_limit
    FROM users u
    INNER JOIN plans p ON p.id = u.plan_id
    WHERE u.id = ?
    LIMIT 1
");
$stmt->execute([$uid]);
$user = $stmt->fetch();

// Usage counts — all prepared
$s = $pdo->prepare("SELECT COUNT(*) FROM qr_codes WHERE user_id = ? AND deleted_at IS NULL");
$s->execute([$uid]);
$qrDynamic = (int)$s->fetchColumn();

$s = $pdo->prepare("SELECT COALESCE(SUM(scan_count),0) FROM qr_codes WHERE user_id = ? AND deleted_at IS NULL");
$s->execute([$uid]);
$totalScans = (int)$s->fetchColumn();

$s = $pdo->prepare("SELECT COUNT(*) FROM menus WHERE user_id = ? AND deleted_at IS NULL");
$s->execute([$uid]);
$menuCount = (int)$s->fetchColumn();

$s = $pdo->prepare("SELECT COUNT(*) FROM catalogs WHERE user_id = ? AND deleted_at IS NULL");
$s->execute([$uid]);
$catalogCount = (int)$s->fetchColumn();

$s = $pdo->prepare("SELECT COUNT(*) FROM user_forms WHERE user_id = ? AND deleted_at IS NULL");
$s->execute([$uid]);
$formCount = (int)$s->fetchColumn();

// Trial days left
$trialDaysLeft = 0;
if (!empty($user['trial_end'])) {
    $diff = strtotime($user['trial_end']) - time();
    $trialDaysLeft = max(0, (int)ceil($diff / 86400));
}

require __DIR__ . '/../views/billing.php';
