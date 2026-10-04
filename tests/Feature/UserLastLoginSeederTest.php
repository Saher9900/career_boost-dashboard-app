<?php

use App\Models\User;
use Database\Seeders\UserLastLoginSeeder;

it('seeds 90 percent of users with a login in the last five days', function () {
    User::factory()->count(10)->create();

    (new UserLastLoginSeeder)->run();

    $recentCount = User::query()
        ->where('last_login_at', '>=', now()->subDays(5))
        ->count();
    $olderCount = User::query()
        ->where('last_login_at', '<', now()->subDays(5))
        ->count();

    expect($recentCount)->toBe(9)
        ->and($olderCount)->toBe(1);
});
