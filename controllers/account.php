<?php

require_login();

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT u.*, p.name AS plan_name, p.price AS plan_price
    FROM users u
    INNER JOIN plans p ON p.id = u.plan_id
    WHERE u.id = ?
    LIMIT 1
");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$success = '';
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!csrf_validate()) {
        $errors[] = 'Solicitud inválida. Recarga la página.';
    } else {

        $name    = trim($_POST['name']    ?? '');
        $company = trim($_POST['company'] ?? '');
        $phone   = trim($_POST['phone']   ?? '');
        $rfc     = strtoupper(trim($_POST['rfc']   ?? ''));
        $email   = trim($_POST['email']   ?? '');

        if (empty($name)) {
            $errors[] = 'El nombre es obligatorio.';
        }

        if (!empty($rfc)) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE rfc = ? AND id != ? LIMIT 1");
            $stmt->execute([$rfc, $userId]);
            if ($stmt->fetch()) $errors[] = 'Este RFC ya está registrado con otra cuenta.';
        }

        if (!empty($email) && $email !== $user['email']) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
            $stmt->execute([$email, $userId]);
            if ($stmt->fetch()) $errors[] = 'Este correo ya está en uso.';
        }

        if (empty($errors)) {
            $pdo->prepare("
                UPDATE users
                SET name    = ?,
                    company = ?,
                    phone   = ?,
                    rfc     = ?,
                    email   = ?
                WHERE id = ?
            ")->execute([
                $name,
                $company,
                $phone,
                $rfc,
                !empty($email) ? $email : $user['email'],
                $userId
            ]);

            $success = 'Datos actualizados correctamente.';

            $stmt = $pdo->prepare("
                SELECT u.*, p.name AS plan_name, p.price AS plan_price
                FROM users u INNER JOIN plans p ON p.id = u.plan_id
                WHERE u.id = ? LIMIT 1
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
        }
    }
}

$stmt = $pdo->prepare("
    SELECT * FROM payments WHERE user_id = ? ORDER BY created_at DESC LIMIT 10
");
$stmt->execute([$userId]);
$payments = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT s.*, p.name AS plan_name
    FROM subscriptions s
    INNER JOIN plans p ON p.id = s.plan_id
    WHERE s.user_id = ? AND s.status = 'active'
    ORDER BY s.created_at DESC LIMIT 1
");
$stmt->execute([$userId]);
$subscription = $stmt->fetch();

require __DIR__ . '/../views/account.php';
