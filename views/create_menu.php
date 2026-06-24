<?php
$pageTitle = 'Crear Menú';
require __DIR__ . '/partials/head.php';
?>
<div class="d-flex">
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="flex-grow-1 p-4" style="background:#f4f5f7;min-height:100vh">

  <div class="d-flex align-items-center mb-4">
    <a href="/menus" class="btn btn-sm btn-outline-secondary me-3" style="border-radius:8px"><i class="bi bi-arrow-left"></i></a>
    <h4 class="mb-0 fw-bold">Nuevo Menú Digital</h4>
  </div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger" style="border-radius:12px"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="card border-0 shadow-sm p-4" style="border-radius:16px;max-width:640px">
    <form method="POST">
      <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= csrf_token() ?>">

      <div class="mb-3">
        <label class="form-label fw-semibold">Nombre del menú <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" style="border-radius:10px"
               placeholder="Ej: Menú Restaurante Don Juan"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
               oninput="autoSlug(this.value)" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Slug (URL pública) <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text" style="background:#f0f0f5;border-right:0;font-size:13px">/m/</span>
          <input type="text" name="slug" id="slug" class="form-control" style="border-radius:0 10px 10px 0;border-left:0"
                 placeholder="mi-restaurante"
                 value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>" required>
        </div>
        <small class="text-muted">Solo letras minúsculas, números y guiones. Ej: <code>mi-restaurante</code></small>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Descripción</label>
        <textarea name="description" class="form-control" style="border-radius:10px" rows="2"
                  placeholder="Breve descripción del menú"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">Color de acento</label>
        <div class="d-flex align-items-center gap-3">
          <input type="color" name="color" id="colorPicker" class="form-control form-control-color"
                 value="<?= htmlspecialchars($_POST['color'] ?? '#1a1a2e') ?>" style="width:56px;height:40px;border-radius:8px;cursor:pointer">
          <span class="text-muted" style="font-size:13px">Aparece como barra decorativa en el menú público</span>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-dark fw-semibold" style="border-radius:10px;padding:10px 28px">
          <i class="bi bi-check-lg me-1"></i>Crear menú
        </button>
        <a href="/menus" class="btn btn-outline-secondary" style="border-radius:10px;padding:10px 20px">Cancelar</a>
      </div>
    </form>
  </div>

</main>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script>
function autoSlug(val) {
  const s = document.getElementById('slug');
  if (s.dataset.edited) return;
  s.value = val.toLowerCase()
    .normalize('NFD').replace(/[̀-ͯ]/g,'')
    .replace(/[^a-z0-9]+/g,'-')
    .replace(/^-+|-+$/g,'');
}
document.getElementById('slug').addEventListener('input', () => {
  document.getElementById('slug').dataset.edited = '1';
});
</script>
