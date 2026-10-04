<x-app-layout>
    @php($isAdmin = auth()->user()->role === 'admin')

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-[#138a9e]">Job Vacancies</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">
                    Add Job Vacancy
                </h2>
            </div>
            <a href="{{ route($isAdmin ? 'job-vacancies.index' : 'my-job-vacancies.index') }}" class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">
                Back to vacancies
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <h3 class="text-lg font-semibold text-gray-900">Job Vacancy details</h3>
                <p class="mt-1 text-sm text-gray-500">Create a new job listing and assign it to a company and category.</p>
            </div>

            <form action="{{ route($isAdmin ? 'job-vacancies.store' : 'my-job-vacancies.store') }}" method="POST" class="space-y-8 px-6 py-6 sm:px-8 sm:py-8">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" placeholder="Senior Backend Developer" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <label for="company_id" class="mb-2 block text-sm font-medium text-gray-700">Company</label>
                        <select id="company_id" name="company_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#138a9e] focus:ring-[#138a9e]" required>
                            <option value="">Select a company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                    </div>

                    <div>
                        <label for="job_category_id" class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                        <select id="job_category_id" name="job_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#138a9e] focus:ring-[#138a9e]" required>
                            <option value="">Select a category</option>
                            @foreach ($jobCategories as $jobCategory)
                                <option value="{{ $jobCategory->id }}" {{ old('job_category_id') == $jobCategory->id ? 'selected' : '' }}>
                                    {{ $jobCategory->title }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('job_category_id')" class="mt-2" />
                    </div>

                    <div>
                        <label for="location" class="mb-2 block text-sm font-medium text-gray-700">Location</label>
                        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location')" placeholder="Cairo, Egypt" required />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>

                    <div>
                        <label for="salary" class="mb-2 block text-sm font-medium text-gray-700">Salary</label>
                        <x-text-input id="salary" name="salary" type="text" class="mt-1 block w-full" :value="old('salary')" placeholder="$2000" required />
                        <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                    </div>

                    <div>
                        <label for="type" class="mb-2 block text-sm font-medium text-gray-700">Type</label>
                        <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#138a9e] focus:ring-[#138a9e]" required>
                            <option value="">Select type</option>
                            <option value="full_time" {{ old('type') === 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="contract" {{ old('type') === 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="remote" {{ old('type') === 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="hybrid" {{ old('type') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#138a9e] focus:ring-[#138a9e]" placeholder="Describe the role, responsibilities, and requirements..." required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 112 0v-3a1 1 0 10-2 0v3zm1-8a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-red-700">Please correct the following errors:</h4>
                                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                    <a href="{{ route($isAdmin ? 'job-vacancies.index' : 'my-job-vacancies.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#138a9e] focus:ring-offset-2">
                        Cancel
                    </a>
                    <x-primary-button type="submit">
                        Save Vacancy
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
