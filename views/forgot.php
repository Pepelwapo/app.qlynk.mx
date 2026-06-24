<?php
$pageTitle = 'Recuperar contraseña';
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex justify-content-center align-items-center"
     style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e,#0f3460)">
<div class="card shadow-lg border-0" style="width:420px;border-radius:20px">
<div class="card-body p-5">

    <div class="text-center mb-4">
        <div style="font-size:40px">🔐</div>
        <h4 class="fw-bold mt-2">Recuperar contraseña</h4>
        <p class="text-muted" style="font-size:14px">
            Ingresa tu correo y te enviamos un enlace
        </p>
    </div>

    <?php if (!empty($sent)): ?>
    <!-- ── Estado: enviado ── -->
    <div class="alert alert-success text-center py-3" style="font-size:14px;border-radius:12px">
        <div style="font-size:28px;margin-bottom:6px">📬</div>
        <strong>¡Enlace enviado!</strong><br>
        <span class="text-muted" style="font-size:13px">
            Si ese correo existe en QLynk, recibirás un mensaje en minutos.<br>
            Revisa también tu carpeta de spam.
        </span>
    </div>
    <div class="text-center mt-3">
        <a href="/login" class="text-muted" style="font-size:14px">← Volver al login</a>
    </div>

    <?php else: ?>
    <!-- ── Formulario ── -->

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger py-2 text-center" style="font-size:13px;border-radius:10px">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden"
               name="<?php echo CSRF_TOKEN_NAME; ?>"
               value="<?php echo csrf_token(); ?>">

        <div class="mb-4">
            <label class="form-label fw-bold" style="font-size:13px">
                Correo electrónico
            </label>
            <input name="email" type="email" class="form-control form-control-lg"
                   placeholder="tu@correo.com"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                   required autofocus>
        </div>

        <button class="btn btn-dark w-100 btn-lg fw-semibold">
            Enviar enlace de recuperación
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="/login" class="text-muted" style="font-size:13px">← Volver al login</a>
    </div>

    <?php endif; ?>

</div>
</div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
