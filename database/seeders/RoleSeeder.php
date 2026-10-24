<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'super_admin', 'display_name' => 'Super Admin', 'description' => 'Has full access to all features'],
            ['id' => 2, 'name' => 'admin', 'display_name' => 'Admin', 'description' => 'Can manage all features except delete'],
            ['id' => 3, 'name' => 'librarian', 'display_name' => 'Librarian', 'description' => 'Can manage library books'],
            ['id' => 4, 'name' => 'accountant', 'display_name' => 'Accountant', 'description' => 'Can manage payments and fees'],
            ['id' => 5, 'name' => 'teacher', 'display_name' => 'Teacher', 'description' => 'Can manage classes and exam records'],
            ['id' => 6, 'name' => 'student', 'display_name' => 'Student', 'description' => 'Can view own records'],
            ['id' => 7, 'name' => 'parent', 'display_name' => 'Parent', 'description' => 'Can view child records'],
        ]);
    }
}
