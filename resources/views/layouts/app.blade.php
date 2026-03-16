<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Elektron Qabulxona') — SamISI</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    {{-- ===== HEADER ===== --}}
    <header class="bg-primary-700 shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

            {{-- Logo + name --}}
            <a href="{{ route('appeals.index') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shrink-0 shadow">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo" class="w-11 h-11 object-cover">
                </div>
                <div class="leading-tight">
                    <p
                        class="text-white font-bold text-sm sm:text-base tracking-wide group-hover:opacity-90 transition">
                        SamISI</p>
                    <p class="text-primary-200 text-xs hidden sm:block">Elektron Qabulxona</p>
                </div>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
                <a href="{{ route('appeals.index') }}"
                    class="px-3 py-2 rounded-md text-primary-100 hover:bg-primary-600 hover:text-white transition {{ request()->routeIs('appeals.index') ? 'bg-primary-600 text-white' : '' }}">
                    Bosh sahifa
                </a>
                <a href="{{ route('appeals.create') }}"
                    class="px-3 py-2 rounded-md text-primary-100 hover:bg-primary-600 hover:text-white transition {{ request()->routeIs('appeals.create') ? 'bg-primary-600 text-white' : '' }}">
                    Murojaat yuborish
                </a>
                <a href="{{ route('tracking.index') }}"
                    class="px-3 py-2 rounded-md text-primary-100 hover:bg-primary-600 hover:text-white transition {{ request()->routeIs('tracking.*') ? 'bg-primary-600 text-white' : '' }}">
                    Murojaatni kuzatish
                </a>
            </nav>

            {{-- Mobile hamburger --}}
            <button x-data @click="$dispatch('toggle-menu')"
                class="md:hidden text-white p-2 rounded-md hover:bg-primary-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-data="{ open: false }" @toggle-menu.window="open = !open" x-show="open" x-cloak x-transition
            class="md:hidden border-t border-primary-600 bg-primary-800 px-4 py-2 space-y-1">
            <a href="{{ route('appeals.index') }}"
                class="block px-3 py-2 rounded text-primary-100 hover:bg-primary-700">Bosh sahifa</a>
            <a href="{{ route('appeals.create') }}"
                class="block px-3 py-2 rounded text-primary-100 hover:bg-primary-700">Murojaat yuborish</a>
            <a href="{{ route('tracking.index') }}"
                class="block px-3 py-2 rounded text-primary-100 hover:bg-primary-700">Murojaatni kuzatish</a>
        </div>
    </header>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="bg-primary-50 border-l-4 border-primary-500 text-primary-800 px-4 py-3 flex justify-between items-start max-w-6xl mx-auto w-full mt-4 rounded-r-lg shadow-sm">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-4 text-primary-600 hover:text-primary-900">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show"
            class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 flex justify-between items-start max-w-6xl mx-auto w-full mt-4 rounded-r-lg shadow-sm">
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="ml-4 text-red-600 hover:text-red-900">&times;</button>
        </div>
    @endif

    {{-- ===== MAIN ===== --}}
    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-gray-800 text-gray-300 mt-auto">
        <div class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 sm:grid-cols-3 gap-8 text-sm">

            <div>
                <h3 class="text-white font-semibold mb-3 text-base">SamISI Elektron Qabulxona</h3>
                <p class="text-gray-400 leading-relaxed">
                    Samarqand iqtisodiyot va servis instituti rektori virtual qabulxonasi.
                </p>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-3">Bog'lanish</h3>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Samarqand sh., Amir Temur ko'chasi
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z" />
                        </svg>
                        +998 (66) 231-03-93
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        info@sies.uz
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-3">Havolalar</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('appeals.index') }}"
                            class="text-gray-400 hover:text-white transition">Murojaat yuborish</a></li>
                    <li><a href="{{ route('tracking.index') }}"
                            class="text-gray-400 hover:text-white transition">Murojaatni kuzatish</a></li>
                    <li><a href="https://sies.uz" target="_blank"
                            class="text-gray-400 hover:text-white transition">Asosiy sayt →</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 px-4 py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} SamISI — Barcha huquqlar himoyalangan.
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
