<?php
$pageTitle = 'Mis Formularios';
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Formularios de Contacto</h4>
    <a href="/forms/create" class="btn btn-dark btn-sm fw-semibold" style="border-radius:8px;padding:8px 16px">
      <i class="bi bi-plus-lg me-1"></i>Crear Formulario
    </a>
  </div>

  <?php if (empty($forms)): ?>
  <div class="card border-0 shadow-sm text-center p-5" style="border-radius:16px;max-width:500px;margin:0 auto">
    <div style="font-size:48px;margin-bottom:16px">📋</div>
    <h5 class="fw-bold mb-2">Aún no tienes formularios</h5>
    <p class="text-muted mb-4" style="font-size:14px">
      Crea formularios de contacto y recibe respuestas directamente desde tu panel.
    </p>
    <a href="/forms/create" class="btn btn-dark fw-semibold" style="border-radius:10px">
      <i class="bi bi-ui-checks me-2"></i>Crear mi primer formulario
    </a>
  </div>

  <?php else: ?>
  <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
    <div class="table-responsive">
    <table class="table mb-0">
      <thead style="background:#f8f9fa;border-bottom:2px solid #eee">
        <tr style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:#888">
          <th class="py-3 px-3">Nombre</th>
          <th class="py-3 px-3">Slug</th>
          <th class="py-3 px-3 text-center">Respuestas</th>
          <th class="py-3 px-3 text-center">Estado</th>
          <th class="py-3 px-3 text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($forms as $f): ?>
        <tr style="border-bottom:1px solid #f0f0f0;vertical-align:middle">
          <td class="py-3 px-3 fw-semibold"><?= htmlspecialchars($f['name']) ?></td>
          <td class="py-3 px-3">
            <code style="font-size:12px;background:#f0f0f5;padding:2px 6px;border-radius:4px">/f/<?= htmlspecialchars($f['slug']) ?></code>
          </td>
          <td class="py-3 px-3 text-center">
            <a href="/forms/responses?id=<?= $f['id'] ?>" class="text-decoration-none fw-bold">
              <?= (int)$f['response_count'] ?>
            </a>
          </td>
          <td class="py-3 px-3 text-center">
            <span class="badge <?= $f['active'] ? 'bg-success' : 'bg-secondary' ?>" style="font-size:11px">
              <?= $f['active'] ? 'Activo' : 'Inactivo' ?>
            </span>
          </td>
          <td class="py-3 px-3 text-end">
            <a href="/forms/edit?id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-dark me-1" style="border-radius:6px;font-size:12px">
              <i class="bi bi-pencil"></i>
            </a>
            <a href="/forms/responses?id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary me-1" style="border-radius:6px;font-size:12px" title="Ver respuestas">
              <i class="bi bi-inbox"></i>
            </a>
            <a href="/f/<?= htmlspecialchars($f['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1" style="border-radius:6px;font-size:12px" title="Ver formulario">
              <i class="bi bi-eye"></i>
            </a>
            <a href="/forms/delete?id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:12px" title="Eliminar"
               onclick="return confirm('¿Eliminar este formulario y todas sus respuestas?')">
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>
  <?php endif; ?>

</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
