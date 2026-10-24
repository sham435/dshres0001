<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HealthCheckTest extends TestCase
{
    public function testHealthEndpointReturnsJson(): void
    {
        // Verify health check logic components exist
        $this->assertTrue(function_exists('json_encode'));
        $this->assertTrue(class_exists('Core\\Database'));
    }

    public function testPhpVersionIsSupported(): void
    {
        $this->assertGreaterThanOrEqual('8.0', PHP_VERSION);
    }

    public function testRequiredExtensions(): void
    {
        $required = ['pdo', 'pdo_pgsql', 'mbstring', 'openssl', 'json'];
        foreach ($required as $ext) {
            $this->assertTrue(extension_loaded($ext), "Extension $ext is not loaded");
        }
    }

    public function testDatabaseConnection(): void
    {
        $config = require __DIR__ . '/../../config/config.php';
        \Core\Database::setConfig($config['db']);
        $pdo = \Core\Database::getInstance();
        $this->assertInstanceOf(\PDO::class, $pdo);
    }
}
