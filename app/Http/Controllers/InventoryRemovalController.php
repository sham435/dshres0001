<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\DecideRemovalRequest;
use App\Http\Requests\Inventory\RecommendRemovalRequest;
use App\Models\InvItem;
use App\Models\InvRemoval;
use App\Models\InvRemovedArchive;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryRemovalController extends Controller
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
        return view('admin.inventory.removals.index', [
            'tab' => $request->get('tab', 'inventory'),
            'eligible' => InvItem::forSchool($schoolId)
                ->whereNotIn('id', InvRemoval::where('status', 'pending')->pluck('original_item_id'))
                ->with(['section', 'incharge'])->orderBy('item_name')->paginate(15, ['*'], 'eligible_page')->withQueryString(),
            'pending' => InvRemoval::where('school_id', $schoolId)->where('status', 'pending')
                ->latest()->paginate(15, ['*'], 'pending_page')->withQueryString(),
            'archive' => InvRemovedArchive::where('school_id', $schoolId)
                ->latest('removal_date')->paginate(15, ['*'], 'archive_page')->withQueryString(),
        ]);
    }

    public function recommend(RecommendRemovalRequest $request)
    {
        $item = InvItem::forSchool($this->schoolId())
            ->with(['section', 'incharge'])->findOrFail($request->item_id);

        if (InvRemoval::where('original_item_id', $item->id)->where('status', 'pending')->exists()) {
            return back()->with('error', 'A pending recommendation already exists for this item.');
        }

        InvRemoval::create([
            'original_item_id' => $item->id,
            'school_id' => $item->school_id,
            'item_name' => $item->item_name,
            'brand_name' => $item->brand_name,
            'inventory_number' => $item->inventory_number,
            'serial_number' => $item->serial_number,
            'section_id' => $item->section_id,
            'book_number' => $item->book_number,
            'incharge_officer_id' => $item->incharge_officer_id,
            'condition_status' => $item->condition_status,
            'recommendation_reason' => $request->reason,
            'recommended_by' => $request->recommended_by ?? auth()->user()->name,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.inventory.removals.index', ['tab' => 'pending'])->with('success', 'Removal recommended.');
    }

    public function decide(DecideRemovalRequest $request, $id)
    {
        $rec = InvRemoval::where('school_id', $this->schoolId())->where('status', 'pending')->findOrFail($id);

        if ($request->decision === 'approve') {
            $this->inventory->approveRemoval($rec, $request->validated());
            return redirect()->route('admin.inventory.removals.index', ['tab' => 'archive'])->with('success', 'Item removed and archived.');
        }

        $rec->delete();
        return redirect()->route('admin.inventory.removals.index', ['tab' => 'inventory'])->with('success', 'Recommendation rejected; item kept in inventory.');
    }

    public function archiveDestroy(Request $request, $id)
    {
        $request->validate(['confirm_delete' => 'required|accepted']);
        InvRemovedArchive::where('school_id', $this->schoolId())->findOrFail($id)->delete();
        return redirect()->route('admin.inventory.removals.index', ['tab' => 'archive'])->with('success', 'Archive record deleted.');
    }

    public function pendingCsv()
    {
        $rows = InvRemoval::where('school_id', $this->schoolId())->where('status', 'pending')->get();
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Item', 'Inventory No', 'Reason', 'Recommended By', 'Date']);
            foreach ($rows as $r) {
                fputcsv($out, [$r->item_name, $r->inventory_number, $r->recommendation_reason, $r->recommended_by, $r->recommendation_date]);
            }
            fclose($out);
        }, 'pending_removals.csv', ['Content-Type' => 'text/csv']);
    }

    public function archiveCsv()
    {
        $rows = InvRemovedArchive::where('school_id', $this->schoolId())->get();
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Item', 'Inventory No', 'Reason', 'Removed By', 'Removal Date', 'Disposal']);
            foreach ($rows as $r) {
                fputcsv($out, [$r->item_name, $r->inventory_number, $r->removal_reason, $r->removed_by, $r->removal_date, $r->disposal_method]);
            }
            fclose($out);
        }, 'removed_archive.csv', ['Content-Type' => 'text/csv']);
    }
}
