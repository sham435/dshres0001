<?php

namespace App\Http\Controllers;

use App\Models\InvItem;
use App\Models\InvOfficer;
use App\Models\InvSection;
use App\Services\InventoryService;

class InventoryDashboardController extends Controller
{
    private InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->middleware('auth');
        $this->inventory = $inventory;
    }

    public function index()
    {
        $schoolId = $this->inventory->school()->id;
        return view('admin.inventory.dashboard', [
            'totalItems' => InvItem::forSchool($schoolId)->count(),
            'availableItems' => InvItem::forSchool($schoolId)->withStatus('available')->count(),
            'borrowedItems' => InvItem::forSchool($schoolId)->withStatus('borrowed')->count(),
            'transferredItems' => InvItem::forSchool($schoolId)->withStatus('transferred')->count(),
            'needsAttention' => InvItem::forSchool($schoolId)->whereIn('condition_status', ['not_working', 'not_usable'])->count(),
            'totalSections' => InvSection::where('school_id', $schoolId)->count(),
            'totalOfficers' => InvOfficer::where('school_id', $schoolId)->count(),
            'inchargeOfficers' => InvOfficer::where('school_id', $schoolId)->where('is_incharge', true)->count(),
            'recentItems' => InvItem::forSchool($schoolId)->with(['section', 'incharge'])->latest()->take(5)->get(),
        ]);
    }
}
