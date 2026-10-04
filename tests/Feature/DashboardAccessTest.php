<?php

use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\User;

it('renders owner-scoped vacancy links without admin links on the owner vacancy list', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);
    $vacancy = JobVacancy::factory()->create([
        'title' => 'Owner Linked Vacancy',
        'company_id' => $company->id,
    ]);

    $this->actingAs($owner)
        ->get(route('my-job-vacancies.index'))
        ->assertOk()
        ->assertSee('Owner Linked Vacancy')
        ->assertSee(route('my-job-vacancies.index'))
        ->assertSee(route('my-job-vacancies.edit', $vacancy->id))
        ->assertSee(route('my-job-vacancies.destroy', $vacancy->id))
        ->assertDontSee(route('job-vacancies.index'), false)
        ->assertDontSee(route('job-vacancies.edit', $vacancy->id), false)
        ->assertDontSee(route('job-vacancies.destroy', $vacancy->id), false);
});

it('renders the owner restore link on the archived vacancy list', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);
    $vacancy = JobVacancy::factory()->create([
        'company_id' => $company->id,
    ]);

    $vacancy->delete();

    $this->actingAs($owner)
        ->get(route('my-job-vacancies.index', ['archived' => 'true']))
        ->assertOk()
        ->assertSee(route('my-job-vacancies.restore', $vacancy->id))
        ->assertDontSee(route('job-vacancies.restore', $vacancy->id), false);
});

it('links dashboard job titles to the owner edit page for a company owner', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    $company = Company::factory()->create([
        'owner_id' => $owner->id,
    ]);
    $vacancy = JobVacancy::factory()->create([
        'title' => 'Owner Dashboard Vacancy',
        'company_id' => $company->id,
    ]);

    $this->actingAs($owner)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Owner Dashboard Vacancy')
        ->assertSee(route('my-job-vacancies.edit', $vacancy->id))
        ->assertDontSee(route('job-vacancies.show', $vacancy->id), false);
});

it('sends a company owner to their company page instead of an inaccessible intended url', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    Company::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $this->get(route('job-vacancies.index'))->assertRedirect(route('login'));

    $this->post(route('login'), [
        'email' => $owner->email,
        'password' => '123123123',
    ])->assertRedirect(route('my-company.show'));

    $this->assertAuthenticatedAs($owner);
});

it('still honours an intended url the authenticated user can access', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);
    Company::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $this->get(route('profile.edit'))->assertRedirect(route('login'));

    $this->post(route('login'), [
        'email' => $owner->email,
        'password' => '123123123',
    ])->assertRedirect(route('profile.edit'));

    $this->assertAuthenticatedAs($owner);
});
