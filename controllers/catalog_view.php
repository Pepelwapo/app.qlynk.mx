<?php
// Public catalog viewer — no login required
// $slug set by router

$stmt = $pdo->prepare("
    SELECT c.*, u.name AS owner_name
    FROM catalogs c
    INNER JOIN users u ON u.id = c.user_id
    WHERE c.slug = ? AND c.active = 1 AND c.deleted_at IS NULL
    LIMIT 1
");
$stmt->execute([$slug]);
$catalog = $stmt->fetch();

if (!$catalog) {
    http_response_code(404);
    require __DIR__ . '/../views/404.php';
    exit;
}

// Load categories
$stmtCat = $pdo->prepare("SELECT * FROM catalog_categories WHERE catalog_id = ? ORDER BY sort_order ASC, id ASC");
$stmtCat->execute([$catalog['id']]);
$categories = $stmtCat->fetchAll();

// Load all items
$stmtItems = $pdo->prepare("
    SELECT ci.*, cc.name AS category_name
    FROM catalog_items ci
    LEFT JOIN catalog_categories cc ON cc.id = ci.category_id
    WHERE ci.catalog_id = ? AND ci.active = 1
    ORDER BY ci.sort_order ASC, ci.id ASC
");
$stmtItems->execute([$catalog['id']]);
$items = $stmtItems->fetchAll();

// Group items by category_id
$itemsByCategory = [];
foreach ($items as $item) {
    $key = $item['category_id'] ?? 0;
    $itemsByCategory[$key][] = $item;
}

require __DIR__ . '/../views/catalog_public.php';
