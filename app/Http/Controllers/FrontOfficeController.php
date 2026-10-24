<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Enquiry;
use App\Models\Visitor;
use Illuminate\Http\Request;

class FrontOfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ---------- Enquiries ----------

    public function enquiries(Request $request)
    {
        $query = Enquiry::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");
            });
        }
        return view('admin.frontoffice.enquiries', [
            'items' => $query->paginate(15)->withQueryString(),
            'open' => Enquiry::where('status', 'new')->count(),
        ]);
    }

    public function storeEnquiry(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'enquiry_for' => 'nullable|string|max:150',
            'message' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);
        $data['status'] = 'new';
        Enquiry::create($data);
        return back()->with('success', 'Enquiry recorded.');
    }

    public function updateEnquiry(Request $request, $id)
    {
        $item = Enquiry::findOrFail($id);
        $item->update($request->validate([
            'follow_up_date' => 'nullable|date',
            'status' => 'required|in:new,contacted,admitted,closed',
        ]));
        return back()->with('success', 'Enquiry updated.');
    }

    public function destroyEnquiry($id)
    {
        Enquiry::findOrFail($id)->delete();
        return back()->with('success', 'Enquiry deleted.');
    }

    // ---------- Visitors ----------

    public function visitors(Request $request)
    {
        $query = Visitor::latest();
        if ($request->filled('date')) {
            $query->whereDate('visit_date', $request->date);
        }
        return view('admin.frontoffice.visitors', [
            'items' => $query->paginate(15)->withQueryString(),
            'inside' => Visitor::whereDate('visit_date', date('Y-m-d'))->whereNull('time_out')->count(),
        ]);
    }

    public function storeVisitor(Request $request)
    {
        Visitor::create($request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'purpose' => 'required|string|max:255',
            'person_to_meet' => 'nullable|string|max:150',
            'visit_date' => 'required|date',
            'time_in' => 'nullable',
        ]));
        return back()->with('success', 'Visitor logged.');
    }

    public function signOut($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['time_out' => date('H:i:s')]);
        return back()->with('success', 'Visitor signed out.');
    }

    public function destroyVisitor($id)
    {
        Visitor::findOrFail($id)->delete();
        return back()->with('success', 'Record deleted.');
    }

    // ---------- Complaints ----------

    public function complaints(Request $request)
    {
        $query = Complaint::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return view('admin.frontoffice.complaints', [
            'items' => $query->paginate(15)->withQueryString(),
            'open' => Complaint::whereIn('status', ['open', 'in_review'])->count(),
        ]);
    }

    public function storeComplaint(Request $request)
    {
        $data = $request->validate([
            'complainant' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $data['status'] = 'open';
        Complaint::create($data);
        return back()->with('success', 'Complaint recorded.');
    }

    public function updateComplaint(Request $request, $id)
    {
        Complaint::findOrFail($id)->update($request->validate([
            'status' => 'required|in:open,in_review,resolved,closed',
            'resolution' => 'nullable|string',
        ]));
        return back()->with('success', 'Complaint updated.');
    }

    public function destroyComplaint($id)
    {
        Complaint::findOrFail($id)->delete();
        return back()->with('success', 'Complaint deleted.');
    }
}
