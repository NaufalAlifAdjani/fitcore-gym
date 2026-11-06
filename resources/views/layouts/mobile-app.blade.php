@props([
    'title' => 'FitCore Athletic - Mobile App',
    'activeTab' => 'beranda',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#141414]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#141414">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
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
<body class="h-full bg-[#141414] text-white font-sans antialiased selection:bg-[#ED1B45] selection:text-white flex flex-col overscroll-y-none">

    <!-- Sticky Mobile Top Bar (Secondary Dark #141414 with border) -->
    <header class="sticky top-0 z-40 bg-[#141414]/95 backdrop-blur-md border-b border-white/10 pt-safe px-4 py-3 flex items-center justify-between">
        <a href="{{ route('mobile.member.dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-[#ED1B45] flex items-center justify-center text-white shadow-md shadow-[#ED1B45]/30 shrink-0">
                <svg width="18" height="18" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="white"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-poppins text-sm font-extrabold tracking-wider text-white">
                    FITCORE <span class="text-[#ED1B45]">ATHLETIC</span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#A1A1AA]">Member Portal</span>
            </div>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('profile.edit') }}" class="relative block" title="Profil Saya">
                <x-avatar-initial :initials="auth()->user()->name ?? 'MB'" color_theme="red" class="w-8 h-8 text-xs border border-white/20 shadow-sm" />
            </a>
        </div>
    </header>

    <!-- Scrollable Main Content Area with pb-24 -->
    <main class="flex-1 w-full max-w-md mx-auto px-4 py-5 overflow-y-auto pb-24">
        {{ $slot }}
    </main>

    <!-- Fixed Bottom Tab Bar (5 Tabs per stitch-prompts #Shared UI Patterns) -->
    <nav class="fixed bottom-0 inset-x-0 z-50 bg-[#141414]/95 backdrop-blur-lg border-t border-white/10 pb-safe shadow-[0_-8px_20px_rgba(0,0,0,0.5)]"
         x-data="{ currentTab: '{{ $activeTab }}' }">
        <div class="max-w-md mx-auto px-3 flex items-center justify-between h-16">
            
            <!-- 1. Beranda -->
            <a href="{{ route('mobile.member.dashboard') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group"
               :class="currentTab === 'beranda' ? 'text-[#ED1B45]' : 'text-[#A1A1AA] hover:text-white'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Beranda</span>
            </a>

            <!-- 2. Sesi PT -->
            <a href="{{ route('mobile.member.pt-sessions.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group"
               :class="currentTab === 'sessions' ? 'text-[#ED1B45]' : 'text-[#A1A1AA] hover:text-white'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Sesi PT</span>
            </a>

            <!-- 3. Floating Center Action Button: Kartu Member Digital QR (#4) -->
            <div class="flex-1 flex justify-center -mt-6">
                <a href="{{ route('mobile.member.card') }}"
                   class="relative w-13 h-13 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white flex items-center justify-center shadow-[0_6px_20px_rgba(237,27,69,0.55)] border-[3px] border-[#141414] hover:scale-105 active:scale-95 transition-all group"
                   title="Buka QR Pass">
                    <svg class="w-6 h-6 transition group-hover:rotate-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <!-- Pulse badge indicator -->
                    <span class="absolute -top-0.5 -right-0.5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                </a>
            </div>

            <!-- 4. Paket -->
            <a href="{{ route('member.membership.packages') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group"
               :class="currentTab === 'packages' ? 'text-[#ED1B45]' : 'text-[#A1A1AA] hover:text-white'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Paket</span>
            </a>

            <!-- 5. Riwayat -->
            <a href="{{ route('mobile.member.riwayat-transaksi.index') }}"
               class="flex flex-col items-center justify-center flex-1 py-1 transition group"
               :class="currentTab === 'history' ? 'text-[#ED1B45]' : 'text-[#A1A1AA] hover:text-white'">
                <svg class="w-5 h-5 transition group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-[10px] font-semibold mt-1">Riwayat</span>
            </a>

        </div>
    </nav>

    <!-- Global Toast & Verification Modal -->
    <x-toast />
    <x-email-verified-modal />

</body>
</html>
