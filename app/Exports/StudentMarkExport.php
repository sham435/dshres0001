<?php

namespace App\Exports;

use App\Models\StudentMark;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\App;

class StudentMarkExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = StudentMark::query();
        if (!empty($this->filters['grade'])) $query->where('grade', $this->filters['grade']);
        if (!empty($this->filters['section'])) $query->where('section', $this->filters['section']);
        if (!empty($this->filters['subject'])) $query->where('subject', $this->filters['subject']);
        return $query;
    }

    public function headings(): array
    {
        $locale = App::getLocale();
        if ($locale === 'ta') {
            return ['மாணவரின் பெயர்', 'சேர்க்கை எண்', 'வகுப்பு', 'பிரிவு', 'பாடம்', 'மதிப்பெண்', 'தர நிலை', 'தவணை', 'ஆண்டு', 'பாடசாலை'];
        }
        if ($locale === 'si') {
            return ['ශිෂ්‍යයාගේ නම', 'ඇතුළත් කිරීමේ අංකය', 'ශ්‍රේණිය', 'කොටස', 'විෂය', 'ලකුණ', 'ශ්‍රේණි අකුර', 'වාරය', 'වර්ෂය', 'පාසල'];
        }
        return ['Student Name', 'Admission No', 'Grade', 'Section', 'Subject', 'Mark', 'Grade Letter', 'Term', 'Year', 'School'];
    }

    public function map($mark): array
    {
        return [
            $mark->student_name,
            $mark->admission_no,
            $mark->grade,
            $mark->section,
            $mark->subject,
            $mark->mark,
            $mark->grade_letter,
            $mark->term,
            $mark->year,
            $mark->school,
        ];
    }
}
