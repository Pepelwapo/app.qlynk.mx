<?php
$pageTitle = 'Biblioteca de imágenes';
require __DIR__ . '/partials/head.php';
?>
<style>
/* ── Grid de imágenes ── */
.img-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(160px,1fr));
    gap:16px;
}
.img-card {
    background:#fff;border-radius:14px;overflow:hidden;
    box-shadow:0 2px 12px rgba(0,0,0,.07);
    transition:.2s;position:relative;
}
.img-card:hover { box-shadow:0 6px 24px rgba(0,0,0,.13); transform:translateY(-2px); }
.img-thumb {
    width:100%;height:140px;object-fit:cover;display:block;
    background:#f4f5f7;
}
.img-info { padding:10px; }
.img-name { font-size:11px;color:#555;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px; }
.img-size { font-size:10px;color:#aaa; }
.img-actions { display:flex;gap:4px;margin-top:8px; }
.img-actions .btn { font-size:11px;padding:4px 8px;border-radius:7px; }

/* ── Upload drop zone ── */
#dropZone {
    border:2px dashed #d1d5db;border-radius:16px;
    padding:32px;text-align:center;cursor:pointer;
    transition:.2s;background:#fafafa;
}
#dropZone.dragover { border-color:#1a1a2e;background:#f0f1f8; }
#dropZone:hover { border-color:#9ca3af; }

/* ── Empty state ── */
.empty-state { text-align:center;padding:60px 20px;color:#aaa; }
.empty-state .ico { font-size:56px;opacity:.3;margin-bottom:12px; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class="mb-0 fw-bold">Biblioteca de imágenes</h4>
      <p class="text-muted mb-0" style="font-size:13px">
        Sube imágenes para tus menús, catálogos y formularios. Hasta 200 imágenes / 4 MB por imagen.
      </p>
    </div>
    <button class="btn btn-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#uploadModal"
            style="border-radius:10px">
      <i class="bi bi-cloud-upload me-1"></i> Subir imagen
    </button>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
  <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
    <i class="bi bi-check-circle-fill"></i>
    <?php echo htmlspecialchars($success); ?>
  </div>
  <?php endif; ?>

  <!-- Estadística rápida -->
  <?php if (!empty($images)): ?>
  <div class="d-flex align-items-center gap-3 mb-4">
    <span class="badge bg-dark" style="font-size:12px;border-radius:8px;padding:6px 12px">
      <?php echo count($images); ?> imagen<?php echo count($images) !== 1 ? 'es' : ''; ?>
    </span>
    <?php
    $totalSize = array_sum(array_column($images, 'file_size'));
    $mb = number_format($totalSize / 1048576, 1);
    ?>
    <span class="text-muted" style="font-size:12px"><?php echo $mb; ?> MB usados</span>
  </div>
  <?php endif; ?>

  <!-- Grid de imágenes -->
  <?php if (empty($images)): ?>
  <div class="card border-0 shadow-sm" style="border-radius:16px">
    <div class="empty-state">
      <div class="ico">🖼️</div>
      <h6 class="fw-bold mb-1" style="color:#555">Sin imágenes aún</h6>
      <p style="font-size:13px;max-width:280px;margin:0 auto 20px">
        Sube imágenes para usarlas en tus menús, catálogos y formularios.
      </p>
      <button class="btn btn-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#uploadModal"
              style="border-radius:10px">
        <i class="bi bi-cloud-upload me-1"></i> Subir primera imagen
      </button>
    </div>
  </div>
  <?php else: ?>
  <div class="img-grid">
    <?php foreach ($images as $img): ?>
    <div class="img-card" id="imgCard<?php echo (int)$img['id']; ?>">
      <img src="<?php echo htmlspecialchars($img['url']); ?>"
           alt="<?php echo htmlspecialchars($img['original_name']); ?>"
           class="img-thumb"
           loading="lazy"
           onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22160%22 height=%22140%22><rect width=%22100%%22 height=%22100%%22 fill=%22%23f4f5f7%22/><text x=%2250%%22 y=%2250%%22 text-anchor=%22middle%22 dy=%22.35em%22 font-size=%2224%22>🖼️</text></svg>'">
      <div class="img-info">
        <div class="img-name" title="<?php echo htmlspecialchars($img['original_name']); ?>">
          <?php echo htmlspecialchars($img['original_name']); ?>
        </div>
        <div class="img-size"><?php echo number_format($img['file_size'] / 1024, 0); ?> KB</div>
        <div class="img-actions">
          <button class="btn btn-sm btn-outline-dark flex-grow-1"
                  onclick="copyUrl('<?php echo htmlspecialchars($img['url'], ENT_QUOTES); ?>', this)"
                  title="Copiar URL">
            <i class="bi bi-clipboard me-1"></i>Copiar URL
          </button>
          <form method="POST" style="margin:0" onsubmit="return confirm('¿Eliminar esta imagen? No se puede deshacer.')">
            <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="_action"  value="delete">
            <input type="hidden" name="image_id" value="<?php echo (int)$img['id']; ?>">
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</main>
</div>

<!-- ── Modal de upload ────────────────────────────────────────────── -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius:20px">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Subir imagen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
          <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="_action" value="upload">

          <!-- Drop zone -->
          <div id="dropZone" onclick="document.getElementById('imgInput').click()">
            <div id="dropIcon" style="font-size:44px;opacity:.35;margin-bottom:8px">📤</div>
            <div class="fw-semibold" style="font-size:15px;color:#374151" id="dropText">
              Arrastra una imagen aquí
            </div>
            <div class="text-muted" style="font-size:12px;margin-top:4px">
              o haz clic para seleccionar un archivo
            </div>
            <div class="text-muted" style="font-size:11px;margin-top:8px">
              JPEG · PNG · GIF · WebP · SVG — máx. 4 MB
            </div>
          </div>
          <input type="file" id="imgInput" name="image" accept="image/*"
                 style="display:none" onchange="previewFile(this)">

          <!-- Preview -->
          <div id="previewWrap" style="display:none;text-align:center;margin-top:12px">
            <img id="previewImg" src="" alt="" style="max-width:100%;max-height:200px;border-radius:10px;object-fit:contain">
            <div id="previewName" style="font-size:12px;color:#888;margin-top:6px"></div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary flex-grow-1"
                    data-bs-dismiss="modal" style="border-radius:10px">
              Cancelar
            </button>
            <button type="submit" id="uploadBtn" class="btn btn-dark flex-grow-1 fw-semibold"
                    style="border-radius:10px" disabled>
              <i class="bi bi-cloud-upload me-1"></i>Subir imagen
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
/* ── Drag & Drop ── */
var dropZone = document.getElementById('dropZone');
var imgInput = document.getElementById('imgInput');

['dragenter','dragover'].forEach(function(ev) {
    dropZone.addEventListener(ev, function(e) {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
});
['dragleave','drop'].forEach(function(ev) {
    dropZone.addEventListener(ev, function(e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
    });
});
dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    var file = e.dataTransfer.files[0];
    if (file) {
        var dt = new DataTransfer();
        dt.items.add(file);
        imgInput.files = dt.files;
        previewFile(imgInput);
    }
});

/* ── Preview ── */
function previewFile(input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewName').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        document.getElementById('previewWrap').style.display = 'block';
        document.getElementById('dropText').textContent = 'Imagen seleccionada';
        document.getElementById('uploadBtn').disabled = false;
    };
    reader.readAsDataURL(file);
}

/* ── Copy URL ── */
function copyUrl(url, btn) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            var orig = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Copiado!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-dark');
            setTimeout(function() {
                btn.innerHTML = orig;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-dark');
            }, 2000);
        });
    } else {
        var ta = document.createElement('textarea');
        ta.value = url;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        alert('URL copiada: ' + url);
    }
}

/* ── Auto-abrir modal si hubo error de upload ── */
<?php if (!empty($error)): ?>
document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('uploadModal'));
    modal.show();
});
<?php endif; ?>
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
