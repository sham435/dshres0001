<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class AttendanceController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'teacher']);

        $classes = Database::fetchAll("SELECT * FROM classes WHERE is_active = TRUE ORDER BY class_numeric");
        $sections = Database::fetchAll("SELECT * FROM sections WHERE is_active = TRUE ORDER BY section_name");

        $selectedClass = $_GET['class_id'] ?? '';
        $selectedSection = $_GET['section_id'] ?? '';
        $date = $_GET['date'] ?? date('Y-m-d');

        $students = [];
        if ($selectedClass && $selectedSection) {
            $students = Database::fetchAll(
                "SELECT s.id, s.first_name, s.last_name, s.roll_number,
                        sa.status, sa.remarks
                 FROM students s
                 LEFT JOIN student_attendance sa ON s.id = sa.student_id AND sa.attendance_date = :date
                 WHERE s.class_id = :class_id AND s.section_id = :section_id AND s.is_active = TRUE
                 ORDER BY s.roll_number, s.first_name",
                ['date' => $date, 'class_id' => $selectedClass, 'section_id' => $selectedSection]
            );
        }

        $this->view('attendance/index', [
            'title'          => lang('attendance'),
            'classes'        => $classes,
            'sections'       => $sections,
            'students'       => $students,
            'selectedClass'  => $selectedClass,
            'selectedSection'=> $selectedSection,
            'date'           => $date,
        ]);
    }

    public function store(): void
    {
        $this->requireRole(['admin', 'teacher']);

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->redirect('/attendance');
        }

        $date = $_POST['date'] ?? date('Y-m-d');
        $classId = $_POST['class_id'] ?? '';
        $sectionId = $_POST['section_id'] ?? '';
        $attendance = $_POST['attendance'] ?? [];

        try {
            Database::beginTransaction();

            foreach ($attendance as $studentId => $status) {
                $existing = Database::fetchOne(
                    "SELECT id FROM student_attendance WHERE student_id = :sid AND attendance_date = :date",
                    ['sid' => $studentId, 'date' => $date]
                );

                if ($existing) {
                    Database::update('student_attendance', [
                        'status' => $status,
                        'marked_by' => $_SESSION['user_id'],
                    ], 'id = :id', ['id' => $existing['id']]);
                } else {
                    Database::insert('student_attendance', [
                        'student_id'     => $studentId,
                        'class_id'       => $classId,
                        'section_id'     => $sectionId,
                        'attendance_date'=> $date,
                        'status'         => $status,
                        'marked_by'      => $_SESSION['user_id'],
                    ]);
                }
            }

            Database::commit();
            $this->flash('success', lang('success'));
        } catch (\Exception $e) {
            Database::rollback();
            $this->flash('error', 'Failed to save attendance: ' . $e->getMessage());
        }

        $this->redirect("/attendance?class_id={$classId}&section_id={$sectionId}&date={$date}");
    }

    public function report(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $this->view('attendance/report', ['title' => lang('attendance_report')]);
    }
}