<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Companies' }}
        </h2>
    </x-slot>

    @php
        $showJobs = request()->query('jobs') === 'true';
        $showApps = request()->query('apps') === 'true';
        $isAdmin = auth()->user()->role === 'admin';
        $companyShowRoute = $isAdmin ? 'companies.show' : 'my-company.show';
        $companyRouteParams = $isAdmin ? ['company' => $company->id] : [];
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#138a9e]">Company profile</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $company->name }}</h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ $isAdmin ? route('companies.edit', $company) : route('my-company.edit') }}">
                            <x-primary-button type="button">Edit Company</x-primary-button>
                        </a>
                        @if ($isAdmin)
                            <form action="{{ route('companies.destroy', $company) }}" method="POST"
                                onsubmit="return confirm('Archive this company?')">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Archive Company</x-danger-button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid gap-4 p-6 md:grid-cols-3 sm:p-8">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Industry</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $company->industry }}</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Address</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $company->address }}</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Website</p>
                    <a href="{{ $company->website }}" class="mt-2 inline-block text-lg font-semibold text-[#138a9e] hover:text-[#0e6378]">
                        {{ $company->website }}
                    </a>
                </div>
            </div>

            <div class="border-t border-gray-200 p-6 sm:p-8">
                <div class="mb-6 flex flex-wrap gap-2">
                    <a href="{{ route($companyShowRoute, array_merge($companyRouteParams, ['jobs' => 'true'])) }}"
                        class="inline-flex items-center rounded-md border px-3 py-2 text-sm font-medium transition {{ $showJobs ? 'border-[#0e6378] bg-[#0e6378] text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}">
                        Jobs available
                    </a>
                    <a href="{{ route($companyShowRoute, array_merge($companyRouteParams, ['apps' => 'true'])) }}"
                        class="inline-flex items-center rounded-md border px-3 py-2 text-sm font-medium transition {{ $showApps ? 'border-[#0e6378] bg-[#0e6378] text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}">
                        Applications sent
                    </a>
                </div>

                <div class="space-y-6">
                    @if ($showJobs)
                        <div class="overflow-hidden rounded-lg border border-gray-200">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                                <h3 class="text-lg font-semibold text-gray-900">Jobs available</h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-left">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Title</th>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Salary</th>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Type</th>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($company->jobVacancies as $job)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $job->title }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $job->salary }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $job->type }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="#" class="font-medium text-[#138a9e] hover:text-[#0e6378]">view job</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($showApps)
                        <div class="overflow-hidden rounded-lg border border-gray-200">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                                <h3 class="text-lg font-semibold text-gray-900">Applications sent</h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-left">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Applicant</th>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">AI Score</th>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($company->jobApplications as $job)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $job->user->name }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $job->ai_score }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $job->status }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="#" class="font-medium text-[#138a9e] hover:text-[#0e6378]">view Application</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
