<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Core\RateLimiter;

class RateLimiterTest extends TestCase
{
    private string $testIp = '192.168.1.100';
    private string $testAction = 'test_login';

    protected function setUp(): void
    {
        // Clean up test data
        \Core\Database::query(
            "DELETE FROM rate_limits WHERE identifier = :id AND action = :action",
            ['id' => $this->testIp, 'action' => $this->testAction]
        );
    }

    protected function tearDown(): void
    {
        \Core\Database::query(
            "DELETE FROM rate_limits WHERE identifier = :id AND action = :action",
            ['id' => $this->testIp, 'action' => $this->testAction]
        );
    }

    public function testCheckAllowsFirstAttempt(): void
    {
        $result = RateLimiter::check($this->testIp, $this->testAction);
        $this->assertTrue($result['allowed']);
        $this->assertEquals(4, $result['remaining']);
        $this->assertEquals(0, $result['retry_after']);
    }

    public function testHitRecordsAttempt(): void
    {
        RateLimiter::hit($this->testIp, $this->testAction);
        $result = RateLimiter::check($this->testIp, $this->testAction);
        $this->assertTrue($result['allowed']);
        $this->assertEquals(3, $result['remaining']);
    }

    public function testRateLimitAfterMaxAttempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            RateLimiter::hit($this->testIp, $this->testAction);
        }
        $result = RateLimiter::check($this->testIp, $this->testAction);
        $this->assertFalse($result['allowed']);
        $this->assertEquals(0, $result['remaining']);
        $this->assertGreaterThan(0, $result['retry_after']);
    }

    public function testResetClearsLimit(): void
    {
        RateLimiter::hit($this->testIp, $this->testAction);
        RateLimiter::reset($this->testIp, $this->testAction);
        $result = RateLimiter::check($this->testIp, $this->testAction);
        $this->assertTrue($result['allowed']);
        $this->assertEquals(4, $result['remaining']);
    }

    public function testGetClientIpReturnsString(): void
    {
        $ip = RateLimiter::getClientIp();
        $this->assertIsString($ip);
        $this->assertNotEmpty($ip);
    }

    public function testSanitizeIdentifier(): void
    {
        // Test that special characters are stripped
        $result = RateLimiter::check("192.168.1.1; DROP TABLE", $this->testAction);
        $this->assertTrue($result['allowed']);
    }
}