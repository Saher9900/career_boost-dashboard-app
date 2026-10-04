<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Users' }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @if (session('success'))
            <div x-data x-init="setTimeout(() => $el.remove(), 2500)"
                class="mb-6 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800"
                role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="px-2 text-lg font-bold leading-none text-green-600 hover:text-green-800"
                    onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @php($showingArchived = request()->query('archived') === 'true')

        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Manage users</h1>
                <p class="mt-1 text-sm text-gray-500">Review accounts, roles, and archived users.</p>
            </div>
            @unless ($showingArchived)
                <a href="{{ route('users.create') }}">
                    <x-primary-button type="button">Add User</x-primary-button>
                </a>
            @endunless
        </div>

        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500">{{ $showingArchived ? 'Archived accounts' : 'Active accounts' }}</p>
            <nav aria-label="User status" class="inline-flex rounded-lg border border-gray-200 bg-gray-100 p-1">
                <a href="{{ route('users.index') }}" @if (!$showingArchived) aria-current="page" @endif
                    class="rounded-md px-4 py-2 text-sm font-medium transition {{ !$showingArchived ? 'bg-white text-[#0e6378] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Active
                </a>
                <a href="{{ route('users.index', ['archived' => 'true']) }}" @if ($showingArchived) aria-current="page" @endif
                    class="rounded-md px-4 py-2 text-sm font-medium transition {{ $showingArchived ? 'bg-white text-[#0e6378] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Archived
                </a>
            </nav>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($usersPaginated as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    @if ($showingArchived)
                                        {{ $user->name }}
                                    @else
                                        <a href="{{ route('users.show', $user) }}" class="hover:text-[#0e6378]">{{ $user->name }}</a>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    @if ($showingArchived)
                                        <form action="{{ route('users.restore', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <x-secondary-button type="submit" class="text-xs px-3 py-1">Restore</x-secondary-button>
                                        </form>
                                    @else
                                        <a href="{{ route('users.edit', $user) }}">
                                            <x-primary-button type="button" class="mr-2 text-xs px-3 py-1">Edit</x-primary-button>
                                        </a>
                                        @if (auth()->id() === $user->id)
                                            <span class="text-xs text-gray-400">Current account</span>
                                        @else
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit" class="text-xs px-3 py-1">Archive</x-danger-button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-6 py-4">
                <div class="user-pagination">
                    {{ $usersPaginated->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .user-pagination nav p {
        margin: 0;
        color: #4b5563;
    }

    .user-pagination nav a {
        color: #138a9e;
        border-color: #d1d5db;
        background-color: #fff;
        text-decoration: none;
        transition: color 150ms ease, background-color 150ms ease, border-color 150ms ease;
    }

    .user-pagination nav a:hover {
        color: #fff;
        border-color: #0e6378;
        background-color: #0e6378;
    }

    .user-pagination nav a:focus {
        outline: 2px solid #138a9e;
        outline-offset: 2px;
    }

    .user-pagination nav [aria-current="page"] > span {
        color: #fff;
        border-color: #0e6378;
        background-color: #0e6378;
    }

    .user-pagination nav [aria-disabled="true"] span {
        color: #9ca3af;
        border-color: #e5e7eb;
        background-color: #f9fafb;
    }
</style>
