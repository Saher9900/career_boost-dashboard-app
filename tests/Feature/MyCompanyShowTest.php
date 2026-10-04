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

it('returns not found when a company owner has no company', function () {
    $owner = User::factory()->companyOwner()->create();

    $this->actingAs($owner)
        ->get(route('my-company.show'))
        ->assertNotFound();
});
