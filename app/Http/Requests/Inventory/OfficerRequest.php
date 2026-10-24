<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class OfficerRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $id = $this->route('id');
        $unique = 'unique:inv_officers,officer_id_number' . ($id ? ',' . $id : '');
        return [
            'officer_name' => 'required|string|max:100',
            'officer_id_number' => 'required|string|max:50|' . $unique,
            'phone' => 'required|string|max:20',
            'section_id' => 'nullable|exists:inv_sections,id',
            'is_incharge' => 'nullable|boolean',
        ];
    }
}
