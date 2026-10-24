<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentMark;
use App\Imports\StudentMarkImport;
use App\Exports\StudentMarkExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = StudentMark::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }
        if ($grade = $request->get('grade')) {
            $query->where('grade', $grade);
        }
        if ($section = $request->get('section')) {
            $query->where('section', $section);
        }
        if ($subject = $request->get('subject')) {
            $query->where('subject', $subject);
        }

        $marks = $query->latest()->paginate(50)->withQueryString();

        $grades = StudentMark::select('grade')->distinct()->orderBy('grade')->pluck('grade');
        $sections = StudentMark::select('section')->distinct()->orderBy('section')->pluck('section');
        $subjects = StudentMark::select('subject')->distinct()->orderBy('subject')->pluck('subject');

        return view('marks.index', compact('marks', 'grades', 'sections', 'subjects'));
    }

    public function upload()
    {
        return view('marks.upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $import = new StudentMarkImport();
            Excel::import($import, $request->file('file'));

            return redirect()->route('admin.student-marks.index')
                ->with('success', __('marks.import_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.student-marks.upload')
                ->with('error', __('marks.import_error') . ' ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $filters = $request->only(['grade', 'section', 'subject']);
        $export = new StudentMarkExport($filters);
        $filename = 'student_marks_' . date('Ymd') . '.xlsx';
        return Excel::download($export, $filename);
    }
}
