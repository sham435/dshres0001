<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvRemovedArchive extends Model
{
    protected $table = 'inv_removed_archive';

    protected $fillable = [
        'original_item_id', 'school_id', 'item_name', 'brand_name', 'inventory_number',
        'serial_number', 'section_id', 'section_name', 'book_number', 'incharge_officer_id',
        'incharge_officer_name', 'condition_status', 'removal_reason', 'removed_by',
        'removal_date', 'approval_status', 'approved_by', 'disposal_method', 'notes',
        'original_created_at', 'original_updated_at', 'archived_at',
    ];

    protected $casts = [
        'removal_date' => 'datetime',
        'archived_at' => 'datetime',
        'original_created_at' => 'datetime',
        'original_updated_at' => 'datetime',
    ];
}
