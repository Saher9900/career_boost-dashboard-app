<nav class="cus-nav">
    <div class="cus-logo">
        <h1>Career Booster</h1>
    </div>
    <ul class="cus-nav-links">
        @if (auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif
                    class="{{ request()->routeIs('dashboard') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('companies.index') }}" @if (request()->routeIs('companies.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('companies.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Companies</a>
            </li>
            <li>
                <a href="{{ route('job-applications.index') }}"
                    @if (request()->routeIs('job-applications.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('job-applications.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Job
                    Applications</a>
            </li>
            <li>
                <a href="{{ route('job-categories.index') }}"
                    @if (request()->routeIs('job-categories.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('job-categories.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Job
                    Categories</a>
            </li>
            <li>
                <a href="{{ route('job-vacancies.index') }}"
                    @if (request()->routeIs('job-vacancies.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('job-vacancies.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Job
                    Vacancies</a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" @if (request()->routeIs('users.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('users.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Users</a>
            </li>
        @elseif (auth()->user()->role === 'company_owner')
            <li>
                <a href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif
                    class="{{ request()->routeIs('dashboard') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('my-company.show', ['jobs' => 'true']) }}" @if (request()->routeIs('my-company.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('my-company.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">My
                    Company</a>
            </li>
            <li>
                <a href="{{ route('job-applications.index') }}"
                    @if (request()->routeIs('job-applications.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('job-applications.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Job
                    Applications</a>
            </li>
            <li>
                <a href="{{ route('my-job-vacancies.index') }}"
                    @if (request()->routeIs('my-job-vacancies.*')) aria-current="page" @endif
                    class="{{ request()->routeIs('my-job-vacancies.*') ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">Job
                    Vacancies</a>
            </li>
        @endif
        <form action="{{ route('logout') }}" method="POST" class="cus-logout">
            @csrf
            <button type="submit">
                <x-danger-button>
                    log out
                </x-danger-button>
            </button>
        </form>
    </ul>
</nav>

{{-- main #13899e second #0e6378 --}}

<style>
    .cus-nav {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 250px;
        overflow-y: auto;
        z-index: 20;
        display: flex;
        flex-direction: column;
    }

    .cus-logo h1 {
        font-size: 25px;
        display: flex;
        justify-content: center;
        height: 73px;
        border-bottom: 1px solid #000;
        align-items: center;
    }

    .cus-nav-links {
        margin-top: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .cus-nav-links li {
        width: 100%;
    }

    .cus-nav-links li a {
        display: flex;
        border-bottom: 1px solid #13899e;
        width: 100%;
        padding-left: 15px;
        height: 50px;
        align-items: center;
        transition: .2s;
    }

    .cus-nav-links li a:hover {
        background-color: #0e6378;
        color: white;
    }

    .cus-logout {
        width: 100%;
        padding-left: 15px;
        margin-top: 30px;
    }
</style>
