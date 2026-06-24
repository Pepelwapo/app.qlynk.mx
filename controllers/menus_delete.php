<?php

require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: /menus'); exit; }

$pdo->prepare("
    UPDATE menus SET deleted_at = NOW()
    WHERE id = ? AND user_id = ?
")->execute([$id, $_SESSION['user_id']]);

header('Location: /menus');
exit;
