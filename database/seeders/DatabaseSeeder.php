<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use App\Models\CounselorProfile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $counselors = collect([
            ['name' => 'Daniella Phillips', 'email' => 'daniella@example.com'],
            ['name' => 'James Carter', 'email' => 'james.carter@example.com']
        ])->map(function ($data, $index) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'counselor'
            ]);

            CounselorProfile::create([
                'user_id' => $user->id,
                'experience' => [12, 8][$index],
                'office_number' => ['4853966', '4853970'][$index],
                'specialization' => ['Psychology', 'Career Guidance'][$index],
                'status' => 'Available'
            ]);

            return $user;
        });

        // Create students
        $students = collect([
            ['name' => 'Jesse Thomas', 'email' => 'jesse.thomas@example.com'],
            ['name' => 'Amina Yusuf', 'email' => 'amina.yusuf@example.com']
        ])->map(function ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'student'
            ]);
        });

        // Appointments
        Appointment::create([
            'student_id' => $students[0]->id,
            'counselor_id' => $counselors[0]->id,
            'session_topic' => 'Academic Guidance',
            'preferred_date' => 'Thursday',
            'preferred_time' => '12:30pm',
            'notes' => 'Need help with exam preparation.',
            'status' => 'Pending'
        ]);

//         $user = User::create([
//             'name' => 'Jane Smith',
//             'email' => 'jane@example.com',
//             'password' => bcrypt('password'),
//             'role' => 'counselor',
//         ]);

//         $counselor = Counselor::create([
//             'user_id' => $user->id,
//         ]);

// $user = User::create([
//     'name' => 'John Doe',
//     'email' => 'john@example.com',
//     'password' => bcrypt('password'),
//     'role' => 'student',
// ]);

// $student = Student::create([
//     'user_id' => $user->id,
//     'is_anonymous' => false,
// ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
