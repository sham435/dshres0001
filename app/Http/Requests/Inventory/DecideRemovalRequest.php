<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class DecideRemovalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'decision' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string',
            'disposal_method' => 'nullable|string|max:100',
            'approved_by' => 'nullable|string|max:100',
        ];
    }
}
