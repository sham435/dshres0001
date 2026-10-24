<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'item_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'inventory_number' => 'required|string|max:100|unique:inv_items,inventory_number',
            'serial_number' => 'nullable|string|max:100',
            'section_id' => 'nullable|exists:inv_sections,id',
            'book_number' => 'required|string|max:100',
            'incharge_officer_id' => 'nullable|exists:inv_officers,id',
            'received_date' => 'nullable|date',
            'received_from' => 'nullable|string|max:255',
            'is_transferred' => 'nullable|boolean',
            'transferred_date' => 'nullable|date',
            'borrowed_officer' => 'nullable|string|max:255',
            'condition_status' => 'required|in:working,good,not_working,not_usable',
            'item_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ];
    }
}
