<?php

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\JobApplicationSeeder;
use Database\Seeders\JobVacancySeeder;

it('seeds multiple vacancies for each company owner company without duplicates', function () {
    $firstOwner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $firstCompany = Company::factory()->create([
        'owner_id' => $firstOwner->id,
    ]);

    $secondOwner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $secondCompany = Company::factory()->create([
        'owner_id' => $secondOwner->id,
    ]);

    $admin = User::factory()->create([
        'role' => 'admin',
    ]);
    $adminCompany = Company::factory()->create([
        'owner_id' => $admin->id,
    ]);

    (new JobVacancySeeder)->run();

    expect($firstCompany->jobVacancies()->count())->toBe(3)
        ->and($secondCompany->jobVacancies()->count())->toBe(3)
        ->and($adminCompany->jobVacancies()->count())->toBe(0);

    (new JobVacancySeeder)->run();

    expect($firstCompany->jobVacancies()->count())->toBe(3)
        ->and($secondCompany->jobVacancies()->count())->toBe(3)
        ->and($adminCompany->jobVacancies()->count())->toBe(0);
});

it('creates owner companies and seeds their vacancies through the database seeder', function () {
    (new DatabaseSeeder)->run();

    $owners = User::query()
        ->where('role', 'company_owner')
        ->with('companies')
        ->get();

    expect($owners)->not->toBeEmpty();

    foreach ($owners as $owner) {
        expect($owner->companies)->not->toBeEmpty();

        foreach ($owner->companies as $company) {
            expect($company->jobVacancies()->count())->toBeGreaterThanOrEqual(3);
        }
    }

    foreach (JobVacancy::all() as $jobVacancy) {
        expect($jobVacancy->jobApplications()->count())->toBeGreaterThanOrEqual(3);
    }
});

it('seeds three applications per vacancy with matching resume owners without duplicates', function () {
    $firstVacancy = JobVacancy::factory()->create();
    $secondVacancy = JobVacancy::factory()->create();

    (new JobApplicationSeeder)->run();

    expect($firstVacancy->jobApplications()->count())->toBe(3)
        ->and($secondVacancy->jobApplications()->count())->toBe(3);

    foreach (JobApplication::with('resume')->get() as $application) {
        expect($application->user_id)->toBe($application->resume->user_id);
    }

    (new JobApplicationSeeder)->run();

    expect($firstVacancy->jobApplications()->count())->toBe(3)
        ->and($secondVacancy->jobApplications()->count())->toBe(3);
});
