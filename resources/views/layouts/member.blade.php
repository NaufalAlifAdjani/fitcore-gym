<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'FitCore Athletic - Premium Gym & Performance Center' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
        
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#0B0F17] font-sans antialiased text-slate-100 selection:bg-[#BA0030] selection:text-white">
        <div class="min-h-screen flex flex-col justify-between bg-[#0B0F17]">
            <x-member-navbar :active="$activeNav ?? 'packages'" />

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
