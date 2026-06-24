<?php
if (!isset($pdo)) { header('Location: /login'); exit; }

$stmt = $pdo->prepare("
    SELECT * FROM qr_codes
    WHERE user_id = ?
    AND deleted_at IS NULL
    ORDER BY id DESC
");
$stmt->execute([$_SESSION['user_id']]);
$qrs = $stmt->fetchAll();

$pageTitle = 'Mis QR';
require __DIR__.'/partials/head.php';
?>
<div class="d-flex">
<?php require __DIR__.'/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">QR Dinámicos</h4>
    <a href="/qrs/create" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Crear QR
    </a>
  </div>

  <?php if (empty($qrs)): ?>
    <div class="alert alert-info">
      No tienes QR creados aún. <a href="/qrs/create">Crea el primero</a>.
    </div>
  <?php else: ?>
  <div class="table-responsive">
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Código</th>
        <th>Tipo</th>
        <th>Escaneos</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($qrs as $qr): ?>
    <tr>
      <td><?php echo (int)$qr['id']; ?></td>
      <td><?php echo htmlspecialchars($qr['name']); ?></td>
      <td><code><?php echo htmlspecialchars($qr['short_code']); ?></code></td>
      <td><?php echo htmlspecialchars($qr['type'] ?? 'url'); ?></td>
      <td><?php echo (int)$qr['scan_count']; ?></td>
      <td>
        <a href="/qrs/toggle?id=<?php echo (int)$qr['id']; ?>"
           class="badge text-decoration-none <?php echo $qr['active'] ? 'bg-success' : 'bg-secondary'; ?>">
          <?php echo $qr['active'] ? 'Activo' : 'Inactivo'; ?>
        </a>
      </td>
      <td>
        <a href="/qrs/edit?id=<?php echo (int)$qr['id']; ?>"
           class="btn btn-sm btn-outline-primary">Editar</a>
        <a href="/qrs/delete?id=<?php echo (int)$qr['id']; ?>"
           class="btn btn-sm btn-outline-danger"
           onclick="return confirm('¿Eliminar este QR? No se puede deshacer.')">Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php endif; ?>

</main>
</div>
<?php require __DIR__.'/partials/footer.php'; ?>