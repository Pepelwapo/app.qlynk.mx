<?php
$pageTitle = 'Nueva contraseña';
$safeToken = htmlspecialchars($_GET['token'] ?? '');
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex justify-content-center align-items-center"
     style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e,#0f3460)">
<div class="card shadow-lg border-0" style="width:420px;border-radius:20px">
<div class="card-body p-5">

    <div class="text-center mb-4">
        <div style="font-size:40px">🔑</div>
        <h4 class="fw-bold mt-2">Nueva contraseña</h4>
    </div>

    <?php if (!empty($tokenError)): ?>
    <!-- ── Token inválido / expirado ── -->
    <div class="alert alert-danger text-center py-3" style="border-radius:12px">
        <div style="font-size:28px;margin-bottom:6px">⏱️</div>
        <strong>Enlace inválido o expirado</strong><br>
        <span style="font-size:13px">Los enlaces de recuperación duran 1 hora.</span>
    </div>
    <div class="text-center mt-3">
        <a href="/forgot" class="btn btn-dark btn-sm">Solicitar un nuevo enlace</a>
    </div>

    <?php elseif (!empty($success)): ?>
    <!-- ── Éxito ── -->
    <div class="alert alert-success text-center py-3" style="border-radius:12px">
        <div style="font-size:28px;margin-bottom:6px">✅</div>
        <strong>¡Contraseña actualizada!</strong><br>
        <span style="font-size:13px">Ya puedes iniciar sesión con tu nueva contraseña.</span>
    </div>
    <div class="text-center mt-3">
        <a href="/login" class="btn btn-dark">Iniciar sesión</a>
    </div>

    <?php else: ?>
    <!-- ── Formulario ── -->

    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger py-2" style="font-size:13px;border-radius:10px">
        <?php foreach ($errors as $e): ?>
        <div>• <?php echo htmlspecialchars($e); ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/reset?token=<?php echo $safeToken; ?>">
        <input type="hidden"
               name="<?php echo CSRF_TOKEN_NAME; ?>"
               value="<?php echo csrf_token(); ?>">

        <div class="mb-3">
            <label class="form-label fw-bold" style="font-size:13px">
                Nueva contraseña
                <span class="text-muted fw-normal">(mín. <?php echo PASSWORD_MIN_LENGTH; ?> caracteres)</span>
            </label>
            <input name="password" type="password" class="form-control form-control-lg"
                   placeholder="••••••••" required autofocus>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold" style="font-size:13px">
                Confirmar contraseña
            </label>
            <input name="confirm" type="password" class="form-control form-control-lg"
                   placeholder="••••••••" required>
        </div>

        <button class="btn btn-dark w-100 btn-lg fw-semibold">
            Guardar nueva contraseña
        </button>
    </form>

    <?php endif; ?>

</div>
</div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
