<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\Subject;

class Grade1to11Seeder extends Seeder
{
    public function run()
    {
        $grades = [
            1 => ['section' => 'Primary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G1-LANG'],
                ['name' => 'English',                       'code' => 'G1-ENG'],
                ['name' => 'Mathematics',                   'code' => 'G1-MATH'],
                ['name' => 'Environmental Studies',          'code' => 'G1-ENV'],
                ['name' => 'Islam',                          'code' => 'G1-ISL'],
                ['name' => 'Aesthetic Education',            'code' => 'G1-AESTH'],
                ['name' => 'Physical Education',             'code' => 'G1-PE'],
                ['name' => 'Health',                         'code' => 'G1-HEALTH'],
            ]],
            2 => ['section' => 'Primary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G2-LANG'],
                ['name' => 'English',                       'code' => 'G2-ENG'],
                ['name' => 'Mathematics',                   'code' => 'G2-MATH'],
                ['name' => 'Environmental Studies',          'code' => 'G2-ENV'],
                ['name' => 'Islam',                          'code' => 'G2-ISL'],
                ['name' => 'Aesthetic Education',            'code' => 'G2-AESTH'],
                ['name' => 'Physical Education',             'code' => 'G2-PE'],
                ['name' => 'Health',                         'code' => 'G2-HEALTH'],
            ]],
            3 => ['section' => 'Primary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G3-LANG'],
                ['name' => 'English',                       'code' => 'G3-ENG'],
                ['name' => 'Mathematics',                   'code' => 'G3-MATH'],
                ['name' => 'Environmental Studies',          'code' => 'G3-ENV'],
                ['name' => 'Islam',                          'code' => 'G3-ISL'],
                ['name' => 'Aesthetic Education',            'code' => 'G3-AESTH'],
                ['name' => 'Physical Education',             'code' => 'G3-PE'],
                ['name' => 'Health',                         'code' => 'G3-HEALTH'],
            ]],
            4 => ['section' => 'Primary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G4-LANG'],
                ['name' => 'English',                       'code' => 'G4-ENG'],
                ['name' => 'Mathematics',                   'code' => 'G4-MATH'],
                ['name' => 'Environmental Studies',          'code' => 'G4-ENV'],
                ['name' => 'Islam',                          'code' => 'G4-ISL'],
                ['name' => 'Aesthetic Education',            'code' => 'G4-AESTH'],
                ['name' => 'Physical Education',             'code' => 'G4-PE'],
                ['name' => 'Health',                         'code' => 'G4-HEALTH'],
            ]],
            5 => ['section' => 'Primary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G5-LANG'],
                ['name' => 'English',                       'code' => 'G5-ENG'],
                ['name' => 'Mathematics',                   'code' => 'G5-MATH'],
                ['name' => 'Environmental Studies',          'code' => 'G5-ENV'],
                ['name' => 'Islam',                          'code' => 'G5-ISL'],
                ['name' => 'Aesthetic Education',            'code' => 'G5-AESTH'],
                ['name' => 'Physical Education',             'code' => 'G5-PE'],
                ['name' => 'Health',                         'code' => 'G5-HEALTH'],
            ]],
            6 => ['section' => 'Junior Secondary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G6-LANG'],
                ['name' => 'Second Language (Tamil/Sinhala)','code' => 'G6-LANG2'],
                ['name' => 'English',                        'code' => 'G6-ENG'],
                ['name' => 'Mathematics',                    'code' => 'G6-MATH'],
                ['name' => 'Science',                        'code' => 'G6-SCI'],
                ['name' => 'History',                        'code' => 'G6-HIST'],
                ['name' => 'Geography',                      'code' => 'G6-GEO'],
                ['name' => 'Islam',                          'code' => 'G6-ISL'],
                ['name' => 'Arabic',                         'code' => 'G6-ARAB'],
                ['name' => 'Aesthetic (Art/Music/Dance)',    'code' => 'G6-AESTH'],
                ['name' => 'Health & Physical Education',    'code' => 'G6-HPE'],
                ['name' => 'Practical & Technical Skills',   'code' => 'G6-PTS'],
                ['name' => 'Life Competencies',              'code' => 'G6-LC'],
            ]],
            7 => ['section' => 'Junior Secondary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G7-LANG'],
                ['name' => 'Second Language (Tamil/Sinhala)','code' => 'G7-LANG2'],
                ['name' => 'English',                        'code' => 'G7-ENG'],
                ['name' => 'Mathematics',                    'code' => 'G7-MATH'],
                ['name' => 'Science',                        'code' => 'G7-SCI'],
                ['name' => 'History',                        'code' => 'G7-HIST'],
                ['name' => 'Geography',                      'code' => 'G7-GEO'],
                ['name' => 'Islam',                          'code' => 'G7-ISL'],
                ['name' => 'Arabic',                         'code' => 'G7-ARAB'],
                ['name' => 'Aesthetic (Art/Music/Dance)',    'code' => 'G7-AESTH'],
                ['name' => 'Health & Physical Education',    'code' => 'G7-HPE'],
                ['name' => 'Practical & Technical Skills',   'code' => 'G7-PTS'],
                ['name' => 'Life Competencies',              'code' => 'G7-LC'],
            ]],
            8 => ['section' => 'Junior Secondary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G8-LANG'],
                ['name' => 'Second Language (Tamil/Sinhala)','code' => 'G8-LANG2'],
                ['name' => 'English',                        'code' => 'G8-ENG'],
                ['name' => 'Mathematics',                    'code' => 'G8-MATH'],
                ['name' => 'Science',                        'code' => 'G8-SCI'],
                ['name' => 'History',                        'code' => 'G8-HIST'],
                ['name' => 'Geography',                      'code' => 'G8-GEO'],
                ['name' => 'Islam',                          'code' => 'G8-ISL'],
                ['name' => 'Arabic',                         'code' => 'G8-ARAB'],
                ['name' => 'Aesthetic (Art/Music/Dance)',    'code' => 'G8-AESTH'],
                ['name' => 'Health & Physical Education',    'code' => 'G8-HPE'],
                ['name' => 'Practical & Technical Skills',   'code' => 'G8-PTS'],
                ['name' => 'Life Competencies',              'code' => 'G8-LC'],
            ]],
            9 => ['section' => 'Junior Secondary', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G9-LANG'],
                ['name' => 'Second Language (Tamil/Sinhala)','code' => 'G9-LANG2'],
                ['name' => 'English',                        'code' => 'G9-ENG'],
                ['name' => 'Mathematics',                    'code' => 'G9-MATH'],
                ['name' => 'Science',                        'code' => 'G9-SCI'],
                ['name' => 'History',                        'code' => 'G9-HIST'],
                ['name' => 'Geography',                      'code' => 'G9-GEO'],
                ['name' => 'Islam',                          'code' => 'G9-ISL'],
                ['name' => 'Arabic',                         'code' => 'G9-ARAB'],
                ['name' => 'Aesthetic (Art/Music/Dance)',    'code' => 'G9-AESTH'],
                ['name' => 'Health & Physical Education',    'code' => 'G9-HPE'],
                ['name' => 'Practical & Technical Skills',   'code' => 'G9-PTS'],
                ['name' => 'Life Competencies',              'code' => 'G9-LC'],
            ]],
            10 => ['section' => 'Senior Secondary (O/L)', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G10-LANG'],
                ['name' => 'Second Language (Tamil/Sinhala)','code' => 'G10-LANG2'],
                ['name' => 'English',                        'code' => 'G10-ENG'],
                ['name' => 'Mathematics',                    'code' => 'G10-MATH'],
                ['name' => 'Science',                        'code' => 'G10-SCI'],
                ['name' => 'History',                        'code' => 'G10-HIST'],
                ['name' => 'Geography',                      'code' => 'G10-GEO'],
                ['name' => 'Islam',                          'code' => 'G10-ISL'],
                ['name' => 'Arabic',                         'code' => 'G10-ARAB'],
                ['name' => 'Aesthetic (Art/Music/Dance)',    'code' => 'G10-AESTH'],
                ['name' => 'Health & Physical Education',    'code' => 'G10-HPE'],
                ['name' => 'Practical & Technical Skills',   'code' => 'G10-PTS'],
                ['name' => 'Life Competencies',              'code' => 'G10-LC'],
                ['name' => 'Citizenship Education',          'code' => 'G10-CITIZEN'],
            ]],
            11 => ['section' => 'Senior Secondary (O/L)', 'subjects' => [
                ['name' => 'First Language (Sinhala/Tamil)', 'code' => 'G11-LANG'],
                ['name' => 'Second Language (Tamil/Sinhala)','code' => 'G11-LANG2'],
                ['name' => 'English',                        'code' => 'G11-ENG'],
                ['name' => 'Mathematics',                    'code' => 'G11-MATH'],
                ['name' => 'Science',                        'code' => 'G11-SCI'],
                ['name' => 'History',                        'code' => 'G11-HIST'],
                ['name' => 'Geography',                      'code' => 'G11-GEO'],
                ['name' => 'Islam',                          'code' => 'G11-ISL'],
                ['name' => 'Arabic',                         'code' => 'G11-ARAB'],
                ['name' => 'Aesthetic (Art/Music/Dance)',    'code' => 'G11-AESTH'],
                ['name' => 'Health & Physical Education',    'code' => 'G11-HPE'],
                ['name' => 'Practical & Technical Skills',   'code' => 'G11-PTS'],
                ['name' => 'Life Competencies',              'code' => 'G11-LC'],
                ['name' => 'Citizenship Education',          'code' => 'G11-CITIZEN'],
            ]],
        ];

        foreach ($grades as $grade => $data) {
            $class = Classes::create([
                'name' => "Grade {$grade}",
                'section' => $data['section'],
                'code' => "GRADE-{$grade}",
                'description' => "Sri Lankan Government School - Grade {$grade} ({$data['section']})"
            ]);

            foreach ($data['subjects'] as $subject) {
                Subject::create([
                    'name' => $subject['name'],
                    'code' => $subject['code'],
                    'class_id' => $class->id,
                    'description' => "Grade {$grade} - {$subject['name']}"
                ]);
            }
        }

        $this->command->info('Created Grade 1-11 classes with subjects.');
    }
}
