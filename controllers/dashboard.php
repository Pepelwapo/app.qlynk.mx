<?php

require_login();

// Usuario + plan en una sola query
$stmt = $pdo->prepare("
    SELECT
        u.*,
        p.name            AS plan_name,
        p.price           AS plan_price,
        p.qr_dynamic_limit,
        p.qr_static_limit,
        p.scans_limit,
        p.collections_limit,
        p.forms_limit
    FROM users u
    INNER JOIN plans p ON p.id = u.plan_id
    WHERE u.id = ?
    LIMIT 1
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// QR activos del usuario
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM qr_codes
    WHERE user_id = ?
    AND deleted_at IS NULL
");
$stmt->execute([$_SESSION['user_id']]);
$qrCount = (int)$stmt->fetchColumn();

// Escaneos totales
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(scan_count), 0)
    FROM qr_codes
    WHERE user_id = ?
    AND deleted_at IS NULL
");
$stmt->execute([$_SESSION['user_id']]);
$totalScans = (int)$stmt->fetchColumn();

// Días restantes de trial — calculados desde la DB, sin hardcodeo
$trialDaysLeft  = 0;
$totalTrialDays = 0;
if (!empty($user['trial_end'])) {
    $diff          = strtotime($user['trial_end']) - time();
    $trialDaysLeft = max(0, (int)ceil($diff / 86400));

    // Duración total del trial = trial_end - created_at (en días)
    if (!empty($user['created_at'])) {
        $totalTrialDays = (int)ceil(
            (strtotime($user['trial_end']) - strtotime($user['created_at'])) / 86400
        );
        $totalTrialDays = max(1, $totalTrialDays); // nunca dividir entre 0
    }
}

require __DIR__ . '/../views/dashboard.php';
