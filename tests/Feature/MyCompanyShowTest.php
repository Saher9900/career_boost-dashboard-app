<?php

use App\Models\Company;
use App\Models\User;

it('shows the logged-in company owner their company on my-company', function () {
    $owner = User::factory()->companyOwner()->create();

    $company = Company::factory()->create([
        'owner_id' => $owner->id,
        'name' => 'Acme Corp',
    ]);

    $this->actingAs($owner)
        ->get(route('my-company.show'))
        ->assertOk()
        ->assertSee('Acme Corp');
});

it('shows jobs tab for company owner via query string on my-company', function () {
    $owner = User::factory()->companyOwner()->create();

    Company::factory()->create([
        'owner_id' => $owner->id,
        'name' => 'Acme Corp',
    ]);

    $this->actingAs($owner)
        ->get(route('my-company.show', ['jobs' => 'true']))
        ->assertOk()
        ->assertSee('Jobs available');
});

it('shows company setup when a company owner has no company', function () {
    $owner = User::factory()->companyOwner()->create();

    $this->actingAs($owner)
        ->get(route('my-company.show'))
        ->assertOk()
        ->assertSee('Set up your company')
        ->assertSee(route('my-company.create'));
});

it('allows an owner without a company to create their company profile', function () {
    $owner = User::factory()->companyOwner()->create();

    $this->actingAs($owner)
        ->get(route('my-company.create'))
        ->assertOk()
        ->assertSee(route('my-company.store'));

    $this->post(route('my-company.store'), [
        'name' => 'Acme Corp',
        'industry' => 'Technology',
        'address' => '123 Main Street',
        'website' => 'https://example.com',
    ])
        ->assertRedirect(route('my-company.show'))
        ->assertSessionHas('success', 'Company profile created successfully.');

    expect($owner->companies()->firstOrFail()->name)->toBe('Acme Corp');

    $this->get(route('dashboard'))->assertOk();
});

it('shows an empty state on company navigation pages when the owner has no company', function () {
    $owner = User::factory()->companyOwner()->create();

    $this->actingAs($owner)
        ->get(route('my-job-vacancies.index'))
        ->assertOk()
        ->assertSee('No job vacancies are shown because your account does not have a company profile yet.')
        ->assertSee(route('my-company.create'));

    $this->get(route('job-applications.index'))
        ->assertOk()
        ->assertSee('No job applications are shown because your account does not have a company profile yet.')
        ->assertSee(route('my-company.create'));

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard metrics are unavailable because your account does not have a company profile yet.')
        ->assertSee(route('my-company.create'));
});

it('still requires a company profile for company-dependent actions', function () {
    $owner = User::factory()->companyOwner()->create();

    $this->actingAs($owner)
        ->get(route('my-job-vacancies.create'))
        ->assertRedirect(route('my-company.show'));

    $this->put(route('my-company.update'), [])
        ->assertRedirect(route('my-company.show'));
});
