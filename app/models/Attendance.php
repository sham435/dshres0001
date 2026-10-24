<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = ['student_id', 'class_id', 'date', 'status', 'remarks', 'marked_by'];

    protected $casts = ['date' => 'date'];

    public const STATUSES = ['present', 'absent', 'late', 'half_day'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function marker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeOfClass($query, $classId)
    {
        if (empty($classId)) {
            return $query;
        }
        return $query->where('class_id', $classId);
    }
}
