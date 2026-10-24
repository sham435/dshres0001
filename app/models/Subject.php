<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'class_id', 'description'];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'subject_id');
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'subject_id');
    }

    public function studyMaterials(): HasMany
    {
        return $this->hasMany(StudyMaterial::class, 'subject_id');
    }
}
