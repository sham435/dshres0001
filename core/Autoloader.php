<?php
declare(strict_types=1);

/**
 * PSR-4 Autoloader
 */
spl_autoload_register(function (string $class): void {
    $prefix = '';
    $baseDir = __DIR__ . '/../';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load core classes
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/RateLimiter.php';
require_once __DIR__ . '/Logger.php';

// Load helpers
foreach (glob(__DIR__ . '/../helpers/*.php') as $helperFile) {
    require_once $helperFile;
}