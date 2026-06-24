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
                 required>
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
            <input name="url" class="form-control" placeholder="https://tudominio.com">
          </div>
        </div>

        <!-- WhatsApp -->
        <div id="fields-whatsapp" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Número de WhatsApp <small class="text-muted">(con código de país)</small>
            </label>
            <input name="wa_phone" class="form-control" placeholder="+521234567890">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Mensaje predefinido <small class="text-muted">(opcional)</small>
            </label>
            <textarea name="wa_message" class="form-control" rows="3"
                      placeholder="Hola, me interesa saber más…"></textarea>
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
                   placeholder="contacto@empresa.com">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Asunto <small class="text-muted">(opcional)</small>
            </label>
            <input name="em_subject" class="form-control"
                   placeholder="Ej: Consulta sobre productos">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Cuerpo del mensaje <small class="text-muted">(opcional)</small>
            </label>
            <textarea name="em_body" class="form-control" rows="3"
                      placeholder="Hola, me comunico para…"></textarea>
          </div>
        </div>

        <!-- Phone -->
        <div id="fields-phone" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Número de teléfono</label>
            <input name="phone_number" class="form-control" placeholder="+521234567890">
          </div>
        </div>

        <!-- SMS -->
        <div id="fields-sms" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Número de teléfono</label>
            <input name="sms_phone" class="form-control" placeholder="+521234567890">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">
              Mensaje <small class="text-muted">(opcional)</small>
            </label>
            <textarea name="sms_message" class="form-control" rows="2"
                      placeholder="Hola, me interesa…"></textarea>
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
            <input name="wifi_ssid" class="form-control" placeholder="MiRedWiFi">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Contraseña</label>
            <input name="wifi_password" class="form-control" placeholder="••••••••">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Seguridad</label>
            <select name="wifi_security" class="form-select">
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
              <input name="vc_name" class="form-control" placeholder="Juan Pérez">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Empresa</label>
              <input name="vc_company" class="form-control" placeholder="Mi Empresa S.A.">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Teléfono</label>
            <input name="vc_phone" class="form-control" placeholder="+521234567890">
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Correo</label>
            <input name="vc_email" type="email" class="form-control"
                   placeholder="juan@empresa.com">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Sitio web</label>
            <input name="vc_website" class="form-control" placeholder="https://...">
          </div>
        </div>

        <!-- PDF -->
        <div id="fields-pdf" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL del PDF</label>
            <input name="pdf_url" class="form-control"
                   placeholder="https://drive.google.com/file/...">
          </div>
        </div>

        <!-- Social -->
        <div id="fields-social" class="qr-fields" style="display:none">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">URL del perfil</label>
            <input name="social_url" class="form-control"
                   placeholder="https://instagram.com/miusuario">
          </div>
        </div>

        <!-- Evento -->
        <div id="fields-event" class="qr-fields" style="display:none">
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Título del evento</label>
            <input name="ev_title" class="form-control" placeholder="Inauguración de sucursal">
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Inicio</label>
              <input name="ev_start" type="datetime-local" class="form-control">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:13px">Fin</label>
              <input name="ev_end" type="datetime-local" class="form-control">
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:13px">Ubicación</label>
            <input name="ev_location" class="form-control" placeholder="Av. Insurgentes 123, CDMX">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
            <textarea name="ev_description" class="form-control" rows="2"
                      placeholder="Breve descripción…"></textarea>
          </div>
        </div>

        <button class="btn btn-dark w-100 fw-semibold" style="border-radius:10px;padding:11px">
          <i class="bi bi-qr-code me-2"></i>Crear QR
        </button>
      </form>

    </div>
    </div>
    </div>

    <!-- ── Panel derecho: información (NO preview live) ── -->
    <div class="col-md-5">
    <div class="card border-0 shadow-sm text-center" style="border-radius:16px;position:sticky;top:20px">
    <div class="card-body p-4">

      <div style="font-size:48px;margin-bottom:8px">⚡</div>
      <h6 class="fw-bold mb-2" style="font-size:15px">QR Dinámico</h6>
      <p class="text-muted mb-4" style="font-size:13px;line-height:1.6">
        Tu código QR se genera al crearlo y <strong>nunca cambia</strong>.
        Puedes actualizar el destino cuantas veces quieras — el QR impreso sigue funcionando.
      </p>

      <hr class="my-3">

      <div class="text-start" style="font-size:13px">
        <div class="d-flex align-items-start gap-2 mb-3">
          <span style="font-size:18px;min-width:24px">🔗</span>
          <div>
            <div class="fw-semibold">Código único y fijo</div>
            <div class="text-muted" style="font-size:12px">El QR siempre apunta al mismo enlace corto, sin importar qué cambies en el destino.</div>
          </div>
        </div>
        <div class="d-flex align-items-start gap-2 mb-3">
          <span style="font-size:18px;min-width:24px">✏️</span>
          <div>
            <div class="fw-semibold">Editable en cualquier momento</div>
            <div class="text-muted" style="font-size:12px">Cambia el destino, el tipo o el contenido sin reimprimir el QR.</div>
          </div>
        </div>
        <div class="d-flex align-items-start gap-2">
          <span style="font-size:18px;min-width:24px">📊</span>
          <div>
            <div class="fw-semibold">Con estadísticas de escaneo</div>
            <div class="text-muted" style="font-size:12px">Cada escaneo queda registrado. Consulta el historial desde la lista de QRs.</div>
          </div>
        </div>
      </div>

      <hr class="my-3">

      <div class="text-muted" style="font-size:11px">
        <i class="bi bi-info-circle me-1"></i>
        El QR y su imagen HD están disponibles después de crearlo.
      </div>

    </div>
    </div>
    </div>

  </div><!-- /row -->

</main>
</div>

<script>
function switchType() {
    document.querySelectorAll('.qr-fields').forEach(el => el.style.display = 'none');
    var type = document.getElementById('qrType').value;
    var el = document.getElementById('fields-' + type);
    if (el) el.style.display = 'block';
}
// Init
switchType();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
