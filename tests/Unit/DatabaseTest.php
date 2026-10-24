<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Core\Database;

class DatabaseTest extends TestCase
{
    public function testConnection(): void
    {
        $pdo = Database::getInstance();
        $this->assertInstanceOf(\PDO::class, $pdo);
    }

    public function testQuery(): void
    {
        $stmt = Database::query("SELECT 1 as num");
        $result = $stmt->fetch();
        $this->assertEquals(1, (int)$result['num']);
    }

    public function testFetchOne(): void
    {
        $result = Database::fetchOne("SELECT 1 as num");
        $this->assertIsArray($result);
        $this->assertEquals(1, (int)$result['num']);
    }

    public function testFetchAll(): void
    {
        $result = Database::fetchAll("SELECT 1 as num UNION SELECT 2");
        $this->assertCount(2, $result);
    }

    public function testFetchColumn(): void
    {
        $result = Database::fetchColumn("SELECT COUNT(*) FROM users");
        $this->assertIsNumeric($result);
    }

    public function testParameterizedQuery(): void
    {
        // Test SQL injection resistance
        $result = Database::fetchOne(
            "SELECT * FROM users WHERE username = :username",
            ['username' => "admin' OR '1'='1"]
        );
        $this->assertNull($result); // Should not find anything
    }

    public function testTransaction(): void
    {
        Database::beginTransaction();
        try {
            Database::query("INSERT INTO csrf_tokens (token, expires_at) VALUES (:t, NOW() + INTERVAL '1 hour')", ['t' => 'test_token_' . uniqid()]);
            Database::rollback();
            $result = Database::fetchOne("SELECT * FROM csrf_tokens WHERE token LIKE 'test_token_%'");
            $this->assertNull($result);
        } catch (\Throwable $e) {
            Database::rollback();
            throw $e;
        }
    }
}