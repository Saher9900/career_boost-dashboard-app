<?php

use App\Models\Company;
use App\Models\User;

it('shows the company edit form and updates only company fields', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $company = Company::factory()->create([
        'name' => 'Old Company',
        'industry' => 'Old Industry',
        'address' => 'Old Address',
        'website' => 'https://old.example.com',
    ]);

    $this->actingAs($admin)
        ->get(route('companies.edit', $company))
        ->assertOk()
        ->assertSee('Edit Company')
        ->assertSee('Company name')
        ->assertDontSee('Owner name')
        ->assertDontSee('Owner email')
        ->assertDontSee('Password');

    $this->actingAs($admin)
        ->put(route('companies.update', $company), [
            'name' => 'Updated Company',
            'industry' => 'Updated Industry',
            'address' => 'Updated Address',
            'website' => 'https://updated.example.com',
        ])
        ->assertRedirect(route('companies.index'));

    $company->refresh();

    expect($company->name)->toBe('Updated Company')
        ->and($company->industry)->toBe('Updated Industry')
        ->and($company->address)->toBe('Updated Address')
        ->and($company->website)->toBe('https://updated.example.com');
});

it('archives and restores a company using the same pattern as categories', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $company = Company::factory()->create();

    $this->actingAs($admin)
        ->delete(route('companies.destroy', $company))
        ->assertRedirect(route('companies.index'));

    expect($company->fresh()->trashed())->toBeTrue();

    $this->actingAs($admin)
        ->put(route('companies.restore', $company->id))
        ->assertRedirect(route('companies.index', ['archived' => 'true']));

    expect($company->fresh()->trashed())->toBeFalse();
});

it('shows edit and archive actions on an admin company detail page', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $company = Company::factory()->create();

    $this->actingAs($admin)
        ->get(route('companies.show', $company))
        ->assertOk()
        ->assertSee('Edit Company')
        ->assertSee('Archive Company');
});

it('shows the owner edit action without offering company archive', function () {
    $owner = User::factory()->create([
        'role' => 'company_owner',
    ]);

    Company::factory()->create([
        'name' => 'Owner Company',
        'owner_id' => $owner->id,
    ]);

    $this->actingAs($owner)
        ->get(route('my-company.show'))
        ->assertOk()
        ->assertSee('Owner Company')
        ->assertSee('Edit Company')
        ->assertDontSee('Archive Company');
});
