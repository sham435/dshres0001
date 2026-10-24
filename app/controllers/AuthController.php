<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class AuthController extends Controller
{
    public function login(): void
    {
        if (is_auth()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', [
            'title' => lang('login'),
        ], 'auth');
    }

    public function authenticate(): void
    {
        $clientIp = \Core\RateLimiter::getClientIp();
        $username = $this->sanitize($_POST['username'] ?? '');

        // Rate limiting check
        $ipCheck = \Core\RateLimiter::check($clientIp, 'login_ip');
        $userCheck = \Core\RateLimiter::check($username, 'login_user');

        if (!$ipCheck['allowed'] || !$userCheck['allowed']) {
            $retryAfter = max($ipCheck['retry_after'], $userCheck['retry_after']);
            http_response_code(429);
            header('Retry-After: ' . $retryAfter);
            $this->flash('error', 'Too many login attempts. Please try again in ' . ceil($retryAfter / 60) . ' minutes.');
            $this->redirect('/login');
        }

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/login');
        }

        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->flash('error', lang('invalid_credentials'));
            $this->redirect('/login');
        }

        $user = Database::fetchOne(
            "SELECT * FROM users WHERE (username = :u OR email = :u) AND is_active = TRUE",
            ['u' => $username]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            // Record failed attempt
            \Core\RateLimiter::hit($clientIp, 'login_ip');
            \Core\RateLimiter::hit($username, 'login_user');
            \Core\Logger::security('Failed login attempt', ['username' => $username, 'ip' => $clientIp]);
            $this->flash('error', lang('invalid_credentials'));
            $this->redirect('/login');
        }

        \Core\Logger::info('Successful login', ['username' => $username, 'ip' => $clientIp]);

        // Reset rate limit on successful login
        \Core\RateLimiter::reset($clientIp, 'login_ip');
        \Core\RateLimiter::reset($username, 'login_user');

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['login_time'] = time();

        // Update last login
        Database::update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $user['id']]);

        $this->flash('success', lang('welcome_back', ['name' => $user['username']]));
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        session_destroy();
        session_start();
        $this->flash('success', 'You have been logged out.');
        $this->redirect('/login');
    }

    public function forgotPassword(): void
    {
        $this->view('auth/forgot-password', ['title' => lang('forgot_password')], 'auth');
    }

    public function sendResetLink(): void
    {
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/forgot-password');
        }

        $email = $this->sanitize($_POST['email'] ?? '');
        $user = Database::fetchOne("SELECT id, email FROM users WHERE email = :email", ['email' => $email]);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            Database::update('users', [
                'reset_token' => $token,
                'reset_token_expires' => $expires,
            ], 'id = :id', ['id' => $user['id']]);

            // In production, send email with reset link
            // For now, store in session for demo
            $_SESSION['reset_link'] = url('/reset-password/' . $token);
            $this->flash('success', 'Password reset link has been sent to your email.');
        } else {
            $this->flash('error', 'No account found with that email.');
        }

        $this->redirect('/forgot-password');
    }

    public function resetPassword(string $token): void
    {
        $user = Database::fetchOne(
            "SELECT id FROM users WHERE reset_token = :token AND reset_token_expires > NOW()",
            ['token' => $token]
        );

        if (!$user) {
            $this->flash('error', 'Invalid or expired reset token.');
            $this->redirect('/forgot-password');
        }

        $this->view('auth/reset-password', [
            'title' => lang('reset_password'),
            'token' => $token,
        ], 'auth');
    }

    public function updatePassword(): void
    {
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/login');
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirmation'] ?? '';

        if (strlen($password) < 8) {
            $this->flash('error', 'Password must be at least 8 characters.');
            $this->redirect('/reset-password/' . $token);
        }

        if ($password !== $confirm) {
            $this->flash('error', 'Passwords do not match.');
            $this->redirect('/reset-password/' . $token);
        }

        $user = Database::fetchOne(
            "SELECT id FROM users WHERE reset_token = :token AND reset_token_expires > NOW()",
            ['token' => $token]
        );

        if (!$user) {
            $this->flash('error', 'Invalid or expired reset token.');
            $this->redirect('/forgot-password');
        }

        $hash = password_hash($password, PASSWORD_ARGON2ID);
        Database::update('users', [
            'password_hash' => $hash,
            'reset_token' => null,
            'reset_token_expires' => null,
        ], 'id = :id', ['id' => $user['id']]);

        $this->flash('success', 'Password has been reset. Please log in.');
        $this->redirect('/login');
    }
}