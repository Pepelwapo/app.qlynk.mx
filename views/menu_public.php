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
  <meta name="description" content="<?php echo htmlspecialchars($menu['description'] ?? ''); ?>">
  <style>
  *{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;-webkit-font-smoothing:antialiased}
  img{max-width:100%;display:block}
  a{color:inherit;text-decoration:none}

  /* ────────────────────────────────────────────────
     GLOBAL COMPONENTS
  ──────────────────────────────────────────────── */
  .search-wrap{position:relative;margin:0 16px 0}
  .search-wrap input{
    width:100%;padding:10px 14px 10px 38px;
    border:1px solid rgba(0,0,0,.08);border-radius:50px;
    font-size:14px;outline:none;transition:.2s;
  }
  .search-wrap .ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);opacity:.4;font-size:16px}

  .section-nav{
    display:flex;gap:8px;overflow-x:auto;padding:12px 16px;
    scrollbar-width:none;-webkit-overflow-scrolling:touch;
    border-bottom:1px solid rgba(0,0,0,.06);
  }
  .section-nav::-webkit-scrollbar{display:none}
  .sec-pill{
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
  .wa-fab:hover{background:#1ebe5c;color:#fff;transform:translateY(-2px);box-shadow:0 10px 32px rgba(0,0,0,.3)}
  .wa-fab svg{width:20px;height:20px;flex-shrink:0}

  .powered{text-align:center;padding:32px 0 20px;font-size:12px;opacity:.4}

  /* Search highlight */
  .item-hidden{display:none!important}

  <?php if ($tpl === 'classic'): ?>
  /* ════════════════════════════════════════════
     CLASSIC — bright, card-style, Burger King vibe
  ════════════════════════════════════════════ */
  body{background:#f7f7f7}

  .hero{
    background:<?php echo $color; ?>;
    padding:44px 20px 36px;text-align:center;
    position:relative;overflow:hidden;
  }
  .hero::before{
    content:'';position:absolute;inset:0;
    background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .hero h1{font-size:clamp(22px,6vw,32px);font-weight:900;color:#fff;letter-spacing:-.5px;position:relative}
  .hero p{color:rgba(255,255,255,.85);font-size:15px;margin-top:8px;position:relative}

  .top-bar{
    background:#fff;position:sticky;top:0;z-index:50;
    box-shadow:0 2px 12px rgba(0,0,0,.07);
  }
  .search-wrap input{background:#f4f5f7;border-color:transparent}
  .search-wrap input:focus{border-color:<?php echo $color; ?>;background:#fff}
  .top-bar-inner{padding:10px 0 0}

  .sec-pill{background:#f4f5f7;color:#555}
  .sec-pill:hover{background:#e8e8e8}
  .sec-pill.active{background:<?php echo $color; ?>;color:#fff;border-color:<?php echo $color; ?>}

  .section-anchor{scroll-margin-top:110px}
  .sec-heading{
    font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;
    color:<?php echo $color; ?>;padding:8px 0 10px;
    display:flex;align-items:center;gap:10px;
  }
  .sec-heading::after{content:'';flex:1;height:2px;background:<?php echo $color; ?>;opacity:.15;border-radius:2px}

  .items-list{display:flex;flex-direction:column;gap:10px;margin-bottom:8px}
  .item-card{
    background:#fff;border-radius:16px;overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,.06);
    display:flex;align-items:stretch;
    transition:.2s;
  }
  .item-card:hover{box-shadow:0 6px 20px rgba(0,0,0,.1);transform:translateY(-1px)}
  .item-img{
    width:110px;min-height:110px;object-fit:cover;flex-shrink:0;
    background:#f4f5f7;
  }
  .item-img-placeholder{
    width:110px;min-height:110px;flex-shrink:0;
    background:linear-gradient(135deg,<?php echo $color; ?>22,<?php echo $color; ?>11);
    display:flex;align-items:center;justify-content:center;
    font-size:32px;
  }
  .item-body{padding:14px 16px;flex:1;display:flex;flex-direction:column;justify-content:center}
  .item-name{font-size:15px;font-weight:700;color:#111;line-height:1.3;margin-bottom:4px}
  .item-desc{font-size:13px;color:#777;line-height:1.5}
  .item-footer{display:flex;align-items:center;justify-content:space-between;margin-top:10px}
  .item-price{font-size:17px;font-weight:800;color:<?php echo $color; ?>}

  .wrap{max-width:680px;margin:0 auto;padding:20px 16px 100px}

  <?php elseif ($tpl === 'dark'): ?>
  /* ════════════════════════════════════════════
     DARK — cinematic, night-time elegance
  ════════════════════════════════════════════ */
  body{background:#0a0a12;color:#e8e8e8}

  .hero{
    background:linear-gradient(160deg,#1a1a2e 0%,<?php echo $color; ?>66 50%,#0a0a12 100%);
    padding:56px 20px 44px;text-align:center;position:relative;overflow:hidden;
  }
  .hero::before{
    content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse 70% 50% at 50% 0%,<?php echo $color; ?>33,transparent);
  }
  .hero h1{font-size:clamp(24px,7vw,36px);font-weight:900;color:#fff;letter-spacing:-.5px;position:relative}
  .hero p{color:rgba(255,255,255,.6);font-size:15px;margin-top:10px;position:relative}

  .top-bar{
    background:rgba(10,10,18,.95);backdrop-filter:blur(20px);
    position:sticky;top:0;z-index:50;
    border-bottom:1px solid rgba(255,255,255,.07);
  }
  .search-wrap input{
    background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.1);color:#e8e8e8;
  }
  .search-wrap input:focus{border-color:<?php echo $color; ?>}
  .search-wrap input::placeholder{color:rgba(255,255,255,.3)}
  .top-bar-inner{padding:10px 0 0}

  .sec-pill{background:rgba(255,255,255,.06);color:#aaa;border-color:rgba(255,255,255,.08)}
  .sec-pill:hover{background:rgba(255,255,255,.1);color:#fff}
  .sec-pill.active{background:<?php echo $color; ?>;color:#fff;border-color:<?php echo $color; ?>}

  .section-anchor{scroll-margin-top:110px}
  .sec-heading{
    font-size:11px;font-weight:800;letter-spacing:2.5px;text-transform:uppercase;
    color:<?php echo $color; ?>;padding:8px 0 12px;
    display:flex;align-items:center;gap:12px;
  }
  .sec-heading::before,.sec-heading::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.07)}

  .items-list{display:flex;flex-direction:column;gap:10px;margin-bottom:8px}
  .item-card{
    background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);
    border-radius:16px;overflow:hidden;display:flex;align-items:stretch;transition:.2s;
  }
  .item-card:hover{background:rgba(255,255,255,.07);border-color:<?php echo $color; ?>55;transform:translateX(3px)}
  .item-img{width:110px;min-height:110px;object-fit:cover;flex-shrink:0;background:#1a1a2e}
  .item-img-placeholder{
    width:110px;min-height:110px;flex-shrink:0;
    background:rgba(255,255,255,.03);display:flex;align-items:center;justify-content:center;font-size:30px;
  }
  .item-body{padding:14px 16px;flex:1;display:flex;flex-direction:column;justify-content:center}
  .item-name{font-size:15px;font-weight:700;color:#f0f0f0;line-height:1.3;margin-bottom:4px}
  .item-desc{font-size:13px;color:#777;line-height:1.5}
  .item-footer{display:flex;align-items:center;justify-content:space-between;margin-top:10px}
  .item-price{font-size:17px;font-weight:800;color:<?php echo $color; ?>}

  .wrap{max-width:680px;margin:0 auto;padding:20px 16px 100px}

  <?php else: /* cards */ ?>
  /* ════════════════════════════════════════════
     CARDS — Instagram-style grid
  ════════════════════════════════════════════ */
  body{background:#f2f4f8}

  .hero{
    background:#fff;border-bottom:4px solid <?php echo $color; ?>;
    padding:32px 20px 24px;text-align:center;
  }
  .hero h1{font-size:clamp(20px,5vw,28px);font-weight:900;color:#111;letter-spacing:-.3px}
  .hero p{color:#777;font-size:14px;margin-top:6px}

  .top-bar{background:#fff;position:sticky;top:0;z-index:50;box-shadow:0 2px 10px rgba(0,0,0,.06)}
  .search-wrap input{background:#f4f5f7;border-color:transparent}
  .search-wrap input:focus{border-color:<?php echo $color; ?>;background:#fff}
  .top-bar-inner{padding:10px 0 0}

  .sec-pill{background:#f4f5f7;color:#555}
  .sec-pill:hover{background:#e8e8e8}
  .sec-pill.active{background:<?php echo $color; ?>;color:#fff}

  .section-anchor{scroll-margin-top:120px}
  .sec-heading{
    display:inline-flex;align-items:center;gap:8px;
    background:<?php echo $color; ?>;color:#fff;
    font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;
    padding:5px 16px;border-radius:30px;margin:24px 0 14px;
  }

  .items-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(160px,1fr));
    gap:14px;margin-bottom:16px;
  }
  .item-card{
    background:#fff;border-radius:18px;overflow:hidden;
    box-shadow:0 2px 12px rgba(0,0,0,.07);
    display:flex;flex-direction:column;
    transition:.2s;cursor:default;
  }
  .item-card:hover{box-shadow:0 8px 28px rgba(0,0,0,.12);transform:translateY(-3px)}
  .item-img{width:100%;height:140px;object-fit:cover;background:#f4f5f7}
  .item-img-placeholder{
    width:100%;height:140px;background:linear-gradient(135deg,<?php echo $color; ?>22,<?php echo $color; ?>08);
    display:flex;align-items:center;justify-content:center;font-size:36px;
  }
  .item-body{padding:12px 14px;flex:1;display:flex;flex-direction:column}
  .item-name{font-size:14px;font-weight:700;color:#111;line-height:1.3;margin-bottom:4px}
  .item-desc{font-size:12px;color:#999;line-height:1.4;flex:1}
  .item-footer{margin-top:10px}
  .item-price{font-size:16px;font-weight:800;color:<?php echo $color; ?>}

  .wrap{max-width:780px;margin:0 auto;padding:20px 16px 100px}
  <?php endif; ?>
  </style>
</head>
<body>

<!-- Hero / Header -->
<div class="hero">
  <h1><?php echo htmlspecialchars($menu['name']); ?></h1>
  <?php if (!empty($menu['description'])): ?>
  <p><?php echo htmlspecialchars($menu['description']); ?></p>
  <?php endif; ?>
</div>

<!-- Sticky top bar: search + section nav -->
<div class="top-bar">
  <div class="top-bar-inner">
    <div class="search-wrap" style="padding:0 16px 10px">
      <span class="ico">🔍</span>
      <input type="search" id="searchInput" placeholder="Buscar platillo…"
             oninput="filterItems(this.value)">
    </div>
    <?php if (!empty($sections)): ?>
    <nav class="section-nav" id="secNav">
      <?php foreach ($sections as $i => $sec): ?>
        <?php if (empty($sec['items'])) continue; ?>
        <button class="sec-pill <?php echo $i === 0 ? 'active' : ''; ?>"
                onclick="scrollToSection('sec-<?php echo (int)$sec['id']; ?>')">
          <?php echo htmlspecialchars($sec['name']); ?>
        </button>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>
  </div>
</div>

<!-- Main content -->
<div class="wrap">

<?php if (empty($sections)): ?>
  <div style="text-align:center;padding:72px 0;opacity:.4">
    <div style="font-size:56px;margin-bottom:12px">🍽️</div>
    <p style="font-size:15px">Este menú aún no tiene platillos. ¡Vuelve pronto!</p>
  </div>
<?php else: ?>

  <?php foreach ($sections as $i => $sec):
    if (empty($sec['items'])) continue;
    $isCards = ($tpl === 'cards');
  ?>

  <div class="section-anchor" id="sec-<?php echo (int)$sec['id']; ?>">
    <div class="sec-heading"><?php echo htmlspecialchars($sec['name']); ?></div>

    <?php if ($isCards): ?>
    <div class="items-grid">
    <?php else: ?>
    <div class="items-list">
    <?php endif; ?>

    <?php foreach ($sec['items'] as $item): ?>
    <div class="item-card" data-name="<?php echo strtolower(htmlspecialchars($item['name'] . ' ' . ($item['description'] ?? ''))); ?>">
      <?php if (!empty($item['image_url'])): ?>
        <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
             alt="<?php echo htmlspecialchars($item['name']); ?>"
             class="item-img" loading="lazy">
      <?php else: ?>
        <div class="item-img-placeholder">🍽️</div>
      <?php endif; ?>
      <div class="item-body">
        <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
        <?php if (!empty($item['description'])): ?>
        <div class="item-desc"><?php echo htmlspecialchars($item['description']); ?></div>
        <?php endif; ?>
        <div class="item-footer">
          <?php if ($item['price'] !== null): ?>
          <div class="item-price">$<?php echo number_format((float)$item['price'], 2); ?></div>
          <?php endif; ?>
          <?php if ($waOn && $phone): ?>
          <?php
          $orderMsg = urlencode('Hola, quiero ordenar: ' . $item['name'] . ' del menú "' . $menu['name'] . '"');
          $orderUrl = 'https://wa.me/' . $phone . '?text=' . $orderMsg;
          ?>
          <a href="<?php echo $orderUrl; ?>" target="_blank" rel="noopener"
             style="font-size:12px;font-weight:600;padding:5px 12px;background:#25D366;color:#fff;border-radius:30px">
            Pedir 💬
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    </div><!-- /items -->
  </div><!-- /section-anchor -->

  <?php endforeach; ?>

<?php endif; ?>

  <div class="powered">Menú digital creado con <a href="https://qlynk.mx" target="_blank">QLynk</a></div>
</div>

<!-- WhatsApp FAB -->
<?php if ($waOn && $phone): ?>
<?php
$waMsg = urlencode('¡Hola! Me gustaría hacer un pedido del menú "' . $menu['name'] . '".');
$waUrl = 'https://wa.me/' . $phone . '?text=' . $waMsg;
?>
<a href="<?php echo $waUrl; ?>" class="wa-fab" target="_blank" rel="noopener">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  Ordenar por WhatsApp
</a>
<?php endif; ?>

<script>
/* ── Search / filter ── */
function filterItems(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('.item-card').forEach(function(card) {
        var name = card.dataset.name || '';
        card.classList.toggle('item-hidden', q.length > 0 && name.indexOf(q) === -1);
    });
}

/* ── Scroll to section ── */
function scrollToSection(id) {
    var el = document.getElementById(id);
    if (el) el.scrollIntoView({behavior:'smooth'});
    document.querySelectorAll('.sec-pill').forEach(function(p) { p.classList.remove('active'); });
    event.currentTarget.classList.add('active');
}

/* ── Scroll spy ── */
(function() {
    var sections = document.querySelectorAll('.section-anchor');
    var pills    = document.querySelectorAll('.sec-pill');
    if (!sections.length) return;
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                var id = entry.target.id;
                pills.forEach(function(p) { p.classList.remove('active'); });
                var idx = Array.prototype.indexOf.call(sections, entry.target);
                if (pills[idx]) pills[idx].classList.add('active');
            }
        });
    }, {rootMargin:'-30% 0px -60% 0px'});
    sections.forEach(function(s) { observer.observe(s); });
})();
</script>
</body>
</html>
