<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-[#138a9e]">Job Applications</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">
                    Edit Application Status
                </h2>
            </div>
            <a href="{{ route('job-applications.index') }}" class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">
                Back to applications
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <h3 class="text-lg font-semibold text-gray-900">Application status</h3>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $jobApplication->user?->name ?? 'Applicant' }} · {{ $jobApplication->jobVacancy?->title ?? 'Job vacancy' }}
                </p>
            </div>

            <form action="{{ route('job-applications.update', $jobApplication) }}" method="POST" class="space-y-6 px-6 py-6 sm:px-8 sm:py-8">
                @csrf
                @method('PUT')

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#138a9e] focus:ring-[#138a9e]">
                        <option value="pending" {{ old('status', $jobApplication->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ old('status', $jobApplication->status) === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ old('status', $jobApplication->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                    <a href="{{ route('job-applications.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#138a9e] focus:ring-offset-2">
                        Cancel
                    </a>
                    <x-primary-button type="submit">
                        Save Status
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>