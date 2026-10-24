<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function home()
    {
        $count = fn ($role) => DB::table('users')->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('roles.name', $role)->count();

        return view('frontend.home', [
            'stats' => [
                'Classes'  => DB::table('classes')->count(),
                'Students' => $count('student'),
                'Teachers' => $count('teacher'),
                'Parents'  => $count('parent'),
            ],
            'news'   => News::published()->limit(4)->get(),
            'events' => Event::whereDate('event_date', '>=', today())->orderBy('event_date')->limit(3)->get(),
        ]);
    }

    public function about() { return view('frontend.about'); }

    public function news()
    {
        return view('frontend.news', ['items' => News::published()->paginate(9)]);
    }

    public function newsShow(News $news)
    {
        abort_unless($news->is_published, 404);
        return view('frontend.news-detail', [
            'item'   => $news,
            'recent' => News::published()->where('id', '!=', $news->id)->limit(5)->get(),
        ]);
    }

    public function events()
    {
        return view('frontend.events', [
            'upcoming' => Event::whereDate('event_date', '>=', today())->orderBy('event_date')->get(),
            'past'     => Event::whereDate('event_date', '<', today())->orderByDesc('event_date')->limit(6)->get(),
        ]);
    }

    public function notices()
    {
        return view('frontend.notices', [
            'items' => DB::table('notices')->orderByDesc('notice_date')->paginate(10),
        ]);
    }

    public function contact() { return view('frontend.contact'); }

    public function contactStore(Request $r)
    {
        ContactMessage::create($r->validate([
            'name' => 'required|max:100', 'email' => 'required|email|max:150',
            'subject' => 'required|max:150', 'message' => 'required|max:3000',
        ]));
        return back()->with('success', 'Message sent. We will get back to you soon.');
    }

    public function result(Request $r)
    {
        $exams = DB::table('exams')->orderByDesc('exam_date')->get(['id', 'name']);
        $data  = ['exams' => $exams, 'student' => null, 'marks' => null, 'exam' => null];

        if ($r->isMethod('post')) {
            $v = $r->validate([
                'exam_id' => 'required|integer',
                'student' => 'required|string|max:150',
                'dob'     => 'required|date',
            ]);

            // identity check: username/email + date of birth (marks are private)
            $student = DB::table('users')
                ->where(fn ($q) => $q->where('username', $v['student'])->orWhere('email', $v['student']))
                ->whereDate('dob', $v['dob'])
                ->first();

            if (!$student) {
                return back()->withInput()->withErrors(['student' => 'No matching student found.']);
            }

            $marks = DB::table('marks')
                ->join('subjects', 'subjects.id', '=', 'marks.subject_id')
                ->where('marks.student_id', $student->id)
                ->where('marks.exam_id', $v['exam_id'])
                ->get(['subjects.name as subject', 'marks.marks_obtained', 'marks.total_marks', 'marks.grade']);

            if ($marks->isEmpty()) {
                return back()->withInput()->withErrors(['exam_id' => 'No results published for this exam.']);
            }

            $data['student'] = $student;
            $data['marks']   = $marks;
            $data['exam']    = $exams->firstWhere('id', (int) $v['exam_id']);
        }

        return view('frontend.result', $data);
    }

    public function admission()
    {
        return view('frontend.admission', ['classes' => DB::table('classes')->orderBy('id')->get(['id', 'name'])]);
    }

    public function admissionStore(Request $r)
    {
        $d = $r->validate([
            'first_name' => 'required|max:80', 'last_name' => 'required|max:80',
            'phone' => 'nullable|max:30', 'email' => 'required|email|max:150',
            'dob' => 'required|date|before:today',
            'academic_year' => 'required|digits:4',
            'class_id' => 'required|exists:classes,id',
            'shift' => 'nullable|max:20', 'gender' => 'required|in:male,female',
            'religion' => 'nullable|max:30', 'nationality' => 'nullable|max:60',
            'home_language' => 'nullable|max:60', 'place_of_birth' => 'nullable|max:100',
            'address' => 'nullable|max:500', 'attended_school' => 'nullable|boolean',
            'previous_school' => 'nullable|max:1000',
            'student_image' => 'nullable|image|max:2048',
            'guardian_name' => 'required|max:100', 'guardian_phone' => 'required|max:30',
            'guardian_profession' => 'nullable|max:100',
            'father_name' => 'nullable|max:100', 'father_phone' => 'nullable|max:30',
            'mother_name' => 'nullable|max:100', 'mother_phone' => 'nullable|max:30',
        ]);

        $d['attended_school'] = $r->boolean('attended_school');
        if ($r->hasFile('student_image')) {
            $d['student_image'] = $r->file('student_image')->store('admissions', 'public');
        }

        AdmissionApplication::create($d);
        return redirect()->route('admission')->with('success', 'Application submitted. We will contact the guardian shortly.');
    }
}
