<?php

$token      = trim($_GET['token'] ?? '');
$tokenError = false;
$success    = false;
$errors     = [];
$user       = null;

if (empty($token)) {
    header('Location: /forgot');
    exit;
}

// Validar token
$stmt = $pdo->prepare("
    SELECT id, name, email
    FROM users
    WHERE email_token     = ?
      AND email_token_exp > NOW()
    LIMIT 1
");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $tokenError = true;
    require __DIR__ . '/../views/reset.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        $errors[] = 'Solicitud inválida. Intenta de nuevo.';
    } else {
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm']  ?? '';

        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'La contraseña debe tener mínimo ' . PASSWORD_MIN_LENGTH . ' caracteres.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        if (empty($errors)) {
            // Actualizar contraseña, limpiar token y verificar email (si llegó aquí lo posee)
            $pdo->prepare("
                UPDATE users
                SET password_hash   = ?,
                    email_token     = NULL,
                    email_token_exp = NULL,
                    email_verified  = 1
                WHERE id = ?
            ")->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);

            $success = true;
        }
    }
}

require __DIR__ . '/../views/reset.php';
