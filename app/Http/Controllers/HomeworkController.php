<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ---------- Teacher ----------

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = Homework::with(['subject', 'class'])->withCount('submissions')->latest();
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }
        return view('teacher.homework.index', ['items' => $query->paginate(15)]);
    }

    public function create()
    {
        return view('teacher.homework.create', [
            'subjects' => Subject::orderBy('name')->get(),
            'classes' => Classes::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'class_id' => 'nullable|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = [
            'teacher_id' => auth()->id(),
            'subject_id' => $validated['subject_id'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ];
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('homework', 'public');
        }
        Homework::create($data);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $route = ($user->isAdmin() || $user->isSuperAdmin()) ? 'admin.homework.index' : 'teacher.homework.index';
        return redirect()->route($route)->with('success', 'Homework posted.');
    }

    public function edit($id)
    {
        $item = $this->owned($id);
        return view('teacher.homework.edit', [
            'item' => $item,
            'subjects' => Subject::orderBy('name')->get(),
            'classes' => Classes::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->owned($id);
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'class_id' => 'nullable|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'file' => 'nullable|file|max:10240',
        ]);

        $data = [
            'subject_id' => $validated['subject_id'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ];
        if ($request->hasFile('file')) {
            if ($item->file_path) {
                Storage::disk('public')->delete($item->file_path);
            }
            $data['file_path'] = $request->file('file')->store('homework', 'public');
        }
        $item->update($data);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $route = ($user->isAdmin() || $user->isSuperAdmin()) ? 'admin.homework.index' : 'teacher.homework.index';
        return redirect()->route($route)->with('success', 'Homework updated.');
    }

    public function destroy($id)
    {
        $item = $this->owned($id);
        if ($item->file_path) {
            Storage::disk('public')->delete($item->file_path);
        }
        $item->delete();

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $route = ($user->isAdmin() || $user->isSuperAdmin()) ? 'admin.homework.index' : 'teacher.homework.index';
        return redirect()->route($route)->with('success', 'Homework deleted.');
    }

    private function owned($id): Homework
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = Homework::where('id', $id);
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }
        return $query->findOrFail($id);
    }

    public function submissions($id)
    {
        $item = $this->owned($id);
        $subs = $item->submissions()->with('student')->latest()->paginate(20);
        return view('teacher.homework.submissions', compact('item', 'subs'));
    }

    public function grade(Request $request, $homeworkId, $submissionId)
    {
        $item = $this->owned($homeworkId);
        $sub = $item->submissions()->findOrFail($submissionId);
        $request->validate(['grade' => 'nullable|string|max:10']);
        $sub->update(['grade' => $request->grade]);
        return back()->with('success', 'Graded.');
    }

    // ---------- Student ----------

    public function studentIndex()
    {
        $items = Homework::with(['subject', 'teacher'])->latest()->paginate(15);
        return view('student.homework.index', compact('items'));
    }

    public function studentShow($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $item = Homework::with(['subject', 'teacher'])->findOrFail($id);
        $mine = $item->submissionFor($user->id);
        return view('student.homework.show', compact('item', 'mine'));
    }

    public function submit(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $item = Homework::findOrFail($id);
        $request->validate([
            'note' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);
        if (!$request->filled('note') && !$request->hasFile('file')) {
            return back()->withErrors(['note' => 'Add a note or attach a file.']);
        }

        $data = ['note' => $request->note];
        $existing = $item->submissionFor($user->id);
        if ($request->hasFile('file')) {
            if ($existing && $existing->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $data['file_path'] = $request->file('file')->store('homework-submissions', 'public');
        }
        HomeworkSubmission::updateOrCreate(
            ['homework_id' => $item->id, 'student_id' => $user->id],
            $data
        );
        return back()->with('success', 'Submitted.');
    }

    // ---------- Parent ----------

    public function child($childId)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!$user->children->contains('id', (int) $childId)) {
            abort(403, 'Unauthorized action.');
        }
        $student = User::findOrFail($childId);
        $items = Homework::with(['subject', 'teacher'])->latest()->paginate(15);
        return view('parent.homework.index', compact('items', 'student'));
    }
}
