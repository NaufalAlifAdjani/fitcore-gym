@props([
    'title' => 'FitCore Athletic - Premium Gym & Performance Center',
    'activeNav' => 'packages',
])

<!DOCTYPE html>
<<<<<<< HEAD
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F6F6F7]">
=======
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
>>>>>>> origin/feature/theme-blaze-orange
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

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
<<<<<<< HEAD
    <body class="bg-[#F6F6F7] font-sans antialiased text-[#16151A] selection:bg-[#ED1B45] selection:text-white flex flex-col min-h-screen">
        <div class="min-h-screen flex flex-col justify-between bg-[#F6F6F7]">
=======
    <body class="bg-[#F8F9FA] font-sans antialiased text-[#16151A] selection:bg-[#FF5722] selection:text-white">
        <div class="min-h-screen flex flex-col justify-between bg-[#F8F9FA]">
>>>>>>> origin/feature/theme-blaze-orange
            <x-member-navbar :active="$activeNav" />

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        <x-toast />
        <x-email-verified-modal />
    </body>
</html>
