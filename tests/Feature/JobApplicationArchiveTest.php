<?php

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;

it('archives and restores job applications and shows resume details', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $resume = Resume::factory()->create([
        'contact_details' => '0123456789 | test@example.com',
        'summary' => 'Experienced backend developer',
        'skills' => 'PHP, Laravel, MySQL',
    ]);

    $jobVacancy = JobVacancy::factory()->create();

    $application = JobApplication::factory()->create([
        'user_id' => $resume->user_id,
        'resume_id' => $resume->id,
        'job_vacancy_id' => $jobVacancy->id,
        'status' => 'pending',
        'ai_score' => 8.5,
    ]);

    $this->actingAs($admin)
        ->delete(route('job-applications.destroy', $application))
        ->assertRedirect(route('job-applications.index'));

    expect($application->fresh()->trashed())->toBeTrue();

    $this->actingAs($admin)
        ->put(route('job-applications.restore', $application->id))
        ->assertRedirect(route('job-applications.index', ['archived' => 'true']))
        ->assertSessionHas('success', 'Job application restored successfully.');

    expect($application->fresh()->trashed())->toBeFalse();

    $this->actingAs($admin)
        ->get(route('job-applications.edit', $application))
        ->assertOk()
        ->assertSee('Edit Application Status')
        ->assertSee('accepted');

    $this->actingAs($admin)
        ->put(route('job-applications.update', $application), [
            'status' => 'shortlisted',
        ])
        ->assertSessionHasErrors('status');

    expect($application->fresh()->status)->toBe('pending');

    $this->actingAs($admin)
        ->put(route('job-applications.update', $application), [
            'status' => 'accepted',
        ])
        ->assertRedirect(route('job-applications.index'))
        ->assertSessionHas('success', 'Job application status updated successfully.');

    expect($application->fresh()->status)->toBe('accepted');

    $this->actingAs($admin)
        ->get(route('job-applications.show', $application))
        ->assertOk()
        ->assertSee('Edit Status')
        ->assertSee('Archive Application')
        ->assertSee('Experienced backend developer')
        ->assertSee('0123456789 | test@example.com')
        ->assertSee('PHP, Laravel, MySQL');
});

it('lets a company owner restore an archived application belonging to their company', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);
    $vacancy = JobVacancy::factory()->create([
        'company_id' => $company->id,
    ]);
    $application = JobApplication::factory()->create([
        'job_vacancy_id' => $vacancy->id,
    ]);

    $application->delete();

    $this->actingAs($owner)
        ->put(route('job-applications.restore', $application->id))
        ->assertRedirect(route('job-applications.index', ['archived' => 'true']))
        ->assertSessionHas('success', 'Job application restored successfully.');

    expect($application->fresh()->trashed())->toBeFalse();
});

it('lets a company owner restore an application whose vacancy is archived', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);
    $vacancy = JobVacancy::factory()->create([
        'company_id' => $company->id,
    ]);
    $application = JobApplication::factory()->create([
        'job_vacancy_id' => $vacancy->id,
    ]);

    $vacancy->delete();
    $application->delete();

    $this->actingAs($owner)
        ->put(route('job-applications.restore', $application->id))
        ->assertRedirect(route('job-applications.index', ['archived' => 'true']))
        ->assertSessionHas('success', 'Job application restored successfully.');

    expect($application->fresh()->trashed())->toBeFalse();
});

it('prevents a company owner from restoring another company archived application', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    Company::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $otherCompany = Company::factory()->create();
    $otherVacancy = JobVacancy::factory()->create([
        'company_id' => $otherCompany->id,
    ]);
    $otherApplication = JobApplication::factory()->create([
        'job_vacancy_id' => $otherVacancy->id,
    ]);

    $otherApplication->delete();

    $this->actingAs($owner)
        ->put(route('job-applications.restore', $otherApplication->id))
        ->assertForbidden();

    expect($otherApplication->fresh()->trashed())->toBeTrue();
});
