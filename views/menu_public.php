<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($menu['name']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <style>
    body { background:#f8f5f0;font-family:'Segoe UI',system-ui,sans-serif }
    .menu-header { background:<?= htmlspecialchars($menu['color']) ?>;color:#fff;padding:40px 20px;text-align:center }
    .menu-header h1 { font-size:28px;font-weight:700;margin:0 0 8px }
    .menu-header p  { opacity:.85;margin:0;font-size:15px }
    .section-title  { font-size:13px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:<?= htmlspecialchars($menu['color']) ?>;border-bottom:2px solid <?= htmlspecialchars($menu['color']) ?>;padding-bottom:8px;margin-bottom:16px;margin-top:32px }
    .item-card      { background:#fff;border-radius:12px;padding:14px 16px;margin-bottom:8px;box-shadow:0 1px 4px rgba(0,0,0,.07);display:flex;align-items:flex-start;gap:12px }
    .item-name      { font-weight:600;font-size:15px;line-height:1.3 }
    .item-desc      { font-size:13px;color:#777;margin-top:3px }
    .item-price     { font-weight:700;font-size:15px;color:<?= htmlspecialchars($menu['color']) ?>;white-space:nowrap;margin-left:auto;padding-left:12px }
    .powered        { text-align:center;padding:32px 0 20px;font-size:12px;color:#bbb }
    .powered a      { color:#bbb;text-decoration:none }
  </style>
</head>
<body>
  <div class="menu-header">
    <h1><?= htmlspecialchars($menu['name']) ?></h1>
    <?php if ($menu['description']): ?>
    <p><?= htmlspecialchars($menu['description']) ?></p>
    <?php endif; ?>
  </div>

  <div class="container" style="max-width:640px;padding:0 16px 40px">
    <?php if (empty($sections)): ?>
    <div class="text-center py-5 text-muted">
      <div style="font-size:40px;margin-bottom:12px">🍽️</div>
      <p>Este menú aún no tiene platillos. ¡Vuelve pronto!</p>
    </div>
    <?php else: ?>
      <?php foreach ($sections as $sec): ?>
        <?php if (!empty($sec['items'])): ?>
        <div class="section-title"><?= htmlspecialchars($sec['name']) ?></div>
        <?php foreach ($sec['items'] as $item): ?>
        <div class="item-card">
          <div class="flex-grow-1">
            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
            <?php if ($item['description']): ?>
            <div class="item-desc"><?= htmlspecialchars($item['description']) ?></div>
            <?php endif; ?>
          </div>
          <?php if ($item['price'] !== null): ?>
          <div class="item-price">$<?= number_format((float)$item['price'], 2) ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="powered">Menú digital creado con <a href="https://qlynk.mx" target="_blank">QLynk</a></div>
</body>
</html>
