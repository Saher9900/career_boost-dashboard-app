<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-[#138a9e]">Users</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">{{ $user->name }}</h2>
            </div>
            <a href="{{ route('users.index') }}" class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">Back to users</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-200 bg-slate-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#138a9e]">User profile</p>
                    <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('users.edit', $user) }}">
                        <x-primary-button type="button">Edit User</x-primary-button>
                    </a>
                    @if (auth()->id() !== $user->id)
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('Archive this user?')">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit">Archive User</x-danger-button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-4 sm:p-8">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Role</p>
                    <p class="mt-2 text-base font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Member since</p>
                    <p class="mt-2 text-base font-semibold text-gray-900">{{ $user->created_at->format('M j, Y') }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Companies</p>
                    <p class="mt-2 text-base font-semibold text-gray-900">{{ $user->companies_count }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Resumes</p>
                    <p class="mt-2 text-base font-semibold text-gray-900">{{ $user->resumes_count }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 px-6 py-5 sm:px-8">
                <p class="text-sm text-gray-600">
                    Job applications <span class="font-semibold text-gray-900">{{ $user->job_applications_count }}</span>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>