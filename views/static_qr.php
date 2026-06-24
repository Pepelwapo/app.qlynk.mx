<?php
require __DIR__ . '/partials/head.php';
?>
<style>
.field-section { display:none; }
.field-section.active { display:block; }
.type-btn {
    padding:10px 14px;
    border:2px solid #e5e7eb;
    border-radius:10px;
    background:#fff;
    cursor:pointer;
    font-size:13px;
    text-align:center;
    transition:.15s;
    color:#333;
    min-width:90px;
}
.type-btn:hover  { border-color:#1a1a2e;background:#f8f9fa; }
.type-btn.active { border-color:#1a1a2e;background:#1a1a2e;color:#fff; }
.qr-wrap {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:16px;
    background:#fff;
    border-radius:16px;
    border:2px dashed #e0e0e0;
    min-width:220px;
    min-height:220px;
}
.form-label-sm { font-size:13px;font-weight:600;color:#444;margin-bottom:4px; }
.form-control-qlynk {
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:9px 12px;
    font-size:14px;
    width:100%;
    transition:border .15s;
}
.form-control-qlynk:focus { outline:none;border-color:#1a1a2e; }
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-2 mb-4">
    <h4 class="mb-0 fw-bold">QR Estáticos</h4>
    <span class="badge bg-dark ms-1" style="font-size:11px;border-radius:6px">Sin rastreo</span>
  </div>
  <p class="text-muted mb-4" style="font-size:14px">
    Genera un QR cuyo contenido está fijo dentro del código. Ideal para WiFi, vCard, texto y más.
    El QR <strong>no pasa por go.qlynk.mx</strong> — se descarga directo.
  </p>

  <div class="row g-4">

    <!-- ── Panel izquierdo ── -->
    <div class="col-lg-7">
    <div class="card border-0 shadow-sm" style="border-radius:16px">
    <div class="card-body p-4">

      <!-- Selector de tipo -->
      <label class="form-label-sm d-block mb-2">Tipo de QR</label>
      <div class="d-flex flex-wrap gap-2 mb-4" id="typeButtons">
        <button class="type-btn active" data-type="url"      onclick="selectType('url',this)">🌐 URL</button>
        <button class="type-btn"        data-type="whatsapp" onclick="selectType('whatsapp',this)">💬 WhatsApp</button>
        <button class="type-btn"        data-type="wifi"     onclick="selectType('wifi',this)">📶 WiFi</button>
        <button class="type-btn"        data-type="vcard"    onclick="selectType('vcard',this)">👤 vCard</button>
        <button class="type-btn"        data-type="email"    onclick="selectType('email',this)">📧 Email</button>
        <button class="type-btn"        data-type="phone"    onclick="selectType('phone',this)">📞 Llamada</button>
        <button class="type-btn"        data-type="sms"      onclick="selectType('sms',this)">💬 SMS</button>
        <button class="type-btn"        data-type="text"     onclick="selectType('text',this)">📝 Texto</button>
        <button class="type-btn"        data-type="event"    onclick="selectType('event',this)">📅 Evento</button>
      </div>

      <!-- ═══ Campos ═══ -->

      <div id="sec-url" class="field-section active">
        <div class="mb-3">
          <label class="form-label-sm">URL</label>
          <input class="form-control-qlynk" id="f-url" type="url"
                 placeholder="https://tudominio.com" oninput="liveUpdate()">
        </div>
      </div>

      <div id="sec-whatsapp" class="field-section">
        <div class="mb-3">
          <label class="form-label-sm">Número de WhatsApp <small class="text-muted">(con código de país)</small></label>
          <input class="form-control-qlynk" id="f-wa-phone" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Mensaje predefinido <small class="text-muted">(opcional)</small></label>
          <textarea class="form-control-qlynk" id="f-wa-msg" rows="3"
                    placeholder="Hola, me interesa… 🎉" oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-wifi" class="field-section">
        <div class="mb-3">
          <label class="form-label-sm">Nombre de la red (SSID)</label>
          <input class="form-control-qlynk" id="f-wifi-ssid" placeholder="MiRedWiFi" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Contraseña</label>
          <div style="position:relative">
            <input class="form-control-qlynk" id="f-wifi-pass" type="password"
                   placeholder="••••••••" oninput="liveUpdate()" style="padding-right:40px">
            <button type="button" onclick="togglePwd()" title="Mostrar/ocultar"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                           background:none;border:none;cursor:pointer;color:#888;font-size:16px">
              👁️
            </button>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Tipo de seguridad</label>
          <select class="form-control-qlynk" id="f-wifi-sec" onchange="liveUpdate()">
            <option value="WPA">WPA / WPA2</option>
            <option value="WEP">WEP</option>
            <option value="nopass">Sin contraseña</option>
          </select>
        </div>
      </div>

      <div id="sec-vcard" class="field-section">
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label-sm">Nombre completo *</label>
            <input class="form-control-qlynk" id="f-vc-name" placeholder="Juan Pérez" oninput="liveUpdate()">
          </div>
          <div class="col-6">
            <label class="form-label-sm">Empresa</label>
            <input class="form-control-qlynk" id="f-vc-co" placeholder="Mi Empresa S.A." oninput="liveUpdate()">
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label-sm">Teléfono</label>
          <input class="form-control-qlynk" id="f-vc-phone" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
        <div class="mb-2">
          <label class="form-label-sm">Correo</label>
          <input class="form-control-qlynk" id="f-vc-email" type="email"
                 placeholder="juan@empresa.com" oninput="liveUpdate()">
        </div>
        <div class="mb-2">
          <label class="form-label-sm">Sitio web</label>
          <input class="form-control-qlynk" id="f-vc-web" placeholder="https://..." oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Cargo / Título</label>
          <input class="form-control-qlynk" id="f-vc-title" placeholder="Director Comercial" oninput="liveUpdate()">
        </div>
      </div>

      <div id="sec-email" class="field-section">
        <div class="mb-3">
          <label class="form-label-sm">Correo destino *</label>
          <input class="form-control-qlynk" id="f-em-email" type="email"
                 placeholder="contacto@empresa.com" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Asunto</label>
          <input class="form-control-qlynk" id="f-em-subj" placeholder="Consulta sobre..." oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Cuerpo del mensaje</label>
          <textarea class="form-control-qlynk" id="f-em-body" rows="3"
                    placeholder="Hola, me comunico para..." oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-phone" class="field-section">
        <div class="mb-3">
          <label class="form-label-sm">Número de teléfono</label>
          <input class="form-control-qlynk" id="f-ph" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
      </div>

      <div id="sec-sms" class="field-section">
        <div class="mb-3">
          <label class="form-label-sm">Número</label>
          <input class="form-control-qlynk" id="f-sms-ph" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Mensaje</label>
          <textarea class="form-control-qlynk" id="f-sms-msg" rows="2"
                    placeholder="Hola..." oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-text" class="field-section">
        <div class="mb-3">
          <label class="form-label-sm">Texto libre</label>
          <textarea class="form-control-qlynk" id="f-text" rows="4"
                    placeholder="Escribe cualquier texto…" oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-event" class="field-section">
        <div class="mb-2">
          <label class="form-label-sm">Título del evento *</label>
          <input class="form-control-qlynk" id="f-ev-title" placeholder="Inauguración de sucursal" oninput="liveUpdate()">
        </div>
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label-sm">Inicio</label>
            <input class="form-control-qlynk" id="f-ev-start" type="datetime-local" oninput="liveUpdate()">
          </div>
          <div class="col-6">
            <label class="form-label-sm">Fin</label>
            <input class="form-control-qlynk" id="f-ev-end" type="datetime-local" oninput="liveUpdate()">
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label-sm">Ubicación</label>
          <input class="form-control-qlynk" id="f-ev-loc" placeholder="Av. Insurgentes 123, CDMX" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="form-label-sm">Descripción</label>
          <textarea class="form-control-qlynk" id="f-ev-desc" rows="2"
                    placeholder="Breve descripción…" oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <!-- Personalización -->
      <hr class="my-3">
      <div class="row g-3">
        <div class="col-6">
          <label class="form-label-sm">Color oscuro (módulos)</label>
          <input type="color" id="colorDark" value="#1a1a2e" class="form-control form-control-color"
                 style="height:40px;border-radius:8px" onchange="liveUpdate()">
        </div>
        <div class="col-6">
          <label class="form-label-sm">Color claro (fondo)</label>
          <input type="color" id="colorLight" value="#ffffff" class="form-control form-control-color"
                 style="height:40px;border-radius:8px" onchange="liveUpdate()">
        </div>
        <div class="col-12">
          <label class="form-label-sm">Tamaño (px)</label>
          <input type="range" id="qrSize" min="100" max="500" value="250"
                 class="form-range" onchange="liveUpdate()" oninput="document.getElementById('sizeVal').textContent=this.value+'px'">
          <span id="sizeVal" style="font-size:12px;color:#888">250px</span>
        </div>
      </div>

    </div>
    </div>
    </div>

    <!-- ── Panel derecho: Preview ── -->
    <div class="col-lg-5">
    <div class="card border-0 shadow-sm text-center" style="border-radius:16px;position:sticky;top:20px">
    <div class="card-body p-4">

      <h6 class="fw-bold mb-1" style="font-size:13px;color:#888;text-transform:uppercase;letter-spacing:.5px">
        Vista previa
      </h6>
      <p class="text-muted mb-4" style="font-size:11px">
        El contenido está <strong>fijo</strong> dentro del QR
      </p>

      <div class="qr-wrap" id="qrWrap">
        <div id="qrCanvas" style="display:flex;align-items:center;justify-content:center;
                                   width:220px;height:220px;color:#ccc;font-size:13px;text-align:center">
          Completa los campos<br>para generar el QR
        </div>
      </div>

      <div class="mt-4 d-flex gap-2 justify-content-center flex-wrap">
        <button onclick="downloadPNG()" id="btnPng"
                class="btn btn-dark btn-sm fw-semibold" style="border-radius:8px;display:none">
          <i class="bi bi-download me-1"></i>Descargar PNG
        </button>
        <button onclick="downloadSVG()" id="btnSvg"
                class="btn btn-outline-secondary btn-sm" style="border-radius:8px;display:none">
          <i class="bi bi-download me-1"></i>Descargar SVG
        </button>
      </div>

      <div class="mt-3" id="qrContentPreview"
           style="font-size:10px;color:#bbb;word-break:break-all;max-height:60px;overflow:hidden">
      </div>

      <hr class="my-3">
      <div class="text-muted" style="font-size:11px">
        <i class="bi bi-shield-check me-1 text-success"></i>
        Sin rastreo · Sin servidor · Descarga directa
      </div>

    </div>
    </div>
    </div>

  </div>
</main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
var currentType = 'url';
var qrObj       = null;
var lastContent = '';

function v(id) {
    var el = document.getElementById(id);
    return el ? el.value.trim() : '';
}

function selectType(type, btn) {
    currentType = type;
    document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.field-section').forEach(s => s.classList.remove('active'));
    var sec = document.getElementById('sec-' + type);
    if (sec) sec.classList.add('active');
    liveUpdate();
}

function buildContent() {
    switch (currentType) {
        case 'url':      return v('f-url');
        case 'text':     return v('f-text');
        case 'whatsapp': {
            var ph = v('f-wa-phone').replace(/\D/g,'');
            var ms = encodeURIComponent(v('f-wa-msg'));
            return ph ? 'https://wa.me/' + ph + (ms ? '?text=' + ms : '') : '';
        }
        case 'wifi': {
            var ssid = v('f-wifi-ssid');
            var pass = v('f-wifi-pass');
            var sec  = v('f-wifi-sec') || 'WPA';
            return ssid ? 'WIFI:S:' + ssid + ';T:' + sec + ';P:' + pass + ';;' : '';
        }
        case 'vcard': {
            var n = v('f-vc-name');
            if (!n) return '';
            var lines = ['BEGIN:VCARD','VERSION:3.0','FN:'+n];
            if (v('f-vc-title')) lines.push('TITLE:'+v('f-vc-title'));
            if (v('f-vc-co'))    lines.push('ORG:'+v('f-vc-co'));
            if (v('f-vc-phone')) lines.push('TEL;TYPE=CELL:'+v('f-vc-phone'));
            if (v('f-vc-email')) lines.push('EMAIL:'+v('f-vc-email'));
            if (v('f-vc-web'))   lines.push('URL:'+v('f-vc-web'));
            lines.push('END:VCARD');
            return lines.join('\r\n');
        }
        case 'email': {
            var em   = v('f-em-email');
            var subj = encodeURIComponent(v('f-em-subj'));
            var body = encodeURIComponent(v('f-em-body'));
            var q    = [];
            if (subj) q.push('subject=' + subj);
            if (body) q.push('body='    + body);
            return em ? 'mailto:' + em + (q.length ? '?' + q.join('&') : '') : '';
        }
        case 'phone':
            return v('f-ph') ? 'tel:' + v('f-ph') : '';
        case 'sms': {
            var sp = v('f-sms-ph');
            var sm = encodeURIComponent(v('f-sms-msg'));
            return sp ? 'sms:' + sp + (sm ? '?body=' + sm : '') : '';
        }
        case 'event': {
            var title = v('f-ev-title');
            if (!title) return '';
            var fmtDt = function(dt) {
                return dt ? dt.replace(/[-:T]/g,'').slice(0,15) + 'Z' : '19700101T000000Z';
            };
            return ['BEGIN:VEVENT',
                    'SUMMARY:' + title,
                    'DTSTART:' + fmtDt(v('f-ev-start')),
                    'DTEND:'   + fmtDt(v('f-ev-end')),
                    'LOCATION:' + v('f-ev-loc'),
                    'DESCRIPTION:' + v('f-ev-desc'),
                    'END:VEVENT'].join('\r\n');
        }
        default: return '';
    }
}

function liveUpdate() {
    var content    = buildContent();
    var size       = parseInt(document.getElementById('qrSize').value) || 250;
    var colorDark  = document.getElementById('colorDark').value;
    var colorLight = document.getElementById('colorLight').value;
    var wrap       = document.getElementById('qrCanvas');
    var btnPng     = document.getElementById('btnPng');
    var btnSvg     = document.getElementById('btnSvg');
    var preview    = document.getElementById('qrContentPreview');

    if (!content) {
        wrap.innerHTML = '<span style="color:#ccc;font-size:13px;text-align:center">Completa los campos<br>para generar el QR</span>';
        wrap.style     = 'display:flex;align-items:center;justify-content:center;width:220px;height:220px';
        qrObj          = null;
        btnPng.style.display = 'none';
        btnSvg.style.display = 'none';
        preview.textContent  = '';
        lastContent    = '';
        return;
    }

    wrap.innerHTML = '';
    wrap.style     = 'width:' + size + 'px;height:' + size + 'px';

    // Actualizar el contenedor para el nuevo tamaño
    document.getElementById('qrWrap').style.minWidth  = (size + 32) + 'px';
    document.getElementById('qrWrap').style.minHeight = (size + 32) + 'px';

    try {
        qrObj = new QRCode(wrap, {
            text:         content,
            width:        size,
            height:       size,
            colorDark:    colorDark,
            colorLight:   colorLight,
            correctLevel: QRCode.CorrectLevel.H
        });
        btnPng.style.display = 'inline-block';
        btnSvg.style.display = 'inline-block';
        preview.textContent  = content.length > 80 ? content.substring(0,80) + '…' : content;
        lastContent          = content;
    } catch(e) {
        wrap.innerHTML = '<span style="color:#e74c3c;font-size:12px;text-align:center">Contenido demasiado largo<br>para este QR</span>';
        wrap.style     = 'display:flex;align-items:center;justify-content:center;width:220px;height:220px';
        btnPng.style.display = 'none';
        btnSvg.style.display = 'none';
    }
}

function getHDCanvas() {
    var src = document.querySelector('#qrCanvas canvas');
    if (!src) return null;
    // Upscale to at least 1080px regardless of the preview size chosen
    var minPx = 1080;
    var scale = Math.max(6, Math.ceil(minPx / src.width));
    var hd    = document.createElement('canvas');
    hd.width  = src.width  * scale;
    hd.height = src.height * scale;
    var ctx   = hd.getContext('2d');
    ctx.imageSmoothingEnabled = false;
    ctx.drawImage(src, 0, 0, hd.width, hd.height);
    return hd;
}

function downloadPNG() {
    var hd = getHDCanvas();
    if (!hd) { alert('Genera un QR primero'); return; }
    var link      = document.createElement('a');
    link.download = 'qlynk-qr-' + currentType + '.png';
    link.href     = hd.toDataURL('image/png');
    link.click();
}

function downloadSVG() {
    var hd = getHDCanvas();
    if (!hd) { alert('Genera un QR primero'); return; }
    var dataUrl = hd.toDataURL('image/png');
    var size    = hd.width;
    var svg     = '<?xml version="1.0" encoding="UTF-8"?>\n'
                + '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '">'
                + '<image href="' + dataUrl + '" width="' + size + '" height="' + size + '"/>'
                + '</svg>';
    var blob = new Blob([svg], {type:'image/svg+xml;charset=utf-8'});
    var url  = URL.createObjectURL(blob);
    var link = document.createElement('a');
    link.download = 'qlynk-qr-' + currentType + '.svg';
    link.href     = url;
    link.click();
    setTimeout(function() { URL.revokeObjectURL(url); }, 1000);
}

function togglePwd() {
    var inp = document.getElementById('f-wifi-pass');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
