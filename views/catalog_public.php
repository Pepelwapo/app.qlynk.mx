<?php
$tpl   = $catalog['template'] ?? 'classic';
$waOn  = !empty($catalog['whatsapp_enabled']);
$phone = preg_replace('/\D/', '', $catalog['whatsapp_phone'] ?? '');

// accent color for classic/cards
$accent = '#1a1a2e';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($catalog['name']); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($catalog['description'] ?? ''); ?>">
  <style>
  *{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;-webkit-font-smoothing:antialiased}
  img{max-width:100%;display:block}
  a{color:inherit;text-decoration:none}

  /* ── Global ── */
  .search-wrap{position:relative}
  .search-wrap input{
    width:100%;padding:10px 14px 10px 38px;
    border:1px solid rgba(0,0,0,.08);border-radius:50px;
    font-size:14px;outline:none;transition:.2s;
  }
  .search-wrap .ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);opacity:.4;font-size:16px}

  .filter-tabs{
    display:flex;gap:8px;overflow-x:auto;
    padding:12px 0;scrollbar-width:none;-webkit-overflow-scrolling:touch;
  }
  .filter-tabs::-webkit-scrollbar{display:none}
  .filter-tab{
    flex-shrink:0;padding:6px 16px;border-radius:50px;
    font-size:13px;font-weight:600;cursor:pointer;
    border:2px solid transparent;transition:.2s;
  }

  .wa-fab{
    position:fixed;bottom:20px;right:20px;z-index:100;
    background:#25D366;color:#fff;border:none;border-radius:50px;
    padding:13px 20px 13px 16px;font-size:14px;font-weight:700;
    box-shadow:0 6px 24px rgba(0,0,0,.25);cursor:pointer;
    display:flex;align-items:center;gap:9px;text-decoration:none;transition:.2s;
  }
  .wa-fab:hover{background:#1ebe5c;color:#fff;transform:translateY(-2px)}
  .wa-fab svg{width:20px;height:20px;flex-shrink:0}

  .powered{text-align:center;padding:32px 0 20px;font-size:12px;opacity:.4}
  .item-hidden{display:none!important}

  <?php if ($tpl === 'classic'): ?>
  /* ════════════════════════════════════════
     CLASSIC — clean professional
  ════════════════════════════════════════ */
  body{background:#f7f8fc}

  .hero{
    background:<?php echo $accent; ?>;
    padding:48px 20px 36px;text-align:center;
    position:relative;overflow:hidden;
  }
  .hero::after{
    content:'';position:absolute;bottom:-1px;left:0;right:0;height:24px;
    background:#f7f8fc;clip-path:ellipse(55% 100% at 50% 100%);
  }
  .hero h1{font-size:clamp(22px,6vw,32px);font-weight:900;color:#fff;letter-spacing:-.3px}
  .hero p{color:rgba(255,255,255,.75);font-size:15px;margin-top:8px}

  .top-bar{background:#fff;position:sticky;top:0;z-index:50;box-shadow:0 2px 12px rgba(0,0,0,.07)}
  .top-bar-inner{padding:12px 16px}
  .search-wrap input{background:#f4f5f7;border-color:transparent}
  .search-wrap input:focus{border-color:<?php echo $accent; ?>;background:#fff}

  .filter-tab{background:#f4f5f7;color:#555}
  .filter-tab:hover{background:#e8e8e8}
  .filter-tab.active{background:<?php echo $accent; ?>;color:#fff}

  .cat-section{margin-bottom:20px}
  .cat-heading{
    font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;
    color:<?php echo $accent; ?>;padding:6px 0 10px;
    display:flex;align-items:center;gap:10px;margin-top:24px;
  }
  .cat-heading::after{content:'';flex:1;height:2px;background:<?php echo $accent; ?>;opacity:.12;border-radius:2px}

  .product-grid{display:flex;flex-direction:column;gap:10px}
  .product-card{
    background:#fff;border-radius:16px;overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,.06);
    display:flex;align-items:stretch;transition:.2s;
  }
  .product-card:hover{box-shadow:0 6px 20px rgba(0,0,0,.1);transform:translateY(-1px)}
  .p-img{width:100px;height:100px;object-fit:cover;flex-shrink:0;background:#f4f5f7}
  .p-img-placeholder{
    width:100px;height:100px;flex-shrink:0;
    background:linear-gradient(135deg,<?php echo $accent; ?>18,<?php echo $accent; ?>08);
    display:flex;align-items:center;justify-content:center;font-size:28px;
  }
  .p-body{padding:14px 16px;flex:1;display:flex;flex-direction:column;justify-content:center}
  .p-name{font-size:14px;font-weight:700;color:#111;line-height:1.3;margin-bottom:4px}
  .p-desc{font-size:12px;color:#777;line-height:1.5}
  .p-sku{font-size:11px;color:#bbb;font-family:monospace;margin-top:3px}
  .p-footer{display:flex;align-items:center;justify-content:space-between;margin-top:8px}
  .p-price{font-size:16px;font-weight:800;color:<?php echo $accent; ?>}

  .wrap{max-width:720px;margin:0 auto;padding:0 16px 100px}

  <?php elseif ($tpl === 'dark'): ?>
  /* ════════════════════════════════════════
     DARK — premium night mode
  ════════════════════════════════════════ */
  body{background:#0a0a14;color:#e0e0e0}

  .hero{
    background:linear-gradient(160deg,#0d0d1a 0%,#1a1a3a 50%,#0d0d1a 100%);
    padding:56px 20px 44px;text-align:center;
    border-bottom:1px solid rgba(255,255,255,.06);
  }
  .hero h1{font-size:clamp(24px,7vw,36px);font-weight:900;color:#fff;letter-spacing:-.4px}
  .hero p{color:rgba(255,255,255,.55);font-size:15px;margin-top:10px}

  .top-bar{
    background:rgba(10,10,20,.96);backdrop-filter:blur(20px);
    position:sticky;top:0;z-index:50;
    border-bottom:1px solid rgba(255,255,255,.07);
  }
  .top-bar-inner{padding:12px 16px}
  .search-wrap input{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.1);color:#e0e0e0}
  .search-wrap input:focus{border-color:#7c3aed}
  .search-wrap input::placeholder{color:rgba(255,255,255,.3)}

  .filter-tab{background:rgba(255,255,255,.06);color:#aaa;border-color:rgba(255,255,255,.08)}
  .filter-tab:hover{background:rgba(255,255,255,.1);color:#fff}
  .filter-tab.active{background:#7c3aed;color:#fff;border-color:#7c3aed}

  .cat-section{margin-bottom:24px}
  .cat-heading{
    font-size:11px;font-weight:800;letter-spacing:2.5px;text-transform:uppercase;
    color:#7c3aed;padding:6px 0 12px;margin-top:28px;
    display:flex;align-items:center;gap:12px;
  }
  .cat-heading::before,.cat-heading::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.07)}

  .product-grid{display:flex;flex-direction:column;gap:10px}
  .product-card{
    background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);
    border-radius:16px;overflow:hidden;display:flex;align-items:stretch;transition:.2s;
  }
  .product-card:hover{background:rgba(255,255,255,.07);border-color:#7c3aed55;transform:translateX(3px)}
  .p-img{width:100px;height:100px;object-fit:cover;flex-shrink:0;background:#1a1a2e}
  .p-img-placeholder{
    width:100px;height:100px;flex-shrink:0;
    background:rgba(124,58,237,.08);display:flex;align-items:center;justify-content:center;font-size:26px;
  }
  .p-body{padding:14px 16px;flex:1;display:flex;flex-direction:column;justify-content:center}
  .p-name{font-size:14px;font-weight:700;color:#f0f0f0;line-height:1.3;margin-bottom:4px}
  .p-desc{font-size:12px;color:#666;line-height:1.5}
  .p-sku{font-size:11px;color:#444;font-family:monospace;margin-top:3px}
  .p-footer{display:flex;align-items:center;justify-content:space-between;margin-top:8px}
  .p-price{font-size:16px;font-weight:800;color:#a78bfa}

  .wrap{max-width:720px;margin:0 auto;padding:0 16px 100px}

  <?php else: /* cards */ ?>
  /* ════════════════════════════════════════
     CARDS — ecommerce grid, aceroplaza-style
  ════════════════════════════════════════ */
  body{background:#f3f4f8}

  .hero{
    background:#fff;border-bottom:4px solid <?php echo $accent; ?>;
    padding:36px 20px 28px;text-align:center;
  }
  .hero h1{font-size:clamp(20px,5vw,28px);font-weight:900;color:#111;letter-spacing:-.3px}
  .hero p{color:#777;font-size:14px;margin-top:6px}

  .top-bar{background:#fff;position:sticky;top:0;z-index:50;box-shadow:0 2px 10px rgba(0,0,0,.06)}
  .top-bar-inner{padding:12px 16px}
  .search-wrap input{background:#f4f5f7;border-color:transparent}
  .search-wrap input:focus{border-color:<?php echo $accent; ?>;background:#fff}

  .filter-tab{background:#f4f5f7;color:#555}
  .filter-tab:hover{background:#e8e8e8}
  .filter-tab.active{background:<?php echo $accent; ?>;color:#fff}

  .cat-section{margin-bottom:8px}
  .cat-heading{
    display:inline-flex;align-items:center;
    background:<?php echo $accent; ?>;color:#fff;
    font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;
    padding:5px 16px;border-radius:30px;margin:24px 0 14px;
  }

  .product-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(155px,1fr));
    gap:14px;
  }
  .product-card{
    background:#fff;border-radius:18px;overflow:hidden;
    box-shadow:0 2px 12px rgba(0,0,0,.07);
    display:flex;flex-direction:column;transition:.2s;cursor:default;
  }
  .product-card:hover{box-shadow:0 8px 28px rgba(0,0,0,.12);transform:translateY(-3px)}
  .p-img{width:100%;height:150px;object-fit:cover;background:#f4f5f7}
  .p-img-placeholder{
    width:100%;height:150px;
    background:linear-gradient(135deg,<?php echo $accent; ?>18,<?php echo $accent; ?>08);
    display:flex;align-items:center;justify-content:center;font-size:36px;
  }
  .p-body{padding:12px 14px;flex:1;display:flex;flex-direction:column}
  .p-name{font-size:14px;font-weight:700;color:#111;line-height:1.3;margin-bottom:4px}
  .p-desc{font-size:12px;color:#999;line-height:1.4;flex:1}
  .p-sku{font-size:10px;color:#bbb;font-family:monospace;margin-top:3px}
  .p-footer{margin-top:10px;display:flex;align-items:center;justify-content:space-between;gap:6px}
  .p-price{font-size:16px;font-weight:800;color:<?php echo $accent; ?>}

  .wrap{max-width:800px;margin:0 auto;padding:0 16px 100px}
  <?php endif; ?>
  </style>
</head>
<body>

<div class="hero">
  <h1><?php echo htmlspecialchars($catalog['name']); ?></h1>
  <?php if (!empty($catalog['description'])): ?>
  <p><?php echo htmlspecialchars($catalog['description']); ?></p>
  <?php endif; ?>
</div>

<!-- Sticky top bar -->
<div class="top-bar">
  <div class="top-bar-inner">
    <div class="search-wrap" style="margin-bottom:10px">
      <span class="ico">🔍</span>
      <input type="search" id="searchInput" placeholder="Buscar producto…"
             oninput="filterItems(this.value)">
    </div>
    <?php if (!empty($categories)): ?>
    <div class="filter-tabs">
      <button class="filter-tab active" onclick="filterCat(0, this)">Todos</button>
      <?php foreach ($categories as $cat): ?>
      <button class="filter-tab" onclick="filterCat(<?php echo (int)$cat['id']; ?>, this)">
        <?php echo htmlspecialchars($cat['name']); ?>
      </button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<div class="wrap">

<?php if (empty($items)): ?>
  <div style="text-align:center;padding:72px 0;opacity:.4">
    <div style="font-size:56px;margin-bottom:12px">📦</div>
    <p style="font-size:15px">Este catálogo aún no tiene productos. ¡Vuelve pronto!</p>
  </div>
<?php else: ?>

  <?php
  $isCards = ($tpl === 'cards');

  // Render a single product card
  function renderCard($item, $tpl, $waOn, $phone, $catName) {
    $hasImg  = !empty($item['image_url']);
    $ico     = $tpl === 'dark' ? '📦' : '🛍️';
    ?>
    <div class="product-card"
         data-cat="<?php echo $item['category_id'] ? (int)$item['category_id'] : 0; ?>"
         data-name="<?php echo strtolower(htmlspecialchars($item['name'] . ' ' . ($item['description'] ?? '') . ' ' . ($item['sku'] ?? ''))); ?>">
      <?php if ($hasImg): ?>
        <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
             alt="<?php echo htmlspecialchars($item['name']); ?>"
             class="p-img" loading="lazy">
      <?php else: ?>
        <div class="p-img-placeholder"><?php echo $ico; ?></div>
      <?php endif; ?>
      <div class="p-body">
        <div class="p-name"><?php echo htmlspecialchars($item['name']); ?></div>
        <?php if (!empty($item['description'])): ?>
        <div class="p-desc"><?php echo htmlspecialchars($item['description']); ?></div>
        <?php endif; ?>
        <?php if (!empty($item['sku'])): ?>
        <div class="p-sku">SKU: <?php echo htmlspecialchars($item['sku']); ?></div>
        <?php endif; ?>
        <div class="p-footer">
          <?php if ($item['price'] !== null): ?>
          <div class="p-price">$<?php echo number_format((float)$item['price'], 2); ?></div>
          <?php else: ?>
          <div></div>
          <?php endif; ?>
          <?php if ($waOn && $phone): ?>
          <?php
          $msg = urlencode('Hola, me interesa: ' . $item['name'] . ($item['sku'] ? ' (SKU: ' . $item['sku'] . ')' : ''));
          $wUrl = 'https://wa.me/' . $phone . '?text=' . $msg;
          ?>
          <a href="<?php echo $wUrl; ?>" target="_blank" rel="noopener"
             style="font-size:11px;font-weight:700;padding:5px 12px;background:#25D366;color:#fff;border-radius:30px;flex-shrink:0">
            Consultar 💬
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php
  }

  if (!empty($categories)):
    foreach ($categories as $cat):
      $catItems = $itemsByCategory[(int)$cat['id']] ?? [];
      if (empty($catItems)) continue;
  ?>
  <div class="cat-section" data-cat="<?php echo (int)$cat['id']; ?>">
    <div class="cat-heading"><?php echo htmlspecialchars($cat['name']); ?></div>
    <div class="product-grid">
      <?php foreach ($catItems as $item): renderCard($item, $tpl, $waOn, $phone, $cat['name']); endforeach; ?>
    </div>
  </div>
  <?php
    endforeach;

    // Uncategorized
    $uncatItems = $itemsByCategory[0] ?? [];
    if (!empty($uncatItems)):
  ?>
  <div class="cat-section" data-cat="0">
    <div class="cat-heading">Sin categoría</div>
    <div class="product-grid">
      <?php foreach ($uncatItems as $item): renderCard($item, $tpl, $waOn, $phone, ''); endforeach; ?>
    </div>
  </div>
  <?php
    endif;
  else: // no categories
  ?>
  <div class="product-grid" style="margin-top:20px">
    <?php foreach ($items as $item): renderCard($item, $tpl, $waOn, $phone, ''); endforeach; ?>
  </div>
  <?php endif; ?>

<?php endif; ?>

  <div class="powered">Catálogo digital creado con <a href="https://qlynk.mx" target="_blank">QLynk</a></div>
</div>

<!-- WhatsApp FAB -->
<?php if ($waOn && $phone): ?>
<?php
$waMsg = urlencode('¡Hola! Vi el catálogo "' . $catalog['name'] . '" y me gustaría obtener más información.');
$waUrl = 'https://wa.me/' . $phone . '?text=' . $waMsg;
?>
<a href="<?php echo $waUrl; ?>" class="wa-fab" target="_blank" rel="noopener">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  Consultar por WhatsApp
</a>
<?php endif; ?>

<script>
/* Category filter */
function filterCat(catId, el) {
    document.querySelectorAll('.filter-tab').forEach(function(t) { t.classList.remove('active'); });
    el.classList.add('active');
    document.querySelectorAll('.cat-section').forEach(function(sec) {
        sec.style.display = (catId === 0 || parseInt(sec.dataset.cat) === catId) ? '' : 'none';
    });
    // Also filter standalone grid items (no-category case)
    document.querySelectorAll('.product-card[data-cat]').forEach(function(card) {
        if (card.closest('.cat-section')) return; // handled above
        card.classList.toggle('item-hidden', catId !== 0 && parseInt(card.dataset.cat) !== catId);
    });
}

/* Text search filter */
function filterItems(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('.product-card').forEach(function(card) {
        var name = card.dataset.name || '';
        card.classList.toggle('item-hidden', q.length > 0 && name.indexOf(q) === -1);
    });
}
</script>
</body>
</html>
