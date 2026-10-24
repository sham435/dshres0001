<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function base()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = LeaveApplication::with(['type', 'user'])->latest();
        if (!($user->isAdmin() || $user->isSuperAdmin())) {
            $query->where('user_id', $user->id);
        }
        return $query;
    }

    private function rolePrefix(): string
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->isTeacher()) {
            return 'teacher';
        }
        if ($user->isAccountant()) {
            return 'accountant';
        }
        if ($user->isLibrarian()) {
            return 'librarian';
        }
        return 'admin';
    }

    private function indexRoute(): string
    {
        return $this->rolePrefix() . '.leaves.index';
    }

    public function index(Request $request)
    {
        $query = $this->base();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $isAdmin = $user->isAdmin() || $user->isSuperAdmin();
        $prefix = $this->rolePrefix();
        return view('leaves.index', [
            'items' => $query->paginate(15)->withQueryString(),
            'isAdmin' => $isAdmin,
            'pending' => $isAdmin ? LeaveApplication::where('status', 'pending')->count() : 0,
            'createUrl' => $isAdmin ? null : route($prefix . '.leaves.create'),
        ]);
    }

    public function create()
    {
        $prefix = $this->rolePrefix();
        return view('leaves.create', [
            'types' => LeaveType::orderBy('name')->get(),
            'balances' => $this->balances(auth()->id()),
            'storeUrl' => route($prefix . '.leaves.store'),
            'backUrl' => route($prefix . '.leaves.index'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string',
            'is_abroad' => 'nullable|boolean',
        ]);

        $type = LeaveType::findOrFail($validated['leave_type_id']);
        $days = $this->rangeDays($validated['from_date'], $validated['to_date']);

        if ($type->max_consecutive_days && $days > $type->max_consecutive_days) {
            return back()->withErrors(['to_date' => __('marks.lv_max_consecutive', ['max' => $type->max_consecutive_days, 'type' => $type->name])])->withInput();
        }

        if ($type->days_allowed > 0) {
            $used = $this->usedDays(auth()->id(), $type->id);
            if ($used + $days > $type->days_allowed) {
                return back()->withErrors(['to_date' => __('marks.lv_quota_exceeded', ['used' => $used, 'quota' => $type->days_allowed])])->withInput();
            }
        }

        LeaveApplication::create([
            'user_id' => auth()->id(),
            'leave_type_id' => $type->id,
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'reason' => $validated['reason'],
            'is_abroad' => $request->boolean('is_abroad'),
            'status' => 'pending',
        ]);
        return redirect()->route($this->indexRoute())->with('success', 'Leave application submitted.');
    }

    public function review(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!($user->isAdmin() || $user->isSuperAdmin())) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate(['decision' => 'required|in:approved,rejected']);
        $app = LeaveApplication::where('status', 'pending')->findOrFail($id);
        $app->update([
            'status' => $request->decision,
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);
        return back()->with('success', "Leave {$request->decision}.");
    }

    // ---------- Admin leave types ----------

    public function balances(int $userId): array
    {
        $out = [];
        foreach (LeaveType::orderBy('name')->get() as $type) {
            $used = $this->usedDays($userId, $type->id);
            $out[$type->id] = [
                'used' => $used,
                'remaining' => $type->days_allowed > 0 ? max(0, $type->days_allowed - $used) : null,
            ];
        }
        return $out;
    }

    private function usedDays(int $userId, int $typeId): int
    {
        $year = date('Y');
        return LeaveApplication::where('user_id', $userId)
            ->where('leave_type_id', $typeId)
            ->where('status', '!=', 'rejected')
            ->whereYear('from_date', $year)
            ->get()
            ->sum(fn ($a) => $this->rangeDays($a->from_date->format('Y-m-d'), $a->to_date->format('Y-m-d')));
    }

    private function rangeDays(string $from, string $to): int
    {
        return (int) (\Carbon\Carbon::parse($from)->diffInDays(\Carbon\Carbon::parse($to)) + 1);
    }

    public function types()
    {
        return view('admin.leaves.types', ['types' => LeaveType::withCount('applications')->orderBy('name')->get()]);
    }

    public function storeType(Request $request)
    {
        LeaveType::create($request->validate([
            'name' => 'required|string|max:100|unique:leave_types,name',
            'days_allowed' => 'required|integer|min:0',
            'max_consecutive_days' => 'nullable|integer|min:1',
            'rule_note' => 'nullable|string|max:500',
        ]));
        return back()->with('success', 'Leave type added.');
    }

    public function destroyType($id)
    {
        $type = LeaveType::findOrFail($id);
        if ($type->applications()->count() > 0) {
            return back()->with('error', 'Cannot delete: applications exist for this type.');
        }
        $type->delete();
        return back()->with('success', 'Leave type deleted.');
    }
}
