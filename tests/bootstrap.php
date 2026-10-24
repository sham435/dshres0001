<?php
declare(strict_types=1);

// Load autoloader
require_once __DIR__ . '/../core/Autoloader.php';

// Load .env for testing
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

// Override with test env
putenv('DB_NAME=school_db_test');
putenv('APP_ENV=testing');

// Configure database
$config = require __DIR__ . '/../config/config.php';
\Core\Database::setConfig($config['db']);