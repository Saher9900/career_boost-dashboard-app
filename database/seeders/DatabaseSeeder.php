<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->count(10)->create();

        if (! User::query()->where('role', 'company_owner')->exists()) {
            User::factory()->count(5)->create([
                'role' => 'company_owner',
            ]);
        }

        $this->call([
            UserLastLoginSeeder::class,
            EnsureCompanyOwnersHaveCompaniesSeeder::class,
            JobVacancySeeder::class,
            JobApplicationSeeder::class,
        ]);
    }
}
