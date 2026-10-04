<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNewJobVacancyRequest;
use App\Http\Requests\UpdateJobVacancyRequest;
use App\Http\Requests\UpdateOwnedJobVacancyRequest;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        if (Auth::user()->role === 'admin') {
            $query = JobVacancy::latest();
        } else {
            $companyId = Company::where('owner_id', Auth::user()->id)->first()->id;
            $query = JobVacancy::where('company_id', $companyId);
        }

        if ($request->input('archived') === 'true') {
            $query->onlyTrashed();
        }

        $jobVacanciesPaginated = $query->paginate(10)->onEachSide(1);

        return view('job-vacancies', compact('jobVacanciesPaginated'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (Auth::user()->role === 'admin') {
            $companies = Company::all();
        } else {
            $companies = Company::where('owner_id', Auth::user()->id)->get();
        }
        $jobCategories = JobCategory::all();

        return view('actions.vacancies.add', compact('companies', 'jobCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewJobVacancyRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        JobVacancy::create($validated);

        if (Auth::user()->role === 'admin') {
            return redirect()->route('job-vacancies.index');
        } else {
            return redirect()->route('my-job-vacancies.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $jobVacancy = JobVacancy::findOrFail($id);

        if (! session()->has('vacancy_viewed'.$id)) {
            session()->put('vacancy_viewed'.$id, true);
            $jobVacancy->increment('view_count');
        }

        return view('actions.vacancies.show', compact('jobVacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $companies = Company::all();
        $jobCategories = JobCategory::all();

        return view('actions.vacancies.edit', compact('jobVacancy', 'companies', 'jobCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobVacancyRequest $request, JobVacancy $jobVacancy): RedirectResponse
    {
        $validated = $request->validated();

        $jobVacancy->update($validated);

        return redirect()->route('job-vacancies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobVacancy $jobVacancy): RedirectResponse
    {
        $jobVacancy->delete();

        return redirect()->route('job-vacancies.index')->with('success', 'Job vacancy archived successfully.');
    }

    public function restore(string $id): RedirectResponse
    {
        $jobVacancy = JobVacancy::withTrashed()->findOrFail($id);
        $jobVacancy->restore();

        return redirect()
            ->route('job-vacancies.index', ['archived' => 'true'])
            ->with('success', 'Job vacancy restored successfully.');
    }

    public function editVacancyByOwner(JobVacancy $jobVacancy)
    {
        // $companyId = Company::where('owner_id', Auth::user()->id)->first()->id;
        // $vacanciesOfOwner = JobVacancy::where('company_id', $companyId)
        // return $vacancies = auth()->user()->jobVacancies;
        $this->authorize('updateVacancyByOwner', $jobVacancy);
        $companies = Company::where('owner_id', Auth::id())->get();
        $jobCategories = JobCategory::all();

        return view('actions.vacancies.edit', compact('jobVacancy', 'companies', 'jobCategories'));

    }

    public function updateVacancyByOwner(UpdateOwnedJobVacancyRequest $request, JobVacancy $jobVacancy): RedirectResponse
    {
        $validated = $request->validated();

        $jobVacancy->update($validated);

        return redirect()
            ->route('my-job-vacancies.index')
            ->with('success', 'Job vacancy updated successfully.');
    }
}
