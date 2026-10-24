<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CheckCountsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'items' => 'required|array',
            'items.*.real_balance' => 'required|integer|min:0',
            'items.*.remarks' => 'nullable|string',
            'items.*.checked_status' => 'nullable|in:pending,checked,verified',
        ];
    }
}
