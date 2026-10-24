<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvCheckSession extends Model
{
    protected $table = 'inv_check_sessions';

    protected $fillable = [
        'school_id', 'session_name', 'check_date', 'conducted_by', 'notes', 'status',
    ];

    protected $casts = ['check_date' => 'date'];

    public const STATUSES = ['active', 'completed', 'cancelled'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(InvSchool::class, 'school_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvCheckItem::class, 'session_id');
    }
}
