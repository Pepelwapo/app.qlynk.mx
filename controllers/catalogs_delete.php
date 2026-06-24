<?php

require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: /catalogs'); exit; }

$pdo->prepare("
    UPDATE catalogs SET deleted_at = NOW()
    WHERE id = ? AND user_id = ?
")->execute([$id, $_SESSION['user_id']]);

header('Location: /catalogs');
exit;
