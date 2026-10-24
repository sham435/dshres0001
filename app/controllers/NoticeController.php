<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class NoticeController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $notices = Database::fetchAll(
            "SELECT n.*, u.username as author
             FROM notices n
             LEFT JOIN users u ON n.created_by = u.id
             WHERE n.is_published = TRUE
             ORDER BY n.publish_date DESC"
        );

        $this->view('notices/index', [
            'title'   => lang('notice_board'),
            'notices' => $notices,
        ]);
    }

    public function create(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $this->view('notices/create', [
            'title' => lang('create') . ' ' . lang('notices'),
        ]);
    }

    public function store(): void
    {
        $this->requireRole(['admin', 'teacher']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/notices/create');
        }

        $title    = $this->sanitize($_POST['title'] ?? '');
        $content  = $this->sanitize($_POST['content'] ?? '');
        $priority = $_POST['priority'] ?? 'normal';
        $audience = $_POST['audience'] ?? 'all';

        if (empty($title) || empty($content)) {
            $this->flash('error', 'Title and content are required.');
            $this->redirect('/notices/create');
        }

        Database::insert('notices', [
            'title'       => $title,
            'content'     => $content,
            'priority'    => $priority,
            'audience'    => $audience,
            'is_published'=> true,
            'publish_date' => date('Y-m-d H:i:s'),
            'created_by'  => $_SESSION['user_id'],
        ]);

        $this->flash('success', lang('success'));
        $this->redirect('/notices');
    }

    public function show(string $id): void
    {
        $this->requireAuth();

        $notice = Database::fetchOne(
            "SELECT n.*, u.username as author
             FROM notices n
             LEFT JOIN users u ON n.created_by = u.id
             WHERE n.id = :id",
            ['id' => $id]
        );

        if (!$notice) {
            $this->flash('error', lang('not_found'));
            $this->redirect('/notices');
        }

        $this->view('notices/show', [
            'title'  => $notice['title'],
            'notice' => $notice,
        ]);
    }
}