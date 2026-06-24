<?php

$token = trim($_GET['token'] ?? '');

if (empty($token)) {
    header('Location: /login');
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, name, email_token_exp, email_verified
    FROM users
    WHERE email_token = ?
    LIMIT 1
");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $verifyError = 'El enlace de verificación no es válido.';
    require __DIR__ . '/../views/verify_email.php';
    exit;
}

if ($user['email_verified']) {
    header('Location: /login?verified=already');
    exit;
}

if (strtotime($user['email_token_exp']) < time()) {
    $verifyError = 'El enlace expiró. Solicita uno nuevo.';
    $expiredUserId = $user['id'];
    require __DIR__ . '/../views/verify_email.php';
    exit;
}

// Activar cuenta
$pdo->prepare("
    UPDATE users
    SET email_verified  = 1,
        email_token     = NULL,
        email_token_exp = NULL
    WHERE id = ?
")->execute([$user['id']]);

header('Location: /login?verified=success');
exit;