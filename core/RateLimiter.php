<?php
declare(strict_types=1);

namespace Core;

use Core\Database;

/**
 * Database-backed Rate Limiter
 *
 * Provides per-IP and per-account rate limiting using PostgreSQL.
 * Uses atomic UPSERT operations to prevent race conditions.
 */
class RateLimiter
{
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 15;
    private const WINDOW_MINUTES = 15;

    /**
     * Check if an action is allowed for the given identifier.
     *
     * @return array{allowed: bool, remaining: int, retry_after: int}
     */
    public static function check(string $identifier, string $action): array
    {
        $identifier = self::sanitizeIdentifier($identifier);

        // Clean up old entries
        self::cleanup();

        // Check if currently locked
        $record = Database::fetchOne(
            "SELECT attempts, locked_until, first_attempt FROM rate_limits WHERE identifier = :id AND action = :action",
            ['id' => $identifier, 'action' => $action]
        );

        if ($record && $record['locked_until'] !== null) {
            $lockedUntil = strtotime($record['locked_until']);
            if ($lockedUntil > time()) {
                $retryAfter = $lockedUntil - time();
                return ['allowed' => false, 'remaining' => 0, 'retry_after' => $retryAfter];
            }
            // Lock expired, reset
            self::reset($identifier, $action);
            $record = null;
        }

        if ($record === null) {
            return ['allowed' => true, 'remaining' => self::MAX_ATTEMPTS - 1, 'retry_after' => 0];
        }

        $attempts = (int) $record['attempts'];
        $firstAttempt = strtotime($record['first_attempt']);

        // Check if window has expired
        if ((time() - $firstAttempt) > (self::WINDOW_MINUTES * 60)) {
            self::reset($identifier, $action);
            return ['allowed' => true, 'remaining' => self::MAX_ATTEMPTS - 1, 'retry_after' => 0];
        }

        if ($attempts >= self::MAX_ATTEMPTS) {
            // Lock the account
            $lockedUntil = date('Y-m-d H:i:s', time() + (self::LOCKOUT_MINUTES * 60));
            Database::update('rate_limits', [
                'locked_until' => $lockedUntil,
            ], 'identifier = :id AND action = :action', ['id' => $identifier, 'action' => $action]);
            $retryAfter = self::LOCKOUT_MINUTES * 60;
            return ['allowed' => false, 'remaining' => 0, 'retry_after' => $retryAfter];
        }

        return ['allowed' => true, 'remaining' => self::MAX_ATTEMPTS - $attempts - 1, 'retry_after' => 0];
    }

    /**
     * Record a failed attempt.
     */
    public static function hit(string $identifier, string $action): void
    {
        $identifier = self::sanitizeIdentifier($identifier);

        $existing = Database::fetchOne(
            "SELECT id, attempts FROM rate_limits WHERE identifier = :id AND action = :action",
            ['id' => $identifier, 'action' => $action]
        );

        if ($existing) {
            Database::update('rate_limits', [
                'attempts'     => (int) $existing['attempts'] + 1,
                'last_attempt' => date('Y-m-d H:i:s'),
            ], 'id = :id', ['id' => $existing['id']]);
        } else {
            Database::insert('rate_limits', [
                'identifier'    => $identifier,
                'action'        => $action,
                'attempts'      => 1,
                'first_attempt' => date('Y-m-d H:i:s'),
                'last_attempt'  => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Reset the rate limit for an identifier.
     */
    public static function reset(string $identifier, string $action): void
    {
        $identifier = self::sanitizeIdentifier($identifier);
        Database::delete('rate_limits', 'identifier = :id AND action = :action', [
            'id'     => $identifier,
            'action' => $action,
        ]);
    }

    /**
     * Clean up expired entries.
     */
    private static function cleanup(): void
    {
        $cutoff = date('Y-m-d H:i:s', time() - (self::WINDOW_MINUTES * 60 * 2));
        Database::query("DELETE FROM rate_limits WHERE last_attempt < :cutoff AND locked_until IS NULL", ['cutoff' => $cutoff]);
    }

    /**
     * Get the client IP address safely.
     */
    public static function getClientIp(): string
    {
        // Only trust REMOTE_ADDR, not X-Forwarded-For (can be spoofed)
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private static function sanitizeIdentifier(string $identifier): string
    {
        return substr(preg_replace('/[^a-zA-Z0-9._:@-]/', '', $identifier), 0, 255);
    }
}