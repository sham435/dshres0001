<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyMaterial;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;

class StudyMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $materials = StudyMaterial::with(['teacher', 'subject'])->latest()->paginate(10);
        } else {
            $materials = StudyMaterial::where('teacher_id', $user->id)->with(['teacher', 'subject'])->latest()->paginate(10);
        }
        $routePrefix = ($user->isAdmin() || $user->isSuperAdmin()) ? 'admin' : 'teacher';
        return view('admin.study-materials.index', compact('materials', 'routePrefix'));
    }

    public function create()
    {
        $subjects = Subject::all();
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $routePrefix = ($user && ($user->isAdmin() || $user->isSuperAdmin())) ? 'admin' : 'teacher';
        return view('admin.study-materials.create', compact('subjects', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|file|max:20480',
        ]);

        $filePath = $request->file('file')->store('study-materials', 'public');

        StudyMaterial::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'subject_id' => $validated['subject_id'],
            'teacher_id' => auth()->id(),
            'file_path' => $filePath,
        ]);

        return redirect()->route($this->indexRoute())->with('success', 'Study material uploaded successfully.');
    }

    public function edit($id)
    {
        $material = StudyMaterial::findOrFail($id);
        $subjects = Subject::all();
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $routePrefix = ($user && ($user->isAdmin() || $user->isSuperAdmin())) ? 'admin' : 'teacher';
        return view('admin.study-materials.edit', compact('material', 'subjects', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $material = StudyMaterial::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'nullable|file|max:20480',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'subject_id' => $validated['subject_id'],
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($material->file_path);
            $data['file_path'] = $request->file('file')->store('study-materials', 'public');
        }

        $material->update($data);

        return redirect()->route($this->indexRoute())->with('success', 'Study material updated successfully.');
    }

    public function destroy($id)
    {
        $material = StudyMaterial::findOrFail($id);
        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return redirect()->route($this->indexRoute())->with('success', 'Study material deleted successfully.');
    }

    public function download($id)
    {
        $material = StudyMaterial::findOrFail($id);
        if (!Storage::disk('public')->exists($material->file_path)) {
            abort(404, 'File not found.');
        }
        return response()->download(
            Storage::disk('public')->path($material->file_path),
            basename($material->file_path)
        );
    }

    private function indexRoute(): string
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user && $user->isTeacher()) {
            return 'teacher.study-materials.index';
        }
        return 'admin.study-materials.index';
    }
}
