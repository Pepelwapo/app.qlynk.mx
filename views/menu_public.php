<?php
$tpl   = $menu['template'] ?? 'classic';
$color = htmlspecialchars($menu['color'] ?? '#e74c3c');
$waOn  = !empty($menu['whatsapp_enabled']);
$phone = preg_replace('/\D/', '', $menu['whatsapp_phone'] ?? '');
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($menu['name']); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <style>
  * { box-sizing:border-box; margin:0; padding:0 }

  /* ── CLASSIC ──────────────────────────────────── */
  <?php if ($tpl === 'classic'): ?>
  body { background:#fafafa; font-family:'Segoe UI',system-ui,sans-serif; }
  .menu-header { background:<?php echo $color; ?>; color:#fff; padding:36px 20px 28px; text-align:center; }
  .menu-header h1 { font-size:26px; font-weight:800; margin-bottom:6px; }
  .menu-header p  { opacity:.9; font-size:14px; }
  .sec-label { font-size:11px; font-weight:800; letter-spacing:1.8px; text-transform:uppercase;
               color:<?php echo $color; ?>; border-bottom:2px solid <?php echo $color; ?>;
               padding-bottom:6px; margin:28px 0 12px; }
  .dish { display:flex; align-items:flex-start; gap:12px; padding:12px 0;
          border-bottom:1px solid #f0f0f0; }
  .dish:last-child { border-bottom:0; }
  .dish-info { flex:1; }
  .dish-name  { font-size:15px; font-weight:600; color:#111; line-height:1.3; }
  .dish-desc  { font-size:13px; color:#888; margin-top:3px; }
  .dish-price { font-size:15px; font-weight:700; color:<?php echo $color; ?>; white-space:nowrap; padding-left:12px; }
  .wrap { max-width:640px; margin:0 auto; padding:0 16px 80px; }

  <?php elseif ($tpl === 'dark'): ?>
  /* ── DARK / ELEGANT ───────────────────────────── */
  body { background:#0f0f1a; font-family:'Segoe UI',system-ui,sans-serif; color:#e8e8e8; }
  .menu-header { background:linear-gradient(135deg, <?php echo $color; ?>, #a52a2a);
                 padding:44px 20px 32px; text-align:center; }
  .menu-header h1 { font-size:28px; font-weight:800; color:#fff; margin-bottom:8px; letter-spacing:.5px; }
  .menu-header p  { color:rgba(255,255,255,.8); font-size:14px; }
  .sec-label { font-size:11px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase;
               color:<?php echo $color; ?>; margin:32px 0 14px;
               display:flex; align-items:center; gap:10px; }
  .sec-label::before,.sec-label::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.1); }
  .dish { display:flex; align-items:flex-start; gap:12px; padding:13px 16px;
          background:rgba(255,255,255,.04); border-radius:10px; margin-bottom:6px;
          border:1px solid rgba(255,255,255,.06); }
  .dish-info { flex:1; }
  .dish-name  { font-size:15px; font-weight:600; color:#f0f0f0; line-height:1.3; }
  .dish-desc  { font-size:12px; color:#888; margin-top:4px; }
  .dish-price { font-size:16px; font-weight:700; color:<?php echo $color; ?>; white-space:nowrap; padding-left:12px; }
  .wrap { max-width:640px; margin:0 auto; padding:0 16px 80px; }

  <?php else: /* cards */ ?>
  /* ── CARDS ─────────────────────────────────────── */
  body { background:#f2f4f8; font-family:'Segoe UI',system-ui,sans-serif; }
  .menu-header { background:#fff; border-bottom:3px solid <?php echo $color; ?>;
                 padding:28px 20px; text-align:center; }
  .menu-header h1 { font-size:24px; font-weight:800; color:#111; margin-bottom:5px; }
  .menu-header p  { color:#777; font-size:14px; }
  .sec-label { font-size:12px; font-weight:800; letter-spacing:1.2px; text-transform:uppercase;
               color:#111; background:<?php echo $color; ?>; color:#fff; display:inline-block;
               padding:4px 12px; border-radius:20px; margin:24px 0 14px; }
  .cards-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(150px,1fr)); gap:12px; margin-bottom:8px; }
  .dish { background:#fff; border-radius:14px; padding:14px 12px;
          box-shadow:0 2px 8px rgba(0,0,0,.07); display:flex; flex-direction:column; gap:4px; }
  .dish-name  { font-size:14px; font-weight:700; color:#111; line-height:1.3; }
  .dish-desc  { font-size:12px; color:#999; flex:1; }
  .dish-price { font-size:15px; font-weight:800; color:<?php echo $color; ?>; margin-top:6px; }
  .wrap { max-width:720px; margin:0 auto; padding:0 16px 80px; }
  <?php endif; ?>

  /* ── WhatsApp floating button ── */
  .wa-btn { position:fixed; bottom:22px; right:22px; z-index:99;
            background:#25D366; color:#fff; border:none; border-radius:50px;
            padding:13px 22px 13px 18px; font-size:15px; font-weight:700;
            box-shadow:0 4px 18px rgba(0,0,0,.22); cursor:pointer;
            display:flex; align-items:center; gap:10px; text-decoration:none;
            transition:.2s; }
  .wa-btn:hover { background:#1ebe5c; color:#fff; transform:translateY(-2px); }
  .wa-btn svg { width:22px; height:22px; flex-shrink:0; }

  .powered { text-align:center; padding:28px 0 16px; font-size:12px; color:#888; }
  .powered a { color:#aaa; text-decoration:none; }
  </style>
</head>
<body>

<div class="menu-header">
  <h1><?php echo htmlspecialchars($menu['name']); ?></h1>
  <?php if (!empty($menu['description'])): ?>
  <p><?php echo htmlspecialchars($menu['description']); ?></p>
  <?php endif; ?>
</div>

<div class="wrap">

<?php if (empty($sections)): ?>
  <div style="text-align:center;padding:60px 0;color:#aaa">
    <div style="font-size:48px;margin-bottom:14px">🍽️</div>
    <p>Este menú aún no tiene platillos. ¡Vuelve pronto!</p>
  </div>
<?php else: ?>
  <?php foreach ($sections as $sec): ?>
    <?php if (empty($sec['items'])) continue; ?>

    <div class="sec-label"><?php echo htmlspecialchars($sec['name']); ?></div>

    <?php if ($tpl === 'cards'): ?>
    <div class="cards-grid">
    <?php endif; ?>

    <?php foreach ($sec['items'] as $item): ?>
      <?php if ($tpl === 'cards'): ?>
      <div class="dish">
        <div class="dish-name"><?php echo htmlspecialchars($item['name']); ?></div>
        <?php if (!empty($item['description'])): ?>
        <div class="dish-desc"><?php echo htmlspecialchars($item['description']); ?></div>
        <?php endif; ?>
        <?php if ($item['price'] !== null): ?>
        <div class="dish-price">$<?php echo number_format((float)$item['price'], 2); ?></div>
        <?php endif; ?>
      </div>
      <?php else: ?>
      <div class="dish">
        <div class="dish-info">
          <div class="dish-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <?php if (!empty($item['description'])): ?>
          <div class="dish-desc"><?php echo htmlspecialchars($item['description']); ?></div>
          <?php endif; ?>
        </div>
        <?php if ($item['price'] !== null): ?>
        <div class="dish-price">$<?php echo number_format((float)$item['price'], 2); ?></div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($tpl === 'cards'): ?>
    </div>
    <?php endif; ?>

  <?php endforeach; ?>
<?php endif; ?>

  <div class="powered">Menú digital creado con <a href="https://qlynk.mx" target="_blank">QLynk</a></div>
</div>

<?php if ($waOn && $phone): ?>
<?php
$waMsg = urlencode('¡Hola! Me gustaría hacer un pedido del menú "' . $menu['name'] . '". ');
$waUrl = 'https://wa.me/' . $phone . '?text=' . $waMsg;
?>
<a href="<?php echo $waUrl; ?>" class="wa-btn" target="_blank" rel="noopener">
  <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
  Ordenar por WhatsApp
</a>
<?php endif; ?>

</body>
</html>
