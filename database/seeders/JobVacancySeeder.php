<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::query()
            ->whereHas('owner', function (Builder $query): void {
                $query->where('role', 'company_owner');
            })
            ->withCount('jobVacancies')
            ->get();

        foreach ($companies as $company) {
            $vacanciesToCreate = max(0, 3 - $company->job_vacancies_count);

            if ($vacanciesToCreate > 0) {
                JobVacancy::factory()
                    ->count($vacanciesToCreate)
                    ->create(['company_id' => $company->id]);
            }
        }
    }
}
