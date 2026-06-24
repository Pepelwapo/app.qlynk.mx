<?php
if (!isset($qr) || !isset($fields)) { header('Location: /qrs'); exit; }
$pageTitle = 'Editar QR';
$goUrl     = APP_GO_URL . '/' . $qr['short_code'];
require __DIR__ . '/partials/head.php';

$tipos  = ['url','whatsapp','email','phone','sms','wifi','vcard','pdf','social','event'];
$labels = [
    'url'      => '🌐 URL / Sitio web',
    'whatsapp' => '💬 WhatsApp',
    'email'    => '📧 Email',
    'phone'    => '📞 Llamada',
    'sms'      => '💬 SMS',
    'wifi'     => '📶 WiFi',
    'vcard'    => '👤 vCard / Contacto',
    'pdf'      => '📄 PDF',
    'social'   => '📱 Red Social',
    'event'    => '📅 Evento',
];
$f = fn(string $key): string => htmlspecialchars($fields[$key] ?? '');
?>
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
       style="border-radius:10px;max-width:900px">
    <i class="bi bi-check-circle-fill text-success"></i>
    Cambios guardados correctamente.
  </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger d-flex align-items-center gap-2 mb-3"
       style="border-radius:10px;max-width:900px">
    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
    <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <div class="row g-4" style="max-width:900px">

    <!-- ── Formulario ── -->
    <div class="col-md-7">
    <div class="card border-0 shadow-sm" style="border-radius:16px">
    <div class="card-body p-4">

      <h5 class="fw-bold mb-4">
        Editar: <span class="text-muted fw-normal"><?php echo htmlspecialchars($qr['name']); ?></span>
      </h5>

      <form method="POST" action="/qrs/edit?id=<?php echo (int)$qr['id']; ?>" id="editForm">
        <input type="hidden"
               name="<?php echo CSRF_TOKEN_NAME; ?>"
               value="<?php echo csrf_token(); ?>">

        <!-- Nombre -->
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:13px">Nombre del QR</label>
          <input name="name" class="form-control" id="qrName"
                 value="<?php echo htmlspecialchars($qr['name']); ?>"
                 required oninput="updatePreview()">
        </div>

        <!-- Tipo -->
        <div class="mb-4">
          <label class="form-label fw-semibold" style="font-size:13px">Tipo de contenido</label>
          <select name="type" id="qrType" class="form-select" onchange="switchType()">
            <?php foreach ($tipos as $val): ?>
            <option value="<?php echo $val; ?>"
                    <?php echo ($qr['type'] === $val) ? 'selected' : ''; ?>>
              <?php echo $labels[$val]; ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- ═══ Campos dinámicos ═══ -->

        <!-- URL -->
        <div id="fields-url" class="qr-fields">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL destino</label>
            <input name="url" class="form-control" placeholder="https://tudominio.com"
                   value="<?php echo $f('url'); ?>" oninput="updatePreview()">
          </div>
        </div>

        <!-- WhatsApp -->
        <div id="fields-whatsapp" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Número de WhatsApp</label>
            <input name="wa_phone" class="form-control" placeholder="+521234567890"
                   value="<?php echo $f('wa_phone'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Mensaje predefinido</label>
            <textarea name="wa_message" class="form-control" rows="3"
                      oninput="updatePreview()"><?php echo $f('wa_message'); ?></textarea>
            <div class="mt-1" style="font-size:12px;color:#888">Tip: puedes usar emojis 🎉🔥✅</div>
          </div>
        </div>

        <!-- Email -->
        <div id="fields-email" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Correo destino</label>
            <input name="em_email" type="email" class="form-control"
                   value="<?php echo $f('em_email'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Asunto</label>
            <input name="em_subject" class="form-control"
                   value="<?php echo $f('em_subject'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Cuerpo del mensaje</label>
            <textarea name="em_body" class="form-control" rows="3"
                      oninput="updatePreview()"><?php echo $f('em_body'); ?></textarea>
          </div>
        </div>

        <!-- Phone -->
        <div id="fields-phone" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Número de teléfono</label>
            <input name="phone_number" class="form-control" placeholder="+521234567890"
                   value="<?php echo $f('phone_number'); ?>" oninput="updatePreview()">
          </div>
        </div>

        <!-- SMS -->
        <div id="fields-sms" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Número de teléfono</label>
            <input name="sms_phone" class="form-control" placeholder="+521234567890"
                   value="<?php echo $f('sms_phone'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Mensaje</label>
            <textarea name="sms_message" class="form-control" rows="2"
                      oninput="updatePreview()"><?php echo $f('sms_message'); ?></textarea>
          </div>
        </div>

        <!-- WiFi -->
        <div id="fields-wifi" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Nombre de red (SSID)</label>
            <input name="wifi_ssid" class="form-control" placeholder="MiRedWiFi"
                   value="<?php echo $f('wifi_ssid'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Contraseña</label>
            <input name="wifi_password" class="form-control"
                   value="<?php echo $f('wifi_password'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Seguridad</label>
            <select name="wifi_security" class="form-select" onchange="updatePreview()">
              <?php foreach (['WPA'=>'WPA / WPA2','WEP'=>'WEP','nopass'=>'Sin contraseña'] as $k=>$v): ?>
              <option value="<?php echo $k; ?>"
                      <?php echo (($fields['wifi_security'] ?? 'WPA') === $k) ? 'selected' : ''; ?>>
                <?php echo $v; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- vCard -->
        <div id="fields-vcard" class="qr-fields" style="display:none">
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Nombre</label>
              <input name="vc_name" class="form-control" placeholder="Juan Pérez"
                     value="<?php echo $f('vc_name'); ?>" oninput="updatePreview()">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Empresa</label>
              <input name="vc_company" class="form-control"
                     value="<?php echo $f('vc_company'); ?>" oninput="updatePreview()">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Teléfono</label>
            <input name="vc_phone" class="form-control"
                   value="<?php echo $f('vc_phone'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Correo</label>
            <input name="vc_email" type="email" class="form-control"
                   value="<?php echo $f('vc_email'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Sitio web</label>
            <input name="vc_website" class="form-control"
                   value="<?php echo $f('vc_website'); ?>" oninput="updatePreview()">
          </div>
        </div>

        <!-- PDF -->
        <div id="fields-pdf" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL del PDF</label>
            <input name="pdf_url" class="form-control"
                   value="<?php echo $f('pdf_url'); ?>" oninput="updatePreview()">
          </div>
        </div>

        <!-- Social -->
        <div id="fields-social" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL del perfil</label>
            <input name="social_url" class="form-control"
                   value="<?php echo $f('social_url'); ?>" oninput="updatePreview()">
          </div>
        </div>

        <!-- Evento -->
        <div id="fields-event" class="qr-fields" style="display:none">
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Título</label>
            <input name="ev_title" class="form-control"
                   value="<?php echo $f('ev_title'); ?>" oninput="updatePreview()">
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Inicio</label>
              <input name="ev_start" type="datetime-local" class="form-control"
                     value="<?php echo $f('ev_start'); ?>" oninput="updatePreview()">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Fin</label>
              <input name="ev_end" type="datetime-local" class="form-control"
                     value="<?php echo $f('ev_end'); ?>" oninput="updatePreview()">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Ubicación</label>
            <input name="ev_location" class="form-control"
                   value="<?php echo $f('ev_location'); ?>" oninput="updatePreview()">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
            <textarea name="ev_description" class="form-control" rows="2"
                      oninput="updatePreview()"><?php echo $f('ev_description'); ?></textarea>
          </div>
        </div>

        <div class="d-flex gap-2 mt-1">
          <button type="submit" class="btn btn-dark flex-grow-1 fw-semibold" style="border-radius:10px;padding:11px">
            <i class="bi bi-check-lg me-1"></i>Guardar cambios
          </button>
          <a href="/qrs" class="btn btn-outline-secondary" style="border-radius:10px;padding:11px">
            Cancelar
          </a>
        </div>

      </form>

    </div>
    </div>
    </div>

    <!-- ── Preview ── -->
    <div class="col-md-5">
    <div class="card border-0 shadow-sm text-center" style="border-radius:16px;position:sticky;top:20px">
    <div class="card-body p-4">

      <h6 class="fw-bold mb-1" style="font-size:13px;color:#888;text-transform:uppercase;letter-spacing:.5px">
        QR Dinámico
      </h6>
      <p style="font-size:11px;color:#aaa" class="mb-3">
        Este es el QR que tus clientes escanean
      </p>

      <!-- QR del go.qlynk.mx (el real) -->
      <div style="display:inline-block;padding:12px;background:#fff;border-radius:12px;border:2px solid #e8e8e8">
        <div id="qrGoCode" style="width:180px;height:180px"></div>
      </div>

      <div class="mt-2" style="font-size:11px;color:#aaa">
        <code style="font-size:10px"><?php echo htmlspecialchars($goUrl); ?></code>
      </div>

      <div class="mt-3">
        <button onclick="downloadPNG()" class="btn btn-outline-dark btn-sm me-2"
                style="border-radius:8px">
          <i class="bi bi-download me-1"></i>PNG
        </button>
        <button onclick="downloadSVG()" class="btn btn-outline-secondary btn-sm"
                style="border-radius:8px">
          <i class="bi bi-download me-1"></i>SVG
        </button>
      </div>

      <hr class="my-3">

      <!-- Stats rápidas -->
      <div class="d-flex justify-content-around mb-3">
        <div class="text-center">
          <div class="fw-bold" style="font-size:22px"><?php echo number_format((int)$qr['scan_count']); ?></div>
          <div style="font-size:11px;color:#aaa">Escaneos totales</div>
        </div>
        <div class="text-center">
          <span class="badge <?php echo $qr['active'] ? 'bg-success' : 'bg-secondary'; ?>">
            <?php echo $qr['active'] ? 'Activo' : 'Inactivo'; ?>
          </span>
          <div style="font-size:11px;color:#aaa;margin-top:4px">Estado</div>
        </div>
        <div class="text-center">
          <code style="font-size:13px"><?php echo htmlspecialchars($qr['short_code']); ?></code>
          <div style="font-size:11px;color:#aaa;margin-top:4px">Código</div>
        </div>
      </div>

      <?php if (!empty($scanDays)): ?>
      <!-- Chart de escaneos (últimos 14 días) -->
      <div class="mt-2">
        <div style="font-size:11px;color:#aaa;text-align:left;margin-bottom:4px">
          Escaneos — últimos 14 días
        </div>
        <canvas id="scanChart" height="100"></canvas>
      </div>
      <?php else: ?>
      <div style="font-size:11px;color:#ccc;text-align:center;margin-top:8px">
        <i class="bi bi-bar-chart me-1"></i>Sin datos de escaneo aún
      </div>
      <?php endif; ?>

    </div>
    </div>
    </div>

  </div>

</main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<?php if (!empty($scanDays)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    var ctx  = document.getElementById('scanChart');
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
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: function(items) { return items[0].label; },
                        label: function(item)  { return item.raw + ' escaneo' + (item.raw !== 1 ? 's' : ''); }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 }, maxRotation: 0, color: '#aaa' }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 9 }, color: '#aaa',
                        callback: function(v) { return Number.isInteger(v) ? v : null; }
                    },
                    grid: { color: '#f0f0f0' }
                }
            }
        }
    });
})();
</script>
<?php endif; ?>
<script>
var qrDynamic = null;

// Renderizar el QR dinámico (apunta a go.qlynk.mx)
(function() {
    var wrap = document.getElementById('qrGoCode');
    qrDynamic = new QRCode(wrap, {
        text:         '<?php echo addslashes($goUrl); ?>',
        width:        180,
        height:       180,
        colorDark:    '#1a1a2e',
        colorLight:   '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
})();

function switchType() {
    document.querySelectorAll('.qr-fields').forEach(el => el.style.display = 'none');
    var type = document.getElementById('qrType').value;
    var el   = document.getElementById('fields-' + type);
    if (el) el.style.display = 'block';
}

function updatePreview() {
    // No regeneramos el QR go.qlynk.mx (no cambia)
    // Solo re-mostramos el tipo correcto
}

function getHDCanvas() {
    var src = document.querySelector('#qrGoCode canvas');
    if (!src) return null;
    var scale = 6; // 180px → 1080px HD
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
    var svg     = '<?xml version="1.0" encoding="UTF-8"?>\n'
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

// Init: mostrar solo los campos del tipo actual
switchType();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
