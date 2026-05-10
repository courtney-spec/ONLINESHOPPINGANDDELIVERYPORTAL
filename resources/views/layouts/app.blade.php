<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'StyleHub' }} — StyleHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 shadow-sm transform transition-transform duration-300
                  lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Logo --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-100">
            <span class="text-2xl">🛒</span>
            <span class="font-display font-bold text-xl text-gray-900">StyleHub</span>
        </div>

        {{-- Role Badge --}}
        <div class="px-6 py-4">
            @php
                $role = auth()->user()->role;
                $badgeColor = match($role) {
                    'admin'           => 'bg-purple-100 text-purple-700',
                    'product_manager' => 'bg-blue-100 text-blue-700',
                    default           => 'bg-orange-100 text-orange-700',
                };
                $roleLabel = match($role) {
                    'admin'           => '⚙️ Administrator',
                    'product_manager' => '📦 Product Manager',
                    default           => '🛍️ Customer',
                };
            @endphp
            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $badgeColor }}">
                {{ $roleLabel }}
            </span>
            <p class="mt-2 text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
        </div>

        {{-- Navigation --}}
        <nav class="px-4 pb-4 space-y-1">
            {{ $sidebar ?? '' }}
        </nav>

        {{-- Logout --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div class="fixed inset-0 bg-black/40 z-40 lg:hidden" x-show="sidebarOpen" x-on:click="sidebarOpen = false"></div>

    {{-- Main content --}}
    <div class="lg:ml-64">
        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button class="lg:hidden text-gray-500 hover:text-gray-700" x-on:click="sidebarOpen = !sidebarOpen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="font-display font-semibold text-gray-900 text-lg">{{ $title ?? 'Dashboard' }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
