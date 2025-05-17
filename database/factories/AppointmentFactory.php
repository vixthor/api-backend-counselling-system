<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => User::factory()->state(['role' => 'student']),
            'counselor_id' => User::factory()->state(['role' => 'counselor']),
            'session_topic' => 'Academic Guidance',
            'preferred_date' => now()->addDays(rand(1, 10))->toDateString(),
            'preferred_time' => '12:30pm',
            'notes' => $this->faker->sentence,
            'status' => 'Pending',
        ];
    }
}
