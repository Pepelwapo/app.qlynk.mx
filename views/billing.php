<?php
$pageTitle = 'Facturación';
require __DIR__ . '/partials/head.php';

/**
 * Render a usage progress bar.
 *  limit = 0          → "No incluido" (grey bar at 0 %)
 *  limit >= 99999999  → "Ilimitado"   (green bar at 100 %)
 *  otherwise          → "used / limit"
 */
function usageBar($used, $limit, $label) {
    $used  = (int)$used;
    $limit = (int)$limit;
    if ($limit >= 99999999) {
        $badge = '<span style="font-size:12px;color:#22c55e;font-weight:600">Ilimitado</span>';
        $bar   = '<div class="progress-bar bg-success" style="width:100%"></div>';
    } elseif ($limit <= 0) {
        $badge = '<span style="font-size:12px;color:#aaa">No incluido</span>';
        $bar   = '<div class="progress-bar bg-secondary" style="width:0%"></div>';
    } else {
        $pct   = min(100, (int)round($used / $limit * 100));
        $color = $pct >= 90 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-success');
        $badge = '<span style="font-size:12px;color:#888">' . number_format($used) . ' / ' . number_format($limit) . '</span>';
        $bar   = '<div class="progress-bar ' . $color . '" style="width:' . $pct . '%"></div>';
    }
    return '<div class="mb-3">'
         . '<div class="d-flex justify-content-between mb-1">'
         . '<span style="font-size:13px">' . htmlspecialchars($label) . '</span>'
         . $badge
         . '</div>'
         . '<div class="progress" style="height:6px;border-radius:4px">' . $bar . '</div>'
         . '</div>';
}

/** Format a DB limit value for display in plan cards */
function fmtLimit($n) {
    $n = (int)$n;
    if ($n >= 99999999) { return 'Ilimitado'; }
    if ($n <= 0)        { return 'No incluido'; }
    return number_format($n);
}
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="mb-4">
    <h4 class="mb-0 fw-bold">Facturación y Plan</h4>
  </div>

  <?php if (!empty($user['trial_end']) && $trialDaysLeft > 0): ?>
  <div class="alert alert-warning d-flex align-items-center gap-3 mb-4"
       style="border-radius:14px;border:0;background:#fff8e6">
    <i class="bi bi-clock-history" style="font-size:24px;color:#f59e0b"></i>
    <div>
      <strong>Período de prueba activo</strong> — Te quedan
      <strong><?= $trialDaysLeft ?> día<?= $trialDaysLeft !== 1 ? 's' : '' ?></strong>.
      Suscríbete para no perder el acceso.
    </div>
  </div>
  <?php endif; ?>

  <!-- ─── Current plan + usage ───────────────────────────────────── -->
  <div class="row g-4 mb-5">

    <!-- Current plan card -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100 p-4"
           style="border-radius:16px;background:linear-gradient(135deg,#1a1a2e,#2d2d4e);color:#fff">
        <div style="font-size:12px;opacity:.7;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px">
          Plan actual
        </div>
        <div style="font-size:28px;font-weight:800;margin-bottom:4px">
          <?= htmlspecialchars($user['plan_name']) ?>
        </div>
        <div style="font-size:20px;font-weight:600;margin-bottom:20px">
          <?php if ((float)$user['plan_price'] > 0): ?>
            $<?= number_format((float)$user['plan_price'], 2) ?> <span style="font-size:14px;opacity:.7">/ mes</span>
          <?php else: ?>
            Gratis
          <?php endif; ?>
        </div>
        <div style="font-size:13px;opacity:.75;line-height:1.6">
          Para cambiar de plan, elige una opción abajo.
        </div>
      </div>
    </div>

    <!-- Usage bars -->
    <div class="col-md-8">
      <div class="card border-0 shadow-sm h-100 p-4" style="border-radius:16px">
        <h6 class="fw-bold mb-4">Uso actual del plan</h6>
        <?= usageBar($qrDynamic,                               $user['qr_dynamic_limit'],   'QR Dinámicos') ?>
        <?= usageBar($totalScans,                              $user['scans_limit'],         'Escaneos totales') ?>
        <?= usageBar($menuCount + $catalogCount,               $user['collections_limit'],   'Colecciones (menús + catálogos)') ?>
        <?= usageBar($formCount,                               $user['forms_limit'],         'Formularios') ?>
      </div>
    </div>

  </div>

  <!-- ─── Available plans (from DB) ─────────────────────────────── -->
  <h5 class="fw-bold mb-3">Planes disponibles</h5>
  <div class="row g-3">

  <?php foreach ($plans as $plan):
    $isCurrent  = ((int)$plan['id'] === (int)$user['plan_id']);
    $isUnlimited = function($n) { return (int)$n >= 99999999; };

    // Highlight the most expensive non-Business-unlimited plan as "popular"
    $isPopular = ($plan['name'] === 'Pro');

    // Card border
    $cardBorder = $isCurrent ? 'border:2px solid #1a1a2e!important' : '';
  ?>
  <div class="col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm p-4 h-100 position-relative"
         style="border-radius:16px;<?= $cardBorder ?>">

      <?php if ($isCurrent): ?>
        <span class="badge position-absolute top-0 end-0 m-3"
              style="background:#1a1a2e;font-size:10px;letter-spacing:.5px">ACTUAL</span>
      <?php elseif ($isPopular): ?>
        <span class="badge position-absolute top-0 end-0 m-3"
              style="background:#7c3aed;font-size:10px;letter-spacing:.5px">POPULAR</span>
      <?php endif; ?>

      <div class="fw-bold fs-5 mb-1"><?= htmlspecialchars($plan['name']) ?></div>

      <div class="fw-semibold mb-3" style="font-size:22px">
        <?php if ((float)$plan['price'] > 0): ?>
          $<?= number_format((float)$plan['price'], 0) ?>
          <small class="text-muted" style="font-size:13px;font-weight:400">/ mes</small>
        <?php else: ?>
          <span style="color:#22c55e">Gratis</span>
        <?php endif; ?>
      </div>

      <ul class="list-unstyled text-muted mb-4" style="font-size:13px;line-height:2.1">
        <li>
          <i class="bi bi-qr-code me-2 text-success"></i>
          <?= fmtLimit($plan['qr_dynamic_limit']) ?> QR dinámicos
        </li>
        <li>
          <i class="bi bi-eye me-2 text-success"></i>
          <?= fmtLimit($plan['scans_limit']) ?> escaneos / mes
        </li>
        <li>
          <?php if ((int)$plan['collections_limit'] > 0 || (int)$plan['collections_limit'] >= 99999999): ?>
            <i class="bi bi-collection me-2 text-success"></i>
          <?php else: ?>
            <i class="bi bi-x text-danger me-2"></i>
          <?php endif; ?>
          <?= fmtLimit($plan['collections_limit']) ?> colecciones
        </li>
        <li>
          <?php if ((int)$plan['forms_limit'] > 0 || (int)$plan['forms_limit'] >= 99999999): ?>
            <i class="bi bi-ui-checks me-2 text-success"></i>
          <?php else: ?>
            <i class="bi bi-x text-danger me-2"></i>
          <?php endif; ?>
          <?= fmtLimit($plan['forms_limit']) ?> formularios
        </li>
      </ul>

      <?php if ($isCurrent): ?>
        <button class="btn btn-outline-secondary w-100 mt-auto fw-semibold"
                style="border-radius:10px" disabled>
          Plan actual
        </button>
      <?php elseif (!empty($plan['mp_link'])): ?>
        <a href="<?= htmlspecialchars($plan['mp_link']) ?>" target="_blank"
           class="btn w-100 mt-auto fw-semibold"
           style="border-radius:10px;background:#1a1a2e;color:#fff">
          Elegir <?= htmlspecialchars($plan['name']) ?>
        </a>
      <?php else: ?>
        <a href="https://qlynk.mx/#pricing" target="_blank"
           class="btn btn-outline-dark w-100 mt-auto fw-semibold"
           style="border-radius:10px">
          Elegir <?= htmlspecialchars($plan['name']) ?>
        </a>
      <?php endif; ?>

    </div>
  </div>
  <?php endforeach; ?>

  </div><!-- /row -->

</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
