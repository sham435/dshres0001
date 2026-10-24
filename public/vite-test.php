<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vite Test - School Management SaaS</title>
<?php
require_once __DIR__ . '/../core/Autoloader.php';
echo \Core\ViteHelper::asset('src/main.js');
?>
</head>
<body>
  <h1>Vite Test Page</h1>
  <p>If you see styled content, Vite is working.</p>
  <button onclick="alert('JS works!')">Test JS</button>
</body>
</html>