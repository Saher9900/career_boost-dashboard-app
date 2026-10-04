<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNewCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Company::latest();

        if ($request->input('archived') === 'true') {
            $query->onlyTrashed();
        }

        $companiesPaginated = $query->paginate(10)->onEachSide(1);

        return view('companies', compact('companiesPaginated'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('actions.companies.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewCompanyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'company_owner',
        ]);

        Company::create([
            'name' => $validated['name'],
            'industry' => $validated['industry'],
            'address' => $validated['address'],
            'website' => $validated['website'],
            'owner_id' => $user->id,
        ]);

        return redirect()->route('companies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(?string $id = null): View
    {
        // $company = $this->resolveCompany($id);

        if ($id) {
            $company = Company::findOrFail($id);
        } else {
            $company = Company::where('owner_id', auth()->user()->id)->first();
        }

        return view('actions.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $id = null): View
    {
        $company = $this->resolveCompany($id);

        return view('actions.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->updateCompany($request, $company);

        return redirect()->route('companies.index');
    }

    /**
     * Update the authenticated company owner's company.
     */
    public function updateOwned(UpdateCompanyRequest $request): RedirectResponse
    {
        $company = Auth::user()->companies()->firstOrFail();

        $this->updateCompany($request, $company);

        return redirect()->route('my-company.show');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('companies.index');
    }

    public function restore(string $id): RedirectResponse
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();

        return redirect()
            ->route('companies.index', ['archived' => 'true'])
            ->with('success', 'Company restored successfully.');
    }

    private function resolveCompany(?string $id): Company
    {
        if ($id !== null && $id !== '') {
            $company = Company::query()->findOrFail($id);

            if (Auth::user()->role === 'company_owner' && $company->owner_id !== Auth::id()) {
                abort(403);
            }

            return $company;
        }

        return Auth::user()->companies()->firstOrFail();
    }

    private function updateCompany(UpdateCompanyRequest $request, Company $company): void
    {
        $validated = $request->validated();

        $company->update([
            'name' => $validated['name'],
            'industry' => $validated['industry'],
            'address' => $validated['address'],
            'website' => $validated['website'] ?? null,
        ]);
    }
}
