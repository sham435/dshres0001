<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;

class MarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Mark::with(['exam', 'student', 'subject']);

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $marks = $query->latest()->paginate(10);
        $exams = Exam::all();
        $classes = Classes::all();
        $subjects = Subject::all();

        return view('admin.marks.index', compact('marks', 'exams', 'classes', 'subjects'));
    }

    public function create()
    {
        $exams = Exam::with('class')->get();
        $subjects = Subject::all();
        $students = User::where('role_id', 6)->get();
        return view('admin.marks.create', compact('exams', 'subjects', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:users,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:10',
            'remarks' => 'nullable|string',
        ]);

        if (empty($validated['grade'])) {
            $validated['grade'] = $this->calculateGrade($validated['marks_obtained'], $validated['total_marks']);
        }

        Mark::create($validated);

        return redirect()->route($this->indexRoute())->with('success', 'Mark created successfully.');
    }

    public function edit($id)
    {
        $mark = Mark::findOrFail($id);
        $exams = Exam::with('class')->get();
        $subjects = Subject::all();
        $students = User::where('role_id', 6)->get();
        return view('admin.marks.edit', compact('mark', 'exams', 'subjects', 'students'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:users,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:10',
            'remarks' => 'nullable|string',
        ]);

        if (empty($validated['grade'])) {
            $validated['grade'] = $this->calculateGrade($validated['marks_obtained'], $validated['total_marks']);
        }

        $mark = Mark::findOrFail($id);
        $mark->update($validated);

        return redirect()->route($this->indexRoute())->with('success', 'Mark updated successfully.');
    }

    public function destroy($id)
    {
        $mark = Mark::findOrFail($id);
        $mark->delete();

        return redirect()->route($this->indexRoute())->with('success', 'Mark deleted successfully.');
    }

    public function marksheet($studentId = null)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isStudent()) {
            $studentId = $user->id;
        } elseif ($user->isParent()) {
            if (!$studentId) {
                return redirect()->back()->with('error', 'Please select a child.');
            }
            if (!$user->children->contains('id', (int) $studentId)) {
                abort(403, 'Unauthorized action.');
            }
        }

        $student = User::findOrFail($studentId);
        $marks = Mark::where('student_id', $studentId)->with(['exam', 'subject'])->get();
        $exams = $marks->groupBy('exam_id');

        return view('admin.marks.marksheet', compact('student', 'marks', 'exams'));
    }

    private function calculateGrade($obtained, $total): string
    {
        if (!$total || $total <= 0) {
            return 'N/A';
        }
        $percentage = ($obtained / $total) * 100;
        if ($percentage >= 75) {
            return 'A';
        } elseif ($percentage >= 65) {
            return 'B';
        } elseif ($percentage >= 55) {
            return 'C';
        } elseif ($percentage >= 40) {
            return 'S';
        }
        return 'F';
    }

    private function indexRoute(): string
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user && $user->isTeacher()) {
            return 'teacher.marks.index';
        }
        return 'admin.marks.index';
    }
}
