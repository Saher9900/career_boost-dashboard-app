<?php

namespace App\Providers;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use App\Policies\JobVacancyPolicy;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-dashboard', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });
        Gate::define(JobVacancy::class, JobVacancyPolicy::class);
        Gate::define('go-to-edit-application', function (User $user, $applicationId) {
            if ($user->role === 'admin') {
                return true;
            } else {
                $companyId = Auth::user()->companies()->value('id');

                if ($companyId === null) {
                    return false;
                }

                $applicationIds = JobApplication::withTrashed()->whereHas('jobVacancy', function ($vacancy) use ($companyId) {
                    return $vacancy->withTrashed()->where('company_id', $companyId);
                })->pluck('id');

                $exist = $applicationIds->contains($applicationId);

                return $exist;
            }
        });

        Event::listen(function (Login $event) {
            $event->user->forceFill([
                'last_login_at' => now(),
            ]);
        });
    }
}
