<?php
$pageTitle = 'Crear cuenta';
require __DIR__ . '/partials/head.php';

$pending      = $_GET['verified'] ?? '';
$pendingEmail = $_SESSION['pending_email'] ?? '';
?>
<div class="d-flex justify-content-center align-items-center py-5"
     style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e,#0f3460)">
<div class="card shadow-lg border-0" style="width:460px;border-radius:20px">
<div class="card-body p-5">

    <?php if ($pending === 'pending'): ?>
    <!-- ── Pendiente de verificación ── -->
    <div class="text-center py-3">
        <div style="font-size:56px">📧</div>
        <h4 class="fw-bold mt-3">Revisa tu correo</h4>
        <p class="text-muted">
            Enviamos un enlace de verificación a<br>
            <strong><?php echo htmlspecialchars($pendingEmail); ?></strong>
        </p>
        <p class="text-muted" style="font-size:13px">
            El enlace expira en 24 horas.<br>
            Revisa tu carpeta de spam si no lo encuentras.
        </p>
        <form method="POST" action="/resend-verification" class="mt-3">
            <input type="hidden"
                   name="<?php echo CSRF_TOKEN_NAME; ?>"
                   value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="email"
                   value="<?php echo htmlspecialchars($pendingEmail); ?>">
            <button class="btn btn-outline-dark btn-sm">
                Reenviar enlace
            </button>
        </form>
        <a href="/login" class="d-block mt-3 text-muted" style="font-size:13px">
            Volver al login
        </a>
    </div>

    <?php else: ?>
    <!-- ── Formulario de registro ── -->

    <div class="text-center mb-4">
        <div style="font-size:36px">⚡</div>
        <h4 class="fw-bold mt-1">Crear cuenta gratis</h4>
        <p class="text-muted" style="font-size:13px">14 días de prueba sin tarjeta</p>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger py-2" style="font-size:13px;border-radius:10px">
        <?php foreach ($errors as $e): ?>
        <div>• <?php echo htmlspecialchars($e); ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden"
               name="<?php echo CSRF_TOKEN_NAME; ?>"
               value="<?php echo csrf_token(); ?>">

        <div class="row g-2 mb-2">
            <div class="col-6">
                <label class="form-label fw-bold" style="font-size:12px">Nombre *</label>
                <input name="name" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                       placeholder="Tu nombre" required>
            </div>
            <div class="col-6">
                <label class="form-label fw-bold" style="font-size:12px">Empresa</label>
                <input name="company" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['company'] ?? ''); ?>"
                       placeholder="Opcional">
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label fw-bold" style="font-size:12px">Correo electrónico *</label>
            <input name="email" type="email" class="form-control"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                   placeholder="tu@correo.com" required>
        </div>

        <div class="row g-2 mb-2">
            <div class="col-6">
                <label class="form-label fw-bold" style="font-size:12px">Teléfono</label>
                <input name="phone" class="form-control"
                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                       placeholder="Opcional">
            </div>
            <div class="col-6">
                <label class="form-label fw-bold" style="font-size:12px">RFC</label>
                <input name="rfc" class="form-control" maxlength="13"
                       value="<?php echo htmlspecialchars($_POST['rfc'] ?? ''); ?>"
                       placeholder="Opcional" style="text-transform:uppercase">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:12px">
                Contraseña * (mín. <?php echo PASSWORD_MIN_LENGTH; ?> caracteres)
            </label>
            <input name="password" type="password" class="form-control"
                   placeholder="••••••••" required>
        </div>

        <button class="btn btn-dark w-100 btn-lg fw-semibold">Crear cuenta gratis</button>

        <p class="text-center text-muted mt-3 mb-0" style="font-size:12px">
            Al registrarte aceptas nuestros
            <a href="/terminos" class="text-dark">Términos de uso</a>
        </p>
    </form>

    <hr class="my-3">
    <p class="text-center text-muted mb-0" style="font-size:13px">
        ¿Ya tienes cuenta?
        <a href="/login" class="text-dark fw-bold">Inicia sesión</a>
    </p>

    <?php endif; ?>

</div>
</div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
