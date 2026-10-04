<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobVacancies = JobVacancy::query()
            ->withCount('jobApplications')
            ->get();

        foreach ($jobVacancies as $jobVacancy) {
            $applicationsToCreate = max(0, 3 - $jobVacancy->job_applications_count);

            for ($applicationIndex = 0; $applicationIndex < $applicationsToCreate; $applicationIndex++) {
                $applicant = User::factory()->create([
                    'role' => 'job_seeker',
                ]);
                $resume = Resume::factory()->create([
                    'user_id' => $applicant->id,
                ]);

                JobApplication::factory()->create([
                    'user_id' => $applicant->id,
                    'resume_id' => $resume->id,
                    'job_vacancy_id' => $jobVacancy->id,
                ]);
            }
        }
    }
}
