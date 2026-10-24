<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;

class TimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Timetable::with('class');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $timetables = $query->latest()->paginate(10);
        $classes = Classes::all();

        return view('admin.timetables.index', compact('timetables', 'classes'));
    }

    public function create()
    {
        $classes = Classes::all();
        $subjects = Subject::all();
        $teachers = User::where('role_id', 5)->get();
        return view('admin.timetables.create', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_number' => 'nullable|string|max:20',
        ]);

        Timetable::create($validated);

        return redirect()->route('admin.timetables.index')->with('success', 'Timetable created successfully.');
    }

    public function edit($id)
    {
        $timetable = Timetable::findOrFail($id);
        $classes = Classes::all();
        $subjects = Subject::all();
        $teachers = User::where('role_id', 5)->get();
        return view('admin.timetables.edit', compact('timetable', 'classes', 'subjects', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_number' => 'nullable|string|max:20',
        ]);

        $timetable = Timetable::findOrFail($id);
        $timetable->update($validated);

        return redirect()->route('admin.timetables.index')->with('success', 'Timetable updated successfully.');
    }

    public function destroy($id)
    {
        $timetable = Timetable::findOrFail($id);
        $timetable->delete();

        return redirect()->route('admin.timetables.index')->with('success', 'Timetable deleted successfully.');
    }
}
