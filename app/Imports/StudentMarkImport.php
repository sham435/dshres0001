<?php

namespace App\Imports;

use App\Models\StudentMark;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\Rule;

class StudentMarkImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithChunkReading
{
    use Importable;

    protected string $term = '2nd Term';
    protected int $year = 2022;
    protected string $school = 'A/NACHCHADUWA MUSLIM MAHA VIDYALAYA';
    protected string $grade;
    protected string $section;

    public function __construct(string $grade = '', string $section = '')
    {
        $this->grade = $grade;
        $this->section = $section;
    }

    public function model(array $row)
    {
        $studentName = trim($row['student_name'] ?? $row['studentname'] ?? $row['name'] ?? '');
        $admissionNo = trim($row['admission_no'] ?? $row['admissionno'] ?? $row['admission'] ?? $row['id'] ?? '');
        $subject = trim($row['subject'] ?? $row['subject_name'] ?? $row['subjectname'] ?? '');
        $mark = (float) ($row['mark'] ?? $row['marks'] ?? $row['score'] ?? 0);

        if (empty($studentName) || empty($subject)) {
            return null;
        }

        $admNo = $admissionNo ?: ('U-' . $this->grade . $this->section . '-' . uniqid());
        $admNo = mb_substr($admNo, 0, 50, 'UTF-8');

        return StudentMark::updateOrCreate(
            [
                'admission_no' => $admNo,
                'subject' => $subject,
                'term' => $this->term,
                'year' => $this->year,
                'grade' => $this->grade,
                'section' => $this->section,
            ],
            [
                'student_name' => $studentName,
                'gender' => mb_substr(trim($row['gender'] ?? ''), 0, 10, 'UTF-8'),
                'mark' => $mark,
                'grade_letter' => $this->markToGrade($mark),
                'school' => $this->school,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'student_name' => 'required|string|max:200',
            'mark' => 'nullable|numeric|min:0|max:100',
            'marks' => 'nullable|numeric|min:0|max:100',
            'score' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function onFailure(Failure ...$failures)
    {
        // Failures are collected automatically by Maatwebsite
    }

    protected function markToGrade(float $mark): string
    {
        if ($mark >= 75) return 'A';
        if ($mark >= 65) return 'B';
        if ($mark >= 55) return 'C';
        if ($mark >= 35) return 'S';
        return 'W';
    }
}
