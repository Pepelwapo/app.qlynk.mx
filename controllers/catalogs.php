<?php

require_login();

$stmt = $pdo->prepare("
    SELECT * FROM catalogs
    WHERE user_id = ? AND deleted_at IS NULL
    ORDER BY id DESC
");
$stmt->execute([$_SESSION['user_id']]);
$catalogs = $stmt->fetchAll();

require __DIR__ . '/../views/catalogs.php';
