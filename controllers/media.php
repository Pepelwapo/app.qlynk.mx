<?php

require_login();

$uid   = (int)$_SESSION['user_id'];
$error = '';
$success = '';

// ── Subir imagen ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_action'])) {

    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página.';
    } else {

        $action = trim($_POST['_action']);

        if ($action === 'upload') {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
            $maxSize = 4 * 1024 * 1024; // 4 MB

            if (empty($_FILES['image']['name'])) {
                $error = 'Selecciona un archivo de imagen.';
            } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Error al subir el archivo (código ' . $_FILES['image']['error'] . ').';
            } elseif ($_FILES['image']['size'] > $maxSize) {
                $error = 'La imagen supera el límite de 4 MB.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $_FILES['image']['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mime, $allowed)) {
                    $error = 'Solo se permiten imágenes (JPEG, PNG, GIF, WebP, SVG).';
                } else {
                    // Verificar límite (máx 200 imágenes por usuario)
                    try {
                        $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM user_images WHERE user_id = ?");
                        $cntStmt->execute([$uid]);
                        $imgCount = (int)$cntStmt->fetchColumn();
                    } catch (Exception $e) {
                        $imgCount = 0;
                    }

                    if ($imgCount >= 200) {
                        $error = 'Has alcanzado el límite de 200 imágenes. Elimina algunas antes de subir más.';
                    } else {
                        $ext        = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                        $ext        = $ext ?: 'jpg';
                        $newName    = 'u' . $uid . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                        $uploadDir  = __DIR__ . '/../../uploads/images/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        $destPath   = $uploadDir . $newName;

                        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
                            $error = 'No se pudo guardar el archivo. Contacta soporte.';
                        } else {
                            $origName = htmlspecialchars(basename($_FILES['image']['name']));
                            $fileSize = $_FILES['image']['size'];
                            $imageUrl = APP_APP_URL . '/uploads/images/' . $newName;

                            try {
                                $pdo->prepare("
                                    INSERT INTO user_images (user_id, filename, original_name, mime_type, file_size, url, created_at)
                                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                                ")->execute([$uid, $newName, $origName, $mime, $fileSize, $imageUrl]);
                            } catch (Exception $e) {
                                // table may not exist yet — still works, file is saved
                            }
                            $success = 'Imagen subida correctamente.';
                        }
                    }
                }
            }

        } elseif ($action === 'delete') {
            $imgId = (int)($_POST['image_id'] ?? 0);
            if ($imgId) {
                try {
                    $stmt = $pdo->prepare("SELECT filename FROM user_images WHERE id = ? AND user_id = ? LIMIT 1");
                    $stmt->execute([$imgId, $uid]);
                    $img = $stmt->fetch();
                    if ($img) {
                        $filePath = __DIR__ . '/../../uploads/images/' . $img['filename'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $pdo->prepare("DELETE FROM user_images WHERE id = ? AND user_id = ?")->execute([$imgId, $uid]);
                        $success = 'Imagen eliminada.';
                    }
                } catch (Exception $e) {
                    $error = 'No se pudo eliminar la imagen.';
                }
            }
        }
    }
}

// ── Cargar imágenes del usuario ────────────────────────────────────
$images = [];
try {
    $stmt = $pdo->prepare("
        SELECT * FROM user_images
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$uid]);
    $images = $stmt->fetchAll();
} catch (Exception $e) {
    // tabla no existe aún
}

require __DIR__ . '/../views/media.php';
