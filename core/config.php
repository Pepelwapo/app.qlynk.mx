<?php

declare(strict_types=1);

session_start();

define('APP_NAME', 'QLynk');

define('APP_URL', 'https://qlynk.mx');

define('APP_APP_URL', 'https://app.qlynk.mx');

define('APP_GO_URL', 'https://go.qlynk.mx');

define('TRIAL_DAYS', 14);

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

define('DB_HOST', 'localhost');

define('DB_NAME', 'joseju33_qlynkdb');

define('DB_USER', 'joseju33_devpp');

define('DB_PASS', 'fLn!vjk~z%z_');

/*
|--------------------------------------------------------------------------
| EMAIL
|--------------------------------------------------------------------------
*/

define('SMTP_HOST',       'smtp.titan.email');
define('SMTP_PORT',       465);
define('SMTP_USER',       'noreply@qlynk.mx');
define('SMTP_PASS',       "]q(Kjih'K@S=DZ%");
define('SMTP_FROM_NAME',  'QLynk');
define('SMTP_ENCRYPTION', 'ssl');

/*
|--------------------------------------------------------------------------
| SECURITY
|--------------------------------------------------------------------------
*/

define('CSRF_TOKEN_NAME', '_token');

define('PASSWORD_MIN_LENGTH', 8);

// -------------------------------------------------------
// AUTH
// -------------------------------------------------------
function require_login(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
}