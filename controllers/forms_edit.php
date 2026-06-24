<?php

require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: /forms'); exit; }

$stmt = $pdo->prepare("SELECT * FROM user_forms WHERE id = ? AND user_id = ? AND deleted_at IS NULL LIMIT 1");
$stmt->execute([$id, $_SESSION['user_id']]);
$form = $stmt->fetch();
if (!$form) { header('Location: /forms'); exit; }

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página.';
    } else {
        $action = trim($_POST['_action'] ?? 'save_meta');

        switch ($action) {

            case 'save_meta':
                $name    = trim($_POST['name']        ?? '');
                $desc    = trim($_POST['description'] ?? '');
                $slug    = trim($_POST['slug']        ?? '');
                $smsg    = trim($_POST['success_msg'] ?? '');
                if (empty($name) || empty($slug)) {
                    $error = 'Nombre y slug son obligatorios.';
                } elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
                    $error = 'El slug solo puede tener letras minúsculas, números y guiones.';
                } else {
                    $chk = $pdo->prepare("SELECT id FROM user_forms WHERE slug = ? AND id != ?");
                    $chk->execute([$slug, $id]);
                    if ($chk->fetch()) {
                        $error = 'Ese slug ya está en uso.';
                    } else {
                        $pdo->prepare("UPDATE user_forms SET name=?,description=?,slug=?,success_msg=? WHERE id=? AND user_id=?")
                            ->execute([$name, $desc, $slug, $smsg, $id, $_SESSION['user_id']]);
                        $success = 'Formulario actualizado.';
                        $stmt = $pdo->prepare("SELECT * FROM user_forms WHERE id = ? LIMIT 1");
                        $stmt->execute([$id]);
                        $form = $stmt->fetch();
                    }
                }
                break;

            case 'add_field':
                $label   = trim($_POST['field_label']  ?? '');
                $ftype   = trim($_POST['field_type']   ?? 'text');
                $ph      = trim($_POST['field_ph']     ?? '');
                $options = trim($_POST['field_options'] ?? '');
                $req     = (int)(!empty($_POST['field_required']));
                $validTypes = ['text','email','phone','textarea','select','checkbox'];
                if (!empty($label) && in_array($ftype, $validTypes)) {
                    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM form_fields WHERE form_id = ?");
                    $cntStmt->execute([$id]);
                    $cnt = (int)$cntStmt->fetchColumn();
                    $optJson = null;
                    if ($ftype === 'select' && !empty($options)) {
                        $opts = array_filter(array_map('trim', explode("\n", $options)));
                        $optJson = json_encode(array_values($opts));
                    }
                    $pdo->prepare("INSERT INTO form_fields (form_id, label, field_type, placeholder, options, required, sort_order) VALUES (?,?,?,?,?,?,?)")
                        ->execute([$id, $label, $ftype, $ph ?: null, $optJson, $req, $cnt]);
                    $success = 'Campo agregado.';
                }
                break;

            case 'del_field':
                $fid = (int)($_POST['field_id'] ?? 0);
                if ($fid) {
                    $pdo->prepare("DELETE FROM form_fields WHERE id = ? AND form_id = ?")->execute([$fid, $id]);
                    $success = 'Campo eliminado.';
                }
                break;
        }
    }
}

// Load fields
$stmtFields = $pdo->prepare("SELECT * FROM form_fields WHERE form_id = ? ORDER BY sort_order ASC, id ASC");
$stmtFields->execute([$id]);
$fields = $stmtFields->fetchAll();

// Response count
$respStmt = $pdo->prepare("SELECT COUNT(*) FROM form_responses WHERE form_id = ?");
$respStmt->execute([$id]);
$respCount = (int)$respStmt->fetchColumn();

$created = !empty($_GET['created']);

require __DIR__ . '/../views/edit_form.php';
