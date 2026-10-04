<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Company profile
        </h2>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="py-12 rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#138a9e]">Welcome</p>
            <h1 class="mt-3 text-2xl font-bold text-gray-900">Set up your company</h1>
            <p class="mx-auto mt-3 max-w-xl text-gray-600">
                Your account does not have a company profile yet. Create one to manage job vacancies and applications.
            </p>

            @if (session('warning'))
                <p class="mt-4 text-sm text-amber-700">{{ session('warning') }}</p>
            @endif

            <a href="{{ route('my-company.create') }}"
                class="mt-6 inline-flex items-center rounded-md bg-[#0e6378] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0b5061]">
                Create company profile
            </a>
        </div>
    </div>
</x-app-layout>
