<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class ExamController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $exams = Database::fetchAll(
            "SELECT e.*, c.class_name, s.subject_name
             FROM exams e
             LEFT JOIN classes c ON e.class_id = c.id
             LEFT JOIN subjects s ON e.subject_id = s.id
             ORDER BY e.exam_date DESC"
        );
        $this->view('exams/index', ['title' => 'Exams', 'exams' => $exams]);
    }

    public function create(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $classes = Database::fetchAll("SELECT * FROM classes WHERE is_active = TRUE ORDER BY class_numeric");
        $subjects = Database::fetchAll("SELECT * FROM subjects WHERE is_active = TRUE ORDER BY subject_name");
        $this->view('exams/create', ['title' => 'Create Exam', 'classes' => $classes, 'subjects' => $subjects]);
    }

    public function store(): void
    {
        $this->requireRole(['admin', 'teacher']);
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/exams/create');
        }
        Database::insert('exams', [
            'exam_name'     => $this->sanitize($_POST['exam_name'] ?? ''),
            'exam_type'     => $_POST['exam_type'] ?? 'midterm',
            'class_id'      => $_POST['class_id'] ?? null,
            'subject_id'    => $_POST['subject_id'] ?? null,
            'exam_date'     => $_POST['exam_date'] ?? date('Y-m-d'),
            'total_marks'   => (float) ($_POST['total_marks'] ?? 100),
            'passing_marks' => (float) ($_POST['passing_marks'] ?? 40),
            'created_by'    => $_SESSION['user_id'],
        ]);
        $this->flash('success', 'Exam created successfully.');
        $this->redirect('/exams');
    }

    public function results(string $id): void
    {
        $this->requireAuth();
        $exam = Database::fetchOne("SELECT * FROM exams WHERE id = :id", ['id' => $id]);
        $results = Database::fetchAll(
            "SELECT er.*, s.first_name, s.last_name, s.student_id
             FROM exam_results er JOIN students s ON er.student_id = s.id
             WHERE er.exam_id = :id ORDER BY s.first_name",
            ['id' => $id]
        );
        $this->view('exams/results', ['title' => 'Exam Results', 'exam' => $exam, 'results' => $results]);
    }

    public function submitResults(string $id): void
    {
        $this->requireRole(['admin', 'teacher']);
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/exams/' . $id . '/results');
        }
        $marks = $_POST['marks'] ?? [];
        foreach ($marks as $studentId => $mark) {
            $existing = Database::fetchOne(
                "SELECT id FROM exam_results WHERE exam_id = :eid AND student_id = :sid",
                ['eid' => $id, 'sid' => $studentId]
            );
            $gpa = $mark >= 90 ? 4.0 : ($mark >= 80 ? 3.5 : ($mark >= 70 ? 3.0 : ($mark >= 60 ? 2.5 : ($mark >= 50 ? 2.0 : 0))));
            $grade = $mark >= 90 ? 'A+' : ($mark >= 85 ? 'A' : ($mark >= 80 ? 'A-' : ($mark >= 75 ? 'B+' : ($mark >= 70 ? 'B' : ($mark >= 65 ? 'B-' : ($mark >= 60 ? 'C+' : ($mark >= 55 ? 'C' : ($mark >= 50 ? 'C-' : ($mark >= 40 ? 'D' : 'F')))))))));
            $data = [
                'exam_id'       => $id,
                'student_id'    => $studentId,
                'marks_obtained'=> (float) $mark,
                'grade'         => $grade,
                'gpa'           => $gpa,
                'submitted_by'  => $_SESSION['user_id'],
            ];
            if ($existing) {
                Database::update('exam_results', $data, 'id = :id', ['id' => $existing['id']]);
            } else {
                Database::insert('exam_results', $data);
            }
        }
        $this->flash('success', 'Results submitted successfully.');
        $this->redirect('/exams/' . $id . '/results');
    }
}