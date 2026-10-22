<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MAXFIT') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#100d1a] text-gray-900 min-h-screen flex flex-col">

    <!-- Top Navigation Bar Component -->
    <x-navbar />

    <!-- Split Screen Main Layout -->
    <main class="flex-1 grid grid-cols-1 lg:grid-cols-2" x-data="{ role: 'member', showPassword: false }">
        <!-- Left Side: Hero Banner Component -->
        <x-auth-hero />

        <!-- Right Side: Form Container Slot -->
        <div class="bg-white p-8 sm:p-12 lg:p-16 flex items-center justify-center">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </main>

</body>
</html>
