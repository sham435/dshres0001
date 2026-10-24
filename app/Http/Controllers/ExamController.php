<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Classes;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $exams = Exam::with('class')->latest()->paginate(10);
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $classes = Classes::all();
        return view('admin.exams.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'exam_date' => 'required|date',
        ]);

        Exam::create($request->all());

        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $classes = Classes::all();
        return view('admin.exams.edit', compact('exam', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'exam_date' => 'required|date',
        ]);

        $exam = Exam::findOrFail($id);
        $exam->update($request->all());

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }
}
