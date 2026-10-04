<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ai_score' => fake()->randomFloat(1, 0, 10),
            'ai_feedback' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'accepted', 'rejected']),
            'user_id' => User::factory(),
            'resume_id' => Resume::factory(),
            'job_vacancy_id' => JobVacancy::factory(),
        ];
    }
}
