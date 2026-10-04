<?php

namespace Database\Factories;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Resume>
 */
class ResumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_name' => fake()->word(),
            'file_url' => fake()->url(),
            'contact_details' => fake()->text(),
            'education' => fake()->words(3, true),
            'summary' => fake()->paragraph(),
            'skills' => fake()->paragraph(),
            'experience' => fake()->paragraph(),
            'user_id' => User::factory(),
        ];
    }
}
