<?php
declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CsrfTest extends TestCase
{
    public function testCsrfTokenGeneration(): void
    {
        $token = bin2hex(random_bytes(32));
        $this->assertEquals(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[a-f0-9]+$/', $token);
    }

    public function testHashEqualsTimingSafe(): void
    {
        $token1 = 'abc123';
        $token2 = 'abc123';
        $token3 = 'xyz789';
        $this->assertTrue(hash_equals($token1, $token2));
        $this->assertFalse(hash_equals($token1, $token3));
    }

    public function testEmptyTokenRejected(): void
    {
        $this->assertFalse(hash_equals('valid_token', ''));
        $this->assertFalse(hash_equals('', 'valid_token'));
    }
}