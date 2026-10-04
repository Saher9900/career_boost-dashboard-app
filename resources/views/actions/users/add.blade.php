<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-[#138a9e]">Users</p>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">Add User</h2>
            </div>
            <a href="{{ route('users.index') }}" class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">Back to users</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-slate-50 px-6 py-5 sm:px-8">
                <h3 class="text-lg font-semibold text-gray-900">Account details</h3>
                <p class="mt-1 text-sm text-gray-500">Create an account and assign its access role.</p>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-8 px-6 py-6 sm:px-8 sm:py-8">
                @csrf
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Name</label>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="role" class="mb-2 block text-sm font-medium text-gray-700">Role</label>
                        <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#138a9e] focus:ring-[#138a9e]">
                            <option value="job_seeker" {{ old('role', 'job_seeker') === 'job_seeker' ? 'selected' : '' }}>Job Seeker</option>
                            <option value="company_owner" {{ old('role') === 'company_owner' ? 'selected' : '' }}>Company Owner</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div></div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password</label>
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">Confirm password</label>
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
                        <h4 class="text-sm font-semibold text-red-700">Please correct the following errors:</h4>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">Cancel</a>
                    <x-primary-button type="submit">Create User</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>