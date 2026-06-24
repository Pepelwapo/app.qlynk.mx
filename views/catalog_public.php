<?php
$tpl   = $catalog['template'] ?? 'classic';
$waOn  = !empty($catalog['whatsapp_enabled']);
$phone = preg_replace('/\D/', '', $catalog['whatsapp_phone'] ?? '');
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($catalog['name']); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <style>
  * { box-sizing:border-box; margin:0; padding:0; }

  <?php if ($tpl === 'classic'): ?>
  /* ── CLASSIC ─────────────────────────────── */
  body { background:#f7f8fc; font-family:'Segoe UI',system-ui,sans-serif; }
  .cat-header { background:#1a1a2e; color:#fff; padding:40px 20px 28px; text-align:center; }
  .cat-header h1 { font-size:26px; font-weight:800; margin-bottom:6px; }
  .cat-header p  { opacity:.8; font-size:14px; }
  .filter-tabs { display:flex; gap:8px; overflow-x:auto; padding:16px 0 8px; scrollbar-width:none; }
  .filter-tabs::-webkit-scrollbar { display:none; }
  .filter-tab { background:#fff; border:1px solid #ddd; border-radius:20px; padding:5px 16px;
                font-size:13px; cursor:pointer; white-space:nowrap; color:#555; }
  .filter-tab.active { background:#1a1a2e; color:#fff; border-color:#1a1a2e; }
  .cat-title { font-size:11px; font-weight:800; letter-spacing:1.8px; text-transform:uppercase;
               color:#1a1a2e; border-bottom:2px solid #1a1a2e; padding-bottom:6px; margin:24px 0 12px; }
  .product-card { background:#fff; border-radius:12px; padding:14px 16px; margin-bottom:8px;
                  box-shadow:0 1px 5px rgba(0,0,0,.06); display:flex; align-items:flex-start; gap:12px; }
  .p-name  { font-weight:600; font-size:14px; color:#111; }
  .p-desc  { font-size:12px; color:#888; margin-top:3px; }
  .p-sku   { font-size:11px; color:#ccc; font-family:monospace; margin-top:2px; }
  .p-price { font-size:15px; font-weight:800; color:#1a1a2e; white-space:nowrap; padding-left:12px; }
  .wrap { max-width:720px; margin:0 auto; padding:0 16px 80px; }

  <?php elseif ($tpl === 'dark'): ?>
  /* ── DARK / ELEGANT ─────────────────────── */
  body { background:#0f0f1a; font-family:'Segoe UI',system-ui,sans-serif; color:#e0e0e0; }
  .cat-header { background:linear-gradient(135deg, #1a1a2e 0%, #2d2d4e 100%);
                padding:44px 20px 32px; text-align:center; border-bottom:2px solid rgba(255,255,255,.07); }
  .cat-header h1 { font-size:28px; font-weight:800; color:#fff; margin-bottom:8px; letter-spacing:.4px; }
  .cat-header p  { color:rgba(255,255,255,.65); font-size:14px; }
  .filter-tabs { display:flex; gap:8px; overflow-x:auto; padding:16px 0 8px; scrollbar-width:none; }
  .filter-tabs::-webkit-scrollbar { display:none; }
  .filter-tab { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1);
                border-radius:20px; padding:5px 16px; font-size:13px; cursor:pointer;
                white-space:nowrap; color:#aaa; }
  .filter-tab.active { background:#e74c3c; color:#fff; border-color:#e74c3c; }
  .cat-title { font-size:11px; font-weight:700; letter-spacing:2.5px; text-transform:uppercase;
               color:#e74c3c; margin:32px 0 14px;
               display:flex; align-items:center; gap:10px; }
  .cat-title::before,.cat-title::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.08); }
  .product-card { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.07);
                  border-radius:12px; padding:14px 16px; margin-bottom:8px;
                  display:flex; align-items:flex-start; gap:12px; }
  .p-name  { font-weight:600; font-size:14px; color:#f0f0f0; }
  .p-desc  { font-size:12px; color:#888; margin-top:3px; }
  .p-sku   { font-size:11px; color:#555; font-family:monospace; margin-top:2px; }
  .p-price { font-size:15px; font-weight:800; color:#e74c3c; white-space:nowrap; padding-left:12px; }
  .wrap { max-width:720px; margin:0 auto; padding:0 16px 80px; }

  <?php else: /* cards */ ?>
  /* ── CARDS ──────────────────────────────── */
  body { background:#f2f4f8; font-family:'Segoe UI',system-ui,sans-serif; }
  .cat-header { background:#fff; border-bottom:3px solid #1a1a2e; padding:30px 20px; text-align:center; }
  .cat-header h1 { font-size:24px; font-weight:800; color:#111; margin-bottom:5px; }
  .cat-header p  { color:#777; font-size:14px; }
  .filter-tabs { display:flex; gap:8px; overflow-x:auto; padding:16px 0 8px; scrollbar-width:none; }
  .filter-tabs::-webkit-scrollbar { display:none; }
  .filter-tab { background:#fff; border:1px solid #ddd; border-radius:20px; padding:5px 16px;
                font-size:13px; cursor:pointer; white-space:nowrap; color:#555; }
  .filter-tab.active { background:#1a1a2e; color:#fff; border-color:#1a1a2e; }
  .cat-title { font-size:12px; font-weight:800; letter-spacing:1.2px; text-transform:uppercase;
               background:#1a1a2e; color:#fff; display:inline-block; padding:5px 14px;
               border-radius:20px; margin:24px 0 14px; }
  .cards-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:12px; margin-bottom:8px; }
  .product-card { background:#fff; border-radius:14px; padding:14px 12px;
                  box-shadow:0 2px 8px rgba(0,0,0,.07); display:flex; flex-direction:column; gap:3px; }
  .p-name  { font-weight:700; font-size:13px; color:#111; line-height:1.3; }
  .p-desc  { font-size:11px; color:#999; flex:1; margin-top:2px; }
  .p-sku   { font-size:10px; color:#ccc; font-family:monospace; }
  .p-price { font-size:15px; font-weight:800; color:#1a1a2e; margin-top:6px; }
  .wrap { max-width:760px; margin:0 auto; padding:0 16px 80px; }
  <?php endif; ?>

  /* ── WhatsApp floating button ── */
  .wa-btn { position:fixed; bottom:22px; right:22px; z-index:99;
            background:#25D366; color:#fff; border:none; border-radius:50px;
            padding:13px 22px 13px 18px; font-size:15px; font-weight:700;
            box-shadow:0 4px 18px rgba(0,0,0,.22); cursor:pointer;
            display:flex; align-items:center; gap:10px; text-decoration:none; transition:.2s; }
  .wa-btn:hover { background:#1ebe5c; color:#fff; transform:translateY(-2px); }
  .wa-btn svg { width:22px; height:22px; flex-shrink:0; }

  .powered { text-align:center; padding:28px 0 16px; font-size:12px; color:#888; }
  .powered a { color:#aaa; text-decoration:none; }
  </style>
</head>
<body>

<div class="cat-header">
  <h1><?php echo htmlspecialchars($catalog['name']); ?></h1>
  <?php if (!empty($catalog['description'])): ?>
  <p><?php echo htmlspecialchars($catalog['description']); ?></p>
  <?php endif; ?>
</div>

<div class="wrap">

  <?php if (!empty($categories)): ?>
  <div class="filter-tabs" id="filterTabs">
    <div class="filter-tab active" onclick="filterCat(0, this)">Todos</div>
    <?php foreach ($categories as $cat): ?>
    <div class="filter-tab" onclick="filterCat(<?php echo (int)$cat['id']; ?>, this)">
      <?php echo htmlspecialchars($cat['name']); ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php if (empty($items)): ?>
  <div style="text-align:center;padding:60px 0;color:#aaa">
    <div style="font-size:48px;margin-bottom:14px">&#128230;</div>
    <p>Este catálogo aún no tiene productos.</p>
  </div>
  <?php else: ?>

    <?php if (!empty($categories)): ?>
      <?php foreach ($categories as $cat): ?>
      <?php $catItems = $itemsByCategory[$cat['id']] ?? []; ?>
      <?php if (empty($catItems)) continue; ?>
      <div class="cat-section" data-cat="<?php echo (int)$cat['id']; ?>">
        <?php if ($tpl === 'cards'): ?>
        <div class="cat-title"><?php echo htmlspecialchars($cat['name']); ?></div>
        <div class="cards-grid">
          <?php foreach ($catItems as $item): ?>
          <div class="product-card">
            <div class="p-name"><?php echo htmlspecialchars($item['name']); ?></div>
            <?php if (!empty($item['description'])): ?><div class="p-desc"><?php echo htmlspecialchars($item['description']); ?></div><?php endif; ?>
            <?php if (!empty($item['sku'])): ?><div class="p-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></div><?php endif; ?>
            <?php if ($item['price'] !== null): ?><div class="p-price">$<?php echo number_format((float)$item['price'], 2); ?></div><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="cat-title"><?php echo htmlspecialchars($cat['name']); ?></div>
        <?php foreach ($catItems as $item): ?>
        <div class="product-card">
          <div style="flex:1">
            <div class="p-name"><?php echo htmlspecialchars($item['name']); ?></div>
            <?php if (!empty($item['description'])): ?><div class="p-desc"><?php echo htmlspecialchars($item['description']); ?></div><?php endif; ?>
            <?php if (!empty($item['sku'])): ?><div class="p-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></div><?php endif; ?>
          </div>
          <?php if ($item['price'] !== null): ?><div class="p-price">$<?php echo number_format((float)$item['price'], 2); ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>

      <?php $uncatItems = $itemsByCategory[0] ?? []; ?>
      <?php if (!empty($uncatItems)): ?>
      <div class="cat-section" data-cat="uncat">
        <?php if ($tpl === 'cards'): ?>
        <div class="cat-title">Sin categoría</div>
        <div class="cards-grid">
          <?php foreach ($uncatItems as $item): ?>
          <div class="product-card">
            <div class="p-name"><?php echo htmlspecialchars($item['name']); ?></div>
            <?php if (!empty($item['description'])): ?><div class="p-desc"><?php echo htmlspecialchars($item['description']); ?></div><?php endif; ?>
            <?php if (!empty($item['sku'])): ?><div class="p-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></div><?php endif; ?>
            <?php if ($item['price'] !== null): ?><div class="p-price">$<?php echo number_format((float)$item['price'], 2); ?></div><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="cat-title">Sin categoría</div>
        <?php foreach ($uncatItems as $item): ?>
        <div class="product-card">
          <div style="flex:1">
            <div class="p-name"><?php echo htmlspecialchars($item['name']); ?></div>
            <?php if (!empty($item['description'])): ?><div class="p-desc"><?php echo htmlspecialchars($item['description']); ?></div><?php endif; ?>
            <?php if (!empty($item['sku'])): ?><div class="p-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></div><?php endif; ?>
          </div>
          <?php if ($item['price'] !== null): ?><div class="p-price">$<?php echo number_format((float)$item['price'], 2); ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>

    <?php else: ?>
      <?php if ($tpl === 'cards'): ?>
      <div class="cards-grid" style="margin-top:16px">
        <?php foreach ($items as $item): ?>
        <div class="product-card">
          <div class="p-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <?php if (!empty($item['description'])): ?><div class="p-desc"><?php echo htmlspecialchars($item['description']); ?></div><?php endif; ?>
          <?php if (!empty($item['sku'])): ?><div class="p-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></div><?php endif; ?>
          <?php if ($item['price'] !== null): ?><div class="p-price">$<?php echo number_format((float)$item['price'], 2); ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div style="margin-top:16px">
        <?php foreach ($items as $item): ?>
        <div class="product-card">
          <div style="flex:1">
            <div class="p-name"><?php echo htmlspecialchars($item['name']); ?></div>
            <?php if (!empty($item['description'])): ?><div class="p-desc"><?php echo htmlspecialchars($item['description']); ?></div><?php endif; ?>
            <?php if (!empty($item['sku'])): ?><div class="p-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></div><?php endif; ?>
          </div>
          <?php if ($item['price'] !== null): ?><div class="p-price">$<?php echo number_format((float)$item['price'], 2); ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>

  <?php endif; ?>

  <div class="powered">Catálogo digital creado con <a href="https://qlynk.mx" target="_blank">QLynk</a></div>
</div>

<?php if ($waOn && $phone): ?>
<?php
$waMsg = urlencode('Hola! Vi el catálogo "' . $catalog['name'] . '" y me gustaria obtener mas informacion.');
$waUrl = 'https://wa.me/' . $phone . '?text=' . $waMsg;
?>
<a href="<?php echo $waUrl; ?>" class="wa-btn" target="_blank" rel="noopener">
  <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
  Consultar por WhatsApp
</a>
<?php endif; ?>

<script>
function filterCat(catId, el) {
  document.querySelectorAll('.filter-tab').forEach(function(t) { t.classList.remove('active'); });
  el.classList.add('active');
  document.querySelectorAll('.cat-section').forEach(function(sec) {
    sec.style.display = (catId === 0 || sec.dataset.cat == catId) ? '' : 'none';
  });
}
</script>
</body>
</html>
