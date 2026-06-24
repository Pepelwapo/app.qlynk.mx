<?php
$pageTitle = 'Mis Catálogos';
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Catálogos de Productos</h4>
    <a href="/catalogs/create" class="btn btn-dark btn-sm fw-semibold" style="border-radius:8px;padding:8px 16px">
      <i class="bi bi-plus-lg me-1"></i>Crear Catálogo
    </a>
  </div>

  <?php if (empty($catalogs)): ?>
  <div class="card border-0 shadow-sm text-center p-5" style="border-radius:16px;max-width:500px;margin:0 auto">
    <div style="font-size:48px;margin-bottom:16px">📦</div>
    <h5 class="fw-bold mb-2">Aún no tienes catálogos</h5>
    <p class="text-muted mb-4" style="font-size:14px">
      Crea tu primer catálogo de productos y compártelo con tus clientes.
    </p>
    <a href="/catalogs/create" class="btn btn-dark fw-semibold" style="border-radius:10px">
      <i class="bi bi-collection me-2"></i>Crear mi primer catálogo
    </a>
  </div>

  <?php else: ?>
  <div class="row g-3">
    <?php foreach ($catalogs as $c): ?>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100" style="border-radius:16px;overflow:hidden">
        <div style="background:#1a1a2e;height:8px"></div>
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="fw-bold mb-0" style="font-size:15px"><?= htmlspecialchars($c['name']) ?></h6>
            <span class="badge <?= $c['active'] ? 'bg-success' : 'bg-secondary' ?>" style="font-size:10px">
              <?= $c['active'] ? 'Activo' : 'Inactivo' ?>
            </span>
          </div>
          <?php if ($c['description']): ?>
          <p class="text-muted mb-2" style="font-size:13px;line-height:1.4"><?= htmlspecialchars(mb_substr($c['description'], 0, 80)) ?>...</p>
          <?php endif; ?>
          <div class="d-flex align-items-center gap-1 mb-3">
            <code style="font-size:11px;background:#f0f0f5;padding:2px 6px;border-radius:4px">/c/<?= htmlspecialchars($c['slug']) ?></code>
            <a href="/c/<?= htmlspecialchars($c['slug']) ?>" target="_blank" class="text-muted" style="font-size:12px"><i class="bi bi-box-arrow-up-right"></i></a>
          </div>
          <div class="d-flex gap-2">
            <a href="/catalogs/edit?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-dark flex-grow-1" style="border-radius:8px;font-size:13px">
              <i class="bi bi-pencil me-1"></i>Editar
            </a>
            <a href="/c/<?= htmlspecialchars($c['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:13px" title="Ver público">
              <i class="bi bi-eye"></i>
            </a>
            <a href="/catalogs/delete?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:13px" title="Eliminar"
               onclick="return confirm('¿Eliminar este catálogo?')">
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
