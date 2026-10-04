@php
    $closeOnNavigate = $closeOnNavigate ?? false;
@endphp

<nav class="flex-1 overflow-y-auto" @if ($closeOnNavigate) @click="mobileNavOpen = false" @endif>
    <ul class="py-4">
        @foreach ($items as $item)
            @php($isCurrent = request()->routeIs($item['pattern']))
            <li>
                <a href="{{ route($item['route'], $item['params'] ?? []) }}"
                    @if ($isCurrent) aria-current="page" @endif
                    class="flex h-[50px] w-full items-center border-b border-[#13899e] px-4 text-sm transition {{ $isCurrent ? 'bg-[#0e6378] font-semibold text-white' : 'text-gray-700 hover:bg-[#eaf5f6] hover:text-[#0e6378]' }}">
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
