@props([
    'title' => 'FitCore PT Portal',
    'active' => 'dashboard',
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
<body class="h-full bg-[#141414] text-white font-sans antialiased selection:bg-[#ED1B45] selection:text-white flex flex-col overscroll-y-none"
      x-data="{ drawerOpen: false }">

    <!-- Mobile Top Header (with Drawer Hamburger Trigger per stitch-prompts #18 PT) -->
    <header class="sticky top-0 z-40 bg-[#141414]/95 backdrop-blur-md border-b border-white/10 pt-safe px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button type="button" @click="drawerOpen = true" class="p-1.5 text-[#A1A1AA] hover:text-white rounded-lg focus:outline-none cursor-pointer" aria-label="Buka Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#ED1B45] flex items-center justify-center text-white font-black text-xs shadow-md">
                    PT
                </div>
                <span class="font-poppins text-sm font-extrabold tracking-wider text-white">
                    FITCORE <span class="text-[#ED1B45]">COACH</span>
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-avatar-initial :initials="auth()->user()->name ?? 'PT'" color_theme="red" class="w-8 h-8 text-xs border border-white/20 shadow-sm" />
        </div>
    </header>

    <!-- Off-Canvas Drawer Navigation Dari Kiri (stitch-prompts.md #18 PT) -->
    <div x-show="drawerOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-xs transition-opacity" @click="drawerOpen = false"></div>

        <div class="fixed inset-y-0 left-0 max-w-full flex">
            <div class="w-72 bg-[#141414] border-r border-white/10 p-5 flex flex-col justify-between text-white shadow-2xl">
                <div class="space-y-6">
                    <!-- Drawer Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-white/10">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-[#ED1B45] flex items-center justify-center font-bold text-xs text-white">
                                F
                            </div>
                            <span class="font-poppins font-black tracking-wide text-sm text-white">PORTAL TRAINER</span>
                        </div>
                        <button type="button" @click="drawerOpen = false" class="text-[#A1A1AA] hover:text-white p-1 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Menu Navigasi PT -->
                    <nav class="space-y-1.5 text-xs font-bold font-sans">
                        <a href="{{ route('trainer.dashboard') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ $active === 'dashboard' ? 'bg-[#ED1B45] text-white shadow-md' : 'text-[#A1A1AA] hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard Harian</span>
                        </a>

                        <a href="{{ route('trainer.sessions.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ $active === 'sessions' ? 'bg-[#ED1B45] text-white shadow-md' : 'text-[#A1A1AA] hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Jadwal Sesi Klien</span>
                        </a>

                        <a href="{{ route('trainer.clients.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ $active === 'clients' ? 'bg-[#ED1B45] text-white shadow-md' : 'text-[#A1A1AA] hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Member &amp; Klien Saya</span>
                        </a>

                        <a href="{{ route('trainer.availability.index') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ $active === 'availability' ? 'bg-[#ED1B45] text-white shadow-md' : 'text-[#A1A1AA] hover:text-white hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Ketersediaan Waktu</span>
                        </a>
                    </nav>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-white/10">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-3 rounded-full bg-white/5 border border-white/10 text-xs font-bold text-rose-400 flex items-center justify-center gap-2 hover:bg-rose-500/10 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <main class="flex-1 w-full max-w-md mx-auto px-4 py-5 overflow-y-auto pb-16">
        {{ $slot }}
    </main>

    <x-toast />
</body>
</html>
