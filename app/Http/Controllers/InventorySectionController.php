<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\OfficerRequest;
use App\Http\Requests\Inventory\SectionRequest;
use App\Models\InvOfficer;
use App\Models\InvSection;
use App\Services\InventoryService;

class InventorySectionController extends Controller
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

    public function index()
    {
        $schoolId = $this->schoolId();
        $sections = InvSection::where('school_id', $schoolId)
            ->with('inchargeOfficer')->withCount('items')->orderBy('section_name')->get();
        $officers = InvOfficer::where('school_id', $schoolId)->with('section')->orderBy('officer_name')->get();
        return view('admin.inventory.sections.index', [
            'sections' => $sections,
            'officers' => $officers,
            'unassigned' => $officers->whereNull('section_id'),
            'totalSections' => $sections->count(),
            'totalOfficers' => $officers->count(),
            'inchargeCount' => $officers->where('is_incharge', true)->count(),
        ]);
    }

    public function storeSection(SectionRequest $request)
    {
        InvSection::create(array_merge($request->validated(), ['school_id' => $this->schoolId()]));
        return redirect()->route('admin.inventory.sections.index')->with('success', 'Section added.');
    }

    public function updateSection(SectionRequest $request, $id)
    {
        InvSection::where('school_id', $this->schoolId())->findOrFail($id)->update($request->validated());
        return redirect()->route('admin.inventory.sections.index')->with('success', 'Section updated.');
    }

    public function destroySection($id)
    {
        $section = InvSection::where('school_id', $this->schoolId())->findOrFail($id);
        if ($section->officers()->count() > 0 || $section->items()->count() > 0) {
            return redirect()->route('admin.inventory.sections.index')
                ->with('error', 'Cannot delete: officers or items are assigned to this section.');
        }
        $section->delete();
        return redirect()->route('admin.inventory.sections.index')->with('success', 'Section deleted.');
    }

    public function storeOfficer(OfficerRequest $request)
    {
        $data = $request->validated();
        $data['school_id'] = $this->schoolId();
        $data['is_incharge'] = $request->boolean('is_incharge');
        if ($data['is_incharge']) {
            $this->inventory->enforceSingleIncharge($data['school_id'], $data['section_id'] ?? null);
        }
        InvOfficer::create($data);
        return redirect()->route('admin.inventory.sections.index')->with('success', 'Officer added.');
    }

    public function updateOfficer(OfficerRequest $request, $id)
    {
        $officer = InvOfficer::where('school_id', $this->schoolId())->findOrFail($id);
        $data = $request->validated();
        $data['is_incharge'] = $request->boolean('is_incharge');
        if ($data['is_incharge']) {
            $this->inventory->enforceSingleIncharge($officer->school_id, $data['section_id'] ?? null, $officer->id);
        }
        $officer->update($data);
        return redirect()->route('admin.inventory.sections.index')->with('success', 'Officer updated.');
    }

    public function destroyOfficer($id)
    {
        InvOfficer::where('school_id', $this->schoolId())->findOrFail($id)->delete();
        return redirect()->route('admin.inventory.sections.index')->with('success', 'Officer deleted.');
    }

    public function officerDetails($id)
    {
        $officer = InvOfficer::where('school_id', $this->schoolId())->find($id);
        if (!$officer) {
            return response()->json(['error' => 'Officer not found.'], 404);
        }
        return response()->json($officer->only(['id', 'officer_name', 'officer_id_number', 'phone']));
    }
}
