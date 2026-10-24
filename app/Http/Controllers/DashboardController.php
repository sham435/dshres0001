<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Book;
use App\Models\Payment;
use App\Models\Notice;
use App\Models\StudyMaterial;
use App\Models\TeacherClass;
use App\Models\Mark;
use App\Models\Attendance;
use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use App\Models\LeaveApplication;
use App\Models\Enquiry;
use App\Models\Complaint;
use App\Models\Homework;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } elseif ($user->isParent()) {
            return redirect()->route('parent.dashboard');
        } elseif ($user->isAccountant()) {
            return redirect()->route('accountant.dashboard');
        } elseif ($user->isLibrarian()) {
            return redirect()->route('librarian.dashboard');
        }
        return view('home');
    }

    public function adminDashboard()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $today = date('Y-m-d');

        // 6-month fee collection trend (oldest -> latest)
        $feeTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = \Carbon\Carbon::now()->subMonths($i);
            $feeTrend[] = [
                'month' => $d->format('M'),
                'amount' => (float) Payment::whereYear('created_at', $d->year)->whereMonth('created_at', $d->month)->sum('paid_amount'),
            ];
        }

        // last 7 days attendance % trend
        $attTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = \Carbon\Carbon::now()->subDays($i);
            $day = Attendance::whereDate('date', $d->toDateString())->get();
            $attTrend[] = [
                'day' => $d->format('D'),
                'pct' => $day->count() > 0 ? round($day->where('status', 'present')->count() / $day->count() * 100, 1) : 0,
            ];
        }

        // new students per month (6 months)
        $studentTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = \Carbon\Carbon::now()->subMonths($i);
            $studentTrend[] = [
                'month' => $d->format('M'),
                'count' => User::where('role_id', 6)->whereYear('created_at', $d->year)->whereMonth('created_at', $d->month)->count(),
            ];
        }

        $todayAtt = Attendance::whereDate('date', $today)->get();
        $dueQuery = Payment::where('status', '!=', 'paid');

        // live activity feed: payments + admissions + messages
        $activity = collect()
            ->merge(Payment::with('student')->latest()->take(5)->get()->map(fn ($p) => [
                'icon' => 'fa-credit-card', 'tone' => 'green',
                'text' => 'Payment ' . ($p->receipt_number ?? '#' . $p->id),
                'meta' => ($p->student->name ?? 'N/A') . ' — ' . number_format($p->paid_amount, 2),
                'at' => $p->created_at,
            ]))
            ->merge(AdmissionApplication::latest()->take(4)->get()->map(fn ($a) => [
                'icon' => 'fa-file-signature', 'tone' => 'blue',
                'text' => 'Application: ' . $a->first_name . ' ' . $a->last_name,
                'meta' => $a->email,
                'at' => $a->created_at,
            ]))
            ->merge(ContactMessage::latest()->take(4)->get()->map(fn ($m) => [
                'icon' => 'fa-envelope', 'tone' => 'amber',
                'text' => 'Message: ' . $m->subject,
                'meta' => $m->name,
                'at' => $m->created_at,
            ]))
            ->sortByDesc('at')->take(9)->values();

        $data = [
            'user' => $user,
            'greeting' => (int) date('G') < 12 ? __('marks.dash_morning') : ((int) date('G') < 17 ? __('marks.dash_afternoon') : __('marks.dash_evening')),
            'totalUsers' => User::count(),
            'totalStudents' => User::where('role_id', 6)->count(),
            'totalTeachers' => User::where('role_id', 5)->count(),
            'totalClasses' => Classes::count(),
            'totalSubjects' => Subject::count(),
            'totalExams' => Exam::count(),
            'totalBooks' => Book::count(),
            'totalPayments' => Payment::count(),
            'totalNotices' => Notice::count(),
            'feesCollected' => (float) Payment::sum('paid_amount'),
            'feesDue' => (float) $dueQuery->sum('amount') - (float) $dueQuery->sum('paid_amount'),
            'dueCount' => $dueQuery->count(),
            'todayPresent' => $todayAtt->where('status', 'present')->count(),
            'todayAbsent' => $todayAtt->where('status', '!=', 'present')->count(),
            'todayPct' => $todayAtt->count() > 0 ? round($todayAtt->where('status', 'present')->count() / $todayAtt->count() * 100, 1) : 0,
            'pendingLeaves' => LeaveApplication::where('status', 'pending')->count(),
            'newEnquiries' => Enquiry::where('status', 'new')->count(),
            'pendingAdmissions' => AdmissionApplication::where('status', 'pending')->count(),
            'openComplaints' => Complaint::whereIn('status', ['open', 'in_review'])->count(),
            'feeTrend' => $feeTrend,
            'attTrend' => $attTrend,
            'studentTrend' => $studentTrend,
            'activity' => $activity,
            'recentPayments' => Payment::with('student')->latest()->take(5)->get(),
            'duePayments' => $dueQuery->with('student')->latest()->take(6)->get(),
            'recentAdmissions' => AdmissionApplication::latest()->take(5)->get(),
            'recentMessages' => ContactMessage::latest()->take(5)->get(),
            'upcomingExams' => Exam::whereDate('exam_date', '>=', $today)->orderBy('exam_date')->take(5)->get(),
        ];
        return view('admin.dashboard', $data);
    }

    public function teacherDashboard()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $assignedClasses = TeacherClass::where('teacher_id', $user->id)->with(['class', 'subject'])->get();
        $data = [
            'mySubjects' => $assignedClasses->pluck('subject')->filter()->unique('id')->count(),
            'myStudents' => User::where('role_id', 6)->count(),
            'studyMaterials' => StudyMaterial::where('teacher_id', $user->id)->count(),
            'assignedClasses' => $assignedClasses,
            'myHomework' => Homework::where('teacher_id', $user->id)->count(),
            'markedToday' => Attendance::where('marked_by', $user->id)->whereDate('date', date('Y-m-d'))->count(),
            'myLeaves' => LeaveApplication::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];
        return view('teacher.dashboard', $data);
    }

    public function studentDashboard()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $attTotal = Attendance::where('student_id', $user->id)->count();
        $attPresent = Attendance::where('student_id', $user->id)->where('status', 'present')->count();
        $data = [
            'myMarks' => Mark::where('student_id', $user->id)->count(),
            'notices' => Notice::count(),
            'payments' => Payment::where('student_id', $user->id)->count(),
            'booksAvailable' => Book::sum('available_quantity'),
            'attPct' => $attTotal > 0 ? round($attPresent / $attTotal * 100, 1) : 0,
            'homeworkDue' => Homework::count(),
        ];
        return view('student.dashboard', $data);
    }

    public function parentDashboard()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $children = $user->children;
        $data = [
            'children' => $children,
            'recentNotices' => Notice::latest()->take(5)->get(),
            'totalPayments' => Payment::whereIn('student_id', $children->pluck('id'))->count(),
        ];
        return view('parent.dashboard', $data);
    }

    public function accountantDashboard()
    {
        $data = [
            'totalPayments' => Payment::count(),
            'paidAmount' => Payment::where('status', 'paid')->sum('paid_amount'),
            'pendingAmount' => Payment::where('status', 'unpaid')->sum('amount'),
            'recentPayments' => Payment::with('student')->latest()->take(5)->get(),
        ];
        return view('accountant.dashboard', $data);
    }

    public function librarianDashboard()
    {
        $data = [
            'totalBooks' => Book::count(),
            'totalQuantity' => Book::sum('quantity'),
            'availableQuantity' => Book::sum('available_quantity'),
            'availableBooks' => Book::sum('available_quantity'),
            'recentBooks' => Book::latest()->take(5)->get(),
        ];
        return view('librarian.dashboard', $data);
    }
}
