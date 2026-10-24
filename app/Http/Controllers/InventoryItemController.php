<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\StoreItemRequest;
use App\Http\Requests\Inventory\UpdateItemRequest;
use App\Models\InvItem;
use App\Models\InvOfficer;
use App\Models\InvSection;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
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

    private function formData(int $schoolId): array
    {
        return [
            'sections' => InvSection::where('school_id', $schoolId)->orderBy('section_name')->get(),
            'officers' => InvOfficer::where('school_id', $schoolId)->orderBy('officer_name')->get(),
        ];
    }

    public function index(Request $request)
    {
        $items = InvItem::forSchool($this->schoolId())
            ->with(['section', 'incharge'])
            ->filtered($request->only(['search', 'section', 'condition', 'status']))
            ->latest()->paginate(15)->withQueryString();
        $sections = InvSection::where('school_id', $this->schoolId())->orderBy('section_name')->get();
        return view('admin.inventory.items.index', compact('items', 'sections'));
    }

    public function create()
    {
        return view('admin.inventory.items.create', $this->formData($this->schoolId()));
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        $data['school_id'] = $this->schoolId();
        $data['is_transferred'] = $request->boolean('is_transferred');
        $data['image_path'] = $this->inventory->storeItemImage($request->file('item_image'));
        unset($data['item_image']);

        InvItem::create($data);
        return redirect()->route('admin.inventory.items.index')->with('success', 'Item added successfully.');
    }

    public function show($id)
    {
        $item = InvItem::forSchool($this->schoolId())->with(['section', 'incharge'])->findOrFail($id);
        return view('admin.inventory.items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = InvItem::forSchool($this->schoolId())->findOrFail($id);
        return view('admin.inventory.items.edit', array_merge(['item' => $item], $this->formData($this->schoolId())));
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $item = InvItem::forSchool($this->schoolId())->findOrFail($id);
        $data = $request->validated();
        $data['is_transferred'] = $request->boolean('is_transferred');
        $data['image_path'] = $this->inventory->replaceItemImage($item, $request->file('item_image'), $request->input('remove_image') == '1');
        unset($data['item_image'], $data['remove_image']);

        $item->update($data);
        return redirect()->route('admin.inventory.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = InvItem::forSchool($this->schoolId())->findOrFail($id);
        $this->inventory->deleteImage($item->image_path);
        $item->delete();
        return redirect()->route('admin.inventory.items.index')->with('success', 'Item deleted successfully.');
    }

    public function quickUpdate(Request $request, $id)
    {
        $item = InvItem::forSchool($this->schoolId())->findOrFail($id);
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'condition_status' => 'required|in:working,good,not_working,not_usable',
            'status' => 'required|in:available,borrowed,transferred',
            'borrowed_officer' => 'nullable|string|max:255',
            'transferred_date' => 'nullable|date',
        ]);

        $item->update([
            'item_name' => $validated['item_name'],
            'condition_status' => $validated['condition_status'],
            'is_transferred' => $validated['status'] === 'transferred',
            'borrowed_officer' => $validated['status'] === 'borrowed' ? ($validated['borrowed_officer'] ?? null) : null,
            'transferred_date' => $validated['status'] === 'transferred' ? ($validated['transferred_date'] ?? null) : null,
        ]);

        return response()->json(['success' => true, 'message' => 'Item updated.']);
    }

    public function map(Request $request)
    {
        $schoolId = $this->schoolId();
        $sections = InvSection::where('school_id', $schoolId)->withCount('items')->orderBy('section_name')->get();
        $selectedSection = null;
        $sectionItems = collect();
        if ($request->filled('section_id')) {
            $selectedSection = InvSection::where('school_id', $schoolId)->findOrFail($request->section_id);
            $sectionItems = InvItem::forSchool($schoolId)->where('section_id', $selectedSection->id)
                ->with('incharge')->orderBy('item_name')->get();
        }
        return view('admin.inventory.items.map', compact('sections', 'selectedSection', 'sectionItems'));
    }
}
