<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $class = Classes::create([
            'name' => 'Grade 13 (A/L) - Arts Stream',
            'section' => 'Arts',
            'code' => 'AL-ARTS-13',
            'description' => 'GCE Advanced Level Arts Stream for Muslim Schools (1C)'
        ]);

        $subjects = [
            ['name' => 'Islam',                      'code' => 'ISLAM'],
            ['name' => 'Arabic',                     'code' => 'ARAB'],
            ['name' => 'Tamil Literature',            'code' => 'TAMLIT'],
            ['name' => 'Tamil Language',              'code' => 'TAMLANG'],
            ['name' => 'Sinhala Literature',          'code' => 'SINLIT'],
            ['name' => 'Sinhala Language',            'code' => 'SINLANG'],
            ['name' => 'English Literature',          'code' => 'ENGLIT'],
            ['name' => 'Geography',                   'code' => 'GEOG'],
            ['name' => 'History',                     'code' => 'HIST'],
            ['name' => 'Political Science',           'code' => 'POLSCI'],
            ['name' => 'Economics',                   'code' => 'ECON'],
            ['name' => 'Logic & Scientific Method',   'code' => 'LOGIC'],
            ['name' => 'Communication & Media Studies','code' => 'CMS'],
            ['name' => 'Business Studies',            'code' => 'BUSST'],
            ['name' => 'Accounting',                  'code' => 'ACCTG'],
            ['name' => 'Art',                         'code' => 'ART'],
            ['name' => 'Eastern Music',               'code' => 'EMUSIC'],
            ['name' => 'Drama & Theatre',             'code' => 'DRAMA'],
            ['name' => 'Pali',                        'code' => 'PALI'],
            ['name' => 'Sanskrit',                    'code' => 'SANSK'],
            ['name' => 'Hindi',                       'code' => 'HINDI'],
            ['name' => 'French',                      'code' => 'FREN'],
            ['name' => 'German',                      'code' => 'GERM'],
            ['name' => 'General English',             'code' => 'GENENG'],
            ['name' => 'General Knowledge',           'code' => 'GENKNW'],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject['name'],
                'code' => $subject['code'],
                'class_id' => $class->id,
                'description' => 'GCE A/L Arts Stream Subject - ' . $subject['name']
            ]);
        }

        $this->command->info('Created Grade 13 Arts class with ' . count($subjects) . ' subjects.');
    }
}
