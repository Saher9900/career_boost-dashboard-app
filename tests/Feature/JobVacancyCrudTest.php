<?php

use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\User;

it('creates and updates job vacancies with the expected fields', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $company = Company::factory()->create();
    $category = JobCategory::factory()->create();

    $this->actingAs($admin)
        ->get(route('job-vacancies.create'))
        ->assertOk()
        ->assertSee('Job Vacancy')
        ->assertSee('Title')
        ->assertSee('Company')
        ->assertSee('Category');

    $this->actingAs($admin)
        ->post(route('job-vacancies.store'), [
            'title' => 'Senior Backend Developer',
            'description' => 'Build APIs and maintain systems.',
            'location' => 'Cairo',
            'salary' => '$2000',
            'type' => 'full_time',
            'company_id' => $company->id,
            'job_category_id' => $category->id,
        ])
        ->assertRedirect(route('job-vacancies.index'));

    $jobVacancy = JobVacancy::latest()->first();

    expect($jobVacancy)->not->toBeNull()
        ->and($jobVacancy->title)->toBe('Senior Backend Developer')
        ->and($jobVacancy->company_id)->toBe($company->id)
        ->and($jobVacancy->job_category_id)->toBe($category->id);

    $this->actingAs($admin)
        ->get(route('job-vacancies.index'))
        ->assertOk()
        ->assertSee(route('job-vacancies.index'))
        ->assertSee('aria-current="page"', false);

    $this->actingAs($admin)
        ->get(route('job-vacancies.show', $jobVacancy))
        ->assertOk()
        ->assertSee('Edit Vacancy')
        ->assertSee('Archive Vacancy');

    $this->actingAs($admin)
        ->get(route('job-vacancies.edit', $jobVacancy))
        ->assertOk()
        ->assertSee('Edit Job Vacancy')
        ->assertSee('Senior Backend Developer');

    $this->actingAs($admin)
        ->put(route('job-vacancies.update', $jobVacancy), [
            'title' => 'Lead Backend Developer',
            'description' => 'Lead the backend team.',
            'location' => 'Alexandria',
            'salary' => '$2500',
            'type' => 'remote',
            'company_id' => $company->id,
            'job_category_id' => $category->id,
        ])
        ->assertRedirect(route('job-vacancies.index'));

    $jobVacancy->refresh();

    expect($jobVacancy->title)->toBe('Lead Backend Developer')
        ->and($jobVacancy->description)->toBe('Lead the backend team.')
        ->and($jobVacancy->location)->toBe('Alexandria')
        ->and($jobVacancy->salary)->toBe('$2500')
        ->and($jobVacancy->type)->toBe('remote');
});

it('renders the company owner vacancy list', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);

    JobVacancy::factory()->create([
        'title' => 'Owner Vacancy Listing',
        'company_id' => $company->id,
    ]);

    $this->actingAs($owner)
        ->get(route('my-job-vacancies.index'))
        ->assertOk()
        ->assertSee('Owner Vacancy Listing')
        ->assertSee(route('my-job-vacancies.index'))
        ->assertSee('aria-current="page"', false);
});

it('creates vacancies from the company owner route and returns to the owner vacancy list', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);
    $category = JobCategory::factory()->create();

    $this->actingAs($owner)
        ->get(route('my-job-vacancies.create'))
        ->assertOk()
        ->assertSee(route('my-job-vacancies.store'));

    $this->post(route('my-job-vacancies.store'), [
        'title' => 'Owner Created Vacancy',
        'description' => 'A vacancy created by its company owner.',
        'location' => 'Cairo',
        'salary' => '$3000',
        'type' => 'full_time',
        'company_id' => $company->id,
        'job_category_id' => $category->id,
    ])
        ->assertRedirect(route('my-job-vacancies.index'));

    expect(JobVacancy::where('title', 'Owner Created Vacancy')->firstOrFail()->company_id)
        ->toBe($company->id);
});

it('runs the owner update form request and rejects updates to another company vacancy', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);
    $category = JobCategory::factory()->create();
    $vacancy = JobVacancy::factory()->create([
        'title' => 'Original Owner Vacancy',
        'company_id' => $company->id,
        'job_category_id' => $category->id,
    ]);

    $otherOwner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $otherCompany = Company::factory()->create([
        'owner_id' => $otherOwner->id,
    ]);
    $otherVacancy = JobVacancy::factory()->create([
        'title' => 'Protected Vacancy',
        'company_id' => $otherCompany->id,
        'job_category_id' => $category->id,
    ]);

    $this->actingAs($owner)
        ->put(route('my-job-vacancies.update', $vacancy), [
            'title' => 'Updated Owner Vacancy',
            'description' => $vacancy->description,
            'location' => $vacancy->location,
            'salary' => $vacancy->salary,
            'type' => $vacancy->type,
            'company_id' => $company->id,
            'job_category_id' => $category->id,
        ])
        ->assertRedirect(route('my-job-vacancies.index'))
        ->assertSessionHas('success', 'Job vacancy updated successfully.');

    expect($vacancy->fresh()->title)->toBe('Updated Owner Vacancy');

    $this->put(route('my-job-vacancies.update', $otherVacancy), [
        'title' => 'Tampered Vacancy',
        'description' => $otherVacancy->description,
        'location' => $otherVacancy->location,
        'salary' => $otherVacancy->salary,
        'type' => $otherVacancy->type,
        'company_id' => $company->id,
        'job_category_id' => $category->id,
    ])->assertForbidden();

    expect($otherVacancy->fresh()->title)->toBe('Protected Vacancy');
});
