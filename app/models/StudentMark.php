<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMark extends Model
{
    protected $fillable = [
        'student_name', 'admission_no', 'gender', 'grade', 'section',
        'subject', 'mark', 'grade_letter', 'term', 'year', 'school', 'sheet_index'
    ];

    protected $casts = [
        'mark' => 'decimal:1',
        'year' => 'integer',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, fn($q, $s) =>
            $q->where('student_name', 'like', "%{$s}%")
              ->orWhere('admission_no', 'like', "%{$s}%")
        );
        $query->when($filters['grade'] ?? null, fn($q, $v) =>
            $q->where('grade', $v)
        );
        $query->when($filters['section'] ?? null, fn($q, $v) =>
            $q->where('section', $v)
        );
        $query->when($filters['subject'] ?? null, fn($q, $v) =>
            $q->where('subject', $v)
        );
    }
}
