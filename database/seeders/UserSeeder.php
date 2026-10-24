<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            ['name' => 'Super Admin', 'username' => 'cj', 'email' => 'cj@cj.com', 'password' => Hash::make('cj'), 'role_id' => 1, 'phone' => '1234567890', 'address' => 'Admin Address', 'gender' => 'male'],
            ['name' => 'Admin', 'username' => 'admin', 'email' => 'admin@admin.com', 'password' => Hash::make('cj'), 'role_id' => 2, 'phone' => '1234567891', 'address' => 'Admin Address', 'gender' => 'male'],
            ['name' => 'Teacher', 'username' => 'teacher', 'email' => 'teacher@teacher.com', 'password' => Hash::make('cj'), 'role_id' => 5, 'phone' => '1234567892', 'address' => 'Teacher Address', 'gender' => 'male'],
            ['name' => 'Parent', 'username' => 'parent', 'email' => 'parent@parent.com', 'password' => Hash::make('cj'), 'role_id' => 7, 'phone' => '1234567893', 'address' => 'Parent Address', 'gender' => 'male'],
            ['name' => 'Accountant', 'username' => 'accountant', 'email' => 'accountant@accountant.com', 'password' => Hash::make('cj'), 'role_id' => 4, 'phone' => '1234567894', 'address' => 'Accountant Address', 'gender' => 'male'],
            ['name' => 'Librarian', 'username' => 'librarian', 'email' => 'librarian@librarian.com', 'password' => Hash::make('cj'), 'role_id' => 3, 'phone' => '1234567896', 'address' => 'Librarian Address', 'gender' => 'male'],
            ['name' => 'Student', 'username' => 'student', 'email' => 'student@student.com', 'password' => Hash::make('cj'), 'role_id' => 6, 'phone' => '1234567895', 'address' => 'Student Address', 'gender' => 'male'],
        ]);
    }
}
