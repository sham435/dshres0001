<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\CheckCountsRequest;
use App\Http\Requests\Inventory\CheckSessionRequest;
use App\Models\InvCheckSession;
use App\Models\InvSection;
use App\Services\InventoryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventoryCheckController extends Controller
{
    private InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->middleware('auth');
        $this->inventory = $inventory;
    }

    private function schoolId(): int
    {
        return $this->inventory->school()->id;
    }

    public function index(Request $request)
    {
        $schoolId = $this->schoolId();
        $selectedSession = null;
        $sessionItems = collect();
        if ($request->filled('session_id')) {
            $selectedSession = InvCheckSession::where('school_id', $schoolId)->findOrFail($request->session_id);
            $sessionItems = $selectedSession->items()->orderBy('item_name')->get();
        }
        return view('admin.inventory.checks.index', [
            'active' => InvCheckSession::where('school_id', $schoolId)->where('status', 'active')->latest()->get(),
            'history' => InvCheckSession::where('school_id', $schoolId)->where('status', '!=', 'active')->latest()->paginate(10),
            'sections' => InvSection::where('school_id', $schoolId)->orderBy('section_name')->get(),
            'selectedSession' => $selectedSession,
            'sessionItems' => $sessionItems,
        ]);
    }

    public function storeSession(CheckSessionRequest $request)
    {
        $session = $this->inventory->openSession($this->schoolId(), $request->validated());
        return redirect()->route('admin.inventory.checks.index', ['session_id' => $session->id])
            ->with('success', 'Check session created with item snapshot.');
    }

    public function show($id)
    {
        $session = InvCheckSession::where('school_id', $this->schoolId())->with('items')->findOrFail($id);
        return view('admin.inventory.checks.show', compact('session'));
    }

    public function updateChecks(CheckCountsRequest $request, $id)
    {
        $session = InvCheckSession::where('school_id', $this->schoolId())->where('status', 'active')->findOrFail($id);
        $this->inventory->recordCounts($session, $request->validated()['items']);
        return redirect()->route('admin.inventory.checks.show', $session->id)->with('success', 'Balances saved.');
    }

    public function complete($id)
    {
        $session = InvCheckSession::where('school_id', $this->schoolId())->where('status', 'active')->findOrFail($id);
        $session->update(['status' => 'completed']);
        return redirect()->route('admin.inventory.checks.report', $session->id)->with('success', 'Session completed.');
    }

    public function report($id)
    {
        $session = InvCheckSession::where('school_id', $this->schoolId())->with('items')->findOrFail($id);
        return view('admin.inventory.checks.report', ['session' => $session, 'school' => $this->inventory->school()]);
    }

    public function reportCsv($id)
    {
        $session = InvCheckSession::where('school_id', $this->schoolId())->with('items')->findOrFail($id);
        return response()->streamDownload(function () use ($session) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Stock Check Report: ' . $session->session_name . ' (' . $session->check_date . ')']);
            fputcsv($out, ['#', 'Item', 'Inventory No', 'Book', 'Real', 'Surplus', 'Deficiency', 'Remarks', 'Status']);
            foreach ($session->items as $i => $item) {
                fputcsv($out, [$i + 1, $item->item_name, $item->inventory_number, $item->book_balance,
                    $item->real_balance, $item->surplus, $item->deficiency, $item->remarks, $item->checked_status]);
            }
            fclose($out);
        }, 'check_report_' . $session->id . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function reportPdf($id)
    {
        $session = InvCheckSession::where('school_id', $this->schoolId())->with('items')->findOrFail($id);
        return Pdf::loadView('admin.inventory.checks.report-pdf', ['session' => $session, 'school' => $this->inventory->school()])
            ->setPaper('a4', 'landscape')->download('check_report_' . $session->id . '.pdf');
    }
}
