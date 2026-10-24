<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    protected $table = 'homework';

    protected $fillable = ['teacher_id', 'subject_id', 'class_id', 'title', 'description', 'due_date', 'file_path'];

    protected $casts = ['due_date' => 'date'];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(HomeworkSubmission::class, 'homework_id');
    }

    public function submissionFor(int $studentId): ?HomeworkSubmission
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
}
