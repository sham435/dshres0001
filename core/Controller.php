<?php
declare(strict_types=1);

namespace Core;

/**
 * Base Controller
 * 
 * Provides common functionality for all controllers:
 * - View rendering
 * - JSON responses
 * - Redirects
 * - Flash messages
 * - CSRF validation
 */
abstract class Controller
{
    protected array $data = [];
    protected string $layout = 'main';

    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            $this->startSession();
        }
    }

    protected function startSession(): void
    {
        $config = require __DIR__ . '/../config/config.php';
        $session = $config['session'];

        session_name($session['name']);
        session_set_cookie_params([
            'lifetime' => $session['lifetime'],
            'path'     => $session['path'],
            'domain'   => $session['domain'],
            'secure'   => $session['secure'],
            'httponly' => $session['httponly'],
            'samesite' => $session['samesite'],
        ]);
        session_start();
    }

    protected function view(string $view, array $data = [], ?string $layout = null): void
    {
        $this->data = array_merge($this->data, $data);
        extract($this->data);

        $layout = $layout ?? $this->layout;
        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../app/views/layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function flash(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash(string $key): ?string
    {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }

    protected function hasFlash(string $key): bool
    {
        return isset($_SESSION['flash'][$key]);
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrf(): bool
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->flash('error', 'Please log in to continue.');
            $this->redirect('/login');
        }

        // Session timeout (2 hours)
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 7200) {
            session_destroy();
            session_start();
            $this->flash('error', 'Session expired. Please log in again.');
            $this->redirect('/login');
        }
    }

    protected function requireRole(array $roles): void
    {
        $this->requireAuth();
        if (!in_array($_SESSION['user_role'] ?? '', $roles, true)) {
            http_response_code(403);
            echo 'Access Denied';
            exit;
        }
    }

    protected function currentUser(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        return [
            'id'   => $_SESSION['user_id'],
            'role' => $_SESSION['user_role'] ?? '',
            'name' => $_SESSION['user_name'] ?? '',
        ];
    }

    protected function sanitize(mixed $value): mixed
    {
        if (is_string($value)) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }
        return $value;
    }

    protected function validate(array $rules, array $data): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', $rule);

            foreach ($ruleList as $r) {
                if ($r === 'required' && (empty($value) && $value !== '0')) {
                    $errors[$field][] = ucfirst($field) . ' is required.';
                } elseif (str_starts_with($r, 'min:')) {
                    $min = (int) substr($r, 4);
                    if (strlen((string)$value) < $min) {
                        $errors[$field][] = ucfirst($field) . " must be at least {$min} characters.";
                    }
                } elseif (str_starts_with($r, 'max:')) {
                    $max = (int) substr($r, 4);
                    if (strlen((string)$value) > $max) {
                        $errors[$field][] = ucfirst($field) . " must not exceed {$max} characters.";
                    }
                } elseif ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = ucfirst($field) . ' must be a valid email.';
                } elseif ($r === 'numeric' && !is_numeric($value)) {
                    $errors[$field][] = ucfirst($field) . ' must be numeric.';
                }
            }
        }
        return $errors;
    }
}