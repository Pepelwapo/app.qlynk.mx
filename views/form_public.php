<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($form['name']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <style>
    body { background:#f4f5f7;font-family:'Segoe UI',system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center }
    .form-card { background:#fff;border-radius:20px;padding:36px 32px;max-width:520px;width:100%;box-shadow:0 4px 24px rgba(0,0,0,.08);margin:24px auto }
    .form-title { font-size:22px;font-weight:700;margin-bottom:6px }
    .form-desc  { color:#777;font-size:14px;margin-bottom:24px }
    .form-control, .form-select { border-radius:10px;font-size:14px }
    .btn-submit { background:#1a1a2e;color:#fff;border:0;border-radius:10px;padding:12px 28px;font-weight:600;width:100%;font-size:15px }
    .btn-submit:hover { background:#2d2d4e }
    .success-box { text-align:center;padding:32px 20px }
    .success-icon { font-size:52px;margin-bottom:16px }
    .success-msg { font-size:18px;font-weight:600;color:#1a1a2e }
    .powered { text-align:center;font-size:12px;color:#bbb;margin-top:20px }
    .powered a { color:#bbb;text-decoration:none }
  </style>
</head>
<body>
  <div style="width:100%;padding:16px">
    <div class="form-card">
      <?php if ($submitted): ?>
        <div class="success-box">
          <div class="success-icon">✅</div>
          <div class="success-msg"><?= htmlspecialchars($form['success_msg']) ?></div>
        </div>

      <?php else: ?>
        <div class="form-title"><?= htmlspecialchars($form['name']) ?></div>
        <?php if ($form['description']): ?>
        <div class="form-desc"><?= htmlspecialchars($form['description']) ?></div>
        <?php endif; ?>

        <?php if ($formError): ?>
        <div class="alert alert-danger" style="border-radius:10px;font-size:14px"><?= htmlspecialchars($formError) ?></div>
        <?php endif; ?>

        <form method="POST">
          <?php foreach ($fields as $fld): ?>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px">
              <?= htmlspecialchars($fld['label']) ?>
              <?php if ($fld['required']): ?><span class="text-danger"> *</span><?php endif; ?>
            </label>

            <?php
            $ph   = htmlspecialchars($fld['placeholder'] ?? '');
            $name = 'field_' . $fld['id'];
            $val  = htmlspecialchars($_POST[$name] ?? '');
            ?>

            <?php if ($fld['field_type'] === 'textarea'): ?>
              <textarea name="<?= $name ?>" class="form-control" rows="3"
                        placeholder="<?= $ph ?>"
                        <?= $fld['required'] ? 'required' : '' ?>><?= $val ?></textarea>

            <?php elseif ($fld['field_type'] === 'select'): ?>
              <?php $opts = json_decode($fld['options'] ?? '[]', true) ?? []; ?>
              <select name="<?= $name ?>" class="form-select" <?= $fld['required'] ? 'required' : '' ?>>
                <option value="">— Selecciona —</option>
                <?php foreach ($opts as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>" <?= ($_POST[$name] ?? '') === $opt ? 'selected' : '' ?>>
                  <?= htmlspecialchars($opt) ?>
                </option>
                <?php endforeach; ?>
              </select>

            <?php elseif ($fld['field_type'] === 'checkbox'): ?>
              <div class="form-check mt-1">
                <input type="checkbox" name="<?= $name ?>" id="fld<?= $fld['id'] ?>" class="form-check-input"
                       value="sí" <?= !empty($_POST[$name]) ? 'checked' : '' ?>>
                <label for="fld<?= $fld['id'] ?>" class="form-check-label" style="font-size:14px"><?= $ph ?: 'Sí' ?></label>
              </div>

            <?php else: ?>
              <?php
              $inputType = match($fld['field_type']) {
                  'email' => 'email',
                  'phone' => 'tel',
                  default => 'text',
              };
              ?>
              <input type="<?= $inputType ?>" name="<?= $name ?>" class="form-control"
                     placeholder="<?= $ph ?>" value="<?= $val ?>"
                     <?= $fld['required'] ? 'required' : '' ?>>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>

          <?php if (empty($fields)): ?>
          <div class="text-center text-muted py-3" style="font-size:14px">Este formulario aún no tiene campos configurados.</div>
          <?php else: ?>
          <button type="submit" class="btn btn-submit mt-2">Enviar</button>
          <?php endif; ?>
        </form>
      <?php endif; ?>

      <div class="powered">Formulario creado con <a href="https://qlynk.mx" target="_blank">QLynk</a></div>
    </div>
  </div>
</body>
</html>
