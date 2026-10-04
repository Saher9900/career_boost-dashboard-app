<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('creates updates shows and archives users with validated fields', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('users.create'))
        ->assertOk()
        ->assertSee('Add User')
        ->assertSee('Role');

    $this->actingAs($admin)
        ->post(route('users.store'), [
            'name' => 'Jordan Smith',
            'email' => 'jordan@example.com',
            'role' => 'job_seeker',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!',
        ])
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success', 'User created successfully.');

    $user = User::where('email', 'jordan@example.com')->firstOrFail();

    expect($user->name)->toBe('Jordan Smith')
        ->and($user->role)->toBe('job_seeker')
        ->and(Hash::check('StrongPassword123!', $user->password))->toBeTrue();

    $this->actingAs($admin)
        ->get(route('users.edit', $user))
        ->assertOk()
        ->assertSee('Edit User')
        ->assertSee('jordan@example.com');

    $this->actingAs($admin)
        ->put(route('users.update', $user), [
            'name' => 'Jordan Updated',
            'email' => 'jordan.updated@example.com',
            'role' => 'company_owner',
            'password' => '',
            'password_confirmation' => '',
        ])
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success', 'User updated successfully.');

    $user->refresh();

    expect($user->name)->toBe('Jordan Updated')
        ->and($user->email)->toBe('jordan.updated@example.com')
        ->and($user->role)->toBe('company_owner')
        ->and(Hash::check('StrongPassword123!', $user->password))->toBeTrue();

    $this->actingAs($admin)
        ->put(route('users.update', $user), [
            'name' => 'Jordan Updated',
            'email' => 'jordan.updated@example.com',
            'role' => 'superuser',
        ])
        ->assertSessionHasErrors('role');

    $this->actingAs($admin)
        ->get(route('users.show', $user))
        ->assertOk()
        ->assertSee('Jordan Updated')
        ->assertSee('jordan.updated@example.com')
        ->assertSee('Edit User')
        ->assertSee('Archive User')
        ->assertDontSee('StrongPassword123!');

    $this->actingAs($admin)
        ->delete(route('users.destroy', $user))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success', 'User archived successfully.');

    expect($user->fresh()->trashed())->toBeTrue();

    $this->actingAs($admin)
        ->put(route('users.restore', $user->id))
        ->assertRedirect(route('users.index', ['archived' => 'true']))
        ->assertSessionHas('success', 'User restored successfully.');

    expect($user->fresh()->trashed())->toBeFalse();
});

it('prevents an admin from archiving their own account', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin)
        ->put(route('users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'job_seeker',
        ])
        ->assertSessionHasErrors('role');

    expect($admin->fresh()->role)->toBe('admin');

    $this->actingAs($admin)
        ->delete(route('users.destroy', $admin))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('error', 'You cannot archive your own account.');

    expect($admin->fresh()->trashed())->toBeFalse();

    $this->actingAs($admin)
        ->get(route('users.show', $admin))
        ->assertOk()
        ->assertSee('Edit User')
        ->assertDontSee('Archive User');
});
