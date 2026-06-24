<?php
$pageTitle = 'Nuevo QR';
require __DIR__ . '/partials/head.php';
?>
<style>
/* ── Selector de tipo ── */
.qr-type-grid { display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin-bottom:24px; }
.qr-type-btn  {
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    padding:12px 6px;border:2px solid #e5e7eb;border-radius:12px;
    background:#fff;cursor:pointer;transition:.15s;text-align:center;
}
.qr-type-btn:hover  { border-color:#6c757d;background:#f8f9fa; }
.qr-type-btn.active { border-color:#1a1a2e;background:#1a1a2e;color:#fff; }
.qr-type-btn .icon  { font-size:22px;line-height:1.2;margin-bottom:4px; }
.qr-type-btn .lbl   { font-size:11px;font-weight:600;line-height:1.1; }

/* ── Campos dinámicos ── */
.qr-fields { display:none; }
.qr-fields.active { display:block; }

/* ── Inputs del form ── */
.ql-label { font-size:13px;font-weight:600;margin-bottom:4px;display:block;color:#374151; }
.ql-hint  { font-size:11px;color:#9ca3af;margin-top:3px; }

/* ── Panel info ── */
.info-row { display:flex;align-items:flex-start;gap:10px;margin-bottom:14px; }
.info-row .ico { font-size:20px;min-width:24px;margin-top:1px; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="/qrs" class="text-muted text-decoration-none" style="font-size:13px">
      <i class="bi bi-arrow-left"></i> QR Dinámicos
    </a>
    <span class="text-muted">/</span>
    <span style="font-size:13px">Nuevo QR</span>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-warning d-flex align-items-center gap-2" style="border-radius:12px;max-width:900px">
    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
    <div>
      <?php echo htmlspecialchars($error); ?>
      <?php if (strpos($error, 'límite') !== false): ?>
      <div class="mt-2"><a href="/billing" class="btn btn-sm btn-dark ms-1">Ver planes</a></div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="row g-4" style="max-width:960px">

    <!-- ── Formulario ── -->
    <div class="col-md-7">
    <div class="card border-0 shadow-sm" style="border-radius:16px">
    <div class="card-body p-4">

      <h5 class="fw-bold mb-1">Nuevo QR Dinámico</h5>
      <p class="text-muted mb-4" style="font-size:13px">
        El código nunca cambia — solo cambia el destino.
      </p>

      <form method="POST" id="createForm" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">

        <!-- Nombre -->
        <div class="mb-4">
          <label class="ql-label">Nombre del QR</label>
          <input name="name" class="form-control" style="border-radius:10px"
                 placeholder="Ej: Menú restaurante, Carta de vinos…"
                 value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                 required>
        </div>

        <!-- Selector de tipo (grid de íconos) -->
        <div class="mb-1">
          <label class="ql-label">Tipo de contenido</label>
        </div>
        <input type="hidden" name="type" id="typeInput"
               value="<?php echo htmlspecialchars($_POST['type'] ?? 'url'); ?>">

        <div class="qr-type-grid" id="typeGrid">
          <?php
          $types = [
              ['url',      '🌐', 'URL'],
              ['whatsapp', '💬', 'WhatsApp'],
              ['email',    '📧', 'Email'],
              ['phone',    '📞', 'Llamada'],
              ['sms',      '✉️', 'SMS'],
              ['wifi',     '📶', 'WiFi'],
              ['vcard',    '👤', 'Contacto'],
              ['pdf',      '📄', 'PDF'],
              ['social',   '📱', 'Red Social'],
              ['event',    '📅', 'Evento'],
          ];
          $selType = $_POST['type'] ?? 'url';
          foreach ($types as [$val, $ico, $lbl]):
          ?>
          <button type="button"
                  class="qr-type-btn <?php echo $selType === $val ? 'active' : ''; ?>"
                  onclick="selectType('<?php echo $val; ?>', this)">
            <span class="icon"><?php echo $ico; ?></span>
            <span class="lbl"><?php echo $lbl; ?></span>
          </button>
          <?php endforeach; ?>
        </div>

        <!-- ═══ Campos dinámicos por tipo ═══ -->

        <!-- URL -->
        <div id="fields-url" class="qr-fields <?php echo ($selType === 'url') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">URL destino</label>
            <input name="url" class="form-control" style="border-radius:10px"
                   placeholder="https://tudominio.com"
                   value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>">
            <span class="ql-hint">Puede ser cualquier URL: sitio, landing page, tienda…</span>
          </div>
        </div>

        <!-- WhatsApp -->
        <div id="fields-whatsapp" class="qr-fields <?php echo ($selType === 'whatsapp') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Número de WhatsApp <small class="fw-normal text-muted">(con código de país)</small></label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f9f9f9">💬</span>
              <input name="wa_phone" class="form-control" placeholder="+521234567890"
                     value="<?php echo htmlspecialchars($_POST['wa_phone'] ?? ''); ?>">
            </div>
            <span class="ql-hint">Incluye + y el código de país. Ej: +521234567890 (México)</span>
          </div>
          <div class="mb-3">
            <label class="ql-label">Mensaje predefinido <small class="fw-normal text-muted">(opcional)</small></label>
            <textarea name="wa_message" class="form-control" rows="3"
                      placeholder="Hola, me interesa saber más… 🎉"
                      style="border-radius:10px"><?php echo htmlspecialchars($_POST['wa_message'] ?? ''); ?></textarea>
            <span class="ql-hint">El cliente verá este mensaje pre-llenado al abrir WhatsApp.</span>
          </div>
        </div>

        <!-- Email -->
        <div id="fields-email" class="qr-fields <?php echo ($selType === 'email') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Correo destino</label>
            <input name="em_email" type="email" class="form-control" style="border-radius:10px"
                   placeholder="contacto@empresa.com"
                   value="<?php echo htmlspecialchars($_POST['em_email'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Asunto <small class="fw-normal text-muted">(opcional)</small></label>
            <input name="em_subject" class="form-control" style="border-radius:10px"
                   placeholder="Ej: Consulta sobre productos"
                   value="<?php echo htmlspecialchars($_POST['em_subject'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Cuerpo del mensaje <small class="fw-normal text-muted">(opcional)</small></label>
            <textarea name="em_body" class="form-control" rows="3"
                      placeholder="Hola, me comunico para…"
                      style="border-radius:10px"><?php echo htmlspecialchars($_POST['em_body'] ?? ''); ?></textarea>
          </div>
        </div>

        <!-- Llamada -->
        <div id="fields-phone" class="qr-fields <?php echo ($selType === 'phone') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Número de teléfono</label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f9f9f9">📞</span>
              <input name="phone_number" class="form-control" placeholder="+521234567890"
                     value="<?php echo htmlspecialchars($_POST['phone_number'] ?? ''); ?>">
            </div>
            <span class="ql-hint">Al escanear, el teléfono del cliente abrirá la marcadora con este número.</span>
          </div>
        </div>

        <!-- SMS -->
        <div id="fields-sms" class="qr-fields <?php echo ($selType === 'sms') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Número de teléfono</label>
            <input name="sms_phone" class="form-control" style="border-radius:10px"
                   placeholder="+521234567890"
                   value="<?php echo htmlspecialchars($_POST['sms_phone'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Mensaje <small class="fw-normal text-muted">(opcional)</small></label>
            <textarea name="sms_message" class="form-control" rows="2"
                      placeholder="Hola, me interesa…"
                      style="border-radius:10px"><?php echo htmlspecialchars($_POST['sms_message'] ?? ''); ?></textarea>
          </div>
        </div>

        <!-- WiFi -->
        <div id="fields-wifi" class="qr-fields <?php echo ($selType === 'wifi') ? 'active' : ''; ?>">
          <div class="alert alert-info py-2 mb-3" style="font-size:12px;border-radius:10px">
            <i class="bi bi-info-circle me-1"></i>
            Para WiFi te recomendamos un <a href="/qrs/static" class="fw-bold">QR Estático</a> —
            así el contenido está dentro del código y no pasa por servidor.
          </div>
          <div class="mb-3">
            <label class="ql-label">Nombre de la red (SSID)</label>
            <input name="wifi_ssid" class="form-control" style="border-radius:10px"
                   placeholder="MiRedWiFi"
                   value="<?php echo htmlspecialchars($_POST['wifi_ssid'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Contraseña</label>
            <input name="wifi_password" class="form-control" style="border-radius:10px"
                   placeholder="••••••••"
                   value="<?php echo htmlspecialchars($_POST['wifi_password'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Tipo de seguridad</label>
            <select name="wifi_security" class="form-select" style="border-radius:10px">
              <option value="WPA" <?php echo ($_POST['wifi_security'] ?? '') === 'WPA' ? 'selected' : ''; ?>>WPA / WPA2</option>
              <option value="WEP" <?php echo ($_POST['wifi_security'] ?? '') === 'WEP' ? 'selected' : ''; ?>>WEP</option>
              <option value="nopass" <?php echo ($_POST['wifi_security'] ?? '') === 'nopass' ? 'selected' : ''; ?>>Sin contraseña</option>
            </select>
          </div>
        </div>

        <!-- vCard / Contacto -->
        <div id="fields-vcard" class="qr-fields <?php echo ($selType === 'vcard') ? 'active' : ''; ?>">
          <div class="row g-2 mb-2">
            <div class="col-7">
              <label class="ql-label">Nombre completo *</label>
              <input name="vc_name" class="form-control" style="border-radius:10px"
                     placeholder="Juan Pérez"
                     value="<?php echo htmlspecialchars($_POST['vc_name'] ?? ''); ?>">
            </div>
            <div class="col-5">
              <label class="ql-label">Empresa</label>
              <input name="vc_company" class="form-control" style="border-radius:10px"
                     placeholder="Mi Empresa S.A."
                     value="<?php echo htmlspecialchars($_POST['vc_company'] ?? ''); ?>">
            </div>
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="ql-label">Teléfono</label>
              <input name="vc_phone" class="form-control" style="border-radius:10px"
                     placeholder="+521234567890"
                     value="<?php echo htmlspecialchars($_POST['vc_phone'] ?? ''); ?>">
            </div>
            <div class="col-6">
              <label class="ql-label">Correo</label>
              <input name="vc_email" type="email" class="form-control" style="border-radius:10px"
                     placeholder="juan@empresa.com"
                     value="<?php echo htmlspecialchars($_POST['vc_email'] ?? ''); ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="ql-label">Sitio web</label>
            <input name="vc_website" class="form-control" style="border-radius:10px"
                   placeholder="https://..."
                   value="<?php echo htmlspecialchars($_POST['vc_website'] ?? ''); ?>">
          </div>
          <div class="ql-hint mb-3">Al escanear, el contacto se puede guardar directamente en el teléfono.</div>
        </div>

        <!-- PDF -->
        <div id="fields-pdf" class="qr-fields <?php echo ($selType === 'pdf') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Subir PDF</label>
            <input name="pdf_file" type="file" accept=".pdf"
                   class="form-control" style="border-radius:10px">
            <span class="ql-hint">Máximo 800 KB. El archivo se aloja en nuestro servidor.</span>
          </div>
          <div class="mb-3">
            <div class="d-flex align-items-center gap-2 text-muted mb-1" style="font-size:12px">
              <hr style="flex:1"><span>o usa una URL</span><hr style="flex:1">
            </div>
            <label class="ql-label">URL del PDF (si ya tienes alojamiento)</label>
            <input name="pdf_url" class="form-control" style="border-radius:10px"
                   placeholder="https://drive.google.com/file/..."
                   value="<?php echo htmlspecialchars($_POST['pdf_url'] ?? ''); ?>">
            <span class="ql-hint">Google Drive, Dropbox, tu propio servidor, etc.</span>
          </div>
        </div>

        <!-- Red Social -->
        <div id="fields-social" class="qr-fields <?php echo ($selType === 'social') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">URL del perfil</label>
            <input name="social_url" class="form-control" style="border-radius:10px"
                   placeholder="https://instagram.com/miusuario"
                   value="<?php echo htmlspecialchars($_POST['social_url'] ?? ''); ?>">
            <span class="ql-hint">Instagram, Facebook, TikTok, LinkedIn, etc.</span>
          </div>
        </div>

        <!-- Evento -->
        <div id="fields-event" class="qr-fields <?php echo ($selType === 'event') ? 'active' : ''; ?>">
          <div class="mb-2">
            <label class="ql-label">Título del evento *</label>
            <input name="ev_title" class="form-control" style="border-radius:10px"
                   placeholder="Inauguración de sucursal"
                   value="<?php echo htmlspecialchars($_POST['ev_title'] ?? ''); ?>">
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="ql-label">Fecha y hora de inicio</label>
              <input name="ev_start" type="datetime-local" class="form-control" style="border-radius:10px"
                     value="<?php echo htmlspecialchars($_POST['ev_start'] ?? ''); ?>">
            </div>
            <div class="col-6">
              <label class="ql-label">Fecha y hora de fin</label>
              <input name="ev_end" type="datetime-local" class="form-control" style="border-radius:10px"
                     value="<?php echo htmlspecialchars($_POST['ev_end'] ?? ''); ?>">
            </div>
          </div>
          <div class="mb-2">
            <label class="ql-label">Ubicación</label>
            <input name="ev_location" class="form-control" style="border-radius:10px"
                   placeholder="Av. Insurgentes 123, CDMX"
                   value="<?php echo htmlspecialchars($_POST['ev_location'] ?? ''); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Descripción</label>
            <textarea name="ev_description" class="form-control" rows="2"
                      placeholder="Breve descripción del evento…"
                      style="border-radius:10px"><?php echo htmlspecialchars($_POST['ev_description'] ?? ''); ?></textarea>
          </div>
          <span class="ql-hint">Al escanear, el evento se puede agregar al calendario del teléfono.</span>
        </div>

        <!-- Expiración (avanzado) -->
        <hr class="my-4">
        <details>
          <summary class="fw-semibold" style="font-size:13px;cursor:pointer;color:#555">
            <i class="bi bi-gear me-1"></i>Opciones avanzadas
          </summary>
          <div class="mt-3">
            <label class="ql-label">Fecha de expiración <small class="fw-normal text-muted">(opcional)</small></label>
            <input name="expires_at" type="datetime-local" class="form-control" style="border-radius:10px"
                   value="<?php echo htmlspecialchars($_POST['expires_at'] ?? ''); ?>">
            <span class="ql-hint">Después de esta fecha el QR mostrará "expirado". Deja vacío para nunca expirar.</span>
          </div>
        </details>

        <div class="mt-4">
          <button class="btn btn-dark w-100 fw-semibold" style="border-radius:10px;padding:12px;font-size:15px">
            <i class="bi bi-qr-code me-2"></i>Crear QR
          </button>
        </div>
      </form>

    </div>
    </div>
    </div>

    <!-- ── Panel derecho ── -->
    <div class="col-md-5">
    <div class="card border-0 shadow-sm" style="border-radius:16px;position:sticky;top:20px">
    <div class="card-body p-4">

      <div style="font-size:40px;text-align:center;margin-bottom:8px" id="typeIco">🌐</div>
      <h6 class="fw-bold mb-1 text-center" id="typeTitle">URL / Sitio web</h6>
      <p class="text-muted text-center mb-4" style="font-size:12px;line-height:1.5" id="typeDesc">
        Redirige a cualquier sitio web o landing page.
      </p>

      <hr class="my-3">

      <div class="info-row">
        <span class="ico">🔗</span>
        <div>
          <div class="fw-semibold" style="font-size:13px">Código fijo, destino flexible</div>
          <div class="text-muted" style="font-size:12px">El QR nunca cambia — solo cambia adónde lleva.</div>
        </div>
      </div>
      <div class="info-row">
        <span class="ico">✏️</span>
        <div>
          <div class="fw-semibold" style="font-size:13px">Editable en cualquier momento</div>
          <div class="text-muted" style="font-size:12px">Sin reimprimir. Actualiza el destino desde tu panel.</div>
        </div>
      </div>
      <div class="info-row">
        <span class="ico">📊</span>
        <div>
          <div class="fw-semibold" style="font-size:13px">Estadísticas de escaneo</div>
          <div class="text-muted" style="font-size:12px">Registra cuántas veces y cuándo se escanea.</div>
        </div>
      </div>

      <hr class="my-3">
      <div class="text-muted text-center" style="font-size:11px">
        <i class="bi bi-info-circle me-1"></i>
        El QR final aparece al crearlo. Descargas en PNG y SVG HD disponibles.
      </div>

    </div>
    </div>
    </div>

  </div><!-- /row -->

</main>
</div>

<script>
var typeData = {
    'url':      { ico:'🌐', title:'URL / Sitio web',    desc:'Redirige a cualquier sitio web, tienda o landing page.' },
    'whatsapp': { ico:'💬', title:'WhatsApp',            desc:'Abre WhatsApp con un número y mensaje predefinido.' },
    'email':    { ico:'📧', title:'Email',               desc:'Abre la app de correo con destinatario, asunto y cuerpo.' },
    'phone':    { ico:'📞', title:'Llamada telefónica',  desc:'Abre la marcadora con el número listo para llamar.' },
    'sms':      { ico:'✉️', title:'SMS',                 desc:'Abre la app de mensajes con número y texto predefinido.' },
    'wifi':     { ico:'📶', title:'WiFi',                desc:'Conecta automáticamente a una red WiFi al escanear.' },
    'vcard':    { ico:'👤', title:'Tarjeta de contacto', desc:'El teléfono ofrece guardar el contacto automáticamente.' },
    'pdf':      { ico:'📄', title:'Documento PDF',       desc:'Abre un PDF alojado en la nube o sube uno directamente.' },
    'social':   { ico:'📱', title:'Red Social',          desc:'Dirige directamente a tu perfil en cualquier red social.' },
    'event':    { ico:'📅', title:'Evento / Calendario', desc:'Permite agregar un evento al calendario del teléfono.' }
};

function selectType(type, btn) {
    // Actualizar input oculto
    document.getElementById('typeInput').value = type;

    // Marcar botón activo
    document.querySelectorAll('.qr-type-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');

    // Mostrar campos correctos
    document.querySelectorAll('.qr-fields').forEach(function(el) { el.classList.remove('active'); });
    var sec = document.getElementById('fields-' + type);
    if (sec) sec.classList.add('active');

    // Actualizar panel derecho
    var d = typeData[type] || {};
    document.getElementById('typeIco').textContent   = d.ico   || '⚡';
    document.getElementById('typeTitle').textContent = d.title || type;
    document.getElementById('typeDesc').textContent  = d.desc  || '';
}
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
