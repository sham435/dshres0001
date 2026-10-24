<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvCheckItem extends Model
{
    protected $table = 'inv_check_items';

    protected $fillable = [
        'session_id', 'item_id', 'item_name', 'inventory_number', 'book_balance',
        'real_balance', 'surplus', 'deficiency', 'remarks', 'checked_status', 'checked_at',
    ];

    protected $casts = ['checked_at' => 'datetime'];

    public const CHECKED_STATUSES = ['pending', 'checked', 'verified'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(InvCheckSession::class, 'session_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InvItem::class, 'item_id');
    }
}
