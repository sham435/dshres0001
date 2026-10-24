<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class RecommendRemovalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'item_id' => 'required|exists:inv_items,id',
            'reason' => 'required|string',
            'recommended_by' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
