<?php

require_login();

$userId  = $_SESSION['user_id'];
$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF
    if (!csrf_validate()) {
        $errors[] = 'Solicitud inválida. Recarga la página.';
    } else {

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!password_verify($current, $user['password_hash'])) {
            $errors[] = 'La contraseña actual es incorrecta.';
        }
        if (strlen($new) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'La nueva contraseña debe tener mínimo ' . PASSWORD_MIN_LENGTH . ' caracteres.';
        }
        if ($new !== $confirm) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        if (empty($errors)) {
            $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?")
                ->execute([password_hash($new, PASSWORD_DEFAULT), $userId]);
            $success = 'Contraseña actualizada correctamente.';
        }
    }
}

$_SESSION['pwd_success'] = $success;
$_SESSION['pwd_errors']  = $errors;

header('Location: /account?tab=password');
exit;
