<?php
$pageTitle = 'Entrar';
require __DIR__ . '/partials/head.php';

$verified = $_GET['verified'] ?? '';
$reset    = $_GET['reset']    ?? '';
?>
<div class="d-flex justify-content-center align-items-center"
     style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e,#0f3460)">
<div class="card shadow-lg border-0" style="width:400px;border-radius:20px">
<div class="card-body p-5">

    <div class="text-center mb-4">
        <div style="font-size:36px">⚡</div>
        <h4 class="fw-bold mt-1">Bienvenido a QLynk</h4>
        <p class="text-muted" style="font-size:14px">Ingresa a tu cuenta</p>
    </div>

    <?php if ($verified === 'success'): ?>
    <div class="alert alert-success py-2 text-center" style="font-size:14px;border-radius:10px">
        ✅ Correo verificado. Ya puedes entrar.
    </div>
    <?php elseif ($verified === 'already'): ?>
    <div class="alert alert-info py-2 text-center" style="font-size:14px;border-radius:10px">
        Tu correo ya estaba verificado.
    </div>
    <?php elseif ($reset === 'success'): ?>
    <div class="alert alert-success py-2 text-center" style="font-size:14px;border-radius:10px">
        🔑 Contraseña actualizada. Ya puedes entrar.
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2" style="font-size:14px;border-radius:10px">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden"
               name="<?php echo CSRF_TOKEN_NAME; ?>"
               value="<?php echo csrf_token(); ?>">

        <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:13px">Correo electrónico</label>
            <input name="email" type="email" class="form-control form-control-lg"
                   placeholder="tu@correo.com"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                   required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:13px">Contraseña</label>
            <input name="password" type="password" class="form-control form-control-lg"
                   placeholder="••••••••" required>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-muted" for="remember" style="font-size:13px">
                    Recordarme
                </label>
            </div>
            <a href="/forgot" class="text-muted" style="font-size:13px">¿Olvidaste tu contraseña?</a>
        </div>
        <button class="btn btn-dark w-100 btn-lg fw-semibold">Entrar</button>
    </form>

    <hr class="my-4">

    <p class="text-center text-muted mb-0" style="font-size:14px">
        ¿No tienes cuenta?
        <a href="/register" class="text-dark fw-bold">Regístrate gratis</a>
    </p>

</div>
</div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
