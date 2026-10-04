<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'industry' => fake()->text(8),
            'address' => fake()->address(),
            'website' => fake()->url(),
            'owner_id' => User::factory()->state(['role' => 'company_owner']),
        ];
    }
}
