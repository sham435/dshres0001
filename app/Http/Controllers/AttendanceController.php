<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function summary($studentId): array
    {
        $total = Attendance::where('student_id', $studentId)->count();
        $present = Attendance::where('student_id', $studentId)->where('status', 'present')->count();
        return [
            'total' => $total,
            'present' => $present,
            'percentage' => $total > 0 ? round($present / $total * 100, 1) : 0,
        ];
    }

    // ---------- Admin overview ----------

    public function index(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        $query = Attendance::whereDate('date', $date)->with(['student', 'class']);
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        $records = $query->orderBy('id')->paginate(25)->withQueryString();
        $dayRecords = Attendance::whereDate('date', $date)->get();
        $classes = Classes::orderBy('name')->get();
        return view('admin.attendance.index', [
            'records' => $records,
            'classes' => $classes,
            'date' => $date,
            'present' => $dayRecords->where('status', 'present')->count(),
            'absent' => $dayRecords->where('status', 'absent')->count(),
            'late' => $dayRecords->where('status', 'late')->count(),
            'half' => $dayRecords->where('status', 'half_day')->count(),
        ]);
    }

    // ---------- Teacher marking ----------

    public function mark(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        $classes = Classes::orderBy('name')->get();
        $students = collect();
        $existing = [];
        if ($request->filled('class_id')) {
            $class = Classes::findOrFail($request->class_id);
            $students = $class->students()->orderBy('name')->get();
            $existing = Attendance::whereDate('date', $date)->where('class_id', $class->id)
                ->get()->keyBy('student_id');
        }
        return view('teacher.attendance.mark', compact('students', 'classes', 'date', 'existing'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'records' => 'required|array',
            'records.*.status' => 'required|in:present,absent,late,half_day',
            'records.*.remarks' => 'nullable|string|max:500',
        ]);

        foreach ($validated['records'] as $studentId => $row) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $validated['date']],
                [
                    'class_id' => $validated['class_id'],
                    'status' => $row['status'],
                    'remarks' => $row['remarks'] ?? null,
                    'marked_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('teacher.attendance.mark', ['date' => $validated['date'], 'class_id' => $validated['class_id']])
            ->with('success', 'Attendance saved.');
    }

    // ---------- Student / parent views ----------

    public function student()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $records = Attendance::where('student_id', $user->id)->orderBy('date', 'desc')->paginate(20);
        return view('student.attendance.index', ['records' => $records, 'summary' => $this->summary($user->id)]);
    }

    public function child($childId)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!$user->children->contains('id', (int) $childId)) {
            abort(403, 'Unauthorized action.');
        }
        $student = User::findOrFail($childId);
        $records = Attendance::where('student_id', $childId)->orderBy('date', 'desc')->paginate(20);
        return view('parent.attendance.show', ['records' => $records, 'student' => $student, 'summary' => $this->summary($childId)]);
    }
}
