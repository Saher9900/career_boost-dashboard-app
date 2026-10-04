<x-app-layout>
	<x-slot name="header">
		<div class="flex flex-wrap items-center justify-between gap-3">
			<div>
				<p class="text-sm font-medium text-[#138a9e]">Job Categories</p>
				<h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">
					Edit Category
				</h2>
			</div>
			<a href="{{ route('job-categories.index') }}"
				class="text-sm font-semibold text-gray-600 transition hover:text-[#0e6378]">
				Back to categories
			</a>
		</div>
	</x-slot>

	<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
		<div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
			<div class="border-b border-gray-200 px-6 py-5 sm:px-8">
				<h3 class="text-lg font-semibold text-gray-900">Category details</h3>
				<p class="mt-1 text-sm text-gray-500">Update the name of this job category.</p>
			</div>

			<form action="{{ route('job-categories.update', $jobCategory->id) }}" method="POST" class="space-y-6 px-6 py-6 sm:px-8 sm:py-8">
                @csrf
                @method('put')
				<div>
					<label for="title" class="block text-sm font-medium text-gray-700">Category name</label>
					<x-text-input
						id="title"
						name="title"
						type="text"
						value="{{ old('title', $jobCategory->title ?? '') }}"
						class="mt-2 block w-full"
						{{-- placeholder="Enter category name" --}}
						required
					/>
					<x-input-error :messages="$errors->get('title')" class="mt-2" />
					<p class="mt-2 text-xs text-gray-500">Choose a clear name that helps users find relevant jobs.</p>
				</div>

				<div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
					<a href="{{ route('job-categories.index') }}"
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