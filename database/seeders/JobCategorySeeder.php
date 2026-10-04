<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobCategory::create([
            'title' => 'Front-End',
        ]);
        JobCategory::create([
            'title' => 'Back-End',
        ]);
        JobCategory::create([
            'title' => 'dev-ops',
        ]);
    }
}
