<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Government leave categories per Establishments Code Chapter XII.
     * days_allowed = 0 means no fixed annual quota (granted case-by-case).
     * Names are bilingual (English / Sinhala) for display in all locales.
     */
    public function run()
    {
        $types = [
            ['name' => 'Casual Leave / අනියම් නිවාඩු', 'days_allowed' => 21, 'max_consecutive_days' => 6,
                'rule_note' => 'Max 21/year, max 6 at a time, local only. No pro-rata, no carry-forward. (Ch.XII §5)'],
            ['name' => 'Vacation Leave / විවේක නිවාඩු', 'days_allowed' => 24, 'max_consecutive_days' => null,
                'rule_note' => 'Max 24/year (28 for 7-day workers). Carry-forward allowed up to 48 total. (Ch.XII §8)'],
            ['name' => 'Half-Pay Sick Leave / අඩ වැටුප් අසනීප නිවාඩු', 'days_allowed' => 0, 'max_consecutive_days' => null,
                'rule_note' => 'Max 1/6 of service period, max 12 months at a time. Convertible half<->full pay. (Ch.XII §21)'],
            ['name' => 'No-Pay Leave / වැටුප් රහිත නිවාඩු', 'days_allowed' => 90, 'max_consecutive_days' => null,
                'rule_note' => 'Max 3 months for urgent personal/medical reasons, after paid leave exhausted. (Ch.XII §22)'],
            ['name' => 'Maternity Leave (Full Pay) / සම්පූර්ණ වැටුප් සහිත ප්‍රසූති නිවාඩු', 'days_allowed' => 84, 'max_consecutive_days' => null,
                'rule_note' => '84 days per live birth. No duty for 4 weeks after delivery. (Ch.XII §18)'],
            ['name' => 'Maternity Leave (Half Pay) / අඩ වැටුප් සහිත ප්‍රසූති නිවාඩු', 'days_allowed' => 84, 'max_consecutive_days' => null,
                'rule_note' => '84 days after full-pay maternity leave for child care. (Ch.XII §18)'],
            ['name' => 'Maternity Leave (No Pay) / වැටුප් රහිත ප්‍රසූතති නිවාඩු', 'days_allowed' => 84, 'max_consecutive_days' => null,
                'rule_note' => '84 days after half-pay leave for child care. (Ch.XII §18)'],
            ['name' => 'Paternity Leave / පීතෘත්ව නිවාඩු', 'days_allowed' => 3, 'max_consecutive_days' => null,
                'rule_note' => '3 days within 3 months of childbirth. Adoptive fathers: 3 days (Circular 16/2023).'],
            ['name' => 'Study Leave (Full Pay) / සම්පූර්ණ වැටුප් සහිත අධ්‍යයන නිවාඩු', 'days_allowed' => 0, 'max_consecutive_days' => null,
                'rule_note' => 'Bonded compulsory service applies (4x abroad, 2x local). (Ch.XII §14)'],
            ['name' => 'Study Leave (No Pay) / වැටුප් රහිත අධ්‍යයන නිවාඩු', 'days_allowed' => 0, 'max_consecutive_days' => null,
                'rule_note' => 'Max 3 years first, extendable yearly. Permanent officers only. (Ch.XII §15)'],
            ['name' => 'Duty / Exam Leave / රාජකාරි / විභාග නිවාඩු', 'days_allowed' => 0, 'max_consecutive_days' => null,
                'rule_note' => 'First attempt at required exams only; on-duty events. (Ch.XII §19)'],
            ['name' => 'Accident Leave / හදිසි අනතුරු නිවාඩු', 'days_allowed' => 0, 'max_consecutive_days' => null,
                'rule_note' => 'Up to 1 year full pay + 6 months half pay on medical board advice. (Ch.XII §9)'],
            ['name' => 'Special Leave / විශේෂ නිවාඩු', 'days_allowed' => 0, 'max_consecutive_days' => null,
                'rule_note' => 'Floods, disasters, elections, religious duties, sports etc. by circular. (Ch.XII §12)'],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(
                ['name' => $type['name']],
                ['days_allowed' => $type['days_allowed'], 'max_consecutive_days' => $type['max_consecutive_days'], 'rule_note' => $type['rule_note']]
            );
        }
    }
}
