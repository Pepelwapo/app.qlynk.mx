<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle).' — QLynk' : 'QLynk'; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .sidebar { width:230px; min-height:100vh; background:#1a1a2e; }
  .sidebar a { color:#a0a0b0; text-decoration:none; display:block; padding:8px 16px; border-radius:6px; }
  .sidebar a:hover { background:#ffffff15; color:#fff; }
  .sidebar .brand { color:#fff; font-weight:700; font-size:1.3rem; padding:20px 16px 10px; }
</style>
</head>
<body class="bg-light">