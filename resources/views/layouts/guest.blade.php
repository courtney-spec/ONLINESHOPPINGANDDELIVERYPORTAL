<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'StyleHub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-amber-50">

    <div class="min-h-screen flex">

        {{-- Left branding panel --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-orange-500 to-amber-600 relative overflow-hidden items-center justify-center">
            {{-- Decorative circles --}}
            <div class="absolute top-[-60px] left-[-60px] w-64 h-64 rounded-full bg-white/10"></div>
            <div class="absolute bottom-[-80px] right-[-40px] w-80 h-80 rounded-full bg-white/10"></div>
            <div class="absolute top-1/3 right-[-30px] w-40 h-40 rounded-full bg-amber-400/40"></div>

            <div class="relative z-10 text-white text-center px-12">
                <div class="text-6xl mb-6">🛒</div>
                <h1 class="font-display text-5xl font-800 leading-tight mb-4">StyleHub</h1>
                <p class="text-orange-100 text-lg leading-relaxed">
                    Your all-in-one online shopping<br>& delivery management portal
                </p>
                <div class="mt-10 flex flex-col gap-3 text-left">
                    <div class="flex items-center gap-3 bg-white/20 rounded-xl px-4 py-3">
                        <span class="text-2xl">🛍️</span>
                        <span class="text-sm font-medium">Browse thousands of products</span>
                    </div>
                    <div class="flex items-center gap-3 bg-white/20 rounded-xl px-4 py-3">
                        <span class="text-2xl">🚚</span>
                        <span class="text-sm font-medium">Real-time delivery tracking</span>
                    </div>
                    <div class="flex items-center gap-3 bg-white/20 rounded-xl px-4 py-3">
                        <span class="text-2xl">🔒</span>
                        <span class="text-sm font-medium">Secure & seamless checkout</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right form panel --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    <span class="font-display text-3xl font-bold text-orange-600">🛒 StyleHub</span>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
