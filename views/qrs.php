<?php
$pageTitle = 'Mis QR';
require __DIR__.'/partials/head.php';

$stmt = $pdo->prepare("
    SELECT * FROM qr_codes
    WHERE user_id = ?
    AND deleted_at IS NULL
    ORDER BY id DESC
");
$stmt->execute([$_SESSION['user_id']]);
$qrs = $stmt->fetchAll();
?>
<style>
.qr-thumb { width:72px;height:72px;display:inline-block; }
.qr-row   { vertical-align:middle!important; }
.badge-type {
    font-size:11px;font-weight:600;padding:3px 8px;border-radius:20px;
    background:#f0f0f5;color:#555;text-transform:uppercase;letter-spacing:.3px;
}
</style>
<div class="d-flex">
<?php require __DIR__.'/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">QR Dinámicos</h4>
    <a href="/qrs/create" class="btn btn-dark btn-sm fw-semibold" style="border-radius:8px;padding:8px 16px">
      <i class="bi bi-plus-lg me-1"></i>Crear QR
    </a>
  </div>

  <?php if (empty($qrs)): ?>
  <div class="card border-0 shadow-sm text-center p-5" style="border-radius:16px;max-width:500px;margin:0 auto">
    <div style="font-size:48px;margin-bottom:16px">📋</div>
    <h5 class="fw-bold mb-2">Aún no tienes QR dinámicos</h5>
    <p class="text-muted mb-4" style="font-size:14px">
      Crea tu primer código QR y empieza a rastrear escaneos en tiempo real.
    </p>
    <a href="/qrs/create" class="btn btn-dark fw-semibold" style="border-radius:10px">
      <i class="bi bi-qr-code me-2"></i>Crear mi primer QR
    </a>
  </div>

  <?php else: ?>
  <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
    <div class="table-responsive">
    <table class="table mb-0" style="border-collapse:separate">
      <thead style="background:#f8f9fa;border-bottom:2px solid #eee">
        <tr style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:#888">
          <th class="py-3 px-3">QR</th>
          <th class="py-3 px-3">Nombre</th>
          <th class="py-3 px-3">Código</th>
          <th class="py-3 px-3">Tipo</th>
          <th class="py-3 px-3 text-center">Escaneos</th>
          <th class="py-3 px-3 text-center">Estado</th>
          <th class="py-3 px-3 text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($qrs as $qr):
          $goUrl = APP_GO_URL . '/' . htmlspecialchars($qr['short_code'], ENT_QUOTES);
          $safeName = addslashes(htmlspecialchars($qr['name'], ENT_QUOTES));
          $safeCode = htmlspecialchars($qr['short_code'], ENT_QUOTES);
          $typeLabels = [
              'url'=>'URL','whatsapp'=>'WhatsApp','email'=>'Email','phone'=>'Llamada',
              'sms'=>'SMS','wifi'=>'WiFi','vcard'=>'vCard','pdf'=>'PDF',
              'social'=>'Social','event'=>'Evento',
          ];
          $typeLabel = $typeLabels[$qr['type']] ?? strtoupper($qr['type'] ?? 'URL');
      ?>
      <tr class="qr-row" style="border-bottom:1px solid #f0f0f0">
        <!-- Thumbnail QR -->
        <td class="py-3 px-3">
          <div class="d-flex align-items-center gap-2">
            <div class="qr-thumb" id="qt-<?php echo (int)$qr['id']; ?>"></div>
            <button onclick="hdDownload('<?php echo $goUrl; ?>','<?php echo $safeName; ?>')"
                    class="btn btn-sm btn-outline-dark"
                    title="Descargar HD"
                    style="border-radius:6px;padding:3px 8px;font-size:11px">
              <i class="bi bi-download"></i>
            </button>
          </div>
        </td>
        <!-- Nombre -->
        <td class="py-3 px-3">
          <div class="fw-semibold" style="font-size:14px"><?php echo htmlspecialchars($qr['name']); ?></div>
        </td>
        <!-- Código -->
        <td class="py-3 px-3">
          <code style="font-size:12px;background:#f4f5f7;padding:2px 6px;border-radius:4px">
            <?php echo htmlspecialchars($qr['short_code']); ?>
          </code>
        </td>
        <!-- Tipo -->
        <td class="py-3 px-3">
          <span class="badge-type"><?php echo $typeLabel; ?></span>
        </td>
        <!-- Escaneos -->
        <td class="py-3 px-3 text-center fw-bold" style="font-size:14px">
          <?php echo number_format((int)$qr['scan_count']); ?>
        </td>
        <!-- Estado toggle -->
        <td class="py-3 px-3 text-center">
          <a href="/qrs/toggle?id=<?php echo (int)$qr['id']; ?>"
             class="badge text-decoration-none fs-6"
             style="<?php echo $qr['active']
                 ? 'background:#d1fae5;color:#065f46;padding:4px 10px;border-radius:20px;font-size:11px!important'
                 : 'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:20px;font-size:11px!important'; ?>">
            <?php echo $qr['active'] ? '● Activo' : '○ Inactivo'; ?>
          </a>
        </td>
        <!-- Acciones -->
        <td class="py-3 px-3 text-end">
          <a href="/qrs/edit?id=<?php echo (int)$qr['id']; ?>"
             class="btn btn-sm btn-outline-dark me-1" style="border-radius:6px">
            <i class="bi bi-pencil me-1"></i>Editar
          </a>
          <a href="/qrs/delete?id=<?php echo (int)$qr['id']; ?>"
             onclick="return confirm('¿Eliminar este QR? No se puede deshacer.')"
             class="btn btn-sm btn-outline-danger" style="border-radius:6px">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>
  <?php endif; ?>

</main>
</div>

<?php if (!empty($qrs)): ?>
<!-- QR data for JS rendering -->
<script>
var QR_LIST = <?php
    $jsQrs = [];
    foreach ($qrs as $qr) {
        $jsQrs[] = [
            'id'   => (int)$qr['id'],
            'url'  => APP_GO_URL . '/' . $qr['short_code'],
            'name' => $qr['name'],
        ];
    }
    echo json_encode($jsQrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// Renderizar thumbnails QR para cada fila
(function() {
    QR_LIST.forEach(function(item) {
        var el = document.getElementById('qt-' + item.id);
        if (!el) return;
        try {
            new QRCode(el, {
                text:         item.url,
                width:        72,
                height:       72,
                colorDark:    '#1a1a2e',
                colorLight:   '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        } catch(e) {
            el.innerHTML = '<span style="font-size:10px;color:#ccc">QR</span>';
        }
    });
})();

// Descarga HD de un QR de la lista
function hdDownload(url, name) {
    // Crear canvas temporal oculto
    var tmp = document.createElement('div');
    tmp.style.cssText = 'position:fixed;left:-9999px;top:-9999px;width:200px;height:200px';
    document.body.appendChild(tmp);

    try {
        new QRCode(tmp, {
            text:         url,
            width:        200,
            height:       200,
            colorDark:    '#1a1a2e',
            colorLight:   '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    } catch(e) {
        document.body.removeChild(tmp);
        alert('No se pudo generar el QR.');
        return;
    }

    setTimeout(function() {
        var src = tmp.querySelector('canvas');
        if (!src) { document.body.removeChild(tmp); return; }

        var scale = 6; // 200px → 1200px
        var hd    = document.createElement('canvas');
        hd.width  = src.width  * scale;
        hd.height = src.height * scale;
        var ctx   = hd.getContext('2d');
        ctx.imageSmoothingEnabled = false;
        ctx.drawImage(src, 0, 0, hd.width, hd.height);

        var link      = document.createElement('a');
        link.download = (name || 'qr-code') + '.png';
        link.href     = hd.toDataURL('image/png');
        link.click();

        document.body.removeChild(tmp);
    }, 80); // Pequeño delay para que QRCode.js termine de dibujar
}
</script>
<?php endif; ?>

<?php require __DIR__.'/partials/footer.php'; ?>
