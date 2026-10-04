<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ mobileNavOpen: false }"
            @keydown.escape.window="mobileNavOpen = false"
            @resize.window="if (window.innerWidth >= 768) mobileNavOpen = false"
            x-effect="document.body.classList.toggle('overflow-hidden', mobileNavOpen); $refs.pageContent?.toggleAttribute('inert', mobileNavOpen)"
            class="min-h-screen bg-gray-100">

            {{-- Mobile top bar and page heading share one sticky block --}}
            <div class="sticky top-0 z-30 md:ml-[250px]">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="border-b border-gray-200 bg-white shadow-sm">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
            </div>

            <div x-ref="pageContent" class="min-h-screen md:ml-[250px]">
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
