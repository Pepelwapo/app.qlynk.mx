<?php
$pageTitle = 'Nuevo Menú';
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
.tpl-preview { height:130px;display:flex;flex-direction:column;overflow:hidden;position:relative; }
.tpl-label   { padding:10px 14px 12px;display:flex;align-items:center;gap:8px; }
.tpl-label .name  { font-size:14px;font-weight:700;color:#111; }
.tpl-label .badge { font-size:10px;background:#1a1a2e;color:#fff;border-radius:20px;padding:2px 7px; }

/* Preview Classic */
.prev-classic { background:#fff; }
.prev-classic .bar   { height:36px;background:#e74c3c; }
.prev-classic .title { font-size:14px;font-weight:700;padding:8px 12px 4px;color:#111; }
.prev-classic .item  { display:flex;justify-content:space-between;padding:3px 12px;font-size:11px;color:#555; }
.prev-classic .price { color:#e74c3c;font-weight:600; }

/* Preview Dark */
.prev-dark { background:#1a1a2e; }
.prev-dark .bar   { height:36px;background:linear-gradient(90deg,#e74c3c,#c0392b); }
.prev-dark .title { font-size:13px;font-weight:700;padding:7px 12px 4px;color:#e0b170;letter-spacing:.3px; }
.prev-dark .item  { display:flex;justify-content:space-between;padding:3px 12px;font-size:11px;color:#aaa; }
.prev-dark .price { color:#e0b170;font-weight:600; }

/* Preview Cards */
.prev-cards { background:#f7f8fc;display:grid!important;grid-template-columns:1fr 1fr;gap:6px;padding:8px; }
.prev-cards .card-item { background:#fff;border-radius:8px;padding:6px 8px;box-shadow:0 1px 4px rgba(0,0,0,.06); }
.prev-cards .ci-name   { font-size:10px;font-weight:700;color:#111;margin-bottom:2px; }
.prev-cards .ci-price  { font-size:11px;font-weight:700;color:#e74c3c; }
.prev-cards .ci-desc   { font-size:9px;color:#aaa;line-height:1.2; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="/menus" class="text-muted text-decoration-none" style="font-size:13px">
      <i class="bi bi-arrow-left"></i> Menús
    </a>
    <span class="text-muted">/</span>
    <span style="font-size:13px">Nuevo menú</span>
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
      <p class="text-muted mb-4" style="font-size:13px">Podrás personalizar colores y contenido después.</p>

      <input type="hidden" name="template" id="tplInput"
             value="<?php echo htmlspecialchars($_POST['template'] ?? 'classic'); ?>">

      <div class="row g-3">

        <!-- Classic -->
        <div class="col-4">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'classic') === 'classic' ? 'selected' : ''; ?>"
               onclick="pickTemplate('classic', this)">
            <div class="tpl-preview prev-classic flex-column">
              <div class="bar" id="barClassic"></div>
              <div class="title">Sección de entradas</div>
              <div class="item"><span>Bruschetta</span><span class="price">$85</span></div>
              <div class="item"><span>Sopa del día</span><span class="price">$65</span></div>
              <div class="item"><span>Ensalada César</span><span class="price">$95</span></div>
            </div>
            <div class="tpl-label">
              <span class="name">Clásico</span>
              <span class="badge" style="display:none" id="checkClassic">✓</span>
            </div>
          </div>
        </div>

        <!-- Dark -->
        <div class="col-4">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'classic') === 'dark' ? 'selected' : ''; ?>"
               onclick="pickTemplate('dark', this)">
            <div class="tpl-preview prev-dark flex-column">
              <div class="bar" id="barDark"></div>
              <div class="title">✦ Entradas ✦</div>
              <div class="item"><span>Bruschetta</span><span class="price">$85</span></div>
              <div class="item"><span>Sopa del día</span><span class="price">$65</span></div>
              <div class="item"><span>Ensalada César</span><span class="price">$95</span></div>
            </div>
            <div class="tpl-label">
              <span class="name">Elegante</span>
              <span class="badge" style="display:none" id="checkDark">✓</span>
            </div>
          </div>
        </div>

        <!-- Cards -->
        <div class="col-4">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'classic') === 'cards' ? 'selected' : ''; ?>"
               onclick="pickTemplate('cards', this)">
            <div class="tpl-preview prev-cards">
              <div class="card-item">
                <div class="ci-name">Bruschetta</div>
                <div class="ci-price">$85</div>
                <div class="ci-desc">Tomate, albahaca y pan tostado</div>
              </div>
              <div class="card-item">
                <div class="ci-name">Sopa del día</div>
                <div class="ci-price">$65</div>
                <div class="ci-desc">Según temporada</div>
              </div>
              <div class="card-item">
                <div class="ci-name">Ensalada César</div>
                <div class="ci-price">$95</div>
                <div class="ci-desc">Pollo, crutones, parmesano</div>
              </div>
              <div class="card-item">
                <div class="ci-name">Guacamole</div>
                <div class="ci-price">$75</div>
                <div class="ci-desc">Con totopos</div>
              </div>
            </div>
            <div class="tpl-label">
              <span class="name">Tarjetas</span>
              <span class="badge" style="display:none" id="checkCards">✓</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ── 2. Info básica ── -->
    <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
      <h6 class="fw-bold mb-4">Información del menú</h6>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" style="border-radius:10px"
               placeholder="Ej: Menú Restaurante Don Juan"
               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
               oninput="autoSlug(this.value)" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">URL pública (slug) <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text" style="background:#f0f0f5;font-size:13px">/m/</span>
          <input type="text" name="slug" id="slug" class="form-control" style="border-radius:0 10px 10px 0"
                 placeholder="mi-restaurante"
                 value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>" required>
        </div>
        <small class="text-muted">Solo letras minúsculas, números y guiones.</small>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
        <textarea name="description" class="form-control" style="border-radius:10px" rows="2"
                  placeholder="Ej: Restaurante Don Juan · Mariscos y Antojitos"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">Color de acento</label>
        <div class="d-flex align-items-center gap-3">
          <input type="color" name="color" id="colorPicker" class="form-control form-control-color"
                 value="<?php echo htmlspecialchars($_POST['color'] ?? '#e74c3c'); ?>"
                 style="width:56px;height:40px;border-radius:8px;cursor:pointer"
                 oninput="updatePreviewColor(this.value)">
          <span class="text-muted" style="font-size:13px">Color de la barra y destacados en el menú público.</span>
        </div>
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
            Agrega un botón "Ordenar por WhatsApp" en tu menú. Los clientes pueden enviar su pedido directamente.
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
      <a href="/menus" class="btn btn-outline-secondary" style="border-radius:10px;padding:11px 20px">Cancelar</a>
    </div>

  </form>

</main>
</div>

<script>
var selectedTpl = '<?php echo htmlspecialchars($_POST['template'] ?? 'classic'); ?>';

function pickTemplate(tpl, card) {
    selectedTpl = tpl;
    document.getElementById('tplInput').value = tpl;
    document.querySelectorAll('.tpl-card').forEach(function(c) {
        c.classList.remove('selected');
    });
    card.classList.add('selected');
}

function updatePreviewColor(hex) {
    ['barClassic','barDark'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.style.background = hex;
    });
}
// Aplicar color inicial
(function() {
    var v = document.getElementById('colorPicker').value;
    if (v) updatePreviewColor(v);
})();

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
