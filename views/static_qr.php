<?php
$pageTitle = 'QR Estáticos';
require __DIR__ . '/partials/head.php';
?>
<style>
/* ── Tipo buttons ── */
.type-btn {
    padding:9px 14px;border:2px solid #e5e7eb;border-radius:10px;background:#fff;
    cursor:pointer;font-size:13px;text-align:center;transition:.15s;color:#333;
}
.type-btn:hover  { border-color:#1a1a2e;background:#f8f9fa; }
.type-btn.active { border-color:#1a1a2e;background:#1a1a2e;color:#fff; }

/* ── Field sections ── */
.field-section        { display:none; }
.field-section.active { display:block; }

/* ── Inputs ── */
.ql-input {
    border:1px solid #e5e7eb;border-radius:8px;padding:9px 12px;
    font-size:14px;width:100%;transition:border .15s;
}
.ql-input:focus { outline:none;border-color:#1a1a2e; }

/* ── Color picker ── */
.palette-grid {
    display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;
}
.swatch {
    width:28px;height:28px;border-radius:6px;cursor:pointer;
    border:2px solid transparent;transition:.12s;box-shadow:0 1px 3px rgba(0,0,0,.15);
}
.swatch:hover          { transform:scale(1.15); }
.swatch.selected       { border-color:#333;box-shadow:0 0 0 3px rgba(0,0,0,.15); }
.swatch.selected-light { border-color:#888;box-shadow:0 0 0 3px rgba(0,0,0,.10); }

.color-inputs {
    display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:6px;
}
.color-input-group {
    display:flex;align-items:center;gap:4px;
}
.color-input-group label { font-size:11px;color:#888;font-weight:600;min-width:14px; }
.color-mini { border:1px solid #e0e0e0;border-radius:6px;padding:5px 7px;font-size:12px;font-family:monospace; }
.color-mini:focus { outline:none;border-color:#1a1a2e; }
.color-preview-dot {
    width:22px;height:22px;border-radius:50%;
    border:2px solid #e0e0e0;flex-shrink:0;cursor:pointer;
}

/* ── QR wrap ── */
.qr-wrap {
    display:inline-flex;align-items:center;justify-content:center;
    padding:16px;background:#fff;border-radius:16px;
    border:2px dashed #e0e0e0;min-width:220px;min-height:220px;
}
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-3 mb-1">
    <h4 class="mb-0 fw-bold">QR Estáticos</h4>
    <span class="badge bg-dark" style="font-size:11px;border-radius:6px">Sin rastreo</span>
  </div>
  <p class="text-muted mb-4" style="font-size:14px">
    Genera un QR cuyo contenido está fijo dentro del código.
    Ideal para WiFi, vCard, texto y más. Se descarga directo, sin pasar por ningún servidor.
  </p>

  <div class="row g-4">

    <!-- ── Panel izquierdo: campos ── -->
    <div class="col-lg-7">
    <div class="card border-0 shadow-sm" style="border-radius:16px">
    <div class="card-body p-4">

      <!-- Selector de tipo -->
      <label class="fw-semibold d-block mb-2" style="font-size:13px">Tipo de QR</label>
      <div class="d-flex flex-wrap gap-2 mb-4">
        <button class="type-btn active" onclick="selectType('url',this)">🌐 URL</button>
        <button class="type-btn" onclick="selectType('whatsapp',this)">💬 WhatsApp</button>
        <button class="type-btn" onclick="selectType('wifi',this)">📶 WiFi</button>
        <button class="type-btn" onclick="selectType('vcard',this)">👤 vCard</button>
        <button class="type-btn" onclick="selectType('email',this)">📧 Email</button>
        <button class="type-btn" onclick="selectType('phone',this)">📞 Llamada</button>
        <button class="type-btn" onclick="selectType('sms',this)">💬 SMS</button>
        <button class="type-btn" onclick="selectType('text',this)">📝 Texto</button>
        <button class="type-btn" onclick="selectType('event',this)">📅 Evento</button>
      </div>

      <!-- ═══ Campos por tipo ═══ -->

      <div id="sec-url" class="field-section active">
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">URL</label>
          <input class="ql-input" id="f-url" type="url"
                 placeholder="https://tudominio.com" oninput="liveUpdate()">
        </div>
      </div>

      <div id="sec-whatsapp" class="field-section">
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Número <small class="text-muted">(con código de país)</small></label>
          <input class="ql-input" id="f-wa-phone" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Mensaje predefinido <small class="text-muted">(opcional)</small></label>
          <textarea class="ql-input" id="f-wa-msg" rows="3"
                    placeholder="Hola, me interesa… 🎉" oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-wifi" class="field-section">
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Nombre de la red (SSID)</label>
          <input class="ql-input" id="f-wifi-ssid" placeholder="MiRedWiFi" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Contraseña</label>
          <div style="position:relative">
            <input class="ql-input" id="f-wifi-pass" type="password"
                   placeholder="••••••••" oninput="liveUpdate()" style="padding-right:40px">
            <button type="button" onclick="togglePwd()"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                           background:none;border:none;cursor:pointer;color:#888;font-size:16px">
              👁️
            </button>
          </div>
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Tipo de seguridad</label>
          <select class="ql-input" id="f-wifi-sec" onchange="liveUpdate()">
            <option value="WPA">WPA / WPA2</option>
            <option value="WEP">WEP</option>
            <option value="nopass">Sin contraseña</option>
          </select>
        </div>
      </div>

      <div id="sec-vcard" class="field-section">
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="fw-semibold d-block mb-1" style="font-size:13px">Nombre completo *</label>
            <input class="ql-input" id="f-vc-name" placeholder="Juan Pérez" oninput="liveUpdate()">
          </div>
          <div class="col-6">
            <label class="fw-semibold d-block mb-1" style="font-size:13px">Empresa</label>
            <input class="ql-input" id="f-vc-co" placeholder="Mi Empresa S.A." oninput="liveUpdate()">
          </div>
        </div>
        <div class="mb-2">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Teléfono</label>
          <input class="ql-input" id="f-vc-phone" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
        <div class="mb-2">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Correo</label>
          <input class="ql-input" id="f-vc-email" type="email"
                 placeholder="juan@empresa.com" oninput="liveUpdate()">
        </div>
        <div class="mb-2">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Sitio web</label>
          <input class="ql-input" id="f-vc-web" placeholder="https://..." oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Cargo / Título</label>
          <input class="ql-input" id="f-vc-title" placeholder="Director Comercial" oninput="liveUpdate()">
        </div>
      </div>

      <div id="sec-email" class="field-section">
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Correo destino *</label>
          <input class="ql-input" id="f-em-email" type="email"
                 placeholder="contacto@empresa.com" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Asunto</label>
          <input class="ql-input" id="f-em-subj" placeholder="Consulta sobre..." oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Cuerpo del mensaje</label>
          <textarea class="ql-input" id="f-em-body" rows="3"
                    placeholder="Hola, me comunico para..." oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-phone" class="field-section">
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Número de teléfono</label>
          <input class="ql-input" id="f-ph" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
      </div>

      <div id="sec-sms" class="field-section">
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Número</label>
          <input class="ql-input" id="f-sms-ph" placeholder="+521234567890" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Mensaje</label>
          <textarea class="ql-input" id="f-sms-msg" rows="2"
                    placeholder="Hola..." oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-text" class="field-section">
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Texto libre</label>
          <textarea class="ql-input" id="f-text" rows="4"
                    placeholder="Escribe cualquier texto…" oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <div id="sec-event" class="field-section">
        <div class="mb-2">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Título del evento *</label>
          <input class="ql-input" id="f-ev-title" placeholder="Inauguración de sucursal" oninput="liveUpdate()">
        </div>
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="fw-semibold d-block mb-1" style="font-size:13px">Inicio</label>
            <input class="ql-input" id="f-ev-start" type="datetime-local" oninput="liveUpdate()">
          </div>
          <div class="col-6">
            <label class="fw-semibold d-block mb-1" style="font-size:13px">Fin</label>
            <input class="ql-input" id="f-ev-end" type="datetime-local" oninput="liveUpdate()">
          </div>
        </div>
        <div class="mb-2">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Ubicación</label>
          <input class="ql-input" id="f-ev-loc" placeholder="Av. Insurgentes 123, CDMX" oninput="liveUpdate()">
        </div>
        <div class="mb-3">
          <label class="fw-semibold d-block mb-1" style="font-size:13px">Descripción</label>
          <textarea class="ql-input" id="f-ev-desc" rows="2"
                    placeholder="Breve descripción…" oninput="liveUpdate()"></textarea>
        </div>
      </div>

      <!-- ═══ Personalización de colores ═══ -->
      <hr class="my-3">
      <label class="fw-semibold d-block mb-3" style="font-size:13px">Color y tamaño</label>

      <!-- Color del QR (módulos / dark) -->
      <div class="mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
          <div class="color-preview-dot" id="dot-dark" style="background:#1a1a2e"
               title="Color del QR"></div>
          <span style="font-size:12px;color:#555;font-weight:600">Color del QR (módulos)</span>
        </div>

        <!-- Paleta -->
        <div class="palette-grid" id="palette-dark">
          <!-- Oscuros / Negocios -->
          <div class="swatch selected" style="background:#1a1a2e" onclick="pickColor('dark','#1a1a2e',this)"></div>
          <div class="swatch" style="background:#000000" onclick="pickColor('dark','#000000',this)"></div>
          <div class="swatch" style="background:#1e3a5f" onclick="pickColor('dark','#1e3a5f',this)"></div>
          <div class="swatch" style="background:#2c3e50" onclick="pickColor('dark','#2c3e50',this)"></div>
          <div class="swatch" style="background:#4a235a" onclick="pickColor('dark','#4a235a',this)"></div>
          <!-- Colores vivos -->
          <div class="swatch" style="background:#c0392b" onclick="pickColor('dark','#c0392b',this)"></div>
          <div class="swatch" style="background:#e67e22" onclick="pickColor('dark','#e67e22',this)"></div>
          <div class="swatch" style="background:#d4ac0d" onclick="pickColor('dark','#d4ac0d',this)"></div>
          <div class="swatch" style="background:#1e8449" onclick="pickColor('dark','#1e8449',this)"></div>
          <div class="swatch" style="background:#2874a6" onclick="pickColor('dark','#2874a6',this)"></div>
          <!-- Modernos -->
          <div class="swatch" style="background:#e74c3c" onclick="pickColor('dark','#e74c3c',this)"></div>
          <div class="swatch" style="background:#ff6b6b" onclick="pickColor('dark','#ff6b6b',this)"></div>
          <div class="swatch" style="background:#ff922b" onclick="pickColor('dark','#ff922b',this)"></div>
          <div class="swatch" style="background:#2ecc71" onclick="pickColor('dark','#2ecc71',this)"></div>
          <div class="swatch" style="background:#3498db" onclick="pickColor('dark','#3498db',this)"></div>
          <div class="swatch" style="background:#9b59b6" onclick="pickColor('dark','#9b59b6',this)"></div>
          <div class="swatch" style="background:#cc5de8" onclick="pickColor('dark','#cc5de8',this)"></div>
          <div class="swatch" style="background:#1abc9c" onclick="pickColor('dark','#1abc9c',this)"></div>
          <div class="swatch" style="background:#495057" onclick="pickColor('dark','#495057',this)"></div>
          <div class="swatch" style="background:#868e96" onclick="pickColor('dark','#868e96',this)"></div>
        </div>

        <!-- Inputs manuales: Hex y RGB -->
        <div class="color-inputs">
          <div class="color-input-group">
            <label>#</label>
            <input type="text" class="color-mini" id="hex-dark" maxlength="6"
                   value="1a1a2e" placeholder="1a1a2e" style="width:70px"
                   oninput="fromHex('dark')">
          </div>
          <span style="font-size:12px;color:#bbb">|</span>
          <div class="color-input-group">
            <label>R</label>
            <input type="number" class="color-mini" id="r-dark" min="0" max="255"
                   value="26" style="width:50px" oninput="fromRgb('dark')">
          </div>
          <div class="color-input-group">
            <label>G</label>
            <input type="number" class="color-mini" id="g-dark" min="0" max="255"
                   value="26" style="width:50px" oninput="fromRgb('dark')">
          </div>
          <div class="color-input-group">
            <label>B</label>
            <input type="number" class="color-mini" id="b-dark" min="0" max="255"
                   value="46" style="width:50px" oninput="fromRgb('dark')">
          </div>
        </div>
      </div>

      <!-- Color de fondo (light) -->
      <div class="mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
          <div class="color-preview-dot" id="dot-light"
               style="background:#ffffff;border:2px solid #ddd" title="Color de fondo"></div>
          <span style="font-size:12px;color:#555;font-weight:600">Color de fondo</span>
        </div>

        <!-- Paleta fondo -->
        <div class="palette-grid" id="palette-light">
          <div class="swatch selected-light" style="background:#ffffff;border:1px solid #ddd"
               onclick="pickColor('light','#ffffff',this)"></div>
          <div class="swatch" style="background:#f8f9fa" onclick="pickColor('light','#f8f9fa',this)"></div>
          <div class="swatch" style="background:#fff3cd" onclick="pickColor('light','#fff3cd',this)"></div>
          <div class="swatch" style="background:#d1ecf1" onclick="pickColor('light','#d1ecf1',this)"></div>
          <div class="swatch" style="background:#d4edda" onclick="pickColor('light','#d4edda',this)"></div>
          <div class="swatch" style="background:#f5e6ff" onclick="pickColor('light','#f5e6ff',this)"></div>
          <div class="swatch" style="background:#ffeeba" onclick="pickColor('light','#ffeeba',this)"></div>
          <div class="swatch" style="background:#e2e3e5" onclick="pickColor('light','#e2e3e5',this)"></div>
        </div>

        <div class="color-inputs">
          <div class="color-input-group">
            <label>#</label>
            <input type="text" class="color-mini" id="hex-light" maxlength="6"
                   value="ffffff" placeholder="ffffff" style="width:70px"
                   oninput="fromHex('light')">
          </div>
          <span style="font-size:12px;color:#bbb">|</span>
          <div class="color-input-group">
            <label>R</label>
            <input type="number" class="color-mini" id="r-light" min="0" max="255"
                   value="255" style="width:50px" oninput="fromRgb('light')">
          </div>
          <div class="color-input-group">
            <label>G</label>
            <input type="number" class="color-mini" id="g-light" min="0" max="255"
                   value="255" style="width:50px" oninput="fromRgb('light')">
          </div>
          <div class="color-input-group">
            <label>B</label>
            <input type="number" class="color-mini" id="b-light" min="0" max="255"
                   value="255" style="width:50px" oninput="fromRgb('light')">
          </div>
        </div>
      </div>

      <!-- Tamaño -->
      <div class="mb-1">
        <div class="d-flex justify-content-between mb-1">
          <span style="font-size:12px;color:#555;font-weight:600">Tamaño de preview</span>
          <span id="sizeVal" style="font-size:12px;color:#888">250px</span>
        </div>
        <input type="range" id="qrSize" min="100" max="500" value="250"
               class="form-range"
               oninput="document.getElementById('sizeVal').textContent=this.value+'px'"
               onchange="liveUpdate()">
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
        <div id="qrCanvas"
             style="display:flex;align-items:center;justify-content:center;
                    width:220px;height:220px;color:#ccc;font-size:13px;text-align:center">
          Completa los campos<br>para generar el QR
        </div>
      </div>

      <div class="mt-4 d-flex gap-2 justify-content-center flex-wrap">
        <button onclick="downloadPNG()" id="btnPng"
                class="btn btn-dark btn-sm fw-semibold" style="border-radius:8px;display:none">
          <i class="bi bi-download me-1"></i>PNG HD
        </button>
        <button onclick="downloadSVG()" id="btnSvg"
                class="btn btn-outline-secondary btn-sm" style="border-radius:8px;display:none">
          <i class="bi bi-download me-1"></i>SVG
        </button>
      </div>

      <div class="mt-3" id="qrContentPreview"
           style="font-size:10px;color:#bbb;word-break:break-all;max-height:50px;overflow:hidden"></div>

      <hr class="my-3">
      <div class="text-muted" style="font-size:11px">
        <i class="bi bi-shield-check me-1 text-success"></i>
        Sin rastreo · Sin servidor · Descarga directa
      </div>

    </div>
    </div>
    </div>

  </div><!-- /row -->
</main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// ══════════════════════════════════════════════
//  Estado global
// ══════════════════════════════════════════════
var currentType  = 'url';
var qrObj        = null;
var darkColor    = '#1a1a2e';
var lightColor   = '#ffffff';

// ── Helpers de color ──────────────────────────
function hexToRgbVal(hex) {
    hex = hex.replace('#','');
    if (hex.length === 3) hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
    return {
        r: parseInt(hex.slice(0,2), 16),
        g: parseInt(hex.slice(2,4), 16),
        b: parseInt(hex.slice(4,6), 16)
    };
}
function rgbToHexStr(r,g,b) {
    return '#' + [r,g,b].map(function(v){ return Math.min(255,Math.max(0,v)).toString(16).padStart(2,'0'); }).join('');
}
function isValidHex(h) { return /^[0-9a-fA-F]{6}$/.test(h.replace('#','')); }

// Actualizar dot de vista previa + variables globales
function syncColorUI(which, hex) {
    if (which === 'dark') {
        darkColor = hex;
        document.getElementById('dot-dark').style.background = hex;
        var rgb = hexToRgbVal(hex);
        document.getElementById('hex-dark').value = hex.replace('#','');
        document.getElementById('r-dark').value = rgb.r;
        document.getElementById('g-dark').value = rgb.g;
        document.getElementById('b-dark').value = rgb.b;
    } else {
        lightColor = hex;
        document.getElementById('dot-light').style.background = hex;
        var rgb2 = hexToRgbVal(hex);
        document.getElementById('hex-light').value = hex.replace('#','');
        document.getElementById('r-light').value = rgb2.r;
        document.getElementById('g-light').value = rgb2.g;
        document.getElementById('b-light').value = rgb2.b;
    }
}

// Clic en paleta
function pickColor(which, hex, el) {
    // Quitar selected de paleta actual
    var palId = which === 'dark' ? 'palette-dark' : 'palette-light';
    var cls   = which === 'dark' ? 'selected' : 'selected-light';
    document.querySelectorAll('#' + palId + ' .swatch').forEach(function(s){
        s.classList.remove('selected','selected-light');
    });
    el.classList.add(cls);
    syncColorUI(which, hex);
    liveUpdate();
}

// Desde input hex
function fromHex(which) {
    var raw = document.getElementById('hex-' + which).value.replace('#','');
    if (!isValidHex(raw)) return;
    var hex = '#' + raw;
    // Quitar highlight de paleta
    var palId = which === 'dark' ? 'palette-dark' : 'palette-light';
    document.querySelectorAll('#' + palId + ' .swatch').forEach(function(s){
        s.classList.remove('selected','selected-light');
    });
    if (which === 'dark') { darkColor = hex; } else { lightColor = hex; }
    document.getElementById('dot-' + which).style.background = hex;
    var rgb = hexToRgbVal(hex);
    document.getElementById('r-' + which).value = rgb.r;
    document.getElementById('g-' + which).value = rgb.g;
    document.getElementById('b-' + which).value = rgb.b;
    liveUpdate();
}

// Desde inputs RGB
function fromRgb(which) {
    var r   = parseInt(document.getElementById('r-'+which).value) || 0;
    var g   = parseInt(document.getElementById('g-'+which).value) || 0;
    var b   = parseInt(document.getElementById('b-'+which).value) || 0;
    var hex = rgbToHexStr(r, g, b);
    // Quitar highlight de paleta
    var palId = which === 'dark' ? 'palette-dark' : 'palette-light';
    document.querySelectorAll('#' + palId + ' .swatch').forEach(function(s){
        s.classList.remove('selected','selected-light');
    });
    if (which === 'dark') { darkColor = hex; } else { lightColor = hex; }
    document.getElementById('dot-' + which).style.background = hex;
    document.getElementById('hex-' + which).value = hex.replace('#','');
    liveUpdate();
}

// ── Tipo de QR ────────────────────────────────
function selectType(type, btn) {
    currentType = type;
    document.querySelectorAll('.type-btn').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('.field-section').forEach(function(s){ s.classList.remove('active'); });
    var sec = document.getElementById('sec-' + type);
    if (sec) sec.classList.add('active');
    liveUpdate();
}

// ── Leer campo por ID ─────────────────────────
function v(id) { var el = document.getElementById(id); return el ? el.value.trim() : ''; }

// ── Construir contenido QR según tipo ─────────
function buildContent() {
    switch (currentType) {
        case 'url':   return v('f-url');
        case 'text':  return v('f-text');

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
            if (subj) q.push('subject='+subj);
            if (body) q.push('body='+body);
            return em ? 'mailto:' + em + (q.length ? '?'+q.join('&') : '') : '';
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
            return [
                'BEGIN:VEVENT',
                'SUMMARY:' + title,
                'DTSTART:' + fmtDt(v('f-ev-start')),
                'DTEND:'   + fmtDt(v('f-ev-end')),
                'LOCATION:' + v('f-ev-loc'),
                'DESCRIPTION:' + v('f-ev-desc'),
                'END:VEVENT'
            ].join('\r\n');
        }
        default: return '';
    }
}

// ── Generar / actualizar preview ──────────────
function liveUpdate() {
    var content    = buildContent();
    var size       = parseInt(document.getElementById('qrSize').value) || 250;
    var wrap       = document.getElementById('qrCanvas');
    var btnPng     = document.getElementById('btnPng');
    var btnSvg     = document.getElementById('btnSvg');
    var preview    = document.getElementById('qrContentPreview');

    if (!content) {
        wrap.innerHTML = '<span style="color:#ccc;font-size:13px;text-align:center">Completa los campos<br>para generar el QR</span>';
        wrap.style.cssText = 'display:flex;align-items:center;justify-content:center;width:220px;height:220px';
        document.getElementById('qrWrap').style.minWidth  = '';
        document.getElementById('qrWrap').style.minHeight = '';
        qrObj = null;
        btnPng.style.display = 'none';
        btnSvg.style.display = 'none';
        preview.textContent  = '';
        return;
    }

    wrap.innerHTML  = '';
    wrap.style.cssText = 'width:' + size + 'px;height:' + size + 'px';
    document.getElementById('qrWrap').style.minWidth  = (size + 32) + 'px';
    document.getElementById('qrWrap').style.minHeight = (size + 32) + 'px';

    try {
        qrObj = new QRCode(wrap, {
            text:         content,
            width:        size,
            height:       size,
            colorDark:    darkColor,
            colorLight:   lightColor,
            correctLevel: QRCode.CorrectLevel.H
        });
        btnPng.style.display = 'inline-block';
        btnSvg.style.display = 'inline-block';
        preview.textContent  = content.length > 80 ? content.substring(0,80) + '…' : content;
    } catch(e) {
        wrap.innerHTML = '<span style="color:#e74c3c;font-size:12px;text-align:center">Contenido demasiado largo<br>para un QR</span>';
        wrap.style.cssText = 'display:flex;align-items:center;justify-content:center;width:220px;height:220px';
        btnPng.style.display = 'none';
        btnSvg.style.display = 'none';
    }
}

// ── Descarga HD ───────────────────────────────
function getHDCanvas() {
    var src = document.querySelector('#qrCanvas canvas');
    if (!src) return null;
    var minPx = 1200;
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
    var svg     = '<' + '?xml version="1.0" encoding="UTF-8"?>\n'
                + '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '">'
                + '<image href="' + dataUrl + '" width="' + size + '" height="' + size + '"/>'
                + '</svg>';
    var blob = new Blob([svg], {type:'image/svg+xml;charset=utf-8'});
    var url  = URL.createObjectURL(blob);
    var link = document.createElement('a');
    link.download = 'qlynk-qr-' + currentType + '.svg';
    link.href     = url;
    link.click();
    setTimeout(function(){ URL.revokeObjectURL(url); }, 1000);
}

// ── WiFi password toggle ──────────────────────
function togglePwd() {
    var inp = document.getElementById('f-wifi-pass');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
