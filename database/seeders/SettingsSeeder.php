<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            ['key' => 'school_name', 'value' => 'School Management System'],
            ['key' => 'school_address', 'value' => '123 School Street'],
            ['key' => 'school_phone', 'value' => '+1234567890'],
            ['key' => 'school_email', 'value' => 'info@school.com'],
            ['key' => 'academic_year', 'value' => '2026'],
            ['key' => 'term', 'value' => '1'],
            ['key' => 'currency', 'value' => 'USD'],
            ['key' => 'timezone', 'value' => 'UTC'],
        ]);
    }
}
