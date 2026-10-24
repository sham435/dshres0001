<?php
declare(strict_types=1);

/**
 * Global helper functions
 */

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $config = null;
        if ($config === null) {
            $config = require __DIR__ . '/../config/config.php';
        }
        $keys = explode('.', $key);
        $value = $config;
        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        return $value;
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return '/public/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return rtrim(config('app_url'), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path, int $code = 302): void
    {
        header("Location: " . url($path), true, $code);
        exit;
    }
}

if (!function_exists('view')) {
    function view(string $name, array $data = []): void
    {
        extract($data);
        $path = __DIR__ . '/../app/views/' . $name . '.php';
        if (file_exists($path)) {
            require $path;
        } else {
            throw new \RuntimeException("View not found: {$name}");
        }
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        $token = $_SESSION['csrf_token'] ?? '';
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES) . '">';
    }
}

if (!function_exists('lang')) {
    function lang(string $key, array $replace = []): string
    {
        static $translations = null;
        if ($translations === null) {
            $locale = $_SESSION['language'] ?? config('default_language', 'en');
            $file = __DIR__ . '/../lang/' . $locale . '.php';
            $translations = file_exists($file) ? require $file : [];
        }

        $keys = explode('.', $key);
        $value = $translations;
        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key;
            }
        }

        foreach ($replace as $k => $v) {
            $value = str_replace(':' . $k, $v, $value);
        }
        return $value;
    }
}

if (!function_exists('format_date')) {
    function format_date(?string $date, string $format = 'Y-m-d'): string
    {
        if (empty($date)) return '';
        return date($format, strtotime($date));
    }
}

if (!function_exists('format_money')) {
    function format_money(float $amount, string $currency = 'USD'): string
    {
        return $currency . ' ' . number_format($amount, 2);
    }
}

if (!function_exists('flash')) {
    function flash(string $key): ?string
    {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}

if (!function_exists('has_flash')) {
    function has_flash(string $key): bool
    {
        return isset($_SESSION['flash'][$key]);
    }
}

if (!function_exists('is_auth')) {
    function is_auth(): bool
    {
        return !empty($_SESSION['user_id']);
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        if (empty($_SESSION['user_id'])) return null;
        return [
            'id'   => $_SESSION['user_id'],
            'role' => $_SESSION['user_role'] ?? '',
            'name' => $_SESSION['user_name'] ?? '',
        ];
    }
}

if (!function_exists('has_role')) {
    function has_role(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($_SESSION['user_role'] ?? '', $roles, true);
    }
}