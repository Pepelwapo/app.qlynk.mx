<?php
$pageTitle = 'Editar Formulario';
require __DIR__ . '/partials/head.php';

$typeLabels = [
    'text'     => 'Texto corto',
    'email'    => 'Email',
    'phone'    => 'Teléfono',
    'textarea' => 'Texto largo',
    'select'   => 'Opciones (select)',
    'checkbox' => 'Casilla (checkbox)',
];
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center mb-4">
    <a href="/forms" class="btn btn-sm btn-outline-secondary me-3" style="border-radius:8px"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Editar Formulario</h4>
    <a href="/f/<?= htmlspecialchars($form['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-3" style="border-radius:8px;font-size:12px">
      <i class="bi bi-eye me-1"></i>Ver formulario
    </a>
    <a href="/forms/responses?id=<?= $form['id'] ?>" class="btn btn-sm btn-outline-primary ms-2" style="border-radius:8px;font-size:12px">
      <i class="bi bi-inbox me-1"></i>Respuestas (<?= $respCount ?>)
    </a>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger" style="border-radius:12px"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
  <div class="alert alert-success" style="border-radius:12px"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($created): ?>
  <div class="alert alert-success" style="border-radius:12px">✅ ¡Formulario creado! Ahora agrega los campos.</div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- LEFT: Meta + Add field -->
    <div class="col-lg-4">

      <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
        <h6 class="fw-bold mb-3">Configuración del formulario</h6>
        <form method="POST">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
          <input type="hidden" name="_action" value="save_meta">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm" style="border-radius:8px"
                   value="<?= htmlspecialchars($form['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Slug</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text" style="background:#f0f0f5;border-right:0;font-size:12px">/f/</span>
              <input type="text" name="slug" class="form-control" style="border-radius:0 8px 8px 0;border-left:0"
                     value="<?= htmlspecialchars($form['slug']) ?>" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
            <textarea name="description" class="form-control form-control-sm" style="border-radius:8px" rows="2"><?= htmlspecialchars($form['description'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Mensaje de éxito</label>
            <input type="text" name="success_msg" class="form-control form-control-sm" style="border-radius:8px"
                   value="<?= htmlspecialchars($form['success_msg']) ?>">
          </div>
          <button type="submit" class="btn btn-dark btn-sm fw-semibold w-100" style="border-radius:8px">
            <i class="bi bi-save me-1"></i>Guardar cambios
          </button>
        </form>
      </div>

      <!-- Add field -->
      <div class="card border-0 shadow-sm p-4" style="border-radius:16px">
        <h6 class="fw-bold mb-3">Agregar campo</h6>
        <form method="POST" id="addFieldForm">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
          <input type="hidden" name="_action" value="add_field">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Etiqueta <span class="text-danger">*</span></label>
            <input type="text" name="field_label" class="form-control form-control-sm" style="border-radius:8px"
                   placeholder="Ej: Nombre, Email, Teléfono..." required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Tipo de campo</label>
            <select name="field_type" id="fieldTypeSelect" class="form-select form-select-sm" style="border-radius:8px" onchange="toggleOptions(this.value)">
              <?php foreach ($typeLabels as $val => $lbl): ?>
              <option value="<?= $val ?>"><?= $lbl ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Placeholder</label>
            <input type="text" name="field_ph" class="form-control form-control-sm" style="border-radius:8px"
                   placeholder="Texto de ejemplo en el campo">
          </div>
          <div class="mb-3" id="optionsBlock" style="display:none">
            <label class="form-label fw-semibold" style="font-size:13px">Opciones (una por línea)</label>
            <textarea name="field_options" class="form-control form-control-sm" style="border-radius:8px" rows="4"
                      placeholder="Opción 1&#10;Opción 2&#10;Opción 3"></textarea>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" name="field_required" id="fieldRequired" class="form-check-input" value="1">
            <label for="fieldRequired" class="form-check-label" style="font-size:13px">Campo obligatorio</label>
          </div>
          <button type="submit" class="btn btn-outline-dark btn-sm fw-semibold w-100" style="border-radius:8px">
            <i class="bi bi-plus-lg me-1"></i>Agregar campo
          </button>
        </form>
      </div>

    </div>

    <!-- RIGHT: Fields preview -->
    <div class="col-lg-8">
      <h6 class="fw-bold mb-3">Campos del formulario</h6>
      <div style="background:#f8f9fa;border-radius:12px;padding:4px;border:1px dashed #ddd">
        <div style="font-size:11px;color:#999;text-align:center;padding:6px 0 2px">PREVISUALIZACIÓN DEL FORMULARIO</div>
      </div>

      <?php if (empty($fields)): ?>
      <div class="card border-0 shadow-sm text-center p-5 mt-3" style="border-radius:16px">
        <div style="font-size:36px;margin-bottom:12px">📝</div>
        <p class="text-muted mb-0" style="font-size:14px">Agrega campos para construir tu formulario.</p>
      </div>
      <?php else: ?>
      <div class="mt-3">
        <?php foreach ($fields as $idx => $fld): ?>
        <div class="card border-0 shadow-sm mb-3" style="border-radius:12px;overflow:hidden">
          <div class="d-flex align-items-center p-3">
            <span class="badge bg-secondary me-2" style="font-size:10px;min-width:20px"><?= $idx + 1 ?></span>
            <div class="flex-grow-1">
              <div class="d-flex align-items-center gap-2 mb-1">
                <span class="fw-semibold" style="font-size:14px"><?= htmlspecialchars($fld['label']) ?></span>
                <?php if ($fld['required']): ?>
                <span class="text-danger" style="font-size:12px" title="Obligatorio">*</span>
                <?php endif; ?>
                <span class="badge bg-light text-secondary" style="font-size:10px"><?= $typeLabels[$fld['field_type']] ?? $fld['field_type'] ?></span>
              </div>
              <?php if ($fld['placeholder']): ?>
              <small class="text-muted"><?= htmlspecialchars($fld['placeholder']) ?></small>
              <?php endif; ?>
              <?php if ($fld['field_type'] === 'select' && $fld['options']): ?>
              <?php $opts = json_decode($fld['options'], true) ?? []; ?>
              <div class="mt-1"><small class="text-muted">Opciones: <?= implode(', ', array_map('htmlspecialchars', $opts)) ?></small></div>
              <?php endif; ?>
            </div>
            <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este campo?')">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
              <input type="hidden" name="_action" value="del_field">
              <input type="hidden" name="field_id" value="<?= $fld['id'] ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm ms-2" style="border-radius:6px;font-size:11px">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

  </div>

</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script>
function toggleOptions(type) {
  document.getElementById('optionsBlock').style.display = (type === 'select') ? 'block' : 'none';
}
</script>
