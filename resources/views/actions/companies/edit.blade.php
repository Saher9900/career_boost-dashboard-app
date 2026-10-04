@php
    if (auth()->user()->role === 'admin') {
      $formAction = route('companies.update', $company->id);
    }else {
      $formAction = route('my-company.update');
    }
    
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-[#138a9e]">Companies</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">
                    Edit Company
                </h2>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">
                Back to companies
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <h3 class="text-lg font-semibold text-gray-900">Company details</h3>
                <p class="mt-1 text-sm text-gray-500">Update the company profile information.</p>
            </div>

            <form action="{{ $formAction }}" method="POST" class="space-y-8 px-6 py-6 sm:px-8 sm:py-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Company name</label>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $company->name)" placeholder="Acme Studio" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="industry" class="mb-2 block text-sm font-medium text-gray-700">Industry</label>
                            <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full" :value="old('industry', $company->industry)" placeholder="Technology" required />
                            <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                        </div>

                        <div>
                            <label for="website" class="mb-2 block text-sm font-medium text-gray-700">Website</label>
                            <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $company->website)" placeholder="https://example.com" />
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="mb-2 block text-sm font-medium text-gray-700">Address</label>
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $company->address)" placeholder="123 Main Street, City" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
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
                    <a href="{{ route('companies.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#138a9e] focus:ring-offset-2">
                        Cancel
                    </a>
                    <x-primary-button type="submit">
                        Save Changes
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
