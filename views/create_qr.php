<?php
$pageTitle = 'Nuevo QR';
require __DIR__ . '/partials/head.php';
?>
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
  <div class="alert alert-warning d-flex align-items-center gap-2" style="border-radius:12px">
    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
    <div>
      <?php echo htmlspecialchars($error); ?>
      <div class="mt-2">
        <a href="/billing" class="btn btn-sm btn-dark ms-1">Ver planes</a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="row g-4" style="max-width:900px">

    <!-- ── Formulario ── -->
    <div class="col-md-7">
    <div class="card border-0 shadow-sm" style="border-radius:16px">
    <div class="card-body p-4">

      <h5 class="fw-bold mb-4">Nuevo QR Dinámico</h5>

      <form method="POST" id="createForm">
        <input type="hidden"
               name="<?php echo CSRF_TOKEN_NAME; ?>"
               value="<?php echo csrf_token(); ?>">

        <!-- Nombre -->
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:13px">Nombre del QR</label>
          <input name="name" class="form-control" id="qrName"
                 placeholder="Ej: Menú restaurante, Carta de vinos…"
                 value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                 required oninput="updatePreview()">
        </div>

        <!-- Tipo -->
        <div class="mb-4">
          <label class="form-label fw-semibold" style="font-size:13px">Tipo de contenido</label>
          <select name="type" id="qrType" class="form-select" onchange="switchType()">
            <option value="url">🌐 URL / Sitio web</option>
            <option value="whatsapp">💬 WhatsApp</option>
            <option value="email">📧 Email</option>
            <option value="phone">📞 Llamada</option>
            <option value="sms">💬 SMS</option>
            <option value="wifi">📶 WiFi</option>
            <option value="vcard">👤 vCard / Contacto</option>
            <option value="pdf">📄 PDF</option>
            <option value="social">📱 Red Social</option>
            <option value="event">📅 Evento</option>
          </select>
        </div>

        <!-- ═══ Campos dinámicos ═══ -->

        <!-- URL -->
        <div id="fields-url" class="qr-fields">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL destino</label>
            <input name="url" class="form-control" placeholder="https://tudominio.com"
                   oninput="updatePreview()">
          </div>
        </div>

        <!-- WhatsApp -->
        <div id="fields-whatsapp" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Número de WhatsApp <small class="text-muted">(con código de país)</small>
            </label>
            <input name="wa_phone" class="form-control" placeholder="+521234567890"
                   oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Mensaje predefinido <small class="text-muted">(opcional)</small>
            </label>
            <textarea name="wa_message" class="form-control" rows="3"
                      placeholder="Hola, me interesa saber más…"
                      oninput="updatePreview()"></textarea>
            <div class="mt-1" style="font-size:12px;color:#888">
              Tip: puedes usar emojis 🎉🔥✅
            </div>
          </div>
        </div>

        <!-- Email -->
        <div id="fields-email" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Correo destino</label>
            <input name="em_email" type="email" class="form-control"
                   placeholder="contacto@empresa.com" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Asunto <small class="text-muted">(opcional)</small>
            </label>
            <input name="em_subject" class="form-control"
                   placeholder="Ej: Consulta sobre productos" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Cuerpo del mensaje <small class="text-muted">(opcional)</small>
            </label>
            <textarea name="em_body" class="form-control" rows="3"
                      placeholder="Hola, me comunico para…" oninput="updatePreview()"></textarea>
          </div>
        </div>

        <!-- Phone -->
        <div id="fields-phone" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Número de teléfono</label>
            <input name="phone_number" class="form-control" placeholder="+521234567890"
                   oninput="updatePreview()">
          </div>
        </div>

        <!-- SMS -->
        <div id="fields-sms" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Número de teléfono</label>
            <input name="sms_phone" class="form-control" placeholder="+521234567890"
                   oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Mensaje <small class="text-muted">(opcional)</small>
            </label>
            <textarea name="sms_message" class="form-control" rows="2"
                      placeholder="Hola, me interesa…" oninput="updatePreview()"></textarea>
          </div>
        </div>

        <!-- WiFi -->
        <div id="fields-wifi" class="qr-fields" style="display:none">
          <div class="alert alert-info py-2 mb-3" style="font-size:12px;border-radius:8px">
            <i class="bi bi-info-circle me-1"></i>
            Los QR de WiFi funcionan mejor como <a href="/qrs/static" class="fw-bold">QR Estático</a>.
            Aquí se guarda para cambiar las credenciales fácilmente.
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Nombre de la red (SSID)</label>
            <input name="wifi_ssid" class="form-control" placeholder="MiRedWiFi"
                   oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Contraseña</label>
            <input name="wifi_password" class="form-control" placeholder="••••••••"
                   oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Seguridad</label>
            <select name="wifi_security" class="form-select" onchange="updatePreview()">
              <option value="WPA">WPA / WPA2</option>
              <option value="WEP">WEP</option>
              <option value="nopass">Sin contraseña</option>
            </select>
          </div>
        </div>

        <!-- vCard -->
        <div id="fields-vcard" class="qr-fields" style="display:none">
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Nombre completo</label>
              <input name="vc_name" class="form-control" placeholder="Juan Pérez"
                     oninput="updatePreview()">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Empresa</label>
              <input name="vc_company" class="form-control" placeholder="Mi Empresa S.A."
                     oninput="updatePreview()">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Teléfono</label>
            <input name="vc_phone" class="form-control" placeholder="+521234567890"
                   oninput="updatePreview()">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Correo</label>
            <input name="vc_email" type="email" class="form-control"
                   placeholder="juan@empresa.com" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Sitio web</label>
            <input name="vc_website" class="form-control" placeholder="https://..."
                   oninput="updatePreview()">
          </div>
        </div>

        <!-- PDF / Social -->
        <div id="fields-pdf" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL del PDF</label>
            <input name="pdf_url" class="form-control"
                   placeholder="https://drive.google.com/file/..." oninput="updatePreview()">
          </div>
        </div>

        <div id="fields-social" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL del perfil</label>
            <input name="social_url" class="form-control"
                   placeholder="https://instagram.com/miusuario" oninput="updatePreview()">
          </div>
        </div>

        <!-- Evento -->
        <div id="fields-event" class="qr-fields" style="display:none">
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Título del evento</label>
            <input name="ev_title" class="form-control" placeholder="Inauguración de sucursal"
                   oninput="updatePreview()">
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Inicio</label>
              <input name="ev_start" type="datetime-local" class="form-control"
                     oninput="updatePreview()">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Fin</label>
              <input name="ev_end" type="datetime-local" class="form-control"
                     oninput="updatePreview()">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Ubicación</label>
            <input name="ev_location" class="form-control" placeholder="Av. Insurgentes 123, CDMX"
                   oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
            <textarea name="ev_description" class="form-control" rows="2"
                      placeholder="Breve descripción…" oninput="updatePreview()"></textarea>
          </div>
        </div>

        <button class="btn btn-dark w-100 fw-semibold" style="border-radius:10px;padding:11px">
          <i class="bi bi-qr-code me-2"></i>Crear QR
        </button>
      </form>

    </div>
    </div>
    </div>

    <!-- ── Preview ── -->
    <div class="col-md-5">
    <div class="card border-0 shadow-sm text-center" style="border-radius:16px;position:sticky;top:20px">
    <div class="card-body p-4">

      <h6 class="fw-bold mb-1" style="font-size:13px;color:#888;text-transform:uppercase;letter-spacing:.5px">
        Vista previa
      </h6>
      <p style="font-size:11px;color:#aaa" class="mb-3">
        El QR dinámico apuntará a go.qlynk.mx
      </p>

      <div id="qrPreview"
           style="display:inline-block;padding:12px;background:#fff;border-radius:12px;
                  border:2px dashed #e0e0e0;min-width:160px;min-height:160px">
        <div id="qrCanvas" style="display:flex;align-items:center;justify-content:center;
                                   width:160px;height:160px;color:#ccc;font-size:13px">
          Llena los campos<br>para ver el preview
        </div>
      </div>

      <div class="mt-3">
        <button onclick="downloadPNG()" class="btn btn-outline-dark btn-sm me-2"
                id="btnDownload" style="border-radius:8px;display:none">
          <i class="bi bi-download me-1"></i>PNG
        </button>
        <button onclick="downloadSVG()" class="btn btn-outline-secondary btn-sm"
                id="btnDownloadSvg" style="border-radius:8px;display:none">
          <i class="bi bi-download me-1"></i>SVG
        </button>
      </div>

      <div class="mt-3 pt-3 border-top" style="font-size:11px;color:#bbb">
        <i class="bi bi-info-circle me-1"></i>
        Preview del contenido destino.<br>
        El QR real apunta a go.qlynk.mx
      </div>

    </div>
    </div>
    </div>

  </div><!-- /row -->

</main>
</div>

<!-- QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
var qrInstance = null;

function switchType() {
    document.querySelectorAll('.qr-fields').forEach(el => el.style.display = 'none');
    var type = document.getElementById('qrType').value;
    var el = document.getElementById('fields-' + type);
    if (el) el.style.display = 'block';
    updatePreview();
}

function getFieldVal(name) {
    var el = document.querySelector('[name="' + name + '"]');
    return el ? el.value.trim() : '';
}

function buildContent() {
    var type = document.getElementById('qrType').value;
    switch (type) {
        case 'url':
            return getFieldVal('url');
        case 'whatsapp': {
            var phone = getFieldVal('wa_phone').replace(/\D/g, '');
            var msg   = encodeURIComponent(getFieldVal('wa_message'));
            return phone ? 'https://wa.me/' + phone + (msg ? '?text=' + msg : '') : '';
        }
        case 'email': {
            var em   = getFieldVal('em_email');
            var subj = encodeURIComponent(getFieldVal('em_subject'));
            var body = encodeURIComponent(getFieldVal('em_body'));
            var q    = [];
            if (subj) q.push('subject=' + subj);
            if (body) q.push('body=' + body);
            return em ? 'mailto:' + em + (q.length ? '?' + q.join('&') : '') : '';
        }
        case 'phone':
            return getFieldVal('phone_number') ? 'tel:' + getFieldVal('phone_number') : '';
        case 'sms': {
            var sp  = getFieldVal('sms_phone');
            var sm  = encodeURIComponent(getFieldVal('sms_message'));
            return sp ? 'sms:' + sp + (sm ? '?body=' + sm : '') : '';
        }
        case 'wifi': {
            var ssid = getFieldVal('wifi_ssid');
            var pass = getFieldVal('wifi_password');
            var sec  = getFieldVal('wifi_security') || 'WPA';
            return ssid ? 'WIFI:S:' + ssid + ';T:' + sec + ';P:' + pass + ';;' : '';
        }
        case 'vcard': {
            var vcn = getFieldVal('vc_name');
            if (!vcn) return '';
            return 'BEGIN:VCARD\r\nVERSION:3.0\r\nFN:' + vcn
                + '\r\nTEL:' + getFieldVal('vc_phone')
                + '\r\nEMAIL:' + getFieldVal('vc_email')
                + '\r\nORG:' + getFieldVal('vc_company')
                + '\r\nURL:' + getFieldVal('vc_website')
                + '\r\nEND:VCARD';
        }
        case 'pdf':
            return getFieldVal('pdf_url');
        case 'social':
            return getFieldVal('social_url');
        case 'event': {
            var title = getFieldVal('ev_title');
            if (!title) return '';
            var fmtDt = function(dt) { return dt.replace(/[-:T]/g,'').slice(0,15) + 'Z'; };
            return 'BEGIN:VEVENT\r\nSUMMARY:' + title
                + '\r\nDTSTART:' + fmtDt(getFieldVal('ev_start') || '20250101T000000')
                + '\r\nDTEND:'   + fmtDt(getFieldVal('ev_end')   || '20250101T010000')
                + '\r\nLOCATION:'    + getFieldVal('ev_location')
                + '\r\nDESCRIPTION:' + getFieldVal('ev_description')
                + '\r\nEND:VEVENT';
        }
        default: return '';
    }
}

function updatePreview() {
    var content = buildContent();
    var wrap    = document.getElementById('qrCanvas');
    var btnD    = document.getElementById('btnDownload');
    var btnS    = document.getElementById('btnDownloadSvg');

    if (!content) {
        wrap.innerHTML = '<span style="color:#ccc;font-size:13px">Llena los campos<br>para ver el preview</span>';
        qrInstance     = null;
        btnD.style.display = 'none';
        btnS.style.display = 'none';
        return;
    }

    wrap.innerHTML = '';
    wrap.style = 'width:200px;height:200px';

    try {
        qrInstance = new QRCode(wrap, {
            text:         content,
            width:        200,
            height:       200,
            colorDark:    '#1a1a2e',
            colorLight:   '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        btnD.style.display = 'inline-block';
        btnS.style.display = 'inline-block';
    } catch(e) {
        wrap.innerHTML = '<span style="color:#e74c3c;font-size:12px">Contenido demasiado largo</span>';
    }
}

function downloadPNG() {
    var canvas = document.querySelector('#qrCanvas canvas');
    if (!canvas) { alert('Genera un QR primero'); return; }
    var link = document.createElement('a');
    link.download = (document.getElementById('qrName')?.value || 'qr-code') + '.png';
    link.href     = canvas.toDataURL('image/png');
    link.click();
}

function downloadSVG() {
    var content = buildContent();
    if (!content) { alert('Genera un QR primero'); return; }
    // SVG simple via módulos de puntos
    // Usamos imagen PNG embebida en SVG como fallback
    var canvas = document.querySelector('#qrCanvas canvas');
    if (!canvas) { alert('Genera un QR primero'); return; }
    var dataUrl = canvas.toDataURL('image/png');
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">'
            + '<image href="' + dataUrl + '" width="200" height="200"/>'
            + '</svg>';
    var blob = new Blob([svg], {type:'image/svg+xml'});
    var url  = URL.createObjectURL(blob);
    var link = document.createElement('a');
    link.download = (document.getElementById('qrName')?.value || 'qr-code') + '.svg';
    link.href     = url;
    link.click();
    URL.revokeObjectURL(url);
}

// Init
switchType();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
