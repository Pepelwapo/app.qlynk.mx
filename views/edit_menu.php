<?php
$pageTitle = 'Editar Menú';
require __DIR__ . '/partials/head.php';
?>
<style>
.section-card { border-radius:12px;border:1px solid #e5e7eb;background:#fff;margin-bottom:16px }
.section-header { background:#f8f9fa;border-radius:12px 12px 0 0;padding:12px 16px;border-bottom:1px solid #e5e7eb }
.item-row { display:flex;align-items:center;gap:8px;padding:10px 16px;border-bottom:1px solid #f0f0f0 }
.item-row:last-child { border-bottom:0 }
</style>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center mb-4">
    <a href="/menus" class="btn btn-sm btn-outline-secondary me-3" style="border-radius:8px"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Editar Menú</h4>
    <a href="/m/<?= htmlspecialchars($menu['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-3" style="border-radius:8px;font-size:12px">
      <i class="bi bi-eye me-1"></i>Ver público
    </a>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger" style="border-radius:12px"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
  <div class="alert alert-success" style="border-radius:12px"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($created): ?>
  <div class="alert alert-success" style="border-radius:12px">✅ ¡Menú creado! Ahora agrega secciones y platillos.</div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- LEFT: Meta + Add section -->
    <div class="col-lg-4">

      <!-- Meta card -->
      <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
        <h6 class="fw-bold mb-3">Información del menú</h6>
        <form method="POST">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
          <input type="hidden" name="_action" value="save_meta">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm" style="border-radius:8px"
                   value="<?= htmlspecialchars($menu['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Slug</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text" style="background:#f0f0f5;border-right:0;font-size:12px">/m/</span>
              <input type="text" name="slug" class="form-control" style="border-radius:0 8px 8px 0;border-left:0"
                     value="<?= htmlspecialchars($menu['slug']) ?>" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
            <textarea name="description" class="form-control form-control-sm" style="border-radius:8px" rows="2"><?= htmlspecialchars($menu['description'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Color de acento</label>
            <input type="color" name="color" class="form-control form-control-color form-control-sm"
                   value="<?= htmlspecialchars($menu['color']) ?>" style="width:48px;height:32px;border-radius:6px">
          </div>

          <!-- Template selector -->
          <input type="hidden" name="template" id="tplEdit" value="<?= htmlspecialchars($menu['template'] ?? 'classic') ?>">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Plantilla visual</label>
            <div class="d-flex gap-2">
              <?php
              $tpls = ['classic' => 'Clásico', 'dark' => 'Elegante', 'cards' => 'Tarjetas'];
              $curTpl = $menu['template'] ?? 'classic';
              foreach ($tpls as $tkey => $tlabel): ?>
              <div class="flex-fill text-center p-2 rounded border cursor-pointer tpl-btn-edit <?= $curTpl === $tkey ? 'border-dark bg-dark text-white' : 'border-secondary-subtle' ?>"
                   style="font-size:12px;font-weight:600;cursor:pointer"
                   onclick="pickTplEdit('<?= $tkey ?>', this)">
                <?= $tlabel ?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- WhatsApp -->
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="waEditToggle" name="whatsapp_enabled"
                     value="1" onchange="toggleWAEdit(this.checked)"
                     <?= !empty($menu['whatsapp_enabled']) ? 'checked' : '' ?>>
              <label class="form-check-label fw-semibold" for="waEditToggle" style="font-size:13px">
                Pedidos por WhatsApp
              </label>
            </div>
            <div id="waEditFields" style="display:<?= !empty($menu['whatsapp_enabled']) ? 'block' : 'none' ?>;margin-top:8px">
              <input name="whatsapp_phone" class="form-control form-control-sm" style="border-radius:8px"
                     placeholder="+521234567890"
                     value="<?= htmlspecialchars($menu['whatsapp_phone'] ?? '') ?>">
              <small class="text-muted">Con código de país.</small>
            </div>
          </div>

          <button type="submit" class="btn btn-dark btn-sm fw-semibold w-100" style="border-radius:8px">
            <i class="bi bi-save me-1"></i>Guardar cambios
          </button>
        </form>
      </div>

      <!-- Add section card -->
      <div class="card border-0 shadow-sm p-4" style="border-radius:16px">
        <h6 class="fw-bold mb-3">Agregar sección</h6>
        <form method="POST">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
          <input type="hidden" name="_action" value="add_section">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Nombre de la sección</label>
            <input type="text" name="section_name" class="form-control form-control-sm" style="border-radius:8px"
                   placeholder="Ej: Entradas, Platos principales..." required>
          </div>
          <button type="submit" class="btn btn-outline-dark btn-sm fw-semibold w-100" style="border-radius:8px">
            <i class="bi bi-plus-lg me-1"></i>Agregar sección
          </button>
        </form>
      </div>

    </div>

    <!-- RIGHT: Sections + items -->
    <div class="col-lg-8">
      <h6 class="fw-bold mb-3">Secciones y platillos</h6>

      <?php if (empty($sections)): ?>
      <div class="card border-0 shadow-sm text-center p-4" style="border-radius:16px">
        <p class="text-muted mb-0" style="font-size:14px">Agrega tu primera sección para empezar a organizar el menú.</p>
      </div>
      <?php endif; ?>

      <?php foreach ($sections as $sec): ?>
      <div class="section-card">
        <div class="section-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold"><?= htmlspecialchars($sec['name']) ?></span>
          <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar sección y todos sus platillos?')">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
            <input type="hidden" name="_action" value="del_section">
            <input type="hidden" name="section_id" value="<?= $sec['id'] ?>">
            <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius:6px;font-size:11px">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>

        <!-- Items list -->
        <?php foreach ($sec['items'] as $item): ?>
        <div class="item-row">
          <div class="flex-grow-1">
            <span class="fw-semibold" style="font-size:14px"><?= htmlspecialchars($item['name']) ?></span>
            <?php if ($item['description']): ?>
            <br><span class="text-muted" style="font-size:12px"><?= htmlspecialchars($item['description']) ?></span>
            <?php endif; ?>
          </div>
          <?php if ($item['price'] !== null): ?>
          <span class="fw-bold text-success" style="font-size:14px;white-space:nowrap">$<?= number_format($item['price'], 2) ?></span>
          <?php endif; ?>
          <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este platillo?')">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
            <input type="hidden" name="_action" value="del_item">
            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
            <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius:6px;font-size:11px;padding:2px 8px">
              <i class="bi bi-x-lg"></i>
            </button>
          </form>
        </div>
        <?php endforeach; ?>

        <!-- Add item form -->
        <div style="padding:12px 16px;background:#fafafa;border-radius:0 0 12px 12px;border-top:1px solid #f0f0f0">
          <form method="POST" class="row g-2 align-items-end">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
            <input type="hidden" name="_action" value="add_item">
            <input type="hidden" name="section_id" value="<?= $sec['id'] ?>">
            <div class="col-md-4">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">PLATILLO *</label>
              <input type="text" name="item_name" class="form-control form-control-sm" style="border-radius:8px" placeholder="Nombre del platillo" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">DESCRIPCIÓN</label>
              <input type="text" name="item_desc" class="form-control form-control-sm" style="border-radius:8px" placeholder="Ingredientes, descripción...">
            </div>
            <div class="col-md-2">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">PRECIO</label>
              <input type="number" name="item_price" class="form-control form-control-sm" style="border-radius:8px" placeholder="0.00" step="0.01" min="0">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-dark btn-sm w-100" style="border-radius:8px">
                <i class="bi bi-plus-lg"></i> Agregar
              </button>
            </div>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>

</main>
</div>
<script>
function pickTplEdit(tpl, el) {
    document.getElementById('tplEdit').value = tpl;
    document.querySelectorAll('.tpl-btn-edit').forEach(function(b) {
        b.classList.remove('border-dark','bg-dark','text-white');
        b.classList.add('border-secondary-subtle');
        b.style.removeProperty('color');
    });
    el.classList.add('border-dark','bg-dark','text-white');
    el.classList.remove('border-secondary-subtle');
}
function toggleWAEdit(on) {
    document.getElementById('waEditFields').style.display = on ? 'block' : 'none';
}
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
