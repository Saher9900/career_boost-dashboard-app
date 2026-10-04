<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::latest();

        if ($request->input('archived') === 'true') {
            $query->onlyTrashed();
        }

        $usersPaginated = $query->paginate(10)->onEachSide(1);

        return view('users', compact('usersPaginated'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('actions.users.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->loadCount(['companies', 'resumes', 'jobApplications']);

        return view('actions.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('actions.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->user()->is($user) && $validated['role'] !== $user->role) {
            return back()
                ->withErrors(['role' => 'You cannot change your own role.'])
                ->withInput();
        }

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot archive your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User archived successfully.');
    }

    public function restore(string $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()
            ->route('users.index', ['archived' => 'true'])
            ->with('success', 'User restored successfully.');
    }
}
