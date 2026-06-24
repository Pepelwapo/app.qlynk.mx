<?php
$pageTitle = 'Nuevo Formulario';
require __DIR__ . '/partials/head.php';
?>
<style>
.tpl-card {
    border:2px solid #e5e7eb;border-radius:14px;overflow:hidden;
    cursor:pointer;transition:.15s;background:#fff;height:100%;
}
.tpl-card:hover { border-color:#6c757d;transform:translateY(-2px);box-shadow:0 4px 16px rgba(0,0,0,.08); }
.tpl-card.selected { border-color:#1a1a2e;box-shadow:0 0 0 3px rgba(26,26,46,.15); }
.tpl-preview {
    background:#f8f9fb;padding:14px;min-height:148px;
    display:flex;flex-direction:column;gap:5px;
}
.mock-lbl { font-size:9px;font-weight:700;color:#888;letter-spacing:.8px;text-transform:uppercase; }
.mock-in  { background:#fff;border-radius:5px;height:20px;border:1px solid #ddd; }
.mock-in.tall { height:34px; }
.mock-btn { background:#1a1a2e;color:#fff;border-radius:5px;height:22px;width:64%;font-size:8px;
             display:flex;align-items:center;justify-content:center;font-weight:700;margin-top:4px; }
.tpl-label-box { padding:10px 14px 12px;border-top:1px solid #f0f0f0; }
.tpl-label-box .tname { font-size:14px;font-weight:700;color:#111;margin-bottom:2px; }
.tpl-label-box .tdesc { font-size:11px;color:#999; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="/forms" class="text-muted text-decoration-none" style="font-size:13px">
      <i class="bi bi-arrow-left"></i> Formularios
    </a>
    <span class="text-muted">/</span>
    <span style="font-size:13px">Nuevo formulario</span>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger" style="border-radius:12px;max-width:740px">
    <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <form method="POST" style="max-width:740px">
    <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="template" id="tplInput" value="<?php echo htmlspecialchars($_POST['template'] ?? 'blank'); ?>">

    <!-- ── 1. Plantilla ── -->
    <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
      <h6 class="fw-bold mb-1">Elige una plantilla</h6>
      <p class="text-muted mb-4" style="font-size:13px">
        Empieza con campos pre-configurados o crea desde cero. Puedes agregar, editar o eliminar campos después.
      </p>

      <div class="row g-3">

        <!-- Blank -->
        <div class="col-6 col-md-3">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'blank') === 'blank' ? 'selected' : ''; ?>"
               onclick="pickTemplate('blank', this)">
            <div class="tpl-preview" style="align-items:center;justify-content:center">
              <div style="font-size:36px;opacity:.2;line-height:1">+</div>
              <div style="font-size:11px;color:#ccc;margin-top:6px">Sin campos iniciales</div>
            </div>
            <div class="tpl-label-box">
              <div class="tname">Desde cero</div>
              <div class="tdesc">Define tus propios campos</div>
            </div>
          </div>
        </div>

        <!-- Contacto -->
        <div class="col-6 col-md-3">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'blank') === 'contact' ? 'selected' : ''; ?>"
               onclick="pickTemplate('contact', this)">
            <div class="tpl-preview">
              <div class="mock-lbl">Nombre</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">Email</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">Teléfono</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">Mensaje</div>
              <div class="mock-in tall"></div>
              <div class="mock-btn">Enviar mensaje</div>
            </div>
            <div class="tpl-label-box">
              <div class="tname">Contacto</div>
              <div class="tdesc">Nombre, email, mensaje</div>
            </div>
          </div>
        </div>

        <!-- Cotización -->
        <div class="col-6 col-md-3">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'blank') === 'quote' ? 'selected' : ''; ?>"
               onclick="pickTemplate('quote', this)">
            <div class="tpl-preview">
              <div class="mock-lbl">Nombre / Empresa</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">Producto o servicio</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">Cantidad</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">Notas adicionales</div>
              <div class="mock-in tall"></div>
              <div class="mock-btn">Solicitar cotización</div>
            </div>
            <div class="tpl-label-box">
              <div class="tname">Cotización</div>
              <div class="tdesc">Solicitud de presupuesto</div>
            </div>
          </div>
        </div>

        <!-- Encuesta -->
        <div class="col-6 col-md-3">
          <div class="tpl-card <?php echo ($_POST['template'] ?? 'blank') === 'survey' ? 'selected' : ''; ?>"
               onclick="pickTemplate('survey', this)">
            <div class="tpl-preview">
              <div class="mock-lbl">Calificación (1–5)</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">¿Qué te gustó?</div>
              <div class="mock-in tall"></div>
              <div class="mock-lbl">¿Qué mejorarías?</div>
              <div class="mock-in"></div>
              <div class="mock-lbl">¿Nos recomendarías?</div>
              <div class="mock-in"></div>
              <div class="mock-btn">Enviar encuesta</div>
            </div>
            <div class="tpl-label-box">
              <div class="tname">Encuesta</div>
              <div class="tdesc">Satisfacción de clientes</div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ── 2. Datos básicos ── -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:16px">
      <h6 class="fw-bold mb-4">Información del formulario</h6>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" style="border-radius:10px"
               placeholder="Ej: Formulario de contacto"
               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
               oninput="autoSlug(this.value)" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:13px">URL pública (slug) <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text" style="background:#f0f0f5;font-size:13px">/f/</span>
          <input type="text" name="slug" id="slug" class="form-control" style="border-radius:0 10px 10px 0"
                 placeholder="contacto"
                 value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>" required>
        </div>
        <small class="text-muted">Solo letras minúsculas, números y guiones.</small>
      </div>

      <div class="mb-0">
        <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
        <textarea name="description" class="form-control" style="border-radius:10px" rows="2"
                  placeholder="Descripción breve (opcional)"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
      </div>
    </div>

    <!-- ── Acciones ── -->
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-dark fw-semibold px-4" style="border-radius:10px;padding:11px 24px">
        <i class="bi bi-arrow-right me-1"></i>Crear y agregar campos
      </button>
      <a href="/forms" class="btn btn-outline-secondary" style="border-radius:10px;padding:11px 20px">Cancelar</a>
    </div>

  </form>

</main>
</div>

<script>
var tplNames = {
    blank:   '',
    contact: 'Formulario de Contacto',
    quote:   'Solicitud de Cotizacion',
    survey:  'Encuesta de Satisfaccion'
};
var tplDescs = {
    blank:   '',
    contact: 'Envianos un mensaje y te responderemos a la brevedad.',
    quote:   'Completa el formulario y te enviaremos una cotizacion personalizada.',
    survey:  'Tu opinion nos ayuda a mejorar. Solo toma un minuto!'
};

function pickTemplate(tpl, card) {
    document.getElementById('tplInput').value = tpl;
    document.querySelectorAll('.tpl-card').forEach(function(c) {
        c.classList.remove('selected');
    });
    card.classList.add('selected');
    var nameField = document.querySelector('input[name="name"]');
    var descField = document.querySelector('textarea[name="description"]');
    if (!nameField.dataset.edited && tplNames[tpl]) {
        nameField.value = tplNames[tpl];
        autoSlug(tplNames[tpl]);
    }
    if (!descField.dataset.edited && tplDescs[tpl]) {
        descField.value = tplDescs[tpl];
    }
}

function autoSlug(val) {
    var s = document.getElementById('slug');
    if (s.dataset.edited) return;
    s.value = val.toLowerCase()
        .normalize('NFD').replace(/[̀-ͯ]/g,'')
        .replace(/[^a-z0-9]+/g,'-')
        .replace(/^-+|-+$/g,'');
}
document.getElementById('slug').addEventListener('input', function() { this.dataset.edited = '1'; });
document.querySelector('input[name="name"]').addEventListener('input', function() {
    this.dataset.edited = '1';
    autoSlug(this.value);
});
document.querySelector('textarea[name="description"]').addEventListener('input', function() { this.dataset.edited = '1'; });
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
