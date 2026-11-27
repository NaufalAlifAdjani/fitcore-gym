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
    'wide' => false,
])

@php
    $tab = $activeTab ?? $active ?? 'dashboard';
    $currentRoute = request()->route()?->getName() ?? '';
    $currentPath = request()->path();

    $resolvedTab = match (true) {
        in_array($tab, ['sessions', 'jadwal']) || str_starts_with($currentRoute, 'mobile.trainer.sessions') || str_starts_with($currentPath, 'm/trainer/sessions') => 'sessions',
        in_array($tab, ['clients', 'klien']) || str_starts_with($currentRoute, 'mobile.trainer.clients') || str_starts_with($currentPath, 'm/trainer/clients') => 'clients',
        in_array($tab, ['commissions', 'komisi', 'pendapatan']) || str_starts_with($currentRoute, 'mobile.trainer.commissions') || str_starts_with($currentPath, 'm/trainer/commissions') => 'commissions',
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
    @livewireStyles
</head>
<body class="h-full bg-[#F6F6F7] text-[#16151A] font-sans antialiased selection:bg-[#FF5722] selection:text-white flex flex-col overscroll-y-none">

    <!-- Sticky Mobile Top Bar (Clean Light Background with subtle border) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E5E7EB] pt-safe px-4 py-2.5 sm:py-3 flex items-center justify-between shadow-xs">
        <a href="{{ route('mobile.trainer.dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo/logo.png') }}" class="h-5 sm:h-5.5 w-auto object-contain" alt="FitCore">
        </a>

        <div class="flex items-center gap-2">
            <div class="relative" x-data="{ profileOpen: false }">
                <button type="button" @click="profileOpen = !profileOpen" class="relative block cursor-pointer focus:outline-none" title="Menu Profil">
                    @php
                        $userFirstName = strtolower(explode(' ', trim(str_replace('Coach ', '', auth()->user()->name ?? '')))[0] ?? '');
                        $userPhoto = ($userFirstName && file_exists(public_path("images/trainers/coach-{$userFirstName}.jpg")))
                            ? asset("images/trainers/coach-{$userFirstName}.jpg")
                            : (auth()->user()->trainerProfile?->photo_path ? asset('storage/' . auth()->user()->trainerProfile->photo_path) : null);
                    @endphp
                    @if ($userPhoto)
                        <img src="{{ $userPhoto }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-[#E5E7EB] shadow-xs">
                    @else
                        <x-avatar-initial :initials="auth()->user()->name ?? 'PT'" color_theme="red" class="w-8 h-8 text-xs border border-[#E5E7EB] shadow-xs" />
                    @endif
                </button>

                <!-- Profile Dropdown -->
                <div x-cloak x-show="profileOpen" @click.away="profileOpen = false"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-52 rounded-2xl bg-white border border-[#E5E7EB] shadow-xl p-2 z-50 text-sm">
                    <div class="border-b border-[#E5E7EB] px-3 py-2 mb-1">
                        <p class="truncate text-xs font-bold text-[#16151A]">{{ Auth::user()?->name }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="block rounded-xl px-3 py-2 text-xs font-semibold text-[#565A66] hover:bg-orange-50/70 hover:text-[#16151A] transition-colors">
                        Pengaturan Profil
                    </a>

                    <a href="{{ route('profile.edit') }}#password"
                        class="block rounded-xl px-3 py-2 text-xs font-semibold text-[#565A66] hover:bg-orange-50/70 hover:text-[#16151A] transition-colors">
                        Ganti Kata Sandi
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl px-3 py-2 text-left text-xs font-bold text-[#FF5722] hover:bg-orange-50 cursor-pointer transition-colors">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Scrollable Main Content Area with pb-28 -->
    <main class="flex-1 w-full {{ ($wide ?? false) ? 'max-w-5xl' : 'max-w-md' }} mx-auto px-3 sm:px-4 py-4 sm:py-5 overflow-y-auto pb-28">
        {{ $slot }}
    </main>

    <!-- Fixed Bottom Tab Bar (Light Theme Glassmorphism) -->
    <nav class="fixed bottom-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-t border-gray-100 pb-safe shadow-sm"
         x-data="{ currentNavTab: '{{ $resolvedTab }}' }">
        <div class="max-w-md mx-auto px-3 flex items-center justify-between h-16">

            <!-- 1. Dashboard -->
            <a href="{{ route('mobile.trainer.dashboard') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'dashboard' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'dashboard' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Dashboard</span>
            </a>

            <!-- 2. Jadwal Sesi -->
            <a href="{{ route('mobile.trainer.sessions.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'sessions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'sessions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Jadwal Sesi</span>
            </a>

            <!-- 3. Klien Saya -->
            <a href="{{ route('mobile.trainer.clients.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'clients' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'clients' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Klien</span>
            </a>

            <!-- 4. Pendapatan / Komisi -->
            <a href="{{ route('mobile.trainer.commissions.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'commissions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'commissions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Komisi</span>
            </a>

            <!-- 5. Profil -->
            <a href="{{ route('mobile.trainer.profile.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'profile' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'profile' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Profil</span>
            </a>

        </div>
    </nav>

    <!-- Global Toast -->
    <x-toast />

    @livewireScriptConfig
    @stack('scripts')
</body>
</html>