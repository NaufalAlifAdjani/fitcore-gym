{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Mobile Trainer Layout Shell
VIEW: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@props([
    'title' => 'FitCore Coach - Trainer Portal',
    'activeTab' => 'dashboard',
    'active' => null,
])

@php
    $tab = $activeTab ?? $active ?? 'dashboard';
    $currentRoute = request()->route()?->getName() ?? '';
    $currentPath = request()->path();

    $resolvedTab = match (true) {
        in_array($tab, ['sessions', 'jadwal']) || str_starts_with($currentRoute, 'mobile.trainer.sessions') || str_starts_with($currentPath, 'm/trainer/sessions') => 'sessions',
        in_array($tab, ['clients', 'klien']) || str_starts_with($currentRoute, 'mobile.trainer.clients') || str_starts_with($currentPath, 'm/trainer/clients') => 'clients',
        in_array($tab, ['profile', 'profil']) || str_starts_with($currentRoute, 'mobile.trainer.profile') || str_starts_with($currentPath, 'm/trainer/profile') => 'profile',
        default => 'dashboard',
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F6F6F7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#F6F6F7">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Google Fonts: Inter & Poppins (DESIGN.md) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 16px); }
        .pt-safe { padding-top: env(safe-area-inset-top, 0px); }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#F6F6F7] text-[#16151A] font-sans antialiased selection:bg-[#FF5722] selection:text-white flex flex-col overscroll-y-none">

    <!-- Sticky Mobile Top Bar (Clean Light Background with subtle border) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E5E7EB] pt-safe px-4 py-3 flex items-center justify-between shadow-xs">
        <a href="{{ route('mobile.trainer.dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-[#FF5722] to-[#FFB703] flex items-center justify-center text-white shadow-md shadow-[#FF5722]/25 shrink-0 font-poppins font-black text-xs">
                PT
            </div>
            <div class="flex flex-col">
                <span class="font-poppins text-sm font-extrabold tracking-wider text-[#16151A]">
                    FITCORE <span class="text-[#FF5722]">COACH</span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#565A66]">Trainer Portal</span>
            </div>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('mobile.trainer.profile.index') }}" class="relative block" title="Profil Trainer">
                <x-avatar-initial :initials="auth()->user()->name ?? 'PT'" color_theme="red" class="w-8 h-8 text-xs border border-[#E5E7EB] shadow-xs" />
            </a>
        </div>
    </header>

    <!-- Scrollable Main Content Area with pb-24 -->
    <main class="flex-1 w-full max-w-md mx-auto px-4 py-5 overflow-y-auto pb-24">
        {{ $slot }}
    </main>

    <!-- Fixed Bottom Tab Bar (Light Theme per DESIGN.md & stitch-prompts #Shared UI Patterns) -->
    <nav class="fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur-lg border-t border-[#E5E7EB] pb-safe shadow-[0_-4px_16px_rgba(0,0,0,0.06)]"
         x-data="{ currentNavTab: '{{ $resolvedTab }}' }">
        <div class="max-w-md mx-auto px-3 flex items-center justify-between h-16">

            <!-- 1. Dashboard -->
            <a href="{{ route('mobile.trainer.dashboard') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'dashboard' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'dashboard' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Dashboard</span>
            </a>

            <!-- 2. Jadwal Sesi -->
            <a href="{{ route('mobile.trainer.sessions.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'sessions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'sessions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Jadwal Sesi</span>
            </a>

            <!-- 3. Klien Saya -->
            <a href="{{ route('mobile.trainer.clients.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'clients' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'clients' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Klien Saya</span>
            </a>

            <!-- 4. Profil -->
            <a href="{{ route('mobile.trainer.profile.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'profile' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'profile' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Profil</span>
            </a>

        </div>
    </nav>

    <!-- Global Toast -->
    <x-toast />

</body>
</html>