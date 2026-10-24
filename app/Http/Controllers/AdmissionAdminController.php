<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdmissionAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ---------- Applications ----------

    public function applications(Request $request)
    {
        $query = AdmissionApplication::with('class')->withStatus($request->status)->latest();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('guardian_phone', 'like', "%{$search}%");
            });
        }
        $counts = [
            'pending' => AdmissionApplication::where('status', 'pending')->count(),
            'approved' => AdmissionApplication::where('status', 'approved')->count(),
            'rejected' => AdmissionApplication::where('status', 'rejected')->count(),
        ];
        return view('admin.admissions.applications', [
            'applications' => $query->paginate(15)->withQueryString(),
            'counts' => $counts,
        ]);
    }

    public function showApplication($id)
    {
        return view('admin.admissions.show', [
            'application' => AdmissionApplication::with('class')->findOrFail($id),
        ]);
    }

    public function decideApplication(Request $request, $id)
    {
        $request->validate(['decision' => 'required|in:approved,rejected']);
        $application = AdmissionApplication::findOrFail($id);
        // NB: status is in $guarded (applicants must not set it), so assign directly.
        $application->status = $request->decision;
        $application->save();
        return redirect()->route('admin.admissions.applications', ['status' => $request->decision])
            ->with('success', "Application {$request->decision}.");
    }

    public function destroyApplication($id)
    {
        AdmissionApplication::findOrFail($id)->delete();
        return redirect()->route('admin.admissions.applications')->with('success', 'Application deleted.');
    }

    // ---------- Contact inbox ----------

    public function inbox()
    {
        return view('admin.admissions.inbox', [
            'messages' => ContactMessage::latest()->paginate(15),
            'unread' => ContactMessage::where('is_read', false)->count(),
        ]);
    }

    public function showMessage($id)
    {
        $message = ContactMessage::findOrFail($id);
        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }
        return view('admin.admissions.message', compact('message'));
    }

    public function destroyMessage($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return redirect()->route('admin.admissions.inbox')->with('success', 'Message deleted.');
    }
}
