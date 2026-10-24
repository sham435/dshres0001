<?php

namespace App\Http\Controllers;

use App\Models\InvItem;
use App\Models\InvSection;
use App\Services\InventoryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    private InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->middleware('auth');
        $this->inventory = $inventory;
    }

    private function query(Request $request)
    {
        $query = InvItem::forSchool($this->inventory->school()->id)
            ->with(['section', 'incharge'])
            ->filtered($request->only(['search', 'section', 'condition', 'status']));
        if ($request->filled('date_from')) {
            $query->whereDate('received_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('received_date', '<=', $request->date_to);
        }
        return $query->orderBy('created_at', 'desc');
    }

    public function index(Request $request)
    {
        return view('admin.inventory.reports.index', [
            'items' => $this->query($request)->paginate(25)->withQueryString(),
            'sections' => InvSection::where('school_id', $this->inventory->school()->id)->orderBy('section_name')->get(),
        ]);
    }

    public function pdf(Request $request)
    {
        return Pdf::loadView('admin.inventory.reports.pdf', [
            'items' => $this->query($request)->get(),
            'school' => $this->inventory->school(),
        ])->setPaper('a4', 'landscape')->download('inventory_report.pdf');
    }

    public function excel(Request $request)
    {
        $items = $this->query($request)->get();
        return response()->streamDownload(function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['#', 'Item', 'Brand', 'Inventory No', 'Serial No', 'Section', 'Book No', 'Incharge', 'Received', 'Condition', 'Status']);
            foreach ($items as $i => $item) {
                fputcsv($out, [$i + 1, $item->item_name, $item->brand_name, $item->inventory_number,
                    $item->serial_number, optional($item->section)->section_name,
                    $item->book_number, optional($item->incharge)->officer_name,
                    $item->received_date, $item->condition_status, $item->statusLabel()]);
            }
            fclose($out);
        }, 'inventory_report.csv', ['Content-Type' => 'text/csv']);
    }
}
