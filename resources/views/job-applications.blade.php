<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Job Applications' }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div x-data x-init="setTimeout(() => $el.remove(), 2000)"
                class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between"
                role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-green-600 hover:text-green-800 font-bold text-lg leading-none px-2"
                    onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif

        @php($showingArchived = request()->query('archived') === 'true')
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500">Manage active and archived applications</p>
            <nav aria-label="Application status" class="inline-flex rounded-lg border border-gray-200 bg-gray-100 p-1">
                <a href="{{ route('job-applications.index') }}" @if (!$showingArchived) aria-current="page" @endif
                    class="rounded-md px-4 py-2 text-sm font-medium transition {{ !$showingArchived ? 'bg-white text-[#0e6378] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Active
                </a>
                <a href="{{ route('job-applications.index', ['archived' => 'true']) }}"
                    @if ($showingArchived) aria-current="page" @endif
                    class="rounded-md px-4 py-2 text-sm font-medium transition {{ $showingArchived ? 'bg-white text-[#0e6378] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Archived
                </a>
            </nav>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacancy</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AI Score</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($jobApplicationsPaginated as $application)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('job-applications.show', $application->id) }}" class="text-[#138a9e] hover:text-[#0e6378]">
                                    {{ $application->user?->name ?? 'Applicant' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $application->jobVacancy?->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                    @if ($application->status === 'accepted') bg-green-100 text-green-800
                                    @elseif ($application->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $application->ai_score }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if (request()->input('archived') === 'true')
                                    <form action="{{ route('job-applications.restore', $application->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <x-secondary-button type="submit" class="text-xs px-3 py-1">
                                            Restore
                                        </x-secondary-button>
                                    </form>
                                @else
                                    <a href="{{ route('job-applications.edit', $application->id) }}">
                                        <x-primary-button class="mr-2 text-xs px-3 py-1">
                                            Edit Status
                                        </x-primary-button>
                                    </a>

                                    <form action="{{ route('job-applications.destroy', $application->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('delete')
                                        <button type="submit">
                                            <x-danger-button class="text-xs px-3 py-1">
                                                Archive
                                            </x-danger-button>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200">
                <div class="app-pagination">
                    {{ $jobApplicationsPaginated->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .app-pagination nav p {
        margin: 0;
        color: #4b5563;
    }

    .app-pagination nav a {
        color: #138a9e;
        border-color: #d1d5db;
        background-color: #fff;
        text-decoration: none;
        transition: color 150ms ease, background-color 150ms ease, border-color 150ms ease;
    }

    .app-pagination nav a:hover {
        color: #fff;
        border-color: #0e6378;
        background-color: #0e6378;
    }

    .app-pagination nav a:focus {
        outline: 2px solid #138a9e;
        outline-offset: 2px;
    }

    .app-pagination nav [aria-current="page"] > span {
        color: #fff;
        border-color: #0e6378;
        background-color: #0e6378;
    }

    .app-pagination nav [aria-disabled="true"] span {
        color: #9ca3af;
        border-color: #e5e7eb;
        background-color: #f9fafb;
    }
</style>
