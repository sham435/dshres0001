<?php
declare(strict_types=1);

namespace Core;

/**
 * Structured Application Logger
 *
 * Provides structured logging with secret redaction.
 * Logs to file with rotation.
 */
class Logger
{
    private static string $logDir = __DIR__ . '/../storage/logs';
    private static string $logFile = 'app.log';
    private static int $maxFileSize = 10 * 1024 * 1024; // 10MB

    private static array $sensitiveKeys = [
        'password', 'password_hash', 'token', 'secret', 'api_key', 'csrf_token',
        'session_id', 'access_token', 'refresh_token', 'reset_token',
    ];

    public static function log(string $level, string $message, array $context = []): void
    {
        // Redact sensitive data
        $context = self::redact($context);

        $entry = [
            'timestamp' => date('c'),
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context,
            'request_id' => self::getRequestId(),
        ];

        $line = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;

        self::writeToFile($line);
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('info', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('warning', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        if (getenv('APP_DEBUG') === 'true') {
            self::log('debug', $message, $context);
        }
    }

    public static function security(string $message, array $context = []): void
    {
        self::log('security', $message, $context);
    }

    private static function redact(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::redact($value);
            } elseif (is_string($key) && self::isSensitive($key)) {
                $data[$key] = '[REDACTED]';
            }
        }
        return $data;
    }

    private static function isSensitive(string $key): bool
    {
        $lower = strtolower($key);
        foreach (self::$sensitiveKeys as $sensitive) {
            if (str_contains($lower, $sensitive)) {
                return true;
            }
        }
        return false;
    }

    private static function getRequestId(): string
    {
        if (!isset($_SERVER['HTTP_X_REQUEST_ID'])) {
            $_SERVER['HTTP_X_REQUEST_ID'] = bin2hex(random_bytes(8));
        }
        return $_SERVER['HTTP_X_REQUEST_ID'];
    }

    private static function writeToFile(string $line): void
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }

        $logPath = self::$logDir . '/' . self::$logFile;

        // Rotate if too large
        if (file_exists($logPath) && filesize($logPath) > self::$maxFileSize) {
            rename($logPath, $logPath . '.' . date('YmdHis'));
        }

        file_put_contents($logPath, $line, FILE_APPEND | LOCK_EX);
    }
}