<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-[#138a9e]">Job Applications</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">
                    {{ $jobApplication->user?->name ?? 'Applicant Profile' }}
                </h2>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('job-applications.index') }}" class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">
                    Back to applications
                </a>
                <a href="{{ route('job-applications.edit', $jobApplication) }}">
                    <x-primary-button type="button">Edit Status</x-primary-button>
                </a>
                <form action="{{ route('job-applications.destroy', $jobApplication) }}" method="POST"
                    onsubmit="return confirm('Archive this application?')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit">Archive Application</x-danger-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#138a9e]">Applicant overview</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $jobApplication->user?->name ?? 'Unnamed applicant' }}</h1>
                    </div>
                    <span class="inline-flex items-center rounded-full border border-[#0e6378] bg-[#0e6378]/10 px-3 py-1 text-sm font-medium text-[#0e6378]">
                        {{ ucfirst($jobApplication->status) }}
                    </span>
                </div>
            </div>

            <div class="grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-4 sm:p-8">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Vacancy</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $jobApplication->jobVacancy?->title ?? 'N/A' }}</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Company</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $jobApplication->jobVacancy?->company?->name ?? 'N/A' }}</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">AI Score</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $jobApplication->ai_score }}</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Contact</p>
                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $jobApplication->resume?->contact_details ?? 'No contact details' }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 p-6 sm:p-8">
                <div class="grid gap-6 xl:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Resume summary</p>
                        <p class="mt-3 whitespace-pre-line text-base leading-7 text-gray-700">
                            {{ $jobApplication->resume?->summary ?? 'No summary provided.' }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">AI feedback</p>
                        <p class="mt-3 whitespace-pre-line text-base leading-7 text-gray-700">
                            {{ $jobApplication->ai_feedback ?? 'No AI feedback provided.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 xl:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Skills</p>
                        <p class="mt-3 whitespace-pre-line text-base leading-7 text-gray-700">
                            {{ $jobApplication->resume?->skills ?? 'No skills provided.' }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Education</p>
                        <p class="mt-3 whitespace-pre-line text-base leading-7 text-gray-700">
                            {{ $jobApplication->resume?->education ?? 'No education details provided.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Experience</p>
                    <p class="mt-3 whitespace-pre-line text-base leading-7 text-gray-700">
                        {{ $jobApplication->resume?->experience ?? 'No experience details provided.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>