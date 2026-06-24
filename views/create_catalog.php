<?php
$pageTitle = 'Nuevo Catálogo';
require __DIR__ . '/partials/head.php';
?>
<style>
/* ── Selector de plantilla ── */
.tpl-card {
    border:2px solid #e5e7eb;border-radius:14px;padding:0;overflow:hidden;
    cursor:pointer;transition:.15s;background:#fff;
}
.tpl-card:hover { border-color:#6c757d;transform:translateY(-2px);box-shadow:0 4px 16px rgba(0,0,0,.08); }
.tpl-card.selected { border-color:#1a1a2e;box-shadow:0 0 0 3px rgba(26,26,46,.15); }
.tpl-preview { height:120px;overflow:hidden;position:relative; }
.tpl-label   { padding:9px 14px 11px;display:flex;align-items:center;gap:8px; }
.tpl-label .name  { font-size:14px;font-weight:700;color:#111; }
.tpl-label .badge { font-size:10px;background:#1a1a2e;color:#fff;border-radius:20px;padding:2px 7px; }

/* Preview Classic */
.prev-c { background:#fff;display:flex;flex-direction:column; }
.prev-c .bar   { height:32px;background:#1a1a2e; }
.prev-c .row   { display:flex;justify-content:space-between;padding:5px 10px;font-size:10px;color:#555;border-bottom:1px solid #f0f0f0; }
.prev-c .price { font-weight:700;color:#1a1a2e; }

/* Preview Dark */
.prev-d { background:#0f0f1a;display:flex;flex-direction:column; }
.prev-d .bar   { height:32px;background:linear-gradient(90deg,#e74c3c,#c0392b); }
.prev-d .row   { display:flex;justify-content:space-between;padding:5px 10px;font-size:10px;color:#aaa;margin:3px 8px;background:rgba(255,255,255,.04);border-radius:6px; }
.prev-d .price { font-weight:700;color:#e74c3c; }

/* Preview Cards */
.prev-k { background:#f2f4f8;display:grid!important;grid-template-columns:1fr 1fr;gap:5px;padding:7px; }
.prev-k .card-item { background:#fff;border-radius:8px;padding:6px 8px;box-shadow:0 1px 3px rgba(0,0,0,.06); }
.prev-k .ci-name  { font-size:9px;font-weight:700;color:#111;margin-bottom:2px; }
.prev-k .ci-price { font-size:10px;font-weight:800;color:#1a1a2e; }
.prev-k .ci-sku   { font-size:8px;color:#bbb; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="/catalogs" class="text-muted text-decoration-none" style="font-size:13px">
      <i class="bi bi-arrow-left"></i> Catálogos
    </a>
    <span class="text-muted">/</span>
    <span style="font-size:13px">Nuevo catálogo</span>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger" style="border-radius:12px;max-width:700px">
    <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <form method="POST" style="max-width:700px">
    <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">

    <!-- ── 1. Selector de plantilla ── -->
    <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
      <h6 class="fw-bold mb-1">Elige una plantilla</h6>
      <p class="text-muted mb-4" style="font-size:13px">Podrás personalizar el contenido después.</p>

      <input type="hidden" name="template" id="tplInput"
             value="<?php echo htmlspecialchars($_POST['template'] ?? 'classic'); ?>">

      <div class="row g-3">

        <!-- Classic -->
        <div class="col-4">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'classic') === 'classic' ? 'selected' : ''; ?>"
               onclick="pickTemplate('classic', this)">
            <div class="tpl-preview prev-c flex-column">
              <div class="bar"></div>
              <div class="row"><span>Producto A</span><span class="price">$350</span></div>
              <div class="row"><span>Producto B</span><span class="price">$580</span></div>
              <div class="row"><span>Producto C</span><span class="price">$220</span></div>
            </div>
            <div class="tpl-label">
              <span class="name">Clásico</span>
            </div>
          </div>
        </div>

        <!-- Dark -->
        <div class="col-4">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'classic') === 'dark' ? 'selected' : ''; ?>"
               onclick="pickTemplate('dark', this)">
            <div class="tpl-preview prev-d flex-column">
              <div class="bar"></div>
              <div class="row"><span>Producto A</span><span class="price">$350</span></div>
              <div class="row"><span>Producto B</span><span class="price">$580</span></div>
              <div class="row"><span>Producto C</span><span class="price">$220</span></div>
            </div>
            <div class="tpl-label">
              <span class="name">Elegante</span>
            </div>
          </div>
        </div>

        <!-- Cards -->
        <div class="col-4">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'classic') === 'cards' ? 'selected' : ''; ?>"
               onclick="pickTemplate('cards', this)">
            <div class="tpl-preview prev-k">
              <div class="card-item">
                <div class="ci-name">Producto A</div>
                <div class="ci-price">$350</div>
                <div class="ci-sku">SKU: ABC-001</div>
              </div>
              <div class="card-item">
                <div class="ci-name">Producto B</div>
                <div class="ci-price">$580</div>
                <div class="ci-sku">SKU: ABC-002</div>
              </div>
              <div class="card-item">
                <div class="ci-name">Producto C</div>
                <div class="ci-price">$220</div>
                <div class="ci-sku">SKU: ABC-003</div>
              </div>
              <div class="card-item">
                <div class="ci-name">Producto D</div>
                <div class="ci-price">$470</div>
                <div class="ci-sku">SKU: ABC-004</div>
              </div>
            </div>
            <div class="tpl-label">
              <span class="name">Tarjetas</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ── 2. Info básica ── -->
    <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
      <h6 class="fw-bold mb-4">Información del catálogo</h6>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" style="border-radius:10px"
               placeholder="Ej: Catálogo Temporada Verano 2025"
               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
               oninput="autoSlug(this.value)" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">URL pública (slug) <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text" style="background:#f0f0f5;font-size:13px">/c/</span>
          <input type="text" name="slug" id="slug" class="form-control" style="border-radius:0 10px 10px 0"
                 placeholder="mi-catalogo"
                 value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>" required>
        </div>
        <small class="text-muted">Solo letras minúsculas, números y guiones.</small>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
        <textarea name="description" class="form-control" style="border-radius:10px" rows="2"
                  placeholder="Ej: Línea de productos primavera-verano 2025"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
      </div>
    </div>

    <!-- ── 3. WhatsApp ── -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:16px">
      <div class="d-flex align-items-start gap-3 mb-3">
        <div class="form-check form-switch mt-1">
          <input class="form-check-input" type="checkbox" id="waToggle" name="whatsapp_enabled"
                 value="1" onchange="toggleWA(this.checked)"
                 <?php echo !empty($_POST['whatsapp_enabled']) ? 'checked' : ''; ?>>
        </div>
        <div>
          <h6 class="fw-bold mb-1">Pedidos por WhatsApp</h6>
          <p class="text-muted mb-0" style="font-size:13px">
            Agrega un botón de contacto en tu catálogo para que los clientes consulten disponibilidad o hagan pedidos.
          </p>
        </div>
      </div>
      <div id="waFields" style="display:<?php echo !empty($_POST['whatsapp_enabled']) ? 'block' : 'none'; ?>">
        <div class="mb-0">
          <label class="form-label fw-semibold" style="font-size:13px">Número de WhatsApp</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f9f9f9">💬</span>
            <input name="whatsapp_phone" class="form-control"
                   placeholder="+521234567890"
                   value="<?php echo htmlspecialchars($_POST['whatsapp_phone'] ?? ''); ?>">
          </div>
          <small class="text-muted">Con código de país. Ej: +521234567890</small>
        </div>
      </div>
    </div>

    <!-- ── Acciones ── -->
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-dark fw-semibold px-4" style="border-radius:10px;padding:11px 24px">
        <i class="bi bi-arrow-right me-1"></i>Crear y editar contenido
      </button>
      <a href="/catalogs" class="btn btn-outline-secondary" style="border-radius:10px;padding:11px 20px">Cancelar</a>
    </div>

  </form>

</main>
</div>

<script>
function pickTemplate(tpl, card) {
    document.getElementById('tplInput').value = tpl;
    document.querySelectorAll('.tpl-card').forEach(function(c) {
        c.classList.remove('selected');
    });
    card.classList.add('selected');
}

function autoSlug(val) {
    var s = document.getElementById('slug');
    if (s.dataset.edited) return;
    s.value = val.toLowerCase()
        .normalize('NFD').replace(/[̀-ͯ]/g,'')
        .replace(/[^a-z0-9]+/g,'-')
        .replace(/^-+|-+$/g,'');
}
document.getElementById('slug').addEventListener('input', function() {
    this.dataset.edited = '1';
});

function toggleWA(on) {
    document.getElementById('waFields').style.display = on ? 'block' : 'none';
}
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
