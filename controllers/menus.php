<?php

require_login();

$stmt = $pdo->prepare("
    SELECT * FROM menus
    WHERE user_id = ? AND deleted_at IS NULL
    ORDER BY id DESC
");
$stmt->execute([$_SESSION['user_id']]);
$menus = $stmt->fetchAll();

require __DIR__ . '/../views/menus.php';
