<?php

require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: /catalogs'); exit; }

$stmt = $pdo->prepare("SELECT * FROM catalogs WHERE id = ? AND user_id = ? AND deleted_at IS NULL LIMIT 1");
$stmt->execute([$id, $_SESSION['user_id']]);
$catalog = $stmt->fetch();
if (!$catalog) { header('Location: /catalogs'); exit; }

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página.';
    } else {
        $action = trim($_POST['_action'] ?? 'save_meta');

        switch ($action) {

            case 'save_meta':
                $name  = trim($_POST['name']        ?? '');
                $desc  = trim($_POST['description'] ?? '');
                $slug  = trim($_POST['slug']        ?? '');
                $validTpl  = ['classic', 'dark', 'cards'];
                $template  = in_array(trim($_POST['template'] ?? ''), $validTpl) ? trim($_POST['template']) : 'classic';
                $waEnabled = !empty($_POST['whatsapp_enabled']) ? 1 : 0;
                $waPhone   = trim($_POST['whatsapp_phone'] ?? '');
                if (empty($name) || empty($slug)) {
                    $error = 'Nombre y slug son obligatorios.';
                } elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
                    $error = 'El slug solo puede tener letras minúsculas, números y guiones.';
                } else {
                    $chk = $pdo->prepare("SELECT id FROM catalogs WHERE slug = ? AND id != ?");
                    $chk->execute([$slug, $id]);
                    if ($chk->fetch()) {
                        $error = 'Ese slug ya está en uso.';
                    } else {
                        $pdo->prepare("UPDATE catalogs SET name=?,description=?,slug=?,template=?,whatsapp_enabled=?,whatsapp_phone=? WHERE id=? AND user_id=?")
                            ->execute([$name, $desc, $slug, $template, $waEnabled, $waPhone, $id, $_SESSION['user_id']]);
                        $success = 'Catálogo actualizado.';
                        $stmt = $pdo->prepare("SELECT * FROM catalogs WHERE id = ? LIMIT 1");
                        $stmt->execute([$id]);
                        $catalog = $stmt->fetch();
                    }
                }
                break;

            case 'add_category':
                $cname = trim($_POST['category_name'] ?? '');
                if (!empty($cname)) {
                    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM catalog_categories WHERE catalog_id = ?");
                    $cntStmt->execute([$id]);
                    $cnt = (int)$cntStmt->fetchColumn();
                    $pdo->prepare("INSERT INTO catalog_categories (catalog_id, name, sort_order) VALUES (?,?,?)")
                        ->execute([$id, $cname, $cnt]);
                    $success = 'Categoría agregada.';
                }
                break;

            case 'del_category':
                $cid = (int)($_POST['category_id'] ?? 0);
                if ($cid) {
                    $pdo->prepare("UPDATE catalog_items SET category_id = NULL WHERE category_id = ?")->execute([$cid]);
                    $pdo->prepare("DELETE FROM catalog_categories WHERE id = ? AND catalog_id = ?")->execute([$cid, $id]);
                    $success = 'Categoría eliminada.';
                }
                break;

            case 'add_item':
                $cid    = (int)($_POST['category_id']  ?? 0) ?: null;
                $iname  = trim($_POST['item_name']     ?? '');
                $idesc  = trim($_POST['item_desc']     ?? '');
                $price  = trim($_POST['item_price']    ?? '');
                $sku    = trim($_POST['item_sku']      ?? '');
                $imgUrl = trim($_POST['item_image_url'] ?? '');
                if (!empty($iname)) {
                    $priceVal = ($price !== '') ? (float)$price : null;
                    $imgVal   = !empty($imgUrl) ? $imgUrl : null;
                    $cntStmt  = $pdo->prepare("SELECT COUNT(*) FROM catalog_items WHERE catalog_id = ?");
                    $cntStmt->execute([$id]);
                    $cnt = (int)$cntStmt->fetchColumn();
                    try {
                        $pdo->prepare("INSERT INTO catalog_items (catalog_id, category_id, name, description, image_url, price, sku, active, sort_order) VALUES (?,?,?,?,?,?,?,1,?)")
                            ->execute([$id, $cid, $iname, $idesc, $imgVal, $priceVal, $sku ?: null, $cnt]);
                    } catch (Exception $e) {
                        // image_url column may not exist yet
                        $pdo->prepare("INSERT INTO catalog_items (catalog_id, category_id, name, description, price, sku, active, sort_order) VALUES (?,?,?,?,?,?,1,?)")
                            ->execute([$id, $cid, $iname, $idesc, $priceVal, $sku ?: null, $cnt]);
                    }
                    $success = 'Producto agregado.';
                }
                break;

            case 'del_item':
                $iid = (int)($_POST['item_id'] ?? 0);
                if ($iid) {
                    $pdo->prepare("DELETE FROM catalog_items WHERE id = ? AND catalog_id = ?")->execute([$iid, $id]);
                    $success = 'Producto eliminado.';
                }
                break;
        }
    }
}

// Load categories
$stmtCat = $pdo->prepare("SELECT * FROM catalog_categories WHERE catalog_id = ? ORDER BY sort_order ASC, id ASC");
$stmtCat->execute([$id]);
$categories = $stmtCat->fetchAll();

// Load items with category name
$stmtItems = $pdo->prepare("
    SELECT ci.*, cc.name AS category_name
    FROM catalog_items ci
    LEFT JOIN catalog_categories cc ON cc.id = ci.category_id
    WHERE ci.catalog_id = ?
    ORDER BY ci.sort_order ASC, ci.id ASC
");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

$created = !empty($_GET['created']);

require __DIR__ . '/../views/edit_catalog.php';
