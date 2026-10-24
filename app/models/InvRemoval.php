<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvRemoval extends Model
{
    protected $table = 'inv_removals';

    protected $fillable = [
        'original_item_id', 'school_id', 'item_name', 'brand_name', 'inventory_number',
        'serial_number', 'section_id', 'book_number', 'incharge_officer_id', 'condition_status',
        'recommendation_reason', 'recommended_by', 'recommendation_date', 'status', 'notes',
    ];

    protected $casts = ['recommendation_date' => 'datetime'];

    public const STATUSES = ['pending', 'approved', 'rejected'];
}
