<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\SchoolProfileRequest;
use App\Services\InventoryService;

class InventorySchoolController extends Controller
{
    private InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->middleware('auth');
        $this->inventory = $inventory;
    }

    public function edit()
    {
        return view('admin.inventory.school.edit', ['school' => $this->inventory->school()]);
    }

    public function update(SchoolProfileRequest $request)
    {
        $school = $this->inventory->school();
        $data = $request->validated();

        $logoPath = $school->logo_path;
        if ($request->hasFile('logo')) {
            $this->inventory->deleteImage($school->logo_path);
            $logoPath = $request->file('logo')->store('inventory/logos', 'public');
        }

        $school->update([
            'school_name' => $data['school_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'principal_name' => $data['principal_name'],
            'principal_phone' => $data['principal_phone'],
            'inventory_officer_name' => $data['inventory_officer_name'],
            'inventory_officer_phone' => $data['inventory_officer_phone'],
            'username' => $data['username'],
            'password' => !empty($data['new_password']) ? bcrypt($data['new_password']) : $school->password,
            'logo_path' => $logoPath,
        ]);

        return redirect()->route('admin.inventory.school.edit')->with('success', 'School profile updated.');
    }
}
