<?php
$pageTitle = 'Página no encontrada';
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex justify-content-center align-items-center"
     style="min-height:100vh;background:linear-gradient(135deg,#1a1a2e,#0f3460)">
<div class="text-center text-white">
    <div style="font-size:80px;opacity:.2">404</div>
    <h3 class="fw-bold mt-2">Página no encontrada</h3>
    <p class="text-white-50">La URL que buscas no existe en QLynk.</p>
    <a href="/dashboard" class="btn btn-light mt-2">
        <i class="bi bi-house me-1"></i>Ir al dashboard
    </a>
</div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
