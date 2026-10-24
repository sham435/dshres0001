<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class SettingsController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin']);
        $settings = Database::fetchAll("SELECT * FROM school_settings ORDER BY setting_key");
        $this->view('settings/index', ['title' => 'Settings', 'settings' => $settings]);
    }

    public function update(): void
    {
        $this->requireRole(['admin']);
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/settings');
        }
        foreach ($_POST['settings'] ?? [] as $key => $value) {
            Database::update('school_settings', ['setting_value' => $value], 'setting_key = :key', ['key' => $key]);
        }
        $this->flash('success', 'Settings updated successfully.');
        $this->redirect('/settings');
    }
}