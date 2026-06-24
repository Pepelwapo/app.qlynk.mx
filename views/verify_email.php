<?php
$pageTitle = 'Verificar correo';
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex justify-content-center align-items-center" style="min-height:100vh;background:#f8f9fa">
<div class="card shadow border-0 text-center" style="width:420px;border-radius:16px">
<div class="card-body p-5">

<?php if (!empty($verifyError)): ?>
    <div style="font-size:48px">❌</div>
    <h4 class="mt-3">Enlace inválido</h4>
    <p class="text-muted"><?php echo htmlspecialchars($verifyError); ?></p>
    <?php if (!empty($expiredUserId)): ?>
    <form method="POST" action="/resend-verification">
        <input type="hidden" name="email" value="">
        <button class="btn btn-dark w-100">Reenviar verificación</button>
    </form>
    <?php endif; ?>
<?php else: ?>
    <div style="font-size:48px">✅</div>
    <h4 class="mt-3">¡Correo verificado!</h4>
    <p class="text-muted">Tu cuenta está lista. Ya puedes iniciar sesión.</p>
    <a href="/login" class="btn btn-dark w-100">Ir al login</a>
<?php endif; ?>

</div>
</div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>