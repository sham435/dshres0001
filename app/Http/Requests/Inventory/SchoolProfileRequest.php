<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\InvSchool;

class SchoolProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $school = InvSchool::first();
        return [
            'school_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'principal_name' => 'required|string|max:100',
            'principal_phone' => 'required|string|max:20',
            'inventory_officer_name' => 'required|string|max:100',
            'inventory_officer_phone' => 'required|string|max:20',
            'username' => 'required|string|max:50|unique:inv_schools,username,' . optional($school)->id,
            'new_password' => 'nullable|string|min:5|confirmed',
            'logo' => 'nullable|image|max:5120',
        ];
    }
}
