<?php
$pageTitle = 'Respuestas — ' . $form['name'];
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center mb-4">
    <a href="/forms/edit?id=<?= $form['id'] ?>" class="btn btn-sm btn-outline-secondary me-3" style="border-radius:8px"><i class="bi bi-arrow-left"></i></a>
    <div>
      <h4 class="mb-0 fw-bold">Respuestas del formulario</h4>
      <small class="text-muted"><?= htmlspecialchars($form['name']) ?></small>
    </div>
  </div>

  <?php if (empty($responses)): ?>
  <div class="card border-0 shadow-sm text-center p-5" style="border-radius:16px;max-width:500px;margin:0 auto">
    <div style="font-size:48px;margin-bottom:16px">📭</div>
    <h5 class="fw-bold mb-2">Sin respuestas aún</h5>
    <p class="text-muted mb-4" style="font-size:14px">
      Comparte el enlace de tu formulario y las respuestas aparecerán aquí.
    </p>
    <a href="/f/<?= htmlspecialchars($form['slug']) ?>" target="_blank" class="btn btn-outline-dark fw-semibold" style="border-radius:10px">
      <i class="bi bi-box-arrow-up-right me-2"></i>Ver formulario público
    </a>
  </div>

  <?php else: ?>
  <p class="text-muted mb-3" style="font-size:14px"><?= count($responses) ?> respuesta<?= count($responses) !== 1 ? 's' : '' ?> recibida<?= count($responses) !== 1 ? 's' : '' ?></p>

  <?php if (empty($fields)): ?>
  <div class="alert alert-warning" style="border-radius:12px">Este formulario ya no tiene campos configurados.</div>
  <?php endif; ?>

  <div class="d-flex flex-column gap-3">
    <?php foreach ($responses as $idx => $resp): ?>
    <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
      <div class="card-header d-flex justify-content-between align-items-center" style="background:#f8f9fa;border:0;padding:10px 16px">
        <span class="fw-semibold" style="font-size:13px">#<?= count($responses) - $idx ?></span>
        <div class="text-muted" style="font-size:12px">
          <i class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($resp['created_at'])) ?>
          <?php if ($resp['ip_address']): ?>
          &nbsp;·&nbsp;<i class="bi bi-geo me-1"></i><?= htmlspecialchars($resp['ip_address']) ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body p-0">
        <?php if (!empty($resp['data_decoded'])): ?>
        <table class="table table-sm mb-0" style="font-size:13px">
          <?php foreach ($resp['data_decoded'] as $label => $value): ?>
          <tr style="border-bottom:1px solid #f0f0f0">
            <td class="py-2 px-3 fw-semibold text-muted" style="width:35%;background:#fafafa"><?= htmlspecialchars($label) ?></td>
            <td class="py-2 px-3"><?= nl2br(htmlspecialchars((string)$value)) ?></td>
          </tr>
          <?php endforeach; ?>
        </table>
        <?php else: ?>
        <div class="p-3 text-muted" style="font-size:13px">Respuesta vacía.</div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
