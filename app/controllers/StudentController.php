<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class StudentController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'teacher']);

        $students = Database::fetchAll(
            "SELECT s.*, c.class_name, sec.section_name
             FROM students s
             LEFT JOIN classes c ON s.class_id = c.id
             LEFT JOIN sections sec ON s.section_id = sec.id
             WHERE s.is_active = TRUE
             ORDER BY s.first_name, s.last_name"
        );

        $this->view('students/index', [
            'title'    => lang('students'),
            'students' => $students,
        ]);
    }

    public function create(): void
    {
        $this->requireRole(['admin']);

        $classes = Database::fetchAll("SELECT * FROM classes WHERE is_active = TRUE ORDER BY class_numeric");
        $sections = Database::fetchAll("SELECT * FROM sections WHERE is_active = TRUE ORDER BY section_name");

        $this->view('students/create', [
            'title'    => lang('create') . ' ' . lang('students'),
            'classes'  => $classes,
            'sections' => $sections,
        ]);
    }

    public function store(): void
    {
        $this->requireRole(['admin']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/students/create');
        }

        $firstName = $this->sanitize($_POST['first_name'] ?? '');
        $lastName  = $this->sanitize($_POST['last_name'] ?? '');
        $email     = $this->sanitize($_POST['email'] ?? '');
        $classId   = $_POST['class_id'] ?? null;
        $sectionId = $_POST['section_id'] ?? null;
        $dob       = $_POST['date_of_birth'] ?? null;
        $gender    = $_POST['gender'] ?? null;
        $phone     = $this->sanitize($_POST['phone'] ?? '');
        $address   = $this->sanitize($_POST['address'] ?? '');

        if (empty($firstName) || empty($lastName) || empty($email)) {
            $this->flash('error', 'First name, last name, and email are required.');
            $this->redirect('/students/create');
        }

        // Check if email exists
        $existing = Database::fetchOne("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        if ($existing) {
            $this->flash('error', 'Email already registered.');
            $this->redirect('/students/create');
        }

        try {
            Database::beginTransaction();

            // Create user account
            $username = strtolower($firstName . '.' . $lastName);
            $passwordHash = password_hash('Student@123', PASSWORD_ARGON2ID);
            $userId = Database::insert('users', [
                'username'      => $username,
                'email'         => $email,
                'password_hash' => $passwordHash,
                'role'          => 'student',
                'is_active'     => true,
                'email_verified' => true,
            ]);

            // Generate student ID
            $studentId = 'STU' . date('Y') . str_pad((string) mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Create student record
            Database::insert('students', [
                'user_id'       => $userId,
                'student_id'    => $studentId,
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'date_of_birth' => $dob ?: null,
                'gender'        => $gender ?: null,
                'phone'         => $phone ?: null,
                'address'       => $address ?: null,
                'class_id'      => $classId ?: null,
                'section_id'    => $sectionId ?: null,
            ]);

            Database::commit();
            $this->flash('success', 'Student created successfully. Default password: Student@123');
            $this->redirect('/students');
        } catch (\Exception $e) {
            Database::rollback();
            $this->flash('error', 'Failed to create student: ' . $e->getMessage());
            $this->redirect('/students/create');
        }
    }

    public function show(string $id): void
    {
        $this->requireRole(['admin', 'teacher']);

        $student = Database::fetchOne(
            "SELECT s.*, c.class_name, sec.section_name, u.email, u.username
             FROM students s
             LEFT JOIN classes c ON s.class_id = c.id
             LEFT JOIN sections sec ON s.section_id = sec.id
             LEFT JOIN users u ON s.user_id = u.id
             WHERE s.id = :id",
            ['id' => $id]
        );

        if (!$student) {
            $this->flash('error', lang('not_found'));
            $this->redirect('/students');
        }

        $this->view('students/show', [
            'title'   => $student['first_name'] . ' ' . $student['last_name'],
            'student' => $student,
        ]);
    }

    public function edit(string $id): void
    {
        $this->requireRole(['admin']);

        $student = Database::fetchOne("SELECT * FROM students WHERE id = :id", ['id' => $id]);
        if (!$student) {
            $this->flash('error', lang('not_found'));
            $this->redirect('/students');
        }

        $classes = Database::fetchAll("SELECT * FROM classes WHERE is_active = TRUE ORDER BY class_numeric");
        $sections = Database::fetchAll("SELECT * FROM sections WHERE is_active = TRUE ORDER BY section_name");

        $this->view('students/edit', [
            'title'    => lang('edit') . ' ' . lang('students'),
            'student'  => $student,
            'classes'  => $classes,
            'sections' => $sections,
        ]);
    }

    public function update(string $id): void
    {
        $this->requireRole(['admin']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/students/' . $id . '/edit');
        }

        $data = [
            'first_name'    => $this->sanitize($_POST['first_name'] ?? ''),
            'last_name'     => $this->sanitize($_POST['last_name'] ?? ''),
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'gender'        => $_POST['gender'] ?? null,
            'phone'         => $this->sanitize($_POST['phone'] ?? ''),
            'address'       => $this->sanitize($_POST['address'] ?? ''),
            'class_id'      => $_POST['class_id'] ?? null,
            'section_id'    => $_POST['section_id'] ?? null,
        ];

        Database::update('students', $data, 'id = :id', ['id' => $id]);
        $this->flash('success', lang('success'));
        $this->redirect('/students');
    }

    public function destroy(string $id): void
    {
        $this->requireRole(['admin']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/students');
        }

        Database::update('students', ['is_active' => false], 'id = :id', ['id' => $id]);
        $this->flash('success', lang('success'));
        $this->redirect('/students');
    }
}