<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CounselorProfile>
 */
class CounselorProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'experience' => $this->faker->numberBetween(5, 20),
            'office_number' => $this->faker->numerify('4853###'),
            'specialization' => $this->faker->randomElement(['Psychology', 'Career Guidance']),
            'status' => 'Available',
        ];
    }
}
