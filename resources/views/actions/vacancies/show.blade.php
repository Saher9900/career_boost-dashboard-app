<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-[#138a9e]">Job Vacancies</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">
                    {{ $jobVacancy->title }}
                </h2>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('job-vacancies.index') }}" class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">
                    Back to vacancies
                </a>
                <a href="{{ route('job-vacancies.edit', $jobVacancy) }}">
                    <x-primary-button type="button">Edit Vacancy</x-primary-button>
                </a>
                <form action="{{ route('job-vacancies.destroy', $jobVacancy) }}" method="POST"
                    onsubmit="return confirm('Archive this vacancy?')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit">Archive Vacancy</x-danger-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#138a9e]">Vacancy overview</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $jobVacancy->title }}</h1>
                    </div>
                    <span class="inline-flex items-center rounded-full border border-[#0e6378] bg-[#0e6378]/10 px-3 py-1 text-sm font-medium text-[#0e6378]">
                        {{ $jobVacancy->type }}
                    </span>
                </div>
            </div>

            <div class="grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-4 sm:p-8">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Company</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900"><a class="border-b-2 border-[#138a9e] text-[#138a9e] hover:border-[#0e6378] hover:text-[#0e6378]" href="{{ route('companies.show', ['company' => $jobVacancy->company->id, 'jobs' => 'true']) }}">{{ $jobVacancy->company->name }}</a></p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Category</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $jobVacancy->jobCategory->title }}</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Salary</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $jobVacancy->salary }}</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Location</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $jobVacancy->location }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 p-6 sm:p-8">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Description</p>
                    <p class="mt-3 whitespace-pre-line text-base leading-7 text-gray-700">
                        {{ $jobVacancy->description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>