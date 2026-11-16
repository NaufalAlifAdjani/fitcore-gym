@props([
    'title' => 'FitCore Athletic - Mobile App',
    'activeTab' => 'beranda',
])

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
        <a href="{{ route('mobile.member.dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo/logo.png') }}" class="h-5 sm:h-5.5 w-auto object-contain" alt="FitCore">
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('mobile.member.profile.index') }}" class="relative block" title="Profil Saya">
                <x-avatar-initial :initials="auth()->user()->name ?? 'MB'" color_theme="red" class="w-8 h-8 text-xs border border-[#E5E7EB] shadow-xs" />
            </a>
        </div>
    </header>

    <!-- Scrollable Main Content Area with pb-28 -->
    <main class="flex-1 w-full max-w-md mx-auto px-4 py-5 overflow-y-auto pb-28">
        {{ $slot }}
    </main>

    <!-- Fixed Bottom Tab Bar (Light Theme Glassmorphism) -->
    @php
        $currentRoute = request()->route()?->getName() ?? '';
        $currentPath = request()->path();
        $targetTab = $activeTab ?? $attributes->get('active-tab') ?? $attributes->get('activeTab') ?? '';

        $resolvedTab = match (true) {
            in_array($targetTab, ['packages', 'paket', 'pt-packages', 'membership-packages'])
                || str_starts_with($currentRoute, 'mobile.member.packages')
                || str_starts_with($currentRoute, 'mobile.member.pt-packages')
                || str_starts_with($currentRoute, 'member.pt-packages')
                || str_starts_with($currentRoute, 'member.membership')
                || $currentRoute === 'mobile.packages.index'
                || str_starts_with($currentPath, 'm/packages')
                || str_starts_with($currentPath, 'm/pt-packages')
                || str_starts_with($currentPath, 'member/pt-packages')
                || str_starts_with($currentPath, 'membership') => 'packages',

            in_array($targetTab, ['pt-sessions', 'sessions'])
                || (str_starts_with($currentRoute, 'mobile.member.pt-sessions') && !str_contains($currentRoute, 'pt-packages'))
                || (str_starts_with($currentPath, 'm/pt-sessions') && !str_contains($currentPath, 'pt-packages')) => 'pt-sessions',

            in_array($targetTab, ['card', 'qr'])
                || str_starts_with($currentRoute, 'mobile.member.card')
                || str_starts_with($currentPath, 'm/card') => 'card',

            in_array($targetTab, ['history', 'riwayat', 'riwayat-transaksi'])
                || str_starts_with($currentRoute, 'mobile.member.riwayat-transaksi')
                || str_starts_with($currentPath, 'm/riwayat-transaksi') => 'history',

            $targetTab === 'profile'
                || str_starts_with($currentRoute, 'mobile.member.profile')
                || str_starts_with($currentPath, 'm/profile') => 'profile',

            default => 'beranda',
        };
    @endphp

    <nav class="fixed bottom-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-t border-gray-100 pb-safe shadow-sm"
         x-data="{ currentNavTab: '{{ $resolvedTab }}' }">
        <div class="max-w-md mx-auto px-3 flex items-center justify-between h-16">
            
            <!-- 1. Beranda -->
            <a href="{{ route('mobile.member.dashboard') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'beranda' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'beranda' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Beranda</span>
            </a>

            <!-- 2. Sesi PT -->
            <a href="{{ route('mobile.member.pt-sessions.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'pt-sessions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'pt-sessions' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Sesi PT</span>
            </a>

            <!-- 3. Floating Center Action Button: Kartu Member Digital QR (Enlarged Halo Ring Style) -->
            <div class="flex-1 flex justify-center -mt-7">
                <a href="{{ route('mobile.member.card') }}"
                   class="group relative flex items-center justify-center p-1.5 rounded-full bg-orange-100/80 border border-orange-200/90 shadow-[0_8px_25px_rgba(255,87,34,0.30)] hover:scale-105 active:scale-95 transition-all duration-200"
                   title="Buka QR Pass">
                    <!-- Inner Circle Button -->
                    <div class="w-13 h-13 rounded-full bg-gradient-to-tr from-[#FF5722] to-[#FF8A65] text-white flex items-center justify-center border-2 border-white shadow-md shadow-orange-600/30 transition-transform group-hover:rotate-3">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <!-- Viewfinder 4 Corners (Like QRIS / Mobile Banking) -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2" />
                            <!-- Center QR Grid -->
                            <rect x="7" y="7" width="3.5" height="3.5" rx="0.75" stroke-width="1.8" />
                            <rect x="13.5" y="7" width="3.5" height="3.5" rx="0.75" stroke-width="1.8" />
                            <rect x="7" y="13.5" width="3.5" height="3.5" rx="0.75" stroke-width="1.8" />
                            <rect x="13.5" y="13.5" width="3.5" height="3.5" fill="currentColor" rx="0.75" />
                        </svg>
                    </div>

                    <!-- Pulse badge indicator -->
                    <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 ring-2 ring-white"></span>
                    </span>
                </a>
            </div>

            <!-- 4. Paket -->
            <a href="{{ route('mobile.member.packages') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'packages' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'packages' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Paket</span>
            </a>

            <!-- 5. Riwayat -->
            <a href="{{ route('mobile.member.riwayat-transaksi.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group {{ $resolvedTab === 'history' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]' }}"
               :class="currentNavTab === 'history' ? 'text-[#FF5722]' : 'text-[#565A66] hover:text-[#16151A]'">
                <svg class="w-6 h-6 transition group-hover:scale-105 active:scale-95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-[10px] font-semibold mt-1 tracking-tight">Riwayat</span>
            </a>

        </div>
    </nav>

    <!-- Global Toast & Verification Modal -->
    <x-toast />
    <x-email-verified-modal />

    @livewireScriptConfig
    @stack('scripts')
</body>
</html>