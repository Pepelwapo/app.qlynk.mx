<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login');
    exit;
}

// CSRF
if (!csrf_validate()) {
    header('Location: /login');
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    header('Location: /login');
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, name, email_verified
    FROM users
    WHERE email = ?
    LIMIT 1
");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || $user['email_verified']) {
    header('Location: /login');
    exit;
}

$token    = bin2hex(random_bytes(32));
$tokenExp = date('Y-m-d H:i:s', strtotime('+24 hours'));

$pdo->prepare("
    UPDATE users
    SET email_token = ?, email_token_exp = ?
    WHERE id = ?
")->execute([$token, $tokenExp, $user['id']]);

$verifyUrl = APP_APP_URL . '/verify-email?token=' . $token;

$body = "
<div style='font-family:Arial,sans-serif;max-width:520px;margin:0 auto'>
    <h2 style='color:#1a1a2e'>⚡ QLynk — Nuevo enlace de verificación</h2>
    <p>Hola {$user['name']}, aquí está tu nuevo enlace:</p>
    <p style='text-align:center;margin:30px 0'>
        <a href='{$verifyUrl}'
           style='background:#1a1a2e;color:#fff;padding:14px 28px;
                  text-decoration:none;border-radius:8px;font-size:16px'>
            Verificar mi correo
        </a>
    </p>
    <p style='color:#888;font-size:13px'>Expira en 24 horas.</p>
</div>";

send_mail($email, $user['name'], 'Nuevo enlace de verificación — QLynk', $body);

$_SESSION['pending_email'] = $email;
header('Location: /register?verified=pending');
exit;
