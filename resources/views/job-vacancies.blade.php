{{-- 
    if (auth()->user()->role === 'admin') {
                                $jobVacanciesRoute = route('job-vacancies.edit', $jobVacancy->id);
                            } else {
                                $jobVacanciesRoute = route('my-job-vacancies.edit', $jobVacancy->id);
                            }
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Job Vacancies' }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Success Message --}}
        @if (session('success'))
            <div x-data x-init="setTimeout(() => $el.remove(), 2000)"
                class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between"
                role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-green-600 hover:text-green-800 font-bold text-lg leading-none px-2"
                    onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('job-vacancies.create') }}">
                <x-primary-button class="mr-2 text-xs px-3 py-1">
                    Add New Job Vacancy
                </x-primary-button>
            </a>
        @else
            <a href="{{ route('my-job-vacancies.create') }}">
                <x-primary-button class="mr-2 text-xs px-3 py-1">
                    Add New Job Vacancy
                </x-primary-button>
            </a>
        @endif



        @php($showingArchived = request()->query('archived') === 'true')
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500">Manage active and archived job vacancies</p>
            <nav aria-label="Category status" class="inline-flex rounded-lg border border-gray-200 bg-gray-100 p-1">
                <a href="{{ route('job-vacancies.index') }}"
                    @if (!$showingArchived) aria-current="page" @endif
                    class="rounded-md px-4 py-2 text-sm font-medium transition {{ !$showingArchived ? 'bg-white text-[#0e6378] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Active
                </a>
                <a href="{{ route('job-vacancies.index', ['archived' => 'true']) }}"
                    @if ($showingArchived) aria-current="page" @endif
                    class="rounded-md px-4 py-2 text-sm font-medium transition {{ $showingArchived ? 'bg-white text-[#0e6378] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Archived
                </a>
            </nav>
        </div>

        {{-- Categories Table --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/5">
                            Title
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/5">
                            Type
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                            Salary
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                            Company
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                            Category
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($jobVacanciesPaginated as $jobVacancy)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a
                                    href="{{ route('job-vacancies.show', ['job_vacancy' => $jobVacancy->id]) }}">{{ $jobVacancy->title }}</a>{{ $jobVacancy->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $jobVacancy->type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $jobVacancy->salary }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $jobVacancy->company?->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $jobVacancy->jobCategory?->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">

                                @if (request()->input('archived') === 'true')
                                    <form action="{{ route('job-vacancies.restore', $jobVacancy->id) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('PUT')
                                        <x-secondary-button type="submit" class="text-xs px-3 py-1">
                                            Restore
                                        </x-secondary-button>
                                    </form>
                                @else
                                    @if (auth()->user()->role === 'admin')
                                        <a href="{{ route('job-vacancies.edit', $jobVacancy->id) }}">
                                            <x-primary-button class="mr-2 text-xs px-3 py-1">
                                                Edit
                                            </x-primary-button>
                                        </a>
                                    @else
                                        <a href="{{ route('my-job-vacancies.edit', $jobVacancy->id) }}">
                                            <x-primary-button class="mr-2 text-xs px-3 py-1">
                                                Edit
                                            </x-primary-button>
                                        </a>
                                    @endif

                                    <form action="{{ route('job-vacancies.destroy', $jobVacancy->id) }}" method="POST"
                                        style="display: inline">
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
                            <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Laravel Native Pagination Links --}}
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="category-pagination">
                    {{ $jobVacanciesPaginated->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
<style>
    .category-pagination nav p {
        margin: 0;
        color: #4b5563;
    }

    .category-pagination nav a {
        color: #138a9e;
        border-color: #d1d5db;
        background-color: #fff;
        text-decoration: none;
        transition: color 150ms ease, background-color 150ms ease, border-color 150ms ease;
    }

    .category-pagination nav a:hover {
        color: #fff;
        border-color: #0e6378;
        background-color: #0e6378;
    }

    .category-pagination nav a:focus {
        outline: 2px solid #138a9e;
        outline-offset: 2px;
    }

    .category-pagination nav [aria-current="page"]>span {
        color: #fff;
        border-color: #0e6378;
        background-color: #0e6378;
    }

    .category-pagination nav [aria-disabled="true"] span {
        color: #9ca3af;
        border-color: #e5e7eb;
        background-color: #f9fafb;
    }
</style>
