<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CheckSessionRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'session_name' => 'required|string|max:255',
            'check_date' => 'required|date',
            'conducted_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'section_id' => 'nullable|exists:inv_sections,id',
        ];
    }
}
