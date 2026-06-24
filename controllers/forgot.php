<?php

$sent  = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Intenta de nuevo.';
        require __DIR__ . '/../views/forgot.php';
        exit;
    }

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Ingresa tu correo electrónico.';
        require __DIR__ . '/../views/forgot.php';
        exit;
    }

    // Buscar usuario (no revelar si existe o no)
    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token    = bin2hex(random_bytes(32));
        $tokenExp = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo->prepare("
            UPDATE users
            SET email_token     = ?,
                email_token_exp = ?
            WHERE id = ?
        ")->execute([$token, $tokenExp, $user['id']]);

        $resetUrl = APP_APP_URL . '/reset?token=' . $token;

        $body = "
        <div style='font-family:Arial,sans-serif;max-width:520px;margin:0 auto'>
            <h2 style='color:#1a1a2e'>🔑 Recuperar contraseña — QLynk</h2>
            <p>Hola <strong>{$user['name']}</strong>,<br>
            recibimos una solicitud para restablecer la contraseña de tu cuenta.</p>
            <p style='text-align:center;margin:32px 0'>
                <a href='{$resetUrl}'
                   style='background:#1a1a2e;color:#fff;padding:14px 30px;
                          text-decoration:none;border-radius:8px;font-size:16px;
                          font-weight:600;letter-spacing:.3px'>
                    Restablecer contraseña
                </a>
            </p>
            <p style='color:#888;font-size:13px'>
                ⚠️ Este enlace expira en <strong>1 hora</strong>.<br>
                Si no solicitaste esto, ignora este mensaje. Tu contraseña no cambiará.
            </p>
            <hr style='border:none;border-top:1px solid #eee;margin:24px 0'>
            <p style='color:#aaa;font-size:12px'>QLynk — " . APP_URL . "</p>
        </div>";

        send_mail($email, $user['name'], 'Recuperar contraseña — QLynk', $body);
    }

    // Siempre mostramos éxito (evita enumeración de emails)
    $sent = true;
}

require __DIR__ . '/../views/forgot.php';
