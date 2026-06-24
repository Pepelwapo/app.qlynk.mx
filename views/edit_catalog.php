<?php
$pageTitle = 'Editar Catálogo';
require __DIR__ . '/partials/head.php';
?>
<style>
.item-row { display:flex;align-items:flex-start;gap:8px;padding:10px 16px;border-bottom:1px solid #f0f0f0 }
.item-row:last-child { border-bottom:0 }
</style>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center mb-4">
    <a href="/catalogs" class="btn btn-sm btn-outline-secondary me-3" style="border-radius:8px"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Editar Catálogo</h4>
    <a href="/c/<?= htmlspecialchars($catalog['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-3" style="border-radius:8px;font-size:12px">
      <i class="bi bi-eye me-1"></i>Ver público
    </a>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger" style="border-radius:12px"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
  <div class="alert alert-success" style="border-radius:12px"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($created): ?>
  <div class="alert alert-success" style="border-radius:12px">✅ ¡Catálogo creado! Ahora agrega categorías y productos.</div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- LEFT panel -->
    <div class="col-lg-4">

      <!-- Meta -->
      <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
        <h6 class="fw-bold mb-3">Información del catálogo</h6>
        <form method="POST">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
          <input type="hidden" name="_action" value="save_meta">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm" style="border-radius:8px"
                   value="<?= htmlspecialchars($catalog['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Slug</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text" style="background:#f0f0f5;border-right:0;font-size:12px">/c/</span>
              <input type="text" name="slug" class="form-control" style="border-radius:0 8px 8px 0;border-left:0"
                     value="<?= htmlspecialchars($catalog['slug']) ?>" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:13px">Descripción</label>
            <textarea name="description" class="form-control form-control-sm" style="border-radius:8px" rows="2"><?= htmlspecialchars($catalog['description'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn btn-dark btn-sm fw-semibold w-100" style="border-radius:8px">
            <i class="bi bi-save me-1"></i>Guardar cambios
          </button>
        </form>
      </div>

      <!-- Add category -->
      <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
        <h6 class="fw-bold mb-3">Agregar categoría</h6>
        <form method="POST">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
          <input type="hidden" name="_action" value="add_category">
          <div class="mb-3">
            <input type="text" name="category_name" class="form-control form-control-sm" style="border-radius:8px"
                   placeholder="Ej: Electrónica, Ropa, Zapatos..." required>
          </div>
          <button type="submit" class="btn btn-outline-dark btn-sm w-100" style="border-radius:8px">
            <i class="bi bi-plus-lg me-1"></i>Agregar categoría
          </button>
        </form>

        <?php if (!empty($categories)): ?>
        <hr class="my-3">
        <div class="d-flex flex-wrap gap-2">
          <?php foreach ($categories as $cat): ?>
          <div class="d-flex align-items-center gap-1 bg-light rounded px-2 py-1" style="font-size:13px">
            <span><?= htmlspecialchars($cat['name']) ?></span>
            <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar categoría?')">
              <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
              <input type="hidden" name="_action" value="del_category">
              <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
              <button type="submit" class="btn btn-link btn-sm p-0 text-danger" style="line-height:1;font-size:12px">
                <i class="bi bi-x-lg"></i>
              </button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

    </div>

    <!-- RIGHT: Products -->
    <div class="col-lg-8">
      <h6 class="fw-bold mb-3">Productos</h6>

      <!-- Add product form -->
      <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius:16px">
        <h6 class="fw-semibold mb-3" style="font-size:14px">Agregar producto</h6>
        <form method="POST">
          <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
          <input type="hidden" name="_action" value="add_item">
          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">NOMBRE *</label>
              <input type="text" name="item_name" class="form-control form-control-sm" style="border-radius:8px" placeholder="Nombre del producto" required>
            </div>
            <div class="col-md-3">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">PRECIO</label>
              <input type="number" name="item_price" class="form-control form-control-sm" style="border-radius:8px" placeholder="0.00" step="0.01" min="0">
            </div>
            <div class="col-md-3">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">SKU</label>
              <input type="text" name="item_sku" class="form-control form-control-sm" style="border-radius:8px" placeholder="ABC-001">
            </div>
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">DESCRIPCIÓN</label>
              <input type="text" name="item_desc" class="form-control form-control-sm" style="border-radius:8px" placeholder="Descripción del producto">
            </div>
            <div class="col-md-4">
              <label class="form-label" style="font-size:11px;font-weight:600;color:#888;margin-bottom:3px">CATEGORÍA</label>
              <select name="category_id" class="form-select form-select-sm" style="border-radius:8px">
                <option value="">Sin categoría</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-dark btn-sm w-100" style="border-radius:8px">
                <i class="bi bi-plus-lg"></i>
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Products list -->
      <?php if (empty($items)): ?>
      <div class="card border-0 shadow-sm text-center p-4" style="border-radius:16px">
        <p class="text-muted mb-0" style="font-size:14px">Agrega tu primer producto al catálogo.</p>
      </div>
      <?php else: ?>
      <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
        <div class="table-responsive">
        <table class="table mb-0" style="font-size:13px">
          <thead style="background:#f8f9fa">
            <tr style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#888">
              <th class="py-2 px-3">Producto</th>
              <th class="py-2 px-3">Categoría</th>
              <th class="py-2 px-3">SKU</th>
              <th class="py-2 px-3 text-end">Precio</th>
              <th class="py-2 px-3"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
            <tr style="vertical-align:middle;border-bottom:1px solid #f0f0f0">
              <td class="py-2 px-3">
                <span class="fw-semibold"><?= htmlspecialchars($item['name']) ?></span>
                <?php if ($item['description']): ?>
                <br><span class="text-muted" style="font-size:11px"><?= htmlspecialchars($item['description']) ?></span>
                <?php endif; ?>
              </td>
              <td class="py-2 px-3 text-muted"><?= $item['category_name'] ? htmlspecialchars($item['category_name']) : '—' ?></td>
              <td class="py-2 px-3"><code style="font-size:11px"><?= $item['sku'] ? htmlspecialchars($item['sku']) : '—' ?></code></td>
              <td class="py-2 px-3 text-end fw-semibold"><?= $item['price'] !== null ? '$' . number_format($item['price'], 2) : '—' ?></td>
              <td class="py-2 px-3 text-end">
                <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar producto?')">
                  <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">
                  <input type="hidden" name="_action" value="del_item">
                  <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                  <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius:6px;font-size:11px;padding:2px 8px">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        </div>
      </div>
      <?php endif; ?>
    </div>

  </div>
</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
