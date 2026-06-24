<?php

require_login();

$stmt = $pdo->prepare("
    SELECT f.*, COUNT(r.id) AS response_count
    FROM user_forms f
    LEFT JOIN form_responses r ON r.form_id = f.id
    WHERE f.user_id = ? AND f.deleted_at IS NULL
    GROUP BY f.id
    ORDER BY f.id DESC
");
$stmt->execute([$_SESSION['user_id']]);
$forms = $stmt->fetchAll();

require __DIR__ . '/../views/forms.php';
