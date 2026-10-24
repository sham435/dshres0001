<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class TeacherController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin']);

        $teachers = Database::fetchAll(
            "SELECT t.*, u.email, u.username
             FROM teachers t
             LEFT JOIN users u ON t.user_id = u.id
             WHERE t.is_active = TRUE
             ORDER BY t.first_name, t.last_name"
        );

        $this->view('teachers/index', [
            'title'    => lang('teachers'),
            'teachers' => $teachers,
        ]);
    }

    public function create(): void
    {
        $this->requireRole(['admin']);

        $this->view('teachers/create', [
            'title' => lang('create') . ' ' . lang('teachers'),
        ]);
    }

    public function store(): void
    {
        $this->requireRole(['admin']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/teachers/create');
        }

        $firstName = $this->sanitize($_POST['first_name'] ?? '');
        $lastName  = $this->sanitize($_POST['last_name'] ?? '');
        $email     = $this->sanitize($_POST['email'] ?? '');
        $department = $this->sanitize($_POST['department'] ?? '');
        $qualification = $this->sanitize($_POST['qualification'] ?? '');

        if (empty($firstName) || empty($lastName) || empty($email)) {
            $this->flash('error', 'First name, last name, and email are required.');
            $this->redirect('/teachers/create');
        }

        $existing = Database::fetchOne("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        if ($existing) {
            $this->flash('error', 'Email already registered.');
            $this->redirect('/teachers/create');
        }

        try {
            Database::beginTransaction();

            $username = strtolower($firstName . '.' . $lastName);
            $passwordHash = password_hash('Teacher@123', PASSWORD_ARGON2ID);
            $userId = Database::insert('users', [
                'username'      => $username,
                'email'         => $email,
                'password_hash' => $passwordHash,
                'role'          => 'teacher',
                'is_active'     => true,
                'email_verified' => true,
            ]);

            $teacherId = 'TCH' . date('Y') . str_pad((string) mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            Database::insert('teachers', [
                'user_id'       => $userId,
                'teacher_id'    => $teacherId,
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'department'    => $department ?: null,
                'qualification' => $qualification ?: null,
            ]);

            Database::commit();
            $this->flash('success', 'Teacher created successfully. Default password: Teacher@123');
            $this->redirect('/teachers');
        } catch (\Exception $e) {
            Database::rollback();
            $this->flash('error', 'Failed to create teacher: ' . $e->getMessage());
            $this->redirect('/teachers/create');
        }
    }

    public function show(string $id): void
    {
        $this->requireRole(['admin', 'teacher']);

        $teacher = Database::fetchOne(
            "SELECT t.*, u.email, u.username
             FROM teachers t
             LEFT JOIN users u ON t.user_id = u.id
             WHERE t.id = :id",
            ['id' => $id]
        );

        if (!$teacher) {
            $this->flash('error', lang('not_found'));
            $this->redirect('/teachers');
        }

        $this->view('teachers/show', [
            'title'   => $teacher['first_name'] . ' ' . $teacher['last_name'],
            'teacher' => $teacher,
        ]);
    }

    public function edit(string $id): void
    {
        $this->requireRole(['admin']);

        $teacher = Database::fetchOne("SELECT * FROM teachers WHERE id = :id", ['id' => $id]);
        if (!$teacher) {
            $this->flash('error', lang('not_found'));
            $this->redirect('/teachers');
        }

        $this->view('teachers/edit', [
            'title'   => lang('edit') . ' ' . lang('teachers'),
            'teacher' => $teacher,
        ]);
    }

    public function update(string $id): void
    {
        $this->requireRole(['admin']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/teachers/' . $id . '/edit');
        }

        $data = [
            'first_name'     => $this->sanitize($_POST['first_name'] ?? ''),
            'last_name'      => $this->sanitize($_POST['last_name'] ?? ''),
            'department'     => $this->sanitize($_POST['department'] ?? ''),
            'qualification'  => $this->sanitize($_POST['qualification'] ?? ''),
            'phone'          => $this->sanitize($_POST['phone'] ?? ''),
            'address'        => $this->sanitize($_POST['address'] ?? ''),
        ];

        Database::update('teachers', $data, 'id = :id', ['id' => $id]);
        $this->flash('success', lang('success'));
        $this->redirect('/teachers');
    }

    public function destroy(string $id): void
    {
        $this->requireRole(['admin']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/teachers');
        }

        Database::update('teachers', ['is_active' => false], 'id = :id', ['id' => $id]);
        $this->flash('success', lang('success'));
        $this->redirect('/teachers');
    }
}