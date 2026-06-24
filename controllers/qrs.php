<?php

require_login();

$uid = (int)$_SESSION['user_id'];

// ── Acciones POST (crear/eliminar carpeta, mover QR) ──────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        header('Location: /qrs');
        exit;
    }

    $action = trim($_POST['_action'] ?? '');

    switch ($action) {

        case 'create_folder':
            $fname = trim($_POST['folder_name'] ?? '');
            if (!empty($fname)) {
                $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM qr_folders WHERE user_id = ?");
                $cntStmt->execute([$uid]);
                $cnt = (int)$cntStmt->fetchColumn();
                $pdo->prepare("INSERT INTO qr_folders (user_id, name, sort_order) VALUES (?,?,?)")
                    ->execute([$uid, $fname, $cnt]);
            }
            header('Location: /qrs');
            exit;

        case 'del_folder':
            $fid = (int)($_POST['folder_id'] ?? 0);
            if ($fid) {
                // Desasignar QRs de esta carpeta antes de borrar
                $pdo->prepare("UPDATE qr_codes SET folder_id = NULL WHERE folder_id = ? AND user_id = ?")
                    ->execute([$fid, $uid]);
                $pdo->prepare("DELETE FROM qr_folders WHERE id = ? AND user_id = ?")
                    ->execute([$fid, $uid]);
            }
            header('Location: /qrs');
            exit;

        case 'move_qr':
            $qid = (int)($_POST['qr_id']     ?? 0);
            $fid = ($_POST['folder_id'] !== '') ? (int)$_POST['folder_id'] : null;
            if ($qid) {
                $pdo->prepare("UPDATE qr_codes SET folder_id = ? WHERE id = ? AND user_id = ?")
                    ->execute([$fid, $qid, $uid]);
            }
            $back = '/qrs' . (!empty($_POST['current_folder']) ? '?folder=' . (int)$_POST['current_folder'] : '');
            header('Location: ' . $back);
            exit;
    }
}

// ── Cargar carpetas del usuario ───────────────────────────────────
$stmtFolders = $pdo->prepare("SELECT * FROM qr_folders WHERE user_id = ? ORDER BY sort_order ASC, id ASC");
$stmtFolders->execute([$uid]);
$folders = $stmtFolders->fetchAll();

// ── Filtrar por carpeta (tab activo) ──────────────────────────────
$currentFolder = isset($_GET['folder']) ? (int)$_GET['folder'] : 0; // 0 = Todos

if ($currentFolder > 0) {
    $stmtQrs = $pdo->prepare("
        SELECT * FROM qr_codes
        WHERE user_id = ? AND folder_id = ? AND deleted_at IS NULL
        ORDER BY id DESC
    ");
    $stmtQrs->execute([$uid, $currentFolder]);
} else {
    $stmtQrs = $pdo->prepare("
        SELECT * FROM qr_codes
        WHERE user_id = ? AND deleted_at IS NULL
        ORDER BY id DESC
    ");
    $stmtQrs->execute([$uid]);
}
$qrs = $stmtQrs->fetchAll();

// ── Indicador de QR recién creado ────────────────────────────────
$created = !empty($_GET['created']);

require __DIR__ . '/../views/qrs.php';
