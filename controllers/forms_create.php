<?php

require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_validate()) {
        $error = 'Solicitud inválida. Recarga la página.';
    } else {
        $name = trim($_POST['name']        ?? '');
        $desc = trim($_POST['description'] ?? '');
        $slug = trim($_POST['slug']        ?? '');

        $validTpl = ['blank', 'contact', 'quote', 'survey'];
        $template = in_array(trim($_POST['template'] ?? ''), $validTpl) ? trim($_POST['template']) : 'blank';

        // Default success messages per template
        $successMessages = [
            'blank'   => '¡Gracias! Tu respuesta fue enviada.',
            'contact' => '¡Gracias por contactarnos! Te responderemos a la brevedad.',
            'quote'   => '¡Gracias! Recibirás tu cotización en breve.',
            'survey'  => '¡Gracias por tu opinión! Nos ayuda a mejorar.',
        ];
        $success_msg = $successMessages[$template] ?? '¡Gracias! Tu respuesta fue enviada.';

        if (empty($name)) {
            $error = 'El nombre del formulario es obligatorio.';
        } elseif (empty($slug)) {
            $error = 'El slug es obligatorio.';
        } elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
            $error = 'El slug solo puede tener letras minúsculas, números y guiones.';
        } else {
            $chk = $pdo->prepare("SELECT id FROM user_forms WHERE slug = ?");
            $chk->execute([$slug]);
            if ($chk->fetch()) {
                $error = 'Ese slug ya está en uso.';
            } else {
                $pdo->prepare("
                    INSERT INTO user_forms (user_id, name, description, slug, success_msg, active, created_at)
                    VALUES (?, ?, ?, ?, ?, 1, NOW())
                ")->execute([$_SESSION['user_id'], $name, $desc, $slug, $success_msg]);

                $newId = $pdo->lastInsertId();

                // ── Pre-populate fields based on template ──
                if ($template !== 'blank') {
                    $fields = [];
                    if ($template === 'contact') {
                        $fields = [
                            ['Nombre',    'text',     'Tu nombre completo',       null, 1],
                            ['Email',     'email',    'tucorreo@ejemplo.com',     null, 1],
                            ['Teléfono',  'phone',    '+521234567890',            null, 0],
                            ['Mensaje',   'textarea', 'Escribe tu mensaje aquí…', null, 1],
                        ];
                    } elseif ($template === 'quote') {
                        $fields = [
                            ['Nombre o Empresa',      'text',     'Tu nombre o razón social', null, 1],
                            ['Email de contacto',     'email',    'correo@empresa.com',       null, 1],
                            ['Teléfono',              'phone',    '+521234567890',            null, 0],
                            ['Producto o Servicio',   'text',     'Describe lo que necesitas', null, 1],
                            ['Cantidad aproximada',   'text',     'Ej: 500 unidades',         null, 0],
                            ['Notas adicionales',     'textarea', 'Información extra…',       null, 0],
                        ];
                    } elseif ($template === 'survey') {
                        $fields = [
                            ['Tu nombre (opcional)', 'text',     'Nombre',                            null,                                          0],
                            ['Calificación general', 'select',   '',                                  json_encode(['Excelente','Bueno','Regular','Malo','Muy malo']), 1],
                            ['¿Qué fue lo que más te gustó?',    'textarea', 'Cuéntanos…',            null, 0],
                            ['¿Qué podríamos mejorar?',          'textarea', 'Tu opinión es valiosa…',null, 0],
                            ['¿Nos recomendarías?', 'select',   '',                                  json_encode(['Sí, definitivamente','Tal vez','No por ahora']), 0],
                        ];
                    }
                    foreach ($fields as $idx => $f) {
                        $pdo->prepare("
                            INSERT INTO form_fields (form_id, label, field_type, placeholder, options, required, sort_order)
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                        ")->execute([$newId, $f[0], $f[1], $f[2] ?: null, $f[3], $f[4], $idx]);
                    }
                }

                header('Location: /forms/edit?id=' . $newId . '&created=1');
                exit;
            }
        }
    }
}

require __DIR__ . '/../views/create_form.php';
