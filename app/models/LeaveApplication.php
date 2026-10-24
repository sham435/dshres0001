<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApplication extends Model
{
    protected $table = 'leave_applications';

    protected $fillable = ['user_id', 'leave_type_id', 'from_date', 'to_date', 'reason', 'is_abroad', 'status', 'reviewed_by', 'reviewed_at'];

    protected $casts = ['from_date' => 'date', 'to_date' => 'date', 'reviewed_at' => 'datetime', 'is_abroad' => 'boolean'];

    public const STATUSES = ['pending', 'approved', 'rejected'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function days(): int
    {
        if (!$this->from_date || !$this->to_date) {
            return 0;
        }
        return $this->from_date->diffInDays($this->to_date) + 1;
    }
}
