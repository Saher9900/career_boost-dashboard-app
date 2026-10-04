<?php

namespace App\Policies;

use App\Models\JobVacancy;
use App\Models\User;

class JobVacancyPolicy
{
    public function updateVacancyByOwner(User $user, JobVacancy $jobVacancy): bool
    {
        return $user->role === 'company_owner'
            && $jobVacancy->company()->where('owner_id', $user->id)->exists();
    }
}
