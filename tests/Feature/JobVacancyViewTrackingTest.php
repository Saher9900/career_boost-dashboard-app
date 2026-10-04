<?php

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;

it('counts one vacancy view per session and displays the dashboard conversion rate', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $jobVacancy = JobVacancy::factory()->create([
        'title' => 'Conversion Tracking Vacancy',
        'view_count' => 0,
    ]);

    JobApplication::factory()->create([
        'job_vacancy_id' => $jobVacancy->id,
    ]);

    $this->actingAs($admin)
        ->get(route('job-vacancies.show', $jobVacancy))
        ->assertOk();

    expect($jobVacancy->fresh()->view_count)->toBe(1);

    $this->get(route('job-vacancies.show', $jobVacancy))
        ->assertOk();

    expect($jobVacancy->fresh()->view_count)->toBe(1);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Conversion Tracking Vacancy')
        ->assertSee('100.00%');
});

it('shows conversion metrics in the most applied jobs table and marks zero-view rates unavailable', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $lowerRateJob = JobVacancy::factory()->create([
        'title' => 'Many applications, lower rate',
        'view_count' => 100,
    ]);
    JobApplication::factory()->count(2)->create([
        'job_vacancy_id' => $lowerRateJob->id,
    ]);

    $higherRateJob = JobVacancy::factory()->create([
        'title' => 'Fewer applications, higher rate',
        'view_count' => 2,
    ]);
    JobApplication::factory()->create([
        'job_vacancy_id' => $higherRateJob->id,
    ]);

    $noViewsJob = JobVacancy::factory()->create([
        'title' => 'No views yet',
        'view_count' => 0,
    ]);
    JobApplication::factory()->count(3)->create([
        'job_vacancy_id' => $noViewsJob->id,
    ]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Views')
        ->assertSee('Conversion rate')
        ->assertSee('Salary')
        ->assertSee('Type')
        ->assertSee('N/A')
        ->assertViewHas('mostAppliedJobs', function ($jobs) use ($higherRateJob, $lowerRateJob, $noViewsJob) {
            return $jobs->pluck('id')->all() === [
                $noViewsJob->id,
                $lowerRateJob->id,
                $higherRateJob->id,
            ]
                && $jobs->first()->conversionRate === null
                && $jobs->last()->conversionRate === 50.0;
        });
});
