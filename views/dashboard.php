<?php
$pageTitle = 'Dashboard';
require __DIR__ . '/partials/head.php';

// Porcentajes para barras
$pctQr    = $user['qr_dynamic_limit'] > 0
    ? min(100, round($qrCount / $user['qr_dynamic_limit'] * 100))
    : 0;
$pctScans = $user['scans_limit'] > 0
    ? min(100, round($totalScans / $user['scans_limit'] * 100))
    : 0;
$pctTrial = min(100, round((14 - $trialDaysLeft) / 14 * 100));

// Color barra según uso
function barColor(int $pct): string {
    if ($pct >= 90) return '#E24B4A';
    if ($pct >= 70) return '#EF9F27';
    return '#1D9E75';
}
?>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:var(--bs-light,#f8f9fa);min-height:100vh">

  <?php if ($trialDaysLeft <= 7): ?>
  <div class="alert d-flex justify-content-between align-items-center py-2"
       style="background:#fff8e1;border:1px solid #FAC775;color:#854F0B">
    <span>
      <i class="bi bi-clock me-1"></i>
      <strong>Tu trial termina en <?php echo $trialDaysLeft; ?> días.</strong>
      No pierdas tus QR ni tus escaneos.
    </span>
    <a href="/billing" class="btn btn-sm btn-warning text-dark">Upgrade</a>
  </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
    <div>
      <h4 class="mb-0">Hola, <?php echo htmlspecialchars($user['name']); ?></h4>
      <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
    </div>
    <span class="badge rounded-pill text-bg-dark px-3 py-2">
      <?php echo htmlspecialchars($user['plan_name']); ?>
      — $<?php echo number_format($user['plan_price'], 2); ?>/mes
    </span>
  </div>

  <!-- Métricas -->
  <div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted" style="font-size:12px">QR Dinámicos</div>
          <div class="fw-bold" style="font-size:28px">
            <?php echo $qrCount; ?>
            <span class="text-muted" style="font-size:14px">
              / <?php echo $user['qr_dynamic_limit']; ?>
            </span>
          </div>
          <div class="progress mt-2" style="height:6px">
            <div class="progress-bar" role="progressbar"
                 style="width:<?php echo $pctQr; ?>%;background:<?php echo barColor($pctQr); ?>"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted" style="font-size:12px">Escaneos</div>
          <div class="fw-bold" style="font-size:28px">
            <?php echo number_format($totalScans); ?>
            <span class="text-muted" style="font-size:14px">
              / <?php echo number_format($user['scans_limit']); ?>
            </span>
          </div>
          <div class="progress mt-2" style="height:6px">
            <div class="progress-bar" role="progressbar"
                 style="width:<?php echo $pctScans; ?>%;background:<?php echo barColor($pctScans); ?>"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted" style="font-size:12px">Trial</div>
          <div class="fw-bold" style="font-size:28px">
            <?php echo $trialDaysLeft; ?>
            <span class="text-muted" style="font-size:14px">días</span>
          </div>
          <div class="progress mt-2" style="height:6px">
            <div class="progress-bar" role="progressbar"
                 style="width:<?php echo $pctTrial; ?>%;background:<?php echo barColor($pctTrial); ?>"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted" style="font-size:12px">Plan</div>
          <div class="fw-bold mt-1" style="font-size:18px">
            <?php echo htmlspecialchars($user['plan_name']); ?>
          </div>
          <a href="/billing" class="btn btn-sm btn-outline-dark mt-2 w-100">
            Upgrade
          </a>
        </div>
      </div>
    </div>

  </div>

  <!-- Límites del plan -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <h6 class="text-muted mb-3" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
        Uso del plan
      </h6>

      <?php
      $limites = [
          ['QR Dinámicos',   $qrCount,    $user['qr_dynamic_limit']],
          ['Escaneos',       $totalScans, $user['scans_limit']],
          ['Colecciones',    0,           $user['collections_limit']],
          ['Formularios',    0,           $user['forms_limit']],
      ];
      foreach ($limites as [$label, $used, $limit]):
          $pct = $limit > 0 ? min(100, round($used / $limit * 100)) : 0;
      ?>
      <div class="mb-3">
        <div class="d-flex justify-content-between mb-1">
          <small><?php echo $label; ?></small>
          <small class="text-muted">
            <?php echo number_format($used); ?> / <?php echo number_format($limit); ?>
          </small>
        </div>
        <div class="progress" style="height:6px">
          <div class="progress-bar" role="progressbar"
               style="width:<?php echo $pct; ?>%;background:<?php echo barColor($pct); ?>">
          </div>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>

  <!-- Acceso rápido -->
  <div class="row g-3">
    <div class="col-md-4">
      <a href="/qrs/create" class="card border-0 shadow-sm text-decoration-none text-dark h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-qr-code fs-3 text-primary"></i>
          <div>
            <div class="fw-bold" style="font-size:14px">Crear QR</div>
            <div class="text-muted" style="font-size:12px">Nuevo código dinámico</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="/qrs" class="card border-0 shadow-sm text-decoration-none text-dark h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-grid fs-3 text-success"></i>
          <div>
            <div class="fw-bold" style="font-size:14px">Mis QR</div>
            <div class="text-muted" style="font-size:12px">Ver y gestionar</div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="/billing" class="card border-0 shadow-sm text-decoration-none text-dark h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-credit-card fs-3 text-warning"></i>
          <div>
            <div class="fw-bold" style="font-size:14px">Facturación</div>
            <div class="text-muted" style="font-size:12px">Planes y pagos</div>
          </div>
        </div>
      </a>
    </div>
  </div>

</main>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>