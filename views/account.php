<?php
$pageTitle  = 'Mi Cuenta';
$activeTab  = $_GET['tab'] ?? 'datos';
$pwdSuccess = $_SESSION['pwd_success'] ?? '';
$pwdErrors  = $_SESSION['pwd_errors']  ?? [];
unset($_SESSION['pwd_success'], $_SESSION['pwd_errors']);

require __DIR__ . '/partials/head.php';
?>

<style>
.acc-nav .nav-link {
    color: #6c757d;
    border: none;
    border-left: 3px solid transparent;
    border-radius: 0;
    padding: 11px 18px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 9px;
    transition: .15s;
}
.acc-nav .nav-link:hover  { background: #f0f0f0; color: #1a1a2e; }
.acc-nav .nav-link.active { color:#1a1a2e;background:#f8f9fa;border-left:3px solid #1a1a2e;font-weight:600; }
.acc-card  { border:none;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.06); }
.acc-label { font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;display:block; }
.acc-input { border-radius:10px;border:1px solid #e5e7eb;padding:10px 14px;font-size:14px;width:100%;transition:border-color .15s,box-shadow .15s;background:#fff; }
.acc-input:focus { outline:none;border-color:#1a1a2e;box-shadow:0 0 0 3px rgba(26,26,46,.08); }
.btn-acc   { background:#1a1a2e;color:#fff;border:none;border-radius:10px;padding:10px 28px;font-size:14px;font-weight:600;cursor:pointer;transition:background .2s; }
.btn-acc:hover { background:#0f3460; }
.plan-hero { background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%);color:#fff;border-radius:14px;padding:20px 24px;display:flex;justify-content:space-between;align-items:center; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>

<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:52px;height:52px;border-radius:50%;background:#1a1a2e;
                display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="bi bi-person-fill text-white" style="font-size:22px"></i>
    </div>
    <div>
      <div class="fw-bold" style="font-size:17px"><?php echo htmlspecialchars($user['name']); ?></div>
      <div class="text-muted" style="font-size:13px">
        <?php echo htmlspecialchars($user['email']); ?>
        &nbsp;·&nbsp;
        <span class="badge bg-dark" style="font-size:11px;border-radius:6px">
          <?php echo htmlspecialchars($user['plan_name']); ?>
        </span>
      </div>
    </div>
  </div>

  <div class="row g-4">

    <div class="col-md-3">
      <div class="acc-card card">
        <nav class="nav acc-nav flex-column py-2">
          <a class="nav-link <?php echo $activeTab === 'datos'    ? 'active' : ''; ?>" href="/account?tab=datos">
            <i class="bi bi-person"></i> Mis datos
          </a>
          <a class="nav-link <?php echo $activeTab === 'password' ? 'active' : ''; ?>" href="/account?tab=password">
            <i class="bi bi-shield-lock"></i> Contraseña
          </a>
          <a class="nav-link <?php echo $activeTab === 'plan'     ? 'active' : ''; ?>" href="/account?tab=plan">
            <i class="bi bi-credit-card"></i> Plan y pagos
          </a>
        </nav>
      </div>
    </div>

    <div class="col-md-9">

      <?php if ($activeTab === 'datos'): ?>
      <!-- ── MIS DATOS ── -->
      <div class="acc-card card">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4" style="font-size:15px">
          <i class="bi bi-person me-2 text-muted"></i>Información personal
        </h6>

        <?php if ($success): ?>
        <div class="alert alert-success border-0 rounded-3 py-2 mb-3" style="font-size:13px">
          <i class="bi bi-check-circle me-1"></i><?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger border-0 rounded-3 py-2 mb-3" style="font-size:13px">
          <?php foreach ($errors as $e): ?>
          <div><i class="bi bi-exclamation-circle me-1"></i><?php echo htmlspecialchars($e); ?></div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/account">
          <input type="hidden"
                 name="<?php echo CSRF_TOKEN_NAME; ?>"
                 value="<?php echo csrf_token(); ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="acc-label">Nombre *</label>
              <input name="name" class="acc-input"
                     value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="acc-label">Empresa</label>
              <input name="company" class="acc-input" placeholder="Opcional"
                     value="<?php echo htmlspecialchars($user['company'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
              <label class="acc-label">Correo electrónico</label>
              <input name="email" type="email" class="acc-input"
                     value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="col-md-6">
              <label class="acc-label">Teléfono</label>
              <input name="phone" class="acc-input" placeholder="Opcional"
                     value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
              <label class="acc-label">RFC</label>
              <input name="rfc" class="acc-input" maxlength="13"
                     style="text-transform:uppercase" placeholder="Opcional"
                     value="<?php echo htmlspecialchars($user['rfc'] ?? ''); ?>">
            </div>
          </div>
          <div class="mt-4 pt-3 border-top d-flex justify-content-end">
            <button type="submit" class="btn-acc">Guardar cambios</button>
          </div>
        </form>
      </div>
      </div>

      <?php elseif ($activeTab === 'password'): ?>
      <!-- ── CONTRASEÑA ── -->
      <div class="acc-card card" style="max-width:460px">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4" style="font-size:15px">
          <i class="bi bi-shield-lock me-2 text-muted"></i>Cambiar contraseña
        </h6>

        <?php if ($pwdSuccess): ?>
        <div class="alert alert-success border-0 rounded-3 py-2 mb-3" style="font-size:13px">
          <i class="bi bi-check-circle me-1"></i><?php echo htmlspecialchars($pwdSuccess); ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($pwdErrors)): ?>
        <div class="alert alert-danger border-0 rounded-3 py-2 mb-3" style="font-size:13px">
          <?php foreach ($pwdErrors as $e): ?>
          <div><i class="bi bi-exclamation-circle me-1"></i><?php echo htmlspecialchars($e); ?></div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/account/password">
          <input type="hidden"
                 name="<?php echo CSRF_TOKEN_NAME; ?>"
                 value="<?php echo csrf_token(); ?>">
          <div class="mb-3">
            <label class="acc-label">Contraseña actual</label>
            <input name="current_password" type="password" class="acc-input"
                   placeholder="••••••••" required>
          </div>
          <div class="mb-3">
            <label class="acc-label">Nueva contraseña</label>
            <input name="new_password" type="password" class="acc-input"
                   placeholder="Mín. <?php echo PASSWORD_MIN_LENGTH; ?> caracteres" required>
          </div>
          <div class="mb-4">
            <label class="acc-label">Confirmar nueva contraseña</label>
            <input name="confirm_password" type="password" class="acc-input"
                   placeholder="••••••••" required>
          </div>
          <div class="pt-3 border-top d-flex justify-content-end">
            <button type="submit" class="btn-acc">Actualizar contraseña</button>
          </div>
        </form>
      </div>
      </div>

      <?php elseif ($activeTab === 'plan'): ?>
      <!-- ── PLAN Y PAGOS ── -->
      <div class="acc-card card mb-4">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4" style="font-size:15px">
          <i class="bi bi-lightning me-2 text-muted"></i>Plan actual
        </h6>
        <div class="plan-hero">
          <div>
            <div class="fw-bold" style="font-size:20px"><?php echo htmlspecialchars($user['plan_name']); ?></div>
            <div style="font-size:13px;opacity:.7;margin-top:2px">
              $<?php echo number_format($user['plan_price'], 2); ?> MXN / mes
            </div>
            <?php if (!empty($user['trial_end'])): ?>
            <div style="font-size:12px;opacity:.55;margin-top:4px">
              Trial hasta <?php echo date('d/m/Y', strtotime($user['trial_end'])); ?>
            </div>
            <?php endif; ?>
          </div>
          <a href="/billing"
             style="background:#fff;color:#1a1a2e;border:none;border-radius:10px;
                    padding:8px 18px;font-size:13px;font-weight:700;text-decoration:none">
            Cambiar plan
          </a>
        </div>

        <?php if ($subscription): ?>
        <div class="mt-3 p-3 rounded-3"
             style="background:#f0fff4;border:1px solid #c3e6cb;font-size:13px">
          <i class="bi bi-check-circle-fill text-success me-1"></i>
          <strong>Suscripción activa</strong> — <?php echo htmlspecialchars($subscription['plan_name']); ?>
          <?php if ($subscription['next_billing_date']): ?>
          · Próximo cobro:
          <strong><?php echo date('d/m/Y', strtotime($subscription['next_billing_date'])); ?></strong>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      </div>

      <div class="acc-card card">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-4" style="font-size:15px">
          <i class="bi bi-receipt me-2 text-muted"></i>Historial de pagos
        </h6>
        <?php if (empty($payments)): ?>
        <div class="text-center py-5 text-muted">
          <i class="bi bi-inbox" style="font-size:40px;opacity:.2;display:block;margin-bottom:10px"></i>
          <span style="font-size:14px">No hay pagos registrados aún.</span>
        </div>
        <?php else: ?>
        <div class="table-responsive">
        <table class="table table-sm align-middle" style="font-size:13px">
          <thead style="font-size:11px;text-transform:uppercase;color:#aaa">
            <tr><th>Fecha</th><th>Descripción</th><th>Monto</th><th>Estado</th></tr>
          </thead>
          <tbody>
          <?php foreach ($payments as $p): ?>
          <tr>
            <td><?php echo date('d/m/Y', strtotime($p['created_at'])); ?></td>
            <td><?php echo htmlspecialchars($p['description'] ?? '—'); ?></td>
            <td>$<?php echo number_format($p['amount'] ?? 0, 2); ?> <?php echo htmlspecialchars($p['currency']); ?></td>
            <td>
              <?php
                $estado = $p['mp_status'] ?? $p['status'] ?? '—';
                $badge  = match($estado) {
                    'approved','active' => 'bg-success',
                    'pending'           => 'bg-warning text-dark',
                    default             => 'bg-danger'
                };
              ?>
              <span class="badge <?php echo $badge; ?>" style="font-size:11px;border-radius:6px">
                <?php echo htmlspecialchars($estado); ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        </div>
        <?php endif; ?>
      </div>
      </div>

      <?php endif; ?>
    </div>
  </div>
</main>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
