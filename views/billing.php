<?php
$pageTitle = 'Facturación';
require __DIR__ . '/partials/head.php';

function usageBar(int $used, int $limit, string $label): string {
    if ($limit <= 0) {
        return '<div class="mb-3"><div class="d-flex justify-content-between mb-1"><span style="font-size:13px">' . htmlspecialchars($label) . '</span><span style="font-size:12px;color:#888">Ilimitado</span></div><div class="progress" style="height:6px;border-radius:4px"><div class="progress-bar bg-success" style="width:10%"></div></div></div>';
    }
    $pct = min(100, round($used / $limit * 100));
    $color = $pct >= 90 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-success');
    return '<div class="mb-3"><div class="d-flex justify-content-between mb-1"><span style="font-size:13px">' . htmlspecialchars($label) . '</span><span style="font-size:12px;color:#888">' . $used . ' / ' . $limit . '</span></div><div class="progress" style="height:6px;border-radius:4px"><div class="progress-bar ' . $color . '" style="width:' . $pct . '%"></div></div></div>';
}
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="mb-4">
    <h4 class="mb-0 fw-bold">Facturación y Plan</h4>
  </div>

  <?php if (!empty($user['trial_end']) && $trialDaysLeft > 0): ?>
  <div class="alert alert-warning d-flex align-items-center gap-3 mb-4" style="border-radius:14px;border:0;background:#fff8e6">
    <i class="bi bi-clock-history" style="font-size:24px;color:#f59e0b"></i>
    <div>
      <strong>Período de prueba activo</strong> — Te quedan <strong><?= $trialDaysLeft ?> día<?= $trialDaysLeft !== 1 ? 's' : '' ?></strong>.
      Suscríbete para no perder el acceso.
    </div>
  </div>
  <?php endif; ?>

  <div class="row g-4 mb-4">

    <!-- Current plan card -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100 p-4" style="border-radius:16px;background:linear-gradient(135deg,#1a1a2e,#2d2d4e);color:#fff">
        <div style="font-size:12px;opacity:.7;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px">Plan actual</div>
        <div style="font-size:28px;font-weight:800;margin-bottom:4px"><?= htmlspecialchars($user['plan_name']) ?></div>
        <div style="font-size:20px;font-weight:600;margin-bottom:20px">
          <?php if ((float)$user['plan_price'] > 0): ?>
          $<?= number_format((float)$user['plan_price'], 2) ?> / mes
          <?php else: ?>
          Gratis
          <?php endif; ?>
        </div>
        <div style="font-size:13px;opacity:.8">Gestiona tu suscripción con tu proveedor de pago.</div>
      </div>
    </div>

    <!-- Usage -->
    <div class="col-md-8">
      <div class="card border-0 shadow-sm h-100 p-4" style="border-radius:16px">
        <h6 class="fw-bold mb-4">Uso actual del plan</h6>

        <?= usageBar($qrDynamic, (int)$user['qr_dynamic_limit'], 'QR Dinámicos') ?>
        <?= usageBar((int)$totalScans, (int)$user['scans_limit'], 'Escaneos totales') ?>
        <?= usageBar($menuCount + $catalogCount, (int)$user['collections_limit'], 'Colecciones (menús + catálogos)') ?>
        <?= usageBar($formCount, (int)$user['forms_limit'], 'Formularios') ?>
      </div>
    </div>

  </div>

  <!-- Upgrade plans -->
  <h5 class="fw-bold mb-3">Planes disponibles</h5>
  <div class="row g-3">

    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:16px">
        <div class="fw-bold fs-5 mb-1">Starter</div>
        <div class="fw-semibold fs-4 mb-3">$0 <small class="text-muted" style="font-size:14px">/ mes</small></div>
        <ul class="list-unstyled text-muted" style="font-size:14px;line-height:2">
          <li><i class="bi bi-check text-success me-2"></i>5 QR dinámicos</li>
          <li><i class="bi bi-check text-success me-2"></i>1,000 escaneos</li>
          <li><i class="bi bi-check text-success me-2"></i>2 colecciones</li>
          <li><i class="bi bi-check text-success me-2"></i>1 formulario</li>
        </ul>
        <button class="btn btn-outline-dark w-100 mt-auto fw-semibold" style="border-radius:10px" disabled>Plan actual</button>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:16px;border:2px solid #1a1a2e!important">
        <div class="d-flex justify-content-between align-items-start mb-1">
          <div class="fw-bold fs-5">Pro</div>
          <span class="badge bg-dark" style="font-size:10px">POPULAR</span>
        </div>
        <div class="fw-semibold fs-4 mb-3">$299 <small class="text-muted" style="font-size:14px">/ mes</small></div>
        <ul class="list-unstyled text-muted" style="font-size:14px;line-height:2">
          <li><i class="bi bi-check text-success me-2"></i>50 QR dinámicos</li>
          <li><i class="bi bi-check text-success me-2"></i>50,000 escaneos</li>
          <li><i class="bi bi-check text-success me-2"></i>20 colecciones</li>
          <li><i class="bi bi-check text-success me-2"></i>10 formularios</li>
        </ul>
        <a href="https://qlynk.mx/#pricing" target="_blank" class="btn btn-dark w-100 mt-auto fw-semibold" style="border-radius:10px">
          Upgrade a Pro
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:16px">
        <div class="fw-bold fs-5 mb-1">Business</div>
        <div class="fw-semibold fs-4 mb-3">$799 <small class="text-muted" style="font-size:14px">/ mes</small></div>
        <ul class="list-unstyled text-muted" style="font-size:14px;line-height:2">
          <li><i class="bi bi-check text-success me-2"></i>QR ilimitados</li>
          <li><i class="bi bi-check text-success me-2"></i>Escaneos ilimitados</li>
          <li><i class="bi bi-check text-success me-2"></i>Colecciones ilimitadas</li>
          <li><i class="bi bi-check text-success me-2"></i>Formularios ilimitados</li>
        </ul>
        <a href="https://qlynk.mx/#pricing" target="_blank" class="btn btn-outline-dark w-100 mt-auto fw-semibold" style="border-radius:10px">
          Upgrade a Business
        </a>
      </div>
    </div>

  </div>

</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
