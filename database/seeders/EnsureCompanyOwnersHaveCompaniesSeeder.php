<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnsureCompanyOwnersHaveCompaniesSeeder extends Seeder
{
    /**
     * Create a company for every company_owner that does not have one yet.
     */
    public function run(): void
    {
        User::query()
            ->where('role', 'company_owner')
            ->whereDoesntHave('companies')
            ->each(function (User $owner): void {
                Company::query()->create([
                    'name' => $owner->name.' Company',
                    'industry' => 'General',
                    'address' => 'Address pending',
                    'website' => null,
                    'owner_id' => $owner->id,
                ]);
            });
    }
}
