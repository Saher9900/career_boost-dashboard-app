<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class addActiceUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->forceFill([
            'id' => 2000,
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123123123'),
            'role' => 'job_seeker',
            'last_login_at' => now()->subDays(3),
        ])->save();
    }
}
