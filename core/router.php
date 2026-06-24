<?php

require_once __DIR__.'/config.php';
require_once __DIR__.'/csrf.php';
require_once __DIR__.'/db.php';
require_once __DIR__.'/mail.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {

    case '/':
        if (!empty($_SESSION['user_id'])) {
            header('Location: /dashboard');
        } else {
            header('Location: https://qlynk.mx');
        }
        exit;

    case '/login':
        require __DIR__.'/../controllers/login.php';
        break;

    case '/register':
        require __DIR__.'/../controllers/register.php';
        break;

    case '/forgot':
        require __DIR__.'/../controllers/forgot.php';
        break;

    case '/reset':
        require __DIR__.'/../controllers/reset.php';
        break;

    case '/dashboard':
        require __DIR__.'/../controllers/dashboard.php';
        break;

    case '/qrs':
        require __DIR__.'/../controllers/qrs.php';
        break;

    case '/qrs/create':
        require __DIR__.'/../controllers/create_qr.php';
        break;

    case '/qrs/edit':
        require __DIR__.'/../controllers/edit_qr.php';
        break;

    case '/qrs/toggle':
        require __DIR__.'/../controllers/toggle_qr.php';
        break;

    case '/qrs/delete':
        require __DIR__.'/../controllers/delete_qr.php';
        break;

    case '/qrs/static':
        require __DIR__.'/../controllers/static_qr.php';
        break;

    case '/verify-email':
        require __DIR__.'/../controllers/verify_email.php';
        break;

    case '/resend-verification':
        require __DIR__.'/../controllers/resend_verification.php';
        break;

    case '/account':
        require __DIR__.'/../controllers/account.php';
        break;

    case '/account/password':
        require __DIR__.'/../controllers/account_password.php';
        break;

    case '/logout':
        require __DIR__.'/../controllers/logout.php';
        break;

    default:
        http_response_code(404);
        require __DIR__.'/../views/404.php';
}
