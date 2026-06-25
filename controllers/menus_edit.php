<?php

require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: /menus'); exit; }

// Verify ownership
$stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ? AND user_id = ? AND deleted_at IS NULL LIMIT 1");
$stmt->execute([$id, $_SESSION['user_id']]);
$menu = $stmt->fetch();
if (!$menu) { header('Location: /menus'); exit; }

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
                $color = trim($_POST['color']       ?? '#e74c3c');
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
                    $chk = $pdo->prepare("SELECT id FROM menus WHERE slug = ? AND id != ?");
                    $chk->execute([$slug, $id]);
                    if ($chk->fetch()) {
                        $error = 'Ese slug ya está en uso.';
                    } else {
                        $pdo->prepare("UPDATE menus SET name=?,description=?,slug=?,color=?,template=?,whatsapp_enabled=?,whatsapp_phone=? WHERE id=? AND user_id=?")
                            ->execute([$name, $desc, $slug, $color, $template, $waEnabled, $waPhone, $id, $_SESSION['user_id']]);
                        $success = 'Menú actualizado.';
                        // Refresh menu data
                        $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ? LIMIT 1");
                        $stmt->execute([$id]);
                        $menu = $stmt->fetch();
                    }
                }
                break;

            case 'add_section':
                $sname = trim($_POST['section_name'] ?? '');
                if (!empty($sname)) {
                    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM menu_sections WHERE menu_id = ?");
                    $cntStmt->execute([$id]);
                    $cnt = (int)$cntStmt->fetchColumn();
                    $pdo->prepare("INSERT INTO menu_sections (menu_id, name, sort_order) VALUES (?,?,?)")
                        ->execute([$id, $sname, $cnt]);
                    $success = 'Sección agregada.';
                }
                break;

            case 'del_section':
                $sid = (int)($_POST['section_id'] ?? 0);
                if ($sid) {
                    $pdo->prepare("DELETE FROM menu_items WHERE section_id = ?")->execute([$sid]);
                    $pdo->prepare("DELETE FROM menu_sections WHERE id = ? AND menu_id = ?")->execute([$sid, $id]);
                    $success = 'Sección eliminada.';
                }
                break;

            case 'add_item':
                $sid      = (int)($_POST['section_id']   ?? 0);
                $iname    = trim($_POST['item_name']     ?? '');
                $idesc    = trim($_POST['item_desc']     ?? '');
                $price    = trim($_POST['item_price']    ?? '');
                $imgUrl   = trim($_POST['item_image_url'] ?? '');
                if ($sid && !empty($iname)) {
                    $priceVal = ($price !== '') ? (float)$price : null;
                    $imgVal   = !empty($imgUrl) ? $imgUrl : null;
                    $cntStmt  = $pdo->prepare("SELECT COUNT(*) FROM menu_items WHERE section_id = ?");
                    $cntStmt->execute([$sid]);
                    $cnt = (int)$cntStmt->fetchColumn();
                    try {
                        $pdo->prepare("INSERT INTO menu_items (section_id, name, description, image_url, price, active, sort_order) VALUES (?,?,?,?,?,1,?)")
                            ->execute([$sid, $iname, $idesc, $imgVal, $priceVal, $cnt]);
                    } catch (Exception $e) {
                        // image_url column may not exist yet
                        $pdo->prepare("INSERT INTO menu_items (section_id, name, description, price, active, sort_order) VALUES (?,?,?,?,1,?)")
                            ->execute([$sid, $iname, $idesc, $priceVal, $cnt]);
                    }
                    $success = 'Platillo agregado.';
                }
                break;

            case 'del_item':
                $iid = (int)($_POST['item_id'] ?? 0);
                if ($iid) {
                    $pdo->prepare("DELETE FROM menu_items WHERE id = ?")->execute([$iid]);
                    $success = 'Platillo eliminado.';
                }
                break;
        }
    }
}

// Load sections + items
$stmtSec = $pdo->prepare("SELECT * FROM menu_sections WHERE menu_id = ? ORDER BY sort_order ASC, id ASC");
$stmtSec->execute([$id]);
$sections = $stmtSec->fetchAll();

foreach ($sections as &$sec) {
    $stmtItems = $pdo->prepare("SELECT * FROM menu_items WHERE section_id = ? ORDER BY sort_order ASC, id ASC");
    $stmtItems->execute([$sec['id']]);
    $sec['items'] = $stmtItems->fetchAll();
}
unset($sec);

$created = !empty($_GET['created']);

require __DIR__ . '/../views/edit_menu.php';
