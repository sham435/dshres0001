<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'class_id', 'exam_date', 'description'];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'exam_id');
    }
}
