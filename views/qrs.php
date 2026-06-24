<?php
$pageTitle = 'QR Dinámicos';
$goBaseUrl = APP_GO_URL;
require __DIR__ . '/partials/head.php';
?>
<style>
.qr-thumb-cell { width: 70px; text-align: center; }
.qr-thumb-wrap { display:inline-block; }
.folder-tab    { font-size:13px;padding:6px 14px;border-radius:20px;border:1px solid #e0e0e0;
                 background:#fff;cursor:pointer;text-decoration:none;color:#555;transition:.15s; }
.folder-tab:hover   { background:#f8f9fa;color:#333; }
.folder-tab.active  { background:#1a1a2e;color:#fff;border-color:#1a1a2e; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <?php if (!empty($created)): ?>
  <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>QR creado correctamente. <strong>¡Ya puedes descargarlo desde Editar!</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php endif; ?>

  <!-- ── Header ── -->
  <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <h4 class="mb-0 fw-bold me-2">QR Dinámicos</h4>

    <!-- Toggle miniaturas -->
    <button id="btnThumb" onclick="toggleThumbs()"
            class="btn btn-sm btn-outline-secondary" style="border-radius:20px;font-size:12px">
      <i class="bi bi-image me-1"></i><span id="thumbLabel">Ocultar miniaturas</span>
    </button>

    <div class="ms-auto d-flex gap-2">
      <button class="btn btn-sm btn-outline-dark" style="border-radius:20px;font-size:12px"
              data-bs-toggle="modal" data-bs-target="#modalFolder">
        <i class="bi bi-folder-plus me-1"></i>Crear sección
      </button>
      <a href="/qrs/create" class="btn btn-sm btn-dark" style="border-radius:20px;font-size:12px">
        <i class="bi bi-plus-lg me-1"></i>Nuevo QR
      </a>
    </div>
  </div>

  <!-- ── Tabs de secciones ── -->
  <?php if (!empty($folders)): ?>
  <div class="d-flex flex-wrap gap-2 mb-4">
    <a href="/qrs" class="folder-tab <?php echo $currentFolder === 0 ? 'active' : ''; ?>">
      Todos
    </a>
    <?php foreach ($folders as $folder): ?>
    <span class="folder-tab d-inline-flex align-items-center gap-1
          <?php echo $currentFolder === (int)$folder['id'] ? 'active' : ''; ?>">
      <a href="/qrs?folder=<?php echo (int)$folder['id']; ?>"
         style="text-decoration:none;color:inherit">
        <?php echo htmlspecialchars($folder['name']); ?>
      </a>
      <form method="POST" action="/qrs" style="display:inline;margin:0"
            onsubmit="return confirm('¿Eliminar esta sección? Los QRs quedarán sin sección.')">
        <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_action" value="del_folder">
        <input type="hidden" name="folder_id" value="<?php echo (int)$folder['id']; ?>">
        <button type="submit"
                style="background:none;border:none;padding:0 2px;cursor:pointer;
                       color:rgba(255,255,255,.6);font-size:14px;line-height:1"
                title="Eliminar sección">×</button>
      </form>
    </span>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- ── Tabla / Grid de QRs ── -->
  <?php if (empty($qrs)): ?>
    <div class="card border-0 shadow-sm text-center py-5" style="border-radius:16px">
      <div style="font-size:48px">📭</div>
      <h5 class="mt-3 mb-1">Aún no tienes QRs aquí</h5>
      <p class="text-muted mb-4">Crea tu primer QR dinámico y empieza a rastrear escaneos.</p>
      <div><a href="/qrs/create" class="btn btn-dark px-4">
        <i class="bi bi-plus-lg me-2"></i>Crear mi primer QR
      </a></div>
    </div>
  <?php else: ?>
  <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
  <div class="table-responsive">
  <table class="table table-hover mb-0 align-middle">
    <thead style="background:#f8f9fa;font-size:12px;text-transform:uppercase;letter-spacing:.4px;color:#888">
      <tr>
        <th class="qr-thumb-cell thumb-col ps-3">QR</th>
        <th>Nombre</th>
        <th>Código</th>
        <th>Tipo</th>
        <th>Escaneos</th>
        <?php if (!empty($folders)): ?>
        <th>Sección</th>
        <?php endif; ?>
        <th>Estado</th>
        <th class="pe-3">Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $typeLabels = [
        'url'      => 'URL',
        'whatsapp' => 'WhatsApp',
        'email'    => 'Email',
        'phone'    => 'Llamada',
        'sms'      => 'SMS',
        'wifi'     => 'WiFi',
        'vcard'    => 'vCard',
        'pdf'      => 'PDF',
        'social'   => 'Social',
        'event'    => 'Evento',
    ];
    foreach ($qrs as $qr):
        $goUrl     = $goBaseUrl . '/' . $qr['short_code'];
        $typeLabel = $typeLabels[$qr['type'] ?? 'url'] ?? strtoupper($qr['type']);
    ?>
    <tr>
      <td class="qr-thumb-cell thumb-col ps-3">
        <div class="qr-thumb-wrap"
             data-go-url="<?php echo htmlspecialchars($goUrl); ?>"
             style="width:56px;height:56px"></div>
      </td>

      <td>
        <div class="fw-semibold" style="font-size:14px"><?php echo htmlspecialchars($qr['name']); ?></div>
        <?php if (!empty($qr['expires_at'])): ?>
        <div style="font-size:11px;color:#aaa">
          ⏳ Expira <?php echo date('d/m/Y', strtotime($qr['expires_at'])); ?>
        </div>
        <?php endif; ?>
      </td>

      <td><code style="font-size:12px"><?php echo htmlspecialchars($qr['short_code']); ?></code></td>

      <td>
        <span class="badge text-bg-light border" style="font-size:11px"><?php echo $typeLabel; ?></span>
      </td>

      <td><span class="fw-semibold"><?php echo number_format((int)$qr['scan_count']); ?></span></td>

      <?php if (!empty($folders)): ?>
      <td>
        <form method="POST" action="/qrs" style="margin:0">
          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="_action" value="move_qr">
          <input type="hidden" name="qr_id" value="<?php echo (int)$qr['id']; ?>">
          <input type="hidden" name="current_folder" value="<?php echo $currentFolder; ?>">
          <select name="folder_id" class="form-select form-select-sm"
                  style="font-size:12px;min-width:110px;border-radius:8px"
                  onchange="this.form.submit()">
            <option value="">Sin sección</option>
            <?php foreach ($folders as $f): ?>
            <option value="<?php echo (int)$f['id']; ?>"
                    <?php echo ((int)($qr['folder_id'] ?? 0) === (int)$f['id']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($f['name']); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </form>
      </td>
      <?php endif; ?>

      <td>
        <a href="/qrs/toggle?id=<?php echo (int)$qr['id']; ?>"
           class="badge text-decoration-none <?php echo $qr['active'] ? 'bg-success' : 'bg-secondary'; ?>"
           style="font-size:11px" title="Clic para cambiar estado">
          <?php echo $qr['active'] ? 'Activo' : 'Inactivo'; ?>
        </a>
      </td>

      <td class="pe-3">
        <div class="d-flex gap-1">
          <a href="/qrs/edit?id=<?php echo (int)$qr['id']; ?>"
             class="btn btn-sm btn-outline-dark" style="border-radius:8px;font-size:12px">
            <i class="bi bi-pencil"></i> Editar
          </a>
          <a href="<?php echo htmlspecialchars($goUrl); ?>" target="_blank"
             class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:12px"
             title="Abrir enlace corto">
            <i class="bi bi-box-arrow-up-right"></i>
          </a>
          <a href="/qrs/delete?id=<?php echo (int)$qr['id']; ?>"
             class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:12px"
             onclick="return confirm('¿Eliminar este QR? Los escaneos se perderán.')">
            <i class="bi bi-trash"></i>
          </a>
        </div>
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

<!-- ── Modal: crear sección ── -->
<div class="modal fade" id="modalFolder" tabindex="-1">
  <div class="modal-dialog modal-sm">
  <div class="modal-content" style="border-radius:16px;border:0;overflow:hidden">
    <div class="modal-header border-0 pb-0">
      <h6 class="modal-title fw-bold">Nueva sección</h6>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body pt-2">
      <form method="POST" action="/qrs">
        <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="_action" value="create_folder">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:13px">Nombre de la sección</label>
          <input name="folder_name" class="form-control" style="border-radius:10px"
                 placeholder="Ej: Campañas 2025, Tienda Centro…" required autofocus>
          <small class="text-muted">Agrupa tus QRs en secciones temáticas.</small>
        </div>
        <button type="submit" class="btn btn-dark w-100 fw-semibold" style="border-radius:10px">
          <i class="bi bi-folder-plus me-1"></i>Crear sección
        </button>
      </form>
    </div>
  </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
var THUMB_KEY = 'qlynk_qr_thumbs';

function renderThumbs() {
    document.querySelectorAll('.qr-thumb-wrap').forEach(function(el) {
        if (el.children.length > 0) return;
        var url = el.getAttribute('data-go-url');
        if (!url) return;
        try {
            new QRCode(el, {
                text:         url,
                width:        56,
                height:       56,
                colorDark:    '#1a1a2e',
                colorLight:   '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        } catch(e) {}
    });
}

function applyThumbVisibility(show) {
    document.querySelectorAll('.thumb-col').forEach(function(el) {
        el.style.display = show ? '' : 'none';
    });
    document.getElementById('thumbLabel').textContent = show ? 'Ocultar miniaturas' : 'Mostrar miniaturas';
    if (show) renderThumbs();
}

function toggleThumbs() {
    var current = localStorage.getItem(THUMB_KEY) !== 'false';
    var next    = !current;
    localStorage.setItem(THUMB_KEY, next ? 'true' : 'false');
    applyThumbVisibility(next);
}

// Al cargar: aplicar preferencia guardada (default: mostrar)
(function() {
    var show = localStorage.getItem(THUMB_KEY) !== 'false';
    applyThumbVisibility(show);
})();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
