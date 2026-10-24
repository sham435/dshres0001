<?php

namespace App\Services;

use ZipArchive;
use SimpleXMLElement;
use App\Models\StudentMark;

class MarkImportService
{
    protected array $results = [
        'imported' => 0,
        'skipped' => 0,
        'errors' => [],
        'sheets_processed' => 0,
    ];

    protected string $term = '2nd Term';
    protected int $year = 2022;
    protected string $school = 'A/NACHCHADUWA MUSLIM MAHA VIDYALAYA';

    public function import(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            $this->results['errors'][] = "Cannot open file";
            return $this->results;
        }

        $sharedStr = $zip->getFromName('xl/sharedStrings.xml');
        $strings = [];
        if ($sharedStr) {
            $xml = new SimpleXMLElement($sharedStr);
            foreach ($xml->si as $si) {
                $strings[] = (string)$si->t;
            }
        }

        for ($s = 1; $s <= 25; $s++) {
            $sheetXml = $zip->getFromName("xl/worksheets/sheet{$s}.xml");
            if (!$sheetXml) continue;

            $this->processSheetXml($sheetXml, $strings, $s);
        }

        $zip->close();
        return $this->results;
    }

    protected function processSheetXml(string $sheetXml, array $strings, int $sheetIndex): void
    {
        $xml = new SimpleXMLElement($sheetXml);
        $rows = $xml->sheetData->row;
        $rowCount = count($rows);
        if ($rowCount < 5) return;

        // Read first row to detect grade/section
        $firstRow = $this->parseRow($rows[0], $strings);
        $row1Str = implode(' ', $firstRow);

        if (!preg_match('/ශ්‍රේණිය\s*[-–]\s*(\d+)\s*([A-Z])/u', $row1Str, $m)) {
            return;
        }
        $grade = $m[1];
        $section = $m[2];

        // Build subject mapping from rows 3-4 (0-indexed: 2 and 3)
        $headerRow3 = $rowCount > 2 ? $this->parseRow($rows[2], $strings) : [];
        $headerRow4 = $rowCount > 3 ? $this->parseRow($rows[3], $strings) : [];

        $subjects = $this->detectSubjects($headerRow3, $headerRow4, (int)$grade);
        if (empty($subjects)) return;

        $this->results['sheets_processed']++;

        // Process data rows (starting from index 4 = row 5 in Excel)
        for ($i = 4; $i < $rowCount; $i++) {
            $row = $this->parseRow($rows[$i], $strings);
            if (empty($row)) continue;

            $studentName = trim((string)($row[3] ?? ''));
            $admissionNo = trim((string)($row[1] ?? ''));

            if (empty($studentName) && empty($admissionNo)) continue;
            if (is_numeric($studentName)) continue;
            if (mb_strlen($studentName, 'UTF-8') > 20 && preg_match('/[සෞන්දර්යඅනුලකුණු]/u', mb_substr($studentName, 0, 1))) continue;
            if (preg_match('/^(අනු|ශිෂ්ය|සමත්|මුළු|පංතියේ|සාමාන්ය)/u', $studentName)) continue;
            if (preg_match('/[ප්‍රතිශතය]/u', $admissionNo)) continue;

            $gender = mb_substr(trim((string)($row[2] ?? '')), 0, 10, 'UTF-8');

            foreach ($subjects as $colIdx => $subjectName) {
                $rawMark = $row[$colIdx] ?? null;
                if ($rawMark === null || $rawMark === '' || $rawMark === '-') continue;
                if (!is_numeric($rawMark)) continue;

                $mark = (float) $rawMark;
                if ($mark < 0 || $mark > 100) continue;

                $admNo = $admissionNo ?: ('U-' . $grade . $section . '-' . $i);
                $admNo = mb_substr($admNo, 0, 50, 'UTF-8');

                try {
                    StudentMark::updateOrCreate(
                        [
                            'admission_no' => $admNo,
                            'subject' => $subjectName,
                            'term' => $this->term,
                            'year' => $this->year,
                            'grade' => $grade,
                            'section' => $section,
                        ],
                        [
                            'student_name' => $studentName,
                            'gender' => $gender,
                            'mark' => $mark,
                            'grade_letter' => $this->markToGrade($mark, $grade),
                            'school' => $this->school,
                            'sheet_index' => $sheetIndex,
                        ]
                    );
                    $this->results['imported']++;
                } catch (\Exception $e) {
                    $this->results['errors'][] = "Sheet{$sheetIndex} Row{$i}: {$e->getMessage()}";
                }
            }
        }
    }

    protected function parseRow(SimpleXMLElement $row, array $strings): array
    {
        $data = [];
        foreach ($row->c as $cell) {
            $ref = (string)$cell['r'];
            $type = (string)$cell['t'];
            $val = (string)$cell->v;
            if ($type === 's' && $val !== '') {
                $idx = (int)$val;
                $val = $strings[$idx] ?? '';
            }
            $col = preg_replace('/[0-9]/', '', $ref);
            $colIdx = $this->colToIndex($col);
            $data[$colIdx] = $val;
        }
        ksort($data);
        return array_values($data);
    }

    protected function colToIndex(string $col): int
    {
        $col = strtoupper($col);
        $result = 0;
        for ($i = 0; $i < strlen($col); $i++) {
            $result = $result * 26 + ord($col[$i]) - ord('A') + 1;
        }
        return $result - 1;
    }

    protected function detectSubjects(array $row3, array $row4, int $gradeNum): array
    {
        $subjects = [];

        // Primary (Grades 3-5): subjects in cols E-J (index 4-9)
        if ($gradeNum <= 5) {
            $subjects = [
                4 => 'Mother Tongue',
                5 => 'Religion (Islam)',
                6 => 'English',
                7 => 'Mathematics',
                8 => 'Environment Studies',
                9 => 'Tamil',
            ];
        }
        // Junior Secondary (Grades 6-9)
        elseif ($gradeNum <= 9) {
            $subjects = [
                4 => 'Mother Tongue',
                5 => 'Religion (Islam)',
                6 => 'English',
                7 => 'Mathematics',
                8 => 'Science',
                9 => 'History',
            ];
        }
        // O/L (Grades 10-11)
        elseif ($gradeNum <= 11) {
            $subjects = [
                4 => 'Mother Tongue',
                5 => 'Religion',
                6 => 'English',
                7 => 'Mathematics',
                8 => 'History',
                9 => 'Science',
            ];
        }
        // A/L (Grades 12-13)
        else {
            $subjects = [
                11 => 'Business Communication',
                12 => 'History',
                13 => 'Sinhala',
                14 => 'Political Science',
                15 => 'Media Studies',
                16 => 'Art',
                17 => 'Drama',
                18 => 'Music',
            ];
        }

        return $subjects;
    }

    protected function markToGrade(float $mark, string $grade): string
    {
        $g = (int) $grade;
        if ($g >= 12) {
            if ($mark >= 75) return 'A';
            if ($mark >= 65) return 'B';
            if ($mark >= 55) return 'C';
            if ($mark >= 40) return 'S';
            return 'W';
        }
        if ($g >= 10) {
            if ($mark >= 75) return 'A';
            if ($mark >= 65) return 'B';
            if ($mark >= 55) return 'C';
            if ($mark >= 35) return 'S';
            return 'W';
        }
        if ($mark >= 75) return 'A';
        if ($mark >= 65) return 'B';
        if ($mark >= 55) return 'C';
        if ($mark >= 35) return 'S';
        return 'W';
    }
}
