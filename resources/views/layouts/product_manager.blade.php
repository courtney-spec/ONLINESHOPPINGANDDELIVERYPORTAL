<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'StyleHub' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">

    {{-- ── Sidebar ── --}}
    <aside class="w-64 bg-white border-r border-gray-100 shadow-sm flex flex-col fixed inset-y-0 left-0 z-40">
        <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-100">
            <span class="text-2xl">🛒</span>
            <span class="font-display font-bold text-xl text-gray-900">StyleHub</span>
        </div>
        <div class="px-6 py-4 border-b border-gray-100">
            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-700 mb-2">
                📦 Product Manager
            </span>
            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            @php
                $navLinks = [
                    ['route' => 'product_manager.dashboard',          'icon' => '📊', 'label' => 'Dashboard'],
                    ['route' => 'product_manager.products.index',     'icon' => '📦', 'label' => 'All Products'],
                    ['route' => 'product_manager.products.create',    'icon' => '➕', 'label' => 'Add Product'],
                    ['route' => 'product_manager.categories.index',   'icon' => '📁', 'label' => 'Categories'],
                    ['route' => 'product_manager.categories.create',  'icon' => '🗂️', 'label' => 'Add Category'],
                ];
            @endphp
            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors
                          {{ request()->routeIs($link['route']) ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>{{ $link['icon'] }}</span>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="p-4 border-t border-gray-100">
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

    {{-- ── Main Content ── --}}
    <div class="ml-64 flex-1 flex flex-col">
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
            <h1 class="font-display font-semibold text-gray-900 text-lg">{{ $title ?? 'Dashboard' }}</h1>
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </header>

        <main class="p-6 flex-1">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
