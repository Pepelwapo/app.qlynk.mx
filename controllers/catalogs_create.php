<?php

require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página.';
    } else {
        $name  = trim($_POST['name'] ?? '');
        $desc  = trim($_POST['description'] ?? '');
        $slug  = trim($_POST['slug'] ?? '');

        $validTpl  = ['classic', 'dark', 'cards'];
        $template  = in_array(trim($_POST['template'] ?? ''), $validTpl) ? trim($_POST['template']) : 'classic';
        $waEnabled = !empty($_POST['whatsapp_enabled']) ? 1 : 0;
        $waPhone   = trim($_POST['whatsapp_phone'] ?? '');

        if (empty($name)) {
            $error = 'El nombre del catálogo es obligatorio.';
        } elseif (empty($slug)) {
            $error = 'El slug es obligatorio.';
        } elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
            $error = 'El slug solo puede tener letras minúsculas, números y guiones.';
        } else {
            $chk = $pdo->prepare("SELECT id FROM catalogs WHERE slug = ?");
            $chk->execute([$slug]);
            if ($chk->fetch()) {
                $error = 'Ese slug ya está en uso. Elige uno diferente.';
            } else {
                $pdo->prepare("
                    INSERT INTO catalogs
                        (user_id, name, description, slug, template, whatsapp_enabled, whatsapp_phone, active, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())
                ")->execute([$_SESSION['user_id'], $name, $desc, $slug, $template, $waEnabled, $waPhone]);

                $newId = $pdo->lastInsertId();
                header('Location: /catalogs/edit?id=' . $newId . '&created=1');
                exit;
            }
        }
    }
}

require __DIR__ . '/../views/create_catalog.php';
