<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserLastLoginSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->inRandomOrder()->get();
        $recentLoginCount = (int) round($users->count() * 0.9);

        foreach ($users->values() as $index => $user) {
            $lastLoginAt = $index < $recentLoginCount
                ? now()->subDays(random_int(0, 4))->subHours(random_int(0, 23))
                : now()->subDays(random_int(6, 60));

            $user->forceFill([
                'last_login_at' => $lastLoginAt,
            ])->save();
        }
    }
}
