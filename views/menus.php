<?php
$pageTitle = 'Mis Menús';
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Menús Digitales</h4>
    <a href="/menus/create" class="btn btn-dark btn-sm fw-semibold" style="border-radius:8px;padding:8px 16px">
      <i class="bi bi-plus-lg me-1"></i>Crear Menú
    </a>
  </div>

  <?php if (empty($menus)): ?>
  <div class="card border-0 shadow-sm text-center p-5" style="border-radius:16px;max-width:500px;margin:0 auto">
    <div style="font-size:48px;margin-bottom:16px">🍽️</div>
    <h5 class="fw-bold mb-2">Aún no tienes menús digitales</h5>
    <p class="text-muted mb-4" style="font-size:14px">
      Crea tu primer menú digital y compártelo con un QR o enlace.
    </p>
    <a href="/menus/create" class="btn btn-dark fw-semibold" style="border-radius:10px">
      <i class="bi bi-menu-button-wide me-2"></i>Crear mi primer menú
    </a>
  </div>

  <?php else: ?>
  <div class="row g-3">
    <?php foreach ($menus as $m): ?>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100" style="border-radius:16px;overflow:hidden">
        <div style="background:<?= htmlspecialchars($m['color']) ?>;height:8px"></div>
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="fw-bold mb-0" style="font-size:15px"><?= htmlspecialchars($m['name']) ?></h6>
            <span class="badge <?= $m['active'] ? 'bg-success' : 'bg-secondary' ?>" style="font-size:10px">
              <?= $m['active'] ? 'Activo' : 'Inactivo' ?>
            </span>
          </div>
          <?php if ($m['description']): ?>
          <p class="text-muted mb-2" style="font-size:13px;line-height:1.4"><?= htmlspecialchars(mb_substr($m['description'], 0, 80)) ?>...</p>
          <?php endif; ?>
          <div class="d-flex align-items-center gap-1 mb-3">
            <code style="font-size:11px;background:#f0f0f5;padding:2px 6px;border-radius:4px">/m/<?= htmlspecialchars($m['slug']) ?></code>
            <a href="<?= APP_APP_URL ?? 'https://app.qlynk.mx' ?>/m/<?= htmlspecialchars($m['slug']) ?>" target="_blank" class="text-muted" style="font-size:12px"><i class="bi bi-box-arrow-up-right"></i></a>
          </div>
          <div class="d-flex gap-2">
            <a href="/menus/edit?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-dark flex-grow-1" style="border-radius:8px;font-size:13px">
              <i class="bi bi-pencil me-1"></i>Editar
            </a>
            <a href="/m/<?= htmlspecialchars($m['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:13px" title="Ver público">
              <i class="bi bi-eye"></i>
            </a>
            <a href="/menus/delete?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:13px" title="Eliminar"
               onclick="return confirm('¿Eliminar este menú?')">
              <i class="bi bi-trash"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
