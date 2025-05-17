<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Counselor;
use App\Models\User;

class CounselorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $counselors = [
            [
                'name' => 'Jesse Thomas',
                'email' => 'jt@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'counselor',
                'specialization' => 'Academic Guidance',
                'experience' => '12 Years',
                'office_number' => '4853966',
                'status' => 'Available',
            ],
            [
                'name' => 'Mrs. Daniella Phillips',
                'email' => 'daniPhil@example.com',
                'password' => bcrypt('password'),
                'role' => 'counselor',
                'specialization' => 'Psychology',
                'experience' => '12 Years',
                'office_number' => '4853967',
                'status' => 'Available',
            ],
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => bcrypt('password'),
                'role' => 'counselor',
                'specialization' => 'Career Counseling',
                'experience' => '10 Years',
                'office_number' => '4853968',
                'status' => 'Busy',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'password' => bcrypt('password'),
                'role' => 'counselor',
                'specialization' => 'Mental Health',
                'experience' => '8 Years',
                'office_number' => '4853969',
                'status' => 'Busy',
            ],
        ];

        foreach ($counselors as $counselor) {
            $user = User::create([
                'name' => $counselor['name'],
                'email' => $counselor['email'],
                'password' => $counselor['password'],
                'role' => $counselor['role'],
            ]);

            Counselor::create([
                'user_id' => $user->id,
                'name' => $counselor['name'],
                'specialization' => $counselor['specialization'],
                'experience' => $counselor['experience'],
                'office_number' => $counselor['office_number'],
                'email' => $counselor['email'],
                'status' => $counselor['status'],
            ]);
        }
    }
}
