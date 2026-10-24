<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class TimetableController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $classes = Database::fetchAll("SELECT * FROM classes WHERE is_active = TRUE ORDER BY class_numeric");
        $classId = $_GET['class_id'] ?? ($classes[0]['id'] ?? '');
        $timetable = [];
        if ($classId) {
            $timetable = Database::fetchAll(
                "SELECT t.*, s.subject_name, te.first_name || ' ' || te.last_name as teacher_name
                 FROM timetables t
                 LEFT JOIN subjects s ON t.subject_id = s.id
                 LEFT JOIN teachers te ON t.teacher_id = te.id
                 WHERE t.class_id = :cid AND t.is_active = TRUE
                 ORDER BY t.day_of_week, t.period_number",
                ['cid' => $classId]
            );
        }
        $this->view('timetable/index', [
            'title'     => 'Timetable',
            'classes'   => $classes,
            'classId'   => $classId,
            'timetable' => $timetable,
        ]);
    }
}