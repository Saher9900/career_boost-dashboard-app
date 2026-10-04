<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $companyMissing = false;
        if (Auth::user()->role === 'admin') {
            $query = JobApplication::with(['user', 'resume', 'jobVacancy.company'])->latest();
        } else {
            $company = Auth::user()->companies()->first();
            $companyMissing = $company === null;

            $query = JobApplication::with(['user', 'resume', 'jobVacancy.company']);
            if ($company) {
                $query->whereHas('jobVacancy', function ($vacancy) use ($company) {
                    return $vacancy->where('company_id', $company->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }

            $query->latest();
        }

        if ($request->input('archived') === 'true') {
            $query->onlyTrashed();
        }

        $jobApplicationsPaginated = $query->paginate(10)->onEachSide(1)->appends(request()->query());

        return view('job-applications', compact('jobApplicationsPaginated', 'companyMissing'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplication $jobApplication): View|RedirectResponse
    {
        $jobApplication->load(['user', 'resume', 'jobVacancy.company']);

        if ($jobApplication->resume) {
            $jobApplication->setRelation('resume', $jobApplication->resume()->withTrashed()->first() ?: $jobApplication->resume);
        }

        return view('actions.applications.show', compact('jobApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobApplication $jobApplication): View|RedirectResponse
    {
        if (! Gate::allows('go-to-edit-application', [$jobApplication->id])) {
            abort(403);
        }

        return view('actions.applications.edit', compact('jobApplication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,accepted,rejected'],
        ]);

        if (! Gate::allows('go-to-edit-application', [$jobApplication->id])) {
            abort(403);
        }

        $jobApplication->update($validated);

        return redirect()
            ->route('job-applications.index')
            ->with('success', 'Job application status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $jobApplication->delete();

        return redirect()->route('job-applications.index')->with('success', 'Job application archived successfully.');
    }

    public function restore(string $id): RedirectResponse
    {
        $jobApplication = JobApplication::withTrashed()->findOrFail($id);

        if (! Gate::allows('go-to-edit-application', [$jobApplication->id])) {
            abort(403);
        }

        $jobApplication->restore();

        return redirect()
            ->route('job-applications.index', ['archived' => 'true'])
            ->with('success', 'Job application restored successfully.');
    }
}
