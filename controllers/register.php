<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!csrf_validate()) {
        $errors = ['Solicitud inválida. Recarga la página e intenta de nuevo.'];
        require __DIR__ . '/../views/register.php';
        exit;
    }

    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $company  = trim($_POST['company']  ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $rfc      = strtoupper(trim($_POST['rfc'] ?? ''));

    $errors = [];

    if (empty($name))    $errors[] = 'El nombre es obligatorio.';
    if (empty($email))   $errors[] = 'El correo es obligatorio.';
    if (empty($password) || strlen($password) < PASSWORD_MIN_LENGTH)
        $errors[] = 'La contraseña debe tener mínimo ' . PASSWORD_MIN_LENGTH . ' caracteres.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = 'Este correo ya está registrado.';
    }

    if (!empty($rfc) && empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE rfc = ? LIMIT 1");
        $stmt->execute([$rfc]);
        if ($stmt->fetch()) $errors[] = 'Este RFC ya está registrado con otra cuenta.';
    }

    if (empty($errors)) {

        $token    = bin2hex(random_bytes(32));
        $tokenExp = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $stmt = $pdo->prepare("
            INSERT INTO users
            (name, email, password_hash, company, phone, rfc,
             status, trial_end, email_verified, email_token, email_token_exp, plan_id)
            VALUES
            (?, ?, ?, ?, ?, ?,
             'trial', DATE_ADD(NOW(), INTERVAL ? DAY), 0, ?, ?, 1)
        ");
        $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $company,
            $phone,
            $rfc,
            TRIAL_DAYS,
            $token,
            $tokenExp
        ]);

        $verifyUrl = APP_APP_URL . '/verify-email?token=' . $token;

        $body = "
        <div style='font-family:Arial,sans-serif;max-width:520px;margin:0 auto'>
            <h2 style='color:#1a1a2e'>⚡ Bienvenido a QLynk, {$name}</h2>
            <p>Gracias por registrarte. Confirma tu correo dando clic en el botón:</p>
            <p style='text-align:center;margin:30px 0'>
                <a href='{$verifyUrl}'
                   style='background:#1a1a2e;color:#fff;padding:14px 28px;
                          text-decoration:none;border-radius:8px;font-size:16px'>
                    Verificar mi correo
                </a>
            </p>
            <p style='color:#888;font-size:13px'>
                Este enlace expira en 24 horas.<br>
                Si no creaste esta cuenta, ignora este mensaje.
            </p>
            <hr style='border:none;border-top:1px solid #eee'>
            <p style='color:#aaa;font-size:12px'>QLynk — " . APP_URL . "</p>
        </div>";

        send_mail($email, $name, 'Verifica tu correo — QLynk', $body);

        $_SESSION['pending_email'] = $email;
        header('Location: /register?verified=pending');
        exit;
    }
}

require __DIR__ . '/../views/register.php';
