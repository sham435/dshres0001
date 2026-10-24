<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $role = $_SESSION['user_role'] ?? 'student';

        $stats = [];
        if (in_array($role, ['admin', 'teacher'], true)) {
            $stats['total_students'] = Database::fetchColumn("SELECT COUNT(*) FROM students WHERE is_active = TRUE");
            $stats['total_teachers'] = Database::fetchColumn("SELECT COUNT(*) FROM teachers WHERE is_active = TRUE");
            $stats['total_classes'] = Database::fetchColumn("SELECT COUNT(*) FROM classes WHERE is_active = TRUE");
            $stats['total_subjects'] = Database::fetchColumn("SELECT COUNT(*) FROM subjects WHERE is_active = TRUE");
        }

        $notices = Database::fetchAll(
            "SELECT * FROM notices WHERE is_published = TRUE AND (expiry_date IS NULL OR expiry_date > NOW()) ORDER BY publish_date DESC LIMIT 5"
        );

        $view = match ($role) {
            'admin'   => 'dashboard/admin',
            'teacher' => 'dashboard/teacher',
            'student' => 'dashboard/student',
            'parent'  => 'dashboard/parent',
            default   => 'dashboard/student',
        };

        $this->view($view, [
            'title'   => lang('dashboard'),
            'stats'   => $stats,
            'notices' => $notices,
            'role'    => $role,
        ]);
    }
}