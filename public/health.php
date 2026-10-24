<?php
declare(strict_types=1);

// Health check endpoint - no authentication required
header('Content-Type: application/json');

// Load autoloader
require_once __DIR__ . '/../core/Autoloader.php';

// Load .env
$envFile = __DIR__ . '/../.env';
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

$checks = [];
$overallStatus = 'healthy';

// 1. PHP availability
$checks['php'] = [
    'status' => 'ok',
    'version' => PHP_VERSION,
];

// 2. Database connectivity
try {
    $config = require __DIR__ . '/../config/config.php';
    \Core\Database::setConfig($config['db']);
    $pdo = \Core\Database::getInstance();
    $stmt = $pdo->query('SELECT 1');
    $checks['database'] = [
        'status' => 'ok',
        'driver' => 'pgsql',
    ];
} catch (\Throwable $e) {
    $checks['database'] = [
        'status' => 'error',
        'message' => 'Database connection failed',
    ];
    $overallStatus = 'unhealthy';
}

// 3. Storage check
$storagePath = __DIR__ . '/../storage';
$checks['storage'] = [
    'status' => is_writable($storagePath) ? 'ok' : 'error',
    'path' => $storagePath,
];
if ($checks['storage']['status'] === 'error') {
    $overallStatus = 'unhealthy';
}

// 4. Session check
$checks['session'] = [
    'status' => session_status() === PHP_SESSION_ACTIVE || session_start() ? 'ok' : 'error',
];

http_response_code($overallStatus === 'healthy' ? 200 : 503);
echo json_encode([
    'status' => $overallStatus,
    'timestamp' => date('c'),
    'checks' => $checks,
], JSON_PRETTY_PRINT);