<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create company profile
        </h2>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <h1 class="text-lg font-semibold text-gray-900">Company details</h1>
                <p class="mt-1 text-sm text-gray-500">Add the details for your company profile.</p>
            </div>

            <form action="{{ route('my-company.store') }}" method="POST" class="space-y-6 px-6 py-6 sm:px-8">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Company name</label>
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="industry" class="mb-2 block text-sm font-medium text-gray-700">Industry</label>
                    <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full"
                        :value="old('industry')" required />
                    <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                </div>

                <div>
                    <label for="address" class="mb-2 block text-sm font-medium text-gray-700">Address</label>
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                        :value="old('address')" required />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div>
                    <label for="website" class="mb-2 block text-sm font-medium text-gray-700">Website</label>
                    <x-text-input id="website" name="website" type="url" class="mt-1 block w-full"
                        :value="old('website')" />
                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                </div>

                <div class="flex justify-end border-t border-gray-200 pt-6">
                    <x-primary-button type="submit">Create company profile</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
