<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->paragraph(2),
            'location' => fake()->address(),
            'salary' => (string) fake()->numberBetween(3000, 10000),
            'type' => fake()->randomElement(['full_time', 'contract', 'remote', 'hybrid']),
            'company_id' => Company::factory(),
            'job_category_id' => JobCategory::factory(),
        ];
    }
}
