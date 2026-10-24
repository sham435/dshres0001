<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'section_name' => 'required|string|max:100',
            'section_type' => 'required|in:Primary,Secondary,AL,Other',
            'main_section' => 'nullable|string|max:100',
        ];
    }
}
