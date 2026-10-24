<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class LeaveController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $role = $_SESSION['user_role'] ?? '';
        if (in_array($role, ['admin'], true)) {
            $leaves = Database::fetchAll(
                "SELECT lr.*, u.username FROM leave_requests lr LEFT JOIN users u ON lr.user_id = u.id ORDER BY lr.created_at DESC"
            );
        } else {
            $leaves = Database::fetchAll(
                "SELECT * FROM leave_requests WHERE user_id = :uid ORDER BY created_at DESC",
                ['uid' => $_SESSION['user_id']]
            );
        }
        $this->view('leaves/index', ['title' => 'Leave Requests', 'leaves' => $leaves]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->view('leaves/create', ['title' => 'Request Leave']);
    }

    public function store(): void
    {
        $this->requireAuth();
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/leaves/create');
        }
        Database::insert('leave_requests', [
            'user_id'    => $_SESSION['user_id'],
            'leave_type' => $_POST['leave_type'] ?? 'casual',
            'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
            'end_date'   => $_POST['end_date'] ?? date('Y-m-d'),
            'reason'     => $this->sanitize($_POST['reason'] ?? ''),
        ]);
        $this->flash('success', 'Leave request submitted.');
        $this->redirect('/leaves');
    }

    public function approve(string $id): void
    {
        $this->requireRole(['admin']);
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/leaves');
        }
        Database::update('leave_requests', [
            'status'      => 'approved',
            'approved_by' => $_SESSION['user_id'],
            'approved_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);
        $this->flash('success', 'Leave approved.');
        $this->redirect('/leaves');
    }

    public function reject(string $id): void
    {
        $this->requireRole(['admin']);
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/leaves');
        }
        Database::update('leave_requests', [
            'status'           => 'rejected',
            'approved_by'      => $_SESSION['user_id'],
            'approved_at'      => date('Y-m-d H:i:s'),
            'rejection_reason' => $this->sanitize($_POST['rejection_reason'] ?? ''),
        ], 'id = :id', ['id' => $id]);
        $this->flash('success', 'Leave rejected.');
        $this->redirect('/leaves');
    }
}