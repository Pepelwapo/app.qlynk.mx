<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($catalog['name']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <style>
    body { background:#f5f5f8;font-family:'Segoe UI',system-ui,sans-serif }
    .cat-header { background:#1a1a2e;color:#fff;padding:40px 20px;text-align:center }
    .cat-header h1 { font-size:26px;font-weight:700;margin:0 0 8px }
    .cat-header p  { opacity:.8;margin:0;font-size:14px }
    .category-title { font-size:13px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#1a1a2e;border-bottom:2px solid #1a1a2e;padding-bottom:6px;margin:28px 0 14px }
    .product-card { background:#fff;border-radius:12px;padding:14px 16px;margin-bottom:8px;box-shadow:0 1px 4px rgba(0,0,0,.07) }
    .product-top  { display:flex;align-items:flex-start;gap:12px }
    .product-name { font-weight:600;font-size:14px }
    .product-desc { font-size:12px;color:#777;margin-top:2px }
    .product-sku  { font-size:11px;color:#bbb;margin-top:2px;font-family:monospace }
    .product-price { font-weight:700;font-size:15px;color:#1a1a2e;white-space:nowrap;margin-left:auto;padding-left:12px }
    .filter-tabs { display:flex;gap:8px;overflow-x:auto;padding:16px 0 4px;scrollbar-width:none }
    .filter-tabs::-webkit-scrollbar { display:none }
    .filter-tab { background:#fff;border:1px solid #ddd;border-radius:20px;padding:4px 14px;font-size:13px;cursor:pointer;white-space:nowrap;color:#555 }
    .filter-tab.active { background:#1a1a2e;color:#fff;border-color:#1a1a2e }
    .powered { text-align:center;padding:32px 0 20px;font-size:12px;color:#bbb }
    .powered a { color:#bbb;text-decoration:none }
  </style>
</head>
<body>
  <div class="cat-header">
    <h1><?= htmlspecialchars($catalog['name']) ?></h1>
    <?php if ($catalog['description']): ?>
    <p><?= htmlspecialchars($catalog['description']) ?></p>
    <?php endif; ?>
  </div>

  <div class="container" style="max-width:720px;padding:0 16px 40px">

    <?php if (!empty($categories)): ?>
    <div class="filter-tabs" id="filterTabs">
      <div class="filter-tab active" onclick="filterCat(0, this)" data-cat="0">Todos</div>
      <?php foreach ($categories as $cat): ?>
      <div class="filter-tab" onclick="filterCat(<?= $cat['id'] ?>, this)" data-cat="<?= $cat['id'] ?>">
        <?= htmlspecialchars($cat['name']) ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
    <div class="text-center py-5 text-muted">
      <div style="font-size:40px;margin-bottom:12px">📦</div>
      <p>Este catálogo aún no tiene productos.</p>
    </div>
    <?php else: ?>

      <?php if (!empty($categories)): ?>
        <!-- By category -->
        <?php foreach ($categories as $cat): ?>
        <?php $catItems = $itemsByCategory[$cat['id']] ?? []; ?>
        <?php if (!empty($catItems)): ?>
        <div class="cat-section" data-cat="<?= $cat['id'] ?>">
          <div class="category-title"><?= htmlspecialchars($cat['name']) ?></div>
          <?php foreach ($catItems as $item): ?>
          <div class="product-card">
            <div class="product-top">
              <div class="flex-grow-1">
                <div class="product-name"><?= htmlspecialchars($item['name']) ?></div>
                <?php if ($item['description']): ?>
                <div class="product-desc"><?= htmlspecialchars($item['description']) ?></div>
                <?php endif; ?>
                <?php if ($item['sku']): ?>
                <div class="product-sku">SKU: <?= htmlspecialchars($item['sku']) ?></div>
                <?php endif; ?>
              </div>
              <?php if ($item['price'] !== null): ?>
              <div class="product-price">$<?= number_format((float)$item['price'], 2) ?></div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>

        <!-- Uncategorized -->
        <?php $uncatItems = $itemsByCategory[0] ?? []; ?>
        <?php if (!empty($uncatItems)): ?>
        <div class="cat-section" data-cat="0">
          <div class="category-title">Sin categoría</div>
          <?php foreach ($uncatItems as $item): ?>
          <div class="product-card">
            <div class="product-top">
              <div class="flex-grow-1">
                <div class="product-name"><?= htmlspecialchars($item['name']) ?></div>
                <?php if ($item['description']): ?>
                <div class="product-desc"><?= htmlspecialchars($item['description']) ?></div>
                <?php endif; ?>
                <?php if ($item['sku']): ?>
                <div class="product-sku">SKU: <?= htmlspecialchars($item['sku']) ?></div>
                <?php endif; ?>
              </div>
              <?php if ($item['price'] !== null): ?>
              <div class="product-price">$<?= number_format((float)$item['price'], 2) ?></div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      <?php else: ?>
        <!-- All items no categories -->
        <?php foreach ($items as $item): ?>
        <div class="product-card mt-3">
          <div class="product-top">
            <div class="flex-grow-1">
              <div class="product-name"><?= htmlspecialchars($item['name']) ?></div>
              <?php if ($item['description']): ?>
              <div class="product-desc"><?= htmlspecialchars($item['description']) ?></div>
              <?php endif; ?>
              <?php if ($item['sku']): ?>
              <div class="product-sku">SKU: <?= htmlspecialchars($item['sku']) ?></div>
              <?php endif; ?>
            </div>
            <?php if ($item['price'] !== null): ?>
            <div class="product-price">$<?= number_format((float)$item['price'], 2) ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>

    <?php endif; ?>
  </div>

  <div class="powered">Catálogo digital creado con <a href="https://qlynk.mx" target="_blank">QLynk</a></div>

<script>
function filterCat(catId, el) {
  document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.cat-section').forEach(sec => {
    sec.style.display = (catId === 0 || sec.dataset.cat == catId) ? '' : 'none';
  });
}
</script>
</body>
</html>
