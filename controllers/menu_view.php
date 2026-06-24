<?php
// Public menu viewer — no login required
// $slug set by router

$stmt = $pdo->prepare("
    SELECT m.*, u.name AS owner_name
    FROM menus m
    INNER JOIN users u ON u.id = m.user_id
    WHERE m.slug = ? AND m.active = 1 AND m.deleted_at IS NULL
    LIMIT 1
");
$stmt->execute([$slug]);
$menu = $stmt->fetch();

if (!$menu) {
    http_response_code(404);
    require __DIR__ . '/../views/404.php';
    exit;
}

// Load sections + items
$stmtSec = $pdo->prepare("SELECT * FROM menu_sections WHERE menu_id = ? ORDER BY sort_order ASC, id ASC");
$stmtSec->execute([$menu['id']]);
$sections = $stmtSec->fetchAll();

foreach ($sections as &$sec) {
    $stmtItems = $pdo->prepare("SELECT * FROM menu_items WHERE section_id = ? AND active = 1 ORDER BY sort_order ASC, id ASC");
    $stmtItems->execute([$sec['id']]);
    $sec['items'] = $stmtItems->fetchAll();
}
unset($sec);

require __DIR__ . '/../views/menu_public.php';
