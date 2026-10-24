<?php

namespace App\Http\Controllers;

use App\Models\InvCheckItem;
use App\Models\InvItem;
use App\Models\InvSection;
use App\Services\InventoryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventoryToolsController extends Controller
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

    // ---------- Bulk import ----------

    public function bulk()
    {
        return view('admin.inventory.items.bulk');
    }

    public function bulkTemplate()
    {
        $headers = ['item_name', 'brand_name', 'inventory_number', 'serial_number', 'section_name',
            'book_number', 'officer_name', 'received_date', 'received_from', 'condition_status'];
        return response()->streamDownload(function () use ($headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fclose($out);
        }, 'inventory_template.csv', ['Content-Type' => 'text/csv']);
    }

    public function bulkPreview(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:csv,txt|max:5120']);
        $result = $this->inventory->parseCsvPreview($request->file('excel_file'), $this->schoolId());
        return view('admin.inventory.items.bulk', ['preview' => $result['valid'], 'skipped' => $result['skipped']]);
    }

    public function bulkConfirm(Request $request)
    {
        $items = json_decode($request->input('items_data', '[]'), true) ?? [];
        $count = $this->inventory->confirmBulkImport($items, $this->schoolId());
        return redirect()->route('admin.inventory.items.index')->with('success', "{$count} items imported.");
    }

    // ---------- QR labels ----------

    public function qr(Request $request)
    {
        $items = InvItem::forSchool($this->schoolId())
            ->with('section')
            ->search($request->search)
            ->ofSection($request->section)
            ->orderBy('item_name')->paginate(20)->withQueryString();
        $sections = InvSection::where('school_id', $this->schoolId())->orderBy('section_name')->get();
        return view('admin.inventory.items.qr', compact('items', 'sections'));
    }

    public function qrSingle($id)
    {
        $item = InvItem::forSchool($this->schoolId())->findOrFail($id);
        return $this->labelsPdf(collect([$item]), "qr-label-{$item->inventory_number}.pdf");
    }

    public function qrBatch(Request $request)
    {
        $request->validate(['selected_items' => 'required|array|min:1', 'selected_items.*' => 'integer']);
        $items = InvItem::forSchool($this->schoolId())->whereIn('id', $request->selected_items)->get();
        return $this->labelsPdf($items, 'qr-labels.pdf');
    }

    private function labelsPdf($items, string $filename)
    {
        $schoolId = $this->schoolId();
        $labels = $items->map(fn ($item) => [
            'name' => $item->item_name,
            'number' => $item->inventory_number,
            'qr' => $this->inventory->qrDataUri($item, $schoolId),
        ])->all();
        return Pdf::loadView('admin.inventory.items.labels', compact('labels'))->download($filename);
    }

    // ---------- QR scanner ----------

    public function scanner()
    {
        $recent = InvCheckItem::whereHas('session', fn ($q) => $q->where('school_id', $this->schoolId()))
            ->with('session')->latest()->take(10)->get();
        return view('admin.inventory.items.scanner', compact('recent'));
    }

    public function scanLookup(Request $request)
    {
        $request->validate(['qr_data' => 'required|string']);
        $data = json_decode($request->qr_data, true);
        if (empty($data['id'])) {
            return response()->json(['success' => false, 'message' => 'Invalid QR code.'], 422);
        }
        $item = InvItem::forSchool($this->schoolId())->with(['section', 'incharge'])->find($data['id']);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }
        return response()->json(['success' => true, 'item' => [
            'id' => $item->id,
            'item_name' => $item->item_name,
            'inventory_number' => $item->inventory_number,
            'section' => optional($item->section)->section_name,
            'condition_status' => $item->condition_status,
        ]]);
    }

    public function scanLog(\App\Http\Requests\Inventory\ScanLogRequest $request)
    {
        $item = InvItem::forSchool($this->schoolId())->findOrFail($request->item_id);
        $session = $this->inventory->activeSession($this->schoolId());

        InvCheckItem::updateOrCreate(
            ['session_id' => $session->id, 'item_id' => $item->id],
            [
                'item_name' => $item->item_name,
                'inventory_number' => $item->inventory_number,
                'book_balance' => 1,
                'real_balance' => $request->real_balance,
                'surplus' => max(0, $request->real_balance - 1),
                'deficiency' => max(0, 1 - $request->real_balance),
                'remarks' => $request->remarks,
                'checked_status' => 'checked',
                'checked_at' => now(),
            ]
        );

        return redirect()->route('admin.inventory.scanner')->with('success', "Logged {$item->inventory_number}.");
    }
}
