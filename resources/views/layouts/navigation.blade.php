@php
    $navItems = match (auth()->user()->role) {
        'admin' => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard'],
            ['label' => 'Companies', 'route' => 'companies.index', 'pattern' => 'companies.*'],
            ['label' => 'Job Applications', 'route' => 'job-applications.index', 'pattern' => 'job-applications.*'],
            ['label' => 'Job Categories', 'route' => 'job-categories.index', 'pattern' => 'job-categories.*'],
            ['label' => 'Job Vacancies', 'route' => 'job-vacancies.index', 'pattern' => 'job-vacancies.*'],
            ['label' => 'Users', 'route' => 'users.index', 'pattern' => 'users.*'],
        ],
        'company_owner' => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard'],
            ['label' => 'My Company', 'route' => 'my-company.show', 'params' => ['jobs' => 'true'], 'pattern' => 'my-company.*'],
            ['label' => 'Job Applications', 'route' => 'job-applications.index', 'pattern' => 'job-applications.*'],
            ['label' => 'Job Vacancies', 'route' => 'my-job-vacancies.index', 'pattern' => 'my-job-vacancies.*'],
        ],
        default => [],
    };
@endphp

{{-- Desktop sidebar --}}
<aside class="fixed inset-y-0 left-0 z-20 hidden w-[250px] flex-col border-r border-gray-200 bg-white md:flex">
    <div class="flex h-[73px] shrink-0 items-center justify-center border-b border-black">
        <h1 class="text-2xl font-semibold">Career Booster</h1>
    </div>

    @if (auth()->user()->role === 'company_owner')
        <div class="border-b border-gray-200 px-4 py-4">
            <p class="truncate text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
            <p class="mt-1 text-xs text-gray-500">Company owner</p>
            <a href="{{ route('profile.edit') }}" class="mt-2 inline-flex text-xs font-medium text-[#0e6378] hover:underline">
                My account
            </a>
        </div>
    @endif

    @include('layouts.partials.nav-links', ['items' => $navItems])

    <div class="shrink-0 border-t border-gray-200 p-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <x-danger-button class="w-full justify-center">log out</x-danger-button>
        </form>
    </div>
</aside>

{{-- Mobile top bar; the sticky wrapper in layouts.app supplies the pinning --}}
<div class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 md:hidden">
    <div>
        <span class="block text-lg font-semibold">Career Booster</span>
        @if (auth()->user()->role === 'company_owner')
            <span class="block max-w-56 truncate text-xs text-gray-600">{{ auth()->user()->name }}</span>
        @endif
    </div>

    <button type="button" id="mobile-nav-toggle" aria-controls="mobile-nav"
        :aria-expanded="mobileNavOpen.toString()" @click="mobileNavOpen = true"
        class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#138a9e]">
        <span class="sr-only">Open navigation menu</span>
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

{{-- Drawer backdrop --}}
<div x-cloak x-show="mobileNavOpen" @click="mobileNavOpen = false" aria-hidden="true"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-gray-900/50 md:hidden"></div>

{{-- Off-canvas drawer --}}
<div x-cloak x-show="mobileNavOpen" id="mobile-nav" role="dialog" aria-modal="true" aria-label="Main navigation"
    x-data="{
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    x-init="$watch('mobileNavOpen', value => {
        if (value) {
            $nextTick(() => firstFocusable()?.focus())
        } else {
            document.getElementById('mobile-nav-toggle')?.focus()
        }
    })"
    @keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    @keydown.shift.tab.prevent="prevFocusable().focus()"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[80vw] flex-col bg-white shadow-xl md:hidden">
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-4">
        <span class="text-lg font-semibold">Career Booster</span>

        <button type="button" @click="mobileNavOpen = false"
            class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#138a9e]">
            <span class="sr-only">Close navigation menu</span>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    @if (auth()->user()->role === 'company_owner')
        <div class="border-b border-gray-200 px-4 py-4">
            <p class="truncate text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
            <p class="mt-1 text-xs text-gray-500">Company owner</p>
            <a href="{{ route('profile.edit') }}" class="mt-2 inline-flex text-xs font-medium text-[#0e6378] hover:underline">
                My account
            </a>
        </div>
    @endif

    @include('layouts.partials.nav-links', ['items' => $navItems, 'closeOnNavigate' => true])

    <div class="shrink-0 border-t border-gray-200 p-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <x-danger-button class="w-full justify-center">log out</x-danger-button>
        </form>
    </div>
</div>
