<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashBoardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $companyMissing = false;
            $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
                ->where('role', 'job_seeker')->count();
            $totalJobs = JobVacancy::whereNull('deleted_at')->count();
            $totalApplications = JobApplication::whereNull('deleted_at')->count();

            $analytics = (object) [
                'activeUsers' => $activeUsers,
                'totalJobs' => $totalJobs,
                'totalApplications' => $totalApplications,
            ];

            $mostAppliedJobs = JobVacancy::with('company')
                ->withCount('jobApplications as total_applications')
                ->orderByDesc('total_applications')
                ->limit(10)
                ->get();
        } else {
            $company = Auth::user()->companies()->first();
            $companyMissing = $company === null;

            if ($companyMissing) {
                $analytics = (object) [
                    'activeUsers' => 0,
                    'totalJobs' => 0,
                    'totalApplications' => 0,
                ];
                $mostAppliedJobs = collect();
            } else {
                $companyId = $company->id;

                $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
                    ->where('role', 'job_seeker')->whereHas('jobVacancies', function ($vacancy) use ($companyId) {
                        return $vacancy->where('company_id', $companyId);
                    })->count();

                $totalJobs = JobVacancy::where('company_id', $companyId)->count();
                $totalApplications = $company->jobApplications()->count();

                $analytics = (object) [
                    'activeUsers' => $activeUsers,
                    'totalJobs' => $totalJobs,
                    'totalApplications' => $totalApplications,
                ];

                $mostAppliedJobs = JobVacancy::where('company_id', $companyId)
                    ->with('company')
                    ->withCount('jobApplications as total_applications')
                    ->orderByDesc('total_applications')
                    ->limit(10)
                    ->get();
            }
        }

        $mostAppliedJobs = $mostAppliedJobs->map(function ($job) {
            $job->conversionRate = $job->view_count > 0
                ? round(($job->total_applications / $job->view_count) * 100, 2)
                : null;

            return $job;
        });

        return view('dashboard', compact('analytics', 'mostAppliedJobs', 'companyMissing'));
    }
}
