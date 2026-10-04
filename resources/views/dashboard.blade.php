<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#138a9e]">Admin workspace</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-900">Dashboard</h2>
            </div>
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-7 sm:px-6 lg:px-8">
        <section aria-label="Overview metrics">
            <div class="mb-4 flex items-end justify-between gap-3">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">Overview</h1>
                    <p class="mt-1 text-sm text-gray-500">A snapshot of activity across the job board.</p>
                </div>
            </div>

            <ul class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <li class="rounded-lg border border-gray-200 border-l-4 border-l-[#138a9e] bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Active users</p>
                    <p class="mt-3 text-3xl font-semibold tabular-nums text-gray-900">{{ number_format($analytics->activeUsers) }}</p>
                    <p class="mt-2 text-xs text-gray-500">Job seekers active in the last 30 days</p>
                </li>
                <li class="rounded-lg border border-gray-200 border-l-4 border-l-emerald-600 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Active job posts</p>
                    <p class="mt-3 text-3xl font-semibold tabular-nums text-gray-900">{{ number_format($analytics->totalJobs) }}</p>
                    <p class="mt-2 text-xs text-gray-500">Currently published vacancies</p>
                </li>
                <li class="rounded-lg border border-gray-200 border-l-4 border-l-amber-500 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Applications</p>
                    <p class="mt-3 text-3xl font-semibold tabular-nums text-gray-900">{{ number_format($analytics->totalApplications) }}</p>
                    <p class="mt-2 text-xs text-gray-500">Total active applications</p>
                </li>
            </ul>
        </section>

        <section aria-label="Most applied jobs">
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 sm:px-6">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Most applied jobs</h2>
                        <p class="mt-1 text-sm text-gray-500">Top vacancies by application volume with reach and conversion details</p>
                    </div>
                    <span class="rounded-md bg-[#eaf5f6] px-2.5 py-1 text-xs font-semibold text-[#0e6378]">Top 10</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Job</th>
                                <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Company</th>
                                <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Type</th>
                                <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Salary</th>
                                <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Views</th>
                                <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Applications</th>
                                <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Conversion rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($mostAppliedJobs as $job)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="max-w-56 px-5 py-4 text-sm font-medium text-gray-900 sm:px-6">
                                        <a href="{{ route('job-vacancies.show', $job) }}" class="hover:text-[#0e6378]">{{ $job->title }}</a>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 sm:px-6">{{ $job->company?->name ?? 'Company unavailable' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 sm:px-6">{{ ucfirst(str_replace('_', ' ', $job->type)) }}</td>
                                    <td class="px-5 py-4 text-right text-sm tabular-nums text-gray-600 sm:px-6">{{ $job->salary }}</td>
                                    <td class="px-5 py-4 text-right text-sm tabular-nums text-gray-600 sm:px-6">{{ number_format($job->view_count) }}</td>
                                    <td class="px-5 py-4 text-right text-sm font-semibold tabular-nums text-gray-900 sm:px-6">{{ number_format($job->total_applications) }}</td>
                                    <td class="px-5 py-4 text-right sm:px-6">
                                        @if ($job->conversionRate === null)
                                            <span class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-sm font-medium text-gray-500">N/A</span>
                                        @else
                                            <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-sm font-semibold tabular-nums text-emerald-800">
                                                {{ number_format($job->conversionRate, 2) }}%
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-500">No job application data yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
