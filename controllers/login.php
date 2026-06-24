<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página e intenta de nuevo.';
        require __DIR__ . '/../views/login.php';
        exit;
    }

    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {

        // Bloquear si no verificó email
        if (!$user['email_verified']) {
            $_SESSION['pending_email'] = $email;
            $error = 'Debes verificar tu correo antes de entrar. '
                   . '<a href="/resend-verification" style="text-decoration:underline">'
                   . 'Reenviar enlace</a>';
            require __DIR__ . '/../views/login.php';
            exit;
        }

        // Regenerar sesión por seguridad
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];

        // Remember me — 30 días
        if (!empty($_POST['remember'])) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + 60*60*24*30, '/', '', true, true);
            $pdo->prepare("UPDATE users SET email_token = ? WHERE id = ?")
                ->execute([$token, $user['id']]);
        }

        header('Location: /dashboard');
        exit;
    }

    $error = 'Correo o contraseña incorrectos.';
}

require __DIR__ . '/../views/login.php';
