<?php
if (!isset($qr) || !isset($fields)) { header('Location: /qrs'); exit; }
$pageTitle = 'Editar QR';
$goUrl     = APP_GO_URL . '/' . $qr['short_code'];
$selType   = $_POST['type'] ?? $qr['type'];
$f         = function($key) use ($fields) { return htmlspecialchars($fields[$key] ?? ''); };

// Colors: from POST (on validation error) → from DB → fallback to black/white
$curDark  = htmlspecialchars($_POST['dark_color']  ?? ($qr['dark_color']  ?? '#000000'));
$curLight = htmlspecialchars($_POST['light_color'] ?? ($qr['light_color'] ?? '#FFFFFF'));

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

/* ── Color picker ── */
.color-palette { display:grid;grid-template-columns:repeat(8,1fr);gap:6px;margin-bottom:16px; }
.color-swatch  {
    width:30px;height:30px;border-radius:8px;cursor:pointer;border:2px solid transparent;
    transition:.15s;position:relative;overflow:hidden;
}
.color-swatch:hover { transform:scale(1.1); }
.color-swatch.selected { border-color:#1a1a2e;box-shadow:0 0 0 2px #fff,0 0 0 4px #1a1a2e; }

.rgb-row { display:flex;gap:6px;margin-top:6px; }
.rgb-row input { width:100%;font-size:12px;text-align:center;padding:4px;border:1px solid #e5e7eb;border-radius:6px; }
.rgb-row label { font-size:10px;color:#999;text-align:center;display:block;margin-top:2px; }

/* ── Preview QR ── */
#qrPreviewWrap {
    display:flex;align-items:center;justify-content:center;
    width:180px;height:180px;margin:0 auto 16px;
    background:#fff;border-radius:12px;
    box-shadow:0 4px 20px rgba(0,0,0,.08);
    overflow:hidden;
}
</style>

<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="/qrs" class="text-muted text-decoration-none" style="font-size:13px">
      <i class="bi bi-arrow-left"></i> QR Dinámicos
    </a>
    <span class="text-muted">/</span>
    <span style="font-size:13px">Editar QR</span>
  </div>

  <?php if (!empty($saved)): ?>
  <div class="alert alert-success d-flex align-items-center gap-2 mb-3"
       style="border-radius:10px;max-width:980px">
    <i class="bi bi-check-circle-fill text-success"></i>
    Cambios guardados correctamente.
  </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger d-flex align-items-center gap-2 mb-3"
       style="border-radius:10px;max-width:980px">
    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
    <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <div class="row g-4" style="max-width:980px">

    <!-- ══════════════════ FORMULARIO ══════════════════ -->
    <div class="col-md-7">
    <div class="card border-0 shadow-sm" style="border-radius:16px">
    <div class="card-body p-4">

      <h5 class="fw-bold mb-1">
        Editar: <span class="text-muted fw-normal"><?php echo htmlspecialchars($qr['name']); ?></span>
      </h5>
      <p class="text-muted mb-4" style="font-size:13px">
        El código QR no cambia — solo actualizas el contenido al que apunta.
      </p>

      <form method="POST" action="/qrs/edit?id=<?php echo (int)$qr['id']; ?>" id="editForm">
        <input type="hidden" name="<?php echo CSRF_TOKEN_NAME; ?>" value="<?php echo csrf_token(); ?>">
        <!-- Color: sincronizados desde el panel derecho -->
        <input type="hidden" name="dark_color"  id="fDark"  value="<?php echo $curDark; ?>">
        <input type="hidden" name="light_color" id="fLight" value="<?php echo $curLight; ?>">

        <!-- Nombre -->
        <div class="mb-4">
          <label class="ql-label">Nombre del QR</label>
          <input name="name" class="form-control" style="border-radius:10px"
                 value="<?php echo htmlspecialchars($qr['name']); ?>" required>
        </div>

        <!-- Mover a sección (solo si hay carpetas) -->
        <?php if (!empty($folders)): ?>
        <div class="mb-4">
          <label class="ql-label">
            <i class="bi bi-folder me-1"></i>Sección / Carpeta
          </label>
          <select name="folder_id" class="form-select" style="border-radius:10px">
            <option value="">— Sin sección —</option>
            <?php foreach ($folders as $fol): ?>
            <option value="<?php echo (int)$fol['id']; ?>"
              <?php echo ((int)($qr['folder_id'] ?? 0) === (int)$fol['id']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($fol['name']); ?>
            </option>
            <?php endforeach; ?>
          </select>
          <span class="ql-hint">Mueve este QR a otra sección de tu biblioteca.</span>
        </div>
        <?php endif; ?>

        <!-- Selector de tipo (icon grid) -->
        <div class="mb-1">
          <label class="ql-label">Tipo de contenido</label>
        </div>
        <input type="hidden" name="type" id="typeInput" value="<?php echo htmlspecialchars($selType); ?>">

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
          foreach ($types as $t):
              $val = $t[0]; $ico = $t[1]; $lbl = $t[2];
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

        <div id="fields-url" class="qr-fields <?php echo ($selType === 'url') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">URL destino</label>
            <input name="url" class="form-control" style="border-radius:10px"
                   placeholder="https://tudominio.com"
                   value="<?php echo $f('url'); ?>">
          </div>
        </div>

        <div id="fields-whatsapp" class="qr-fields <?php echo ($selType === 'whatsapp') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Número de WhatsApp <small class="fw-normal text-muted">(con código de país)</small></label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f9f9f9">💬</span>
              <input name="wa_phone" class="form-control" placeholder="+521234567890"
                     value="<?php echo $f('wa_phone'); ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="ql-label">Mensaje predefinido <small class="fw-normal text-muted">(opcional)</small></label>
            <textarea name="wa_message" class="form-control" rows="3"
                      placeholder="Hola, me interesa saber más…"
                      style="border-radius:10px"><?php echo $f('wa_message'); ?></textarea>
          </div>
        </div>

        <div id="fields-email" class="qr-fields <?php echo ($selType === 'email') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Correo destino</label>
            <input name="em_email" type="email" class="form-control" style="border-radius:10px"
                   placeholder="contacto@empresa.com"
                   value="<?php echo $f('em_email'); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Asunto <small class="fw-normal text-muted">(opcional)</small></label>
            <input name="em_subject" class="form-control" style="border-radius:10px"
                   placeholder="Ej: Consulta"
                   value="<?php echo $f('em_subject'); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Cuerpo del mensaje <small class="fw-normal text-muted">(opcional)</small></label>
            <textarea name="em_body" class="form-control" rows="3"
                      style="border-radius:10px"><?php echo $f('em_body'); ?></textarea>
          </div>
        </div>

        <div id="fields-phone" class="qr-fields <?php echo ($selType === 'phone') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Número de teléfono</label>
            <div class="input-group">
              <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f9f9f9">📞</span>
              <input name="phone_number" class="form-control" placeholder="+521234567890"
                     value="<?php echo $f('phone_number'); ?>">
            </div>
          </div>
        </div>

        <div id="fields-sms" class="qr-fields <?php echo ($selType === 'sms') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Número de teléfono</label>
            <input name="sms_phone" class="form-control" style="border-radius:10px"
                   placeholder="+521234567890"
                   value="<?php echo $f('sms_phone'); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Mensaje <small class="fw-normal text-muted">(opcional)</small></label>
            <textarea name="sms_message" class="form-control" rows="2"
                      style="border-radius:10px"><?php echo $f('sms_message'); ?></textarea>
          </div>
        </div>

        <div id="fields-wifi" class="qr-fields <?php echo ($selType === 'wifi') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">Nombre de la red (SSID)</label>
            <input name="wifi_ssid" class="form-control" style="border-radius:10px"
                   placeholder="MiRedWiFi"
                   value="<?php echo $f('wifi_ssid'); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Contraseña</label>
            <input name="wifi_password" class="form-control" style="border-radius:10px"
                   value="<?php echo $f('wifi_password'); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Tipo de seguridad</label>
            <select name="wifi_security" class="form-select" style="border-radius:10px">
              <?php foreach (['WPA'=>'WPA / WPA2','WEP'=>'WEP','nopass'=>'Sin contraseña'] as $k=>$v): ?>
              <option value="<?php echo $k; ?>"
                      <?php echo (($fields['wifi_security'] ?? 'WPA') === $k) ? 'selected' : ''; ?>>
                <?php echo $v; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div id="fields-vcard" class="qr-fields <?php echo ($selType === 'vcard') ? 'active' : ''; ?>">
          <div class="row g-2 mb-2">
            <div class="col-7">
              <label class="ql-label">Nombre completo *</label>
              <input name="vc_name" class="form-control" style="border-radius:10px"
                     placeholder="Juan Pérez"
                     value="<?php echo $f('vc_name'); ?>">
            </div>
            <div class="col-5">
              <label class="ql-label">Empresa</label>
              <input name="vc_company" class="form-control" style="border-radius:10px"
                     value="<?php echo $f('vc_company'); ?>">
            </div>
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="ql-label">Teléfono</label>
              <input name="vc_phone" class="form-control" style="border-radius:10px"
                     value="<?php echo $f('vc_phone'); ?>">
            </div>
            <div class="col-6">
              <label class="ql-label">Correo</label>
              <input name="vc_email" type="email" class="form-control" style="border-radius:10px"
                     value="<?php echo $f('vc_email'); ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="ql-label">Sitio web</label>
            <input name="vc_website" class="form-control" style="border-radius:10px"
                   value="<?php echo $f('vc_website'); ?>">
          </div>
        </div>

        <div id="fields-pdf" class="qr-fields <?php echo ($selType === 'pdf') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">URL del PDF</label>
            <input name="pdf_url" class="form-control" style="border-radius:10px"
                   placeholder="https://..."
                   value="<?php echo $f('pdf_url'); ?>">
          </div>
        </div>

        <div id="fields-social" class="qr-fields <?php echo ($selType === 'social') ? 'active' : ''; ?>">
          <div class="mb-3">
            <label class="ql-label">URL del perfil</label>
            <input name="social_url" class="form-control" style="border-radius:10px"
                   placeholder="https://instagram.com/miusuario"
                   value="<?php echo $f('social_url'); ?>">
            <span class="ql-hint">Instagram, Facebook, TikTok, LinkedIn, etc.</span>
          </div>
        </div>

        <div id="fields-event" class="qr-fields <?php echo ($selType === 'event') ? 'active' : ''; ?>">
          <div class="mb-2">
            <label class="ql-label">Título del evento *</label>
            <input name="ev_title" class="form-control" style="border-radius:10px"
                   value="<?php echo $f('ev_title'); ?>">
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="ql-label">Fecha y hora de inicio</label>
              <input name="ev_start" type="datetime-local" class="form-control" style="border-radius:10px"
                     value="<?php echo $f('ev_start'); ?>">
            </div>
            <div class="col-6">
              <label class="ql-label">Fecha y hora de fin</label>
              <input name="ev_end" type="datetime-local" class="form-control" style="border-radius:10px"
                     value="<?php echo $f('ev_end'); ?>">
            </div>
          </div>
          <div class="mb-2">
            <label class="ql-label">Ubicación</label>
            <input name="ev_location" class="form-control" style="border-radius:10px"
                   value="<?php echo $f('ev_location'); ?>">
          </div>
          <div class="mb-3">
            <label class="ql-label">Descripción</label>
            <textarea name="ev_description" class="form-control" rows="2"
                      style="border-radius:10px"><?php echo $f('ev_description'); ?></textarea>
          </div>
        </div>

        <!-- Expiración -->
        <hr class="my-4">
        <details>
          <summary class="fw-semibold" style="font-size:13px;cursor:pointer;color:#555">
            <i class="bi bi-gear me-1"></i>Opciones avanzadas
          </summary>
          <div class="mt-3">
            <label class="ql-label">Fecha de expiración <small class="fw-normal text-muted">(opcional)</small></label>
            <?php
            $expiresVal = '';
            if (!empty($qr['expires_at'])) {
                $expiresVal = date('Y-m-d\TH:i', strtotime($qr['expires_at']));
            }
            ?>
            <input name="expires_at" type="datetime-local" class="form-control" style="border-radius:10px"
                   value="<?php echo htmlspecialchars($expiresVal); ?>">
            <span class="ql-hint">Deja vacío para nunca expirar.</span>
          </div>
        </details>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-dark flex-grow-1 fw-semibold"
                  style="border-radius:10px;padding:11px">
            <i class="bi bi-check-lg me-1"></i>Guardar cambios
          </button>
          <a href="/qrs" class="btn btn-outline-secondary"
             style="border-radius:10px;padding:11px">
            Cancelar
          </a>
        </div>

      </form>

    </div>
    </div>
    </div>

    <!-- ══════════════════ PANEL DERECHO: QR + COLORES ══════════════════ -->
    <div class="col-md-5">
    <div class="card border-0 shadow-sm" style="border-radius:16px;position:sticky;top:20px">
    <div class="card-body p-4">

      <!-- QR real (apunta a go.qlynk.mx) -->
      <h6 class="fw-bold mb-3 text-center">QR Dinámico</h6>
      <div id="qrPreviewWrap">
        <div id="qrGoCode"></div>
      </div>

      <div class="text-center mb-1" style="font-size:10px;color:#aaa">
        <code><?php echo htmlspecialchars($goUrl); ?></code>
      </div>

      <!-- Descargas -->
      <div class="d-flex justify-content-center gap-2 mb-3">
        <button onclick="downloadPNG()" class="btn btn-outline-dark btn-sm" style="border-radius:8px">
          <i class="bi bi-download me-1"></i>PNG
        </button>
        <button onclick="downloadSVG()" class="btn btn-outline-secondary btn-sm" style="border-radius:8px">
          <i class="bi bi-download me-1"></i>SVG
        </button>
      </div>

      <!-- Stats rápidas -->
      <div class="d-flex justify-content-around mb-3" style="background:#f8f9fa;border-radius:12px;padding:12px">
        <div class="text-center">
          <div class="fw-bold" style="font-size:20px"><?php echo number_format((int)$qr['scan_count']); ?></div>
          <div style="font-size:11px;color:#aaa">Escaneos</div>
        </div>
        <div class="text-center">
          <span class="badge <?php echo $qr['active'] ? 'bg-success' : 'bg-secondary'; ?>"
                style="font-size:11px">
            <?php echo $qr['active'] ? 'Activo' : 'Inactivo'; ?>
          </span>
          <div style="font-size:11px;color:#aaa;margin-top:4px">Estado</div>
        </div>
        <div class="text-center">
          <code style="font-size:12px"><?php echo htmlspecialchars($qr['short_code']); ?></code>
          <div style="font-size:11px;color:#aaa;margin-top:2px">Código</div>
        </div>
      </div>

      <?php if (!empty($scanDays)): ?>
      <div class="mb-3">
        <div style="font-size:11px;color:#aaa;margin-bottom:4px">Escaneos — últimos 14 días</div>
        <canvas id="scanChart" height="90"></canvas>
      </div>
      <?php else: ?>
      <div style="font-size:11px;color:#ccc;text-align:center;margin-bottom:12px">
        <i class="bi bi-bar-chart me-1"></i>Sin datos de escaneo aún
      </div>
      <?php endif; ?>

      <hr class="my-3">

      <!-- Color picker -->
      <div style="font-size:13px;font-weight:700;margin-bottom:10px">
        <i class="bi bi-palette me-1"></i>Color del QR
      </div>

      <div class="mb-3">
        <div class="ql-label mb-2" style="font-size:12px">Esquemas rápidos</div>
        <div class="color-palette" id="palette"></div>
      </div>

      <div class="mb-3">
        <label class="ql-label" style="font-size:12px">Módulos (oscuro)</label>
        <div class="d-flex align-items-center gap-2">
          <input type="color" id="darkPicker" value="<?php echo $curDark; ?>"
                 style="width:38px;height:34px;padding:2px;border:1px solid #e5e7eb;border-radius:7px;cursor:pointer"
                 oninput="onDarkColor(this.value)">
          <input type="text"  id="darkHex"    value="<?php echo $curDark; ?>" maxlength="7"
                 class="form-control form-control-sm" style="border-radius:7px;font-size:12px;font-family:monospace"
                 oninput="onDarkHexInput(this.value)">
        </div>
        <div class="rgb-row">
          <div><input type="number" id="dR" min="0" max="255" value="0"   oninput="rgbToHex('dark')"><label>R</label></div>
          <div><input type="number" id="dG" min="0" max="255" value="0"   oninput="rgbToHex('dark')"><label>G</label></div>
          <div><input type="number" id="dB" min="0" max="255" value="0"   oninput="rgbToHex('dark')"><label>B</label></div>
        </div>
      </div>

      <div class="mb-1">
        <label class="ql-label" style="font-size:12px">Fondo (claro)</label>
        <div class="d-flex align-items-center gap-2">
          <input type="color" id="lightPicker" value="<?php echo $curLight; ?>"
                 style="width:38px;height:34px;padding:2px;border:1px solid #e5e7eb;border-radius:7px;cursor:pointer"
                 oninput="onLightColor(this.value)">
          <input type="text"  id="lightHex"    value="<?php echo $curLight; ?>" maxlength="7"
                 class="form-control form-control-sm" style="border-radius:7px;font-size:12px;font-family:monospace"
                 oninput="onLightHexInput(this.value)">
        </div>
        <div class="rgb-row">
          <div><input type="number" id="lR" min="0" max="255" value="255" oninput="rgbToHex('light')"><label>R</label></div>
          <div><input type="number" id="lG" min="0" max="255" value="255" oninput="rgbToHex('light')"><label>G</label></div>
          <div><input type="number" id="lB" min="0" max="255" value="255" oninput="rgbToHex('light')"><label>B</label></div>
        </div>
      </div>

    </div>
    </div>
    </div>

  </div>

</main>
</div>

<!-- QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<?php if (!empty($scanDays)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    var ctx = document.getElementById('scanChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:   <?php echo json_encode($scanDays); ?>,
            datasets: [{
                label:           'Escaneos',
                data:            <?php echo json_encode($scanCounts); ?>,
                backgroundColor: '#1a1a2e',
                borderRadius:    4,
                borderSkipped:   false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 9 }, maxRotation: 0, color: '#aaa' } },
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 9 }, color: '#aaa',
                             callback: function(v) { return Number.isInteger(v) ? v : null; } },
                    grid: { color: '#f0f0f0' }
                }
            }
        }
    });
})();
</script>
<?php endif; ?>
<script>
/* ════════════════════════ Tipo de QR ════════════════════════ */
function selectType(type, btn) {
    document.getElementById('typeInput').value = type;
    document.querySelectorAll('.qr-type-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('.qr-fields').forEach(function(el) { el.classList.remove('active'); });
    var sec = document.getElementById('fields-' + type);
    if (sec) sec.classList.add('active');
}

/* ════════════════════════ Color Picker ════════════════════════ */
var PALETTES = [
    { d:'#000000', l:'#FFFFFF' }, { d:'#1a1a2e', l:'#FFFFFF' },
    { d:'#0f3460', l:'#eef2ff' }, { d:'#7c3aed', l:'#f5f3ff' },
    { d:'#dc2626', l:'#fef2f2' }, { d:'#059669', l:'#ecfdf5' },
    { d:'#d97706', l:'#fffbeb' }, { d:'#0e7490', l:'#ecfeff' },
    { d:'#be185d', l:'#fdf2f8' }, { d:'#1d4ed8', l:'#eff6ff' },
    { d:'#374151', l:'#f9fafb' }, { d:'#064e3b', l:'#d1fae5' },
    { d:'#312e81', l:'#e0e7ff' }, { d:'#78350f', l:'#fef3c7' },
    { d:'#831843', l:'#fce7f3' }, { d:'#134e4a', l:'#ccfbf1' },
];

function buildPalette() {
    var wrap = document.getElementById('palette');
    wrap.innerHTML = '';
    for (var i = 0; i < PALETTES.length; i++) {
        (function(p) {
            var sw = document.createElement('div');
            sw.className  = 'color-swatch';
            sw.title      = p.d + ' / ' + p.l;
            sw.style.background = 'linear-gradient(135deg, ' + p.d + ' 50%, ' + p.l + ' 50%)';
            sw.style.border     = '2px solid #e5e7eb';
            sw.addEventListener('click', function() {
                applyColors(p.d, p.l);
                document.querySelectorAll('.color-swatch').forEach(function(s) { s.classList.remove('selected'); });
                sw.classList.add('selected');
            });
            wrap.appendChild(sw);
        })(PALETTES[i]);
    }
}

function applyColors(dark, light) {
    dark  = dark  || '#000000';
    light = light || '#FFFFFF';

    document.getElementById('darkPicker').value  = dark;
    document.getElementById('darkHex').value     = dark;
    document.getElementById('lightPicker').value = light;
    document.getElementById('lightHex').value    = light;

    var dr = hexToRgb(dark);
    document.getElementById('dR').value = dr.r;
    document.getElementById('dG').value = dr.g;
    document.getElementById('dB').value = dr.b;

    var lr = hexToRgb(light);
    document.getElementById('lR').value = lr.r;
    document.getElementById('lG').value = lr.g;
    document.getElementById('lB').value = lr.b;

    document.getElementById('fDark').value  = dark;
    document.getElementById('fLight').value = light;

    renderQR();
}

function onDarkColor(val) {
    document.getElementById('darkHex').value = val;
    var rgb = hexToRgb(val);
    document.getElementById('dR').value = rgb.r;
    document.getElementById('dG').value = rgb.g;
    document.getElementById('dB').value = rgb.b;
    document.getElementById('fDark').value = val;
    renderQR();
}
function onLightColor(val) {
    document.getElementById('lightHex').value = val;
    var rgb = hexToRgb(val);
    document.getElementById('lR').value = rgb.r;
    document.getElementById('lG').value = rgb.g;
    document.getElementById('lB').value = rgb.b;
    document.getElementById('fLight').value = val;
    renderQR();
}
function onDarkHexInput(v) {
    if (!/^#[0-9a-fA-F]{6}$/.test(v)) return;
    document.getElementById('darkPicker').value = v;
    onDarkColor(v);
}
function onLightHexInput(v) {
    if (!/^#[0-9a-fA-F]{6}$/.test(v)) return;
    document.getElementById('lightPicker').value = v;
    onLightColor(v);
}
function rgbToHex(which) {
    var r = parseInt(document.getElementById(which === 'dark' ? 'dR' : 'lR').value) || 0;
    var g = parseInt(document.getElementById(which === 'dark' ? 'dG' : 'lG').value) || 0;
    var b = parseInt(document.getElementById(which === 'dark' ? 'dB' : 'lB').value) || 0;
    r = Math.min(255, Math.max(0, r));
    g = Math.min(255, Math.max(0, g));
    b = Math.min(255, Math.max(0, b));
    var hex = '#' + ('0'+r.toString(16)).slice(-2)
                  + ('0'+g.toString(16)).slice(-2)
                  + ('0'+b.toString(16)).slice(-2);
    if (which === 'dark') {
        document.getElementById('darkPicker').value = hex;
        document.getElementById('darkHex').value    = hex;
        document.getElementById('fDark').value      = hex;
    } else {
        document.getElementById('lightPicker').value = hex;
        document.getElementById('lightHex').value    = hex;
        document.getElementById('fLight').value      = hex;
    }
    renderQR();
}
function hexToRgb(hex) {
    hex = (hex || '#000000').replace('#','');
    return {
        r: parseInt(hex.substring(0,2),16),
        g: parseInt(hex.substring(2,4),16),
        b: parseInt(hex.substring(4,6),16)
    };
}

/* ════════════════════════ QR Render ════════════════════════ */
var _qrTimer = null;
function renderQR() {
    clearTimeout(_qrTimer);
    _qrTimer = setTimeout(function() { _doRenderQR(); }, 80);
}
function _doRenderQR() {
    var dark  = document.getElementById('fDark').value  || '#000000';
    var light = document.getElementById('fLight').value || '#FFFFFF';
    var box   = document.getElementById('qrGoCode');
    box.innerHTML = '';
    try {
        new QRCode(box, {
            text:         '<?php echo addslashes($goUrl); ?>',
            width:        168,
            height:       168,
            colorDark:    dark,
            colorLight:   light,
            correctLevel: QRCode.CorrectLevel.H
        });
    } catch(e) {}
}

/* ════════════════════════ Downloads ════════════════════════ */
function getHDCanvas() {
    var src = document.querySelector('#qrGoCode canvas');
    if (!src) return null;
    var scale = 6;
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
    if (!hd) return;
    var link      = document.createElement('a');
    link.download = '<?php echo addslashes($qr['name']); ?>-qr.png';
    link.href     = hd.toDataURL('image/png');
    link.click();
}
function downloadSVG() {
    var hd = getHDCanvas();
    if (!hd) return;
    var dataUrl = hd.toDataURL('image/png');
    var size    = hd.width;
    var svg = '<' + '?xml version="1.0" encoding="UTF-8"?>\n'
            + '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '">'
            + '<image href="' + dataUrl + '" width="' + size + '" height="' + size + '"/>'
            + '</svg>';
    var blob = new Blob([svg], {type:'image/svg+xml;charset=utf-8'});
    var url  = URL.createObjectURL(blob);
    var link = document.createElement('a');
    link.download = '<?php echo addslashes($qr['name']); ?>-qr.svg';
    link.href     = url;
    link.click();
    setTimeout(function() { URL.revokeObjectURL(url); }, 1000);
}

/* ════════════════════════ Init ════════════════════════ */
document.addEventListener('DOMContentLoaded', function() {
    buildPalette();
    applyColors(
        document.getElementById('fDark').value  || '#000000',
        document.getElementById('fLight').value || '#FFFFFF'
    );
});
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
