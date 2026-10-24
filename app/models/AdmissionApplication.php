<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionApplication extends Model
{
    protected $guarded = ['id', 'status'];
    protected $casts = ['dob' => 'date', 'attended_school' => 'boolean'];

    public const STATUSES = ['pending', 'approved', 'rejected'];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function scopeWithStatus($query, ?string $status)
    {
        if (in_array($status, self::STATUSES)) {
            return $query->where('status', $status);
        }
        return $query;
    }
}
