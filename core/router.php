<?php

require_once __DIR__.'/config.php';
require_once __DIR__.'/csrf.php';
require_once __DIR__.'/db.php';
require_once __DIR__.'/mail.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// ══════════════════════════════════════════════════════════════════
//  Rutas públicas con slug dinámico (ANTES del switch)
//  /m/{slug}  → menú público
//  /c/{slug}  → catálogo público
//  /f/{slug}  → formulario público
// ══════════════════════════════════════════════════════════════════

if (preg_match('#^/m/([a-z0-9\-]+)$#i', $uri, $m)) {
    $slug = $m[1];
    require __DIR__.'/../controllers/menu_view.php';
    exit;
}

if (preg_match('#^/c/([a-z0-9\-]+)$#i', $uri, $m)) {
    $slug = $m[1];
    require __DIR__.'/../controllers/catalog_view.php';
    exit;
}

if (preg_match('#^/f/([a-z0-9\-]+)$#i', $uri, $m)) {
    $slug = $m[1];
    require __DIR__.'/../controllers/form_view.php';
    exit;
}

// ══════════════════════════════════════════════════════════════════
//  Rutas estáticas
// ══════════════════════════════════════════════════════════════════

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

    // ── QR Dinámicos ──
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

    // ── Menús ──
    case '/menus':
        require __DIR__.'/../controllers/menus.php';
        break;

    case '/menus/create':
        require __DIR__.'/../controllers/menus_create.php';
        break;

    case '/menus/edit':
        require __DIR__.'/../controllers/menus_edit.php';
        break;

    case '/menus/delete':
        require __DIR__.'/../controllers/menus_delete.php';
        break;

    // ── Catálogos ──
    case '/catalogs':
        require __DIR__.'/../controllers/catalogs.php';
        break;

    case '/catalogs/create':
        require __DIR__.'/../controllers/catalogs_create.php';
        break;

    case '/catalogs/edit':
        require __DIR__.'/../controllers/catalogs_edit.php';
        break;

    case '/catalogs/delete':
        require __DIR__.'/../controllers/catalogs_delete.php';
        break;

    // ── Formularios ──
    case '/forms':
        require __DIR__.'/../controllers/forms.php';
        break;

    case '/forms/create':
        require __DIR__.'/../controllers/forms_create.php';
        break;

    case '/forms/edit':
        require __DIR__.'/../controllers/forms_edit.php';
        break;

    case '/forms/delete':
        require __DIR__.'/../controllers/forms_delete.php';
        break;

    case '/forms/responses':
        require __DIR__.'/../controllers/form_responses.php';
        break;

    // ── Facturación ──
    case '/billing':
        require __DIR__.'/../controllers/billing.php';
        break;

    // ── Biblioteca de imágenes ──
    case '/media':
        require __DIR__.'/../controllers/media.php';
        break;

    // ── Términos y condiciones ──
    case '/terminos':
        require __DIR__.'/../controllers/terminos.php';
        break;

    // ── Cuenta ──
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
