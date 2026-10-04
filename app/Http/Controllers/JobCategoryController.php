<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobCategory::latest();
        if ($request->input('archived') === 'true') {
            $query->onlyTrashed();
        }
        $jobCategories = $query->paginate(10)->onEachSide(1);

        return view('job-categories', compact('jobCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MakeCategoryRequest $request)
    {
        $validated = $request->validated();
        JobCategory::create($validated);

        return redirect()
            ->route('job-categories.index')
            ->with('success', 'Job category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobCategory $jobCategory)
    {
        return view('actions.categories.edit', compact('jobCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, JobCategory $jobCategory)
    {
        $validated = $request->validated();
        $jobCategory->update($validated);

        return redirect()->route('job-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobCategory $jobCategory)
    {
        $jobCategory->delete();

        return redirect()->route('job-categories.index');
    }

    public function restore(string $id)
    {
        $jobCategory = JobCategory::withTrashed()->findOrFail($id);
        $jobCategory->restore();

        return redirect()
            ->route('job-categories.index', ['archived' => 'true'])
            ->with('success', 'Job category restored successfully.');
    }
}
