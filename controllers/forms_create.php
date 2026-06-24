<?php

require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página.';
    } else {
        $name    = trim($_POST['name']        ?? '');
        $desc    = trim($_POST['description'] ?? '');
        $slug    = trim($_POST['slug']        ?? '');
        $success_msg = trim($_POST['success_msg'] ?? '¡Gracias! Tu mensaje fue enviado.');

        if (empty($name)) {
            $error = 'El nombre del formulario es obligatorio.';
        } elseif (empty($slug)) {
            $error = 'El slug es obligatorio.';
        } elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
            $error = 'El slug solo puede tener letras minúsculas, números y guiones.';
        } else {
            $chk = $pdo->prepare("SELECT id FROM user_forms WHERE slug = ?");
            $chk->execute([$slug]);
            if ($chk->fetch()) {
                $error = 'Ese slug ya está en uso.';
            } else {
                $pdo->prepare("
                    INSERT INTO user_forms (user_id, name, description, slug, success_msg, active, created_at)
                    VALUES (?, ?, ?, ?, ?, 1, NOW())
                ")->execute([$_SESSION['user_id'], $name, $desc, $slug, $success_msg]);

                $newId = $pdo->lastInsertId();
                header('Location: /forms/edit?id=' . $newId . '&created=1');
                exit;
            }
        }
    }
}

require __DIR__ . '/../views/create_form.php';
