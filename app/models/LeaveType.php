<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $table = 'leave_types';

    protected $fillable = ['name', 'days_allowed', 'max_consecutive_days', 'rule_note'];

    public function applications(): HasMany
    {
        return $this->hasMany(LeaveApplication::class, 'leave_type_id');
    }
}
