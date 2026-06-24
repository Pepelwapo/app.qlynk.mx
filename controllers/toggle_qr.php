<?php

require_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
UPDATE qr_codes
SET active = IF(active=1,0,1)
WHERE id = ?
AND user_id = ?
");

$stmt->execute([
    $id,
    $_SESSION['user_id']
]);

header('Location: /qrs');
exit;