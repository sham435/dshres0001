<?php
declare(strict_types=1);

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com https://unpkg.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://unpkg.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self';");

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load .env file
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// Load autoloader
require_once BASE_PATH . '/core/Autoloader.php';

// Load config
$config = require BASE_PATH . '/config/config.php';

// Set timezone
date_default_timezone_set($config['app_timezone']);

// Database config
\Core\Database::setConfig($config['db']);

// Session configuration
$session = $config['session'];
session_name($session['name']);
session_set_cookie_params([
    'lifetime' => $session['lifetime'],
    'path'     => $session['path'],
    'domain'   => $session['domain'],
    'secure'   => $session['secure'],
    'httponly' => $session['httponly'],
    'samesite' => $session['samesite'],
]);
session_start();

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Language
if (empty($_SESSION['language'])) {
    $_SESSION['language'] = $config['default_language'];
}

// Theme
if (empty($_SESSION['theme'])) {
    $_SESSION['theme'] = $config['default_theme'];
}

// Load routes
$router = new \Core\Router();
require BASE_PATH . '/routes/web.php';

// Dispatch
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($uri, $method);