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
    public function index(Request $request): View|RedirectResponse
    {
        $companyMissing = false;

        if (Auth::user()->role === 'admin') {
            $query = JobVacancy::latest();
        } else {
            $company = Auth::user()->companies()->first();
            $companyMissing = $company === null;
            $query = $company
                ? JobVacancy::where('company_id', $company->id)
                : JobVacancy::whereRaw('1 = 0');
        }

        if ($request->input('archived') === 'true') {
            $query->onlyTrashed();
        }

        $jobVacanciesPaginated = $query->paginate(10)->onEachSide(1);

        return view('job-vacancies', compact('jobVacanciesPaginated', 'companyMissing'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::user()->role === 'admin') {
            $companies = Company::all();
            $ownedCompany = null;
        } else {
            $ownedCompany = Auth::user()->companies()->firstOrFail();
            $companies = collect([$ownedCompany]);
        }
        $jobCategories = JobCategory::all();

        return view('actions.vacancies.add', compact('companies', 'jobCategories', 'ownedCompany'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewJobVacancyRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (Auth::user()->role === 'admin') {
            JobVacancy::create($validated);

            return redirect()->route('job-vacancies.index');
        }

        $company = Auth::user()->companies()->firstOrFail();

        JobVacancy::create([
            ...$validated,
            'company_id' => $company->id,
            'location' => $company->address,
        ]);

        return redirect()->route('my-job-vacancies.index');
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

    public function destroyOwned(JobVacancy $jobVacancy): RedirectResponse
    {
        $this->authorize('deleteVacancyByOwner', $jobVacancy);

        $jobVacancy->delete();

        return redirect()->route('my-job-vacancies.index')->with('success', 'Job vacancy archived successfully.');
    }

    public function restoreOwned(string $id): RedirectResponse
    {
        $jobVacancy = JobVacancy::withTrashed()->findOrFail($id);

        $this->authorize('restoreVacancyByOwner', $jobVacancy);

        $jobVacancy->restore();

        return redirect()
            ->route('my-job-vacancies.index', ['archived' => 'true'])
            ->with('success', 'Job vacancy restored successfully.');
    }

    public function editVacancyByOwner(JobVacancy $jobVacancy): View|RedirectResponse
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
