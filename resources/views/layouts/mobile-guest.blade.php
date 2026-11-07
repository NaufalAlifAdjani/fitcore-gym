{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Guest Layout Shell
| FILE         : resources/views/layouts/mobile-guest.blade.php
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #ED1B45)
| DESKRIPSI    : Layout shell mobile khusus pengunjung publik (tanpa login).
|                Menyediakan sticky header dengan logo, tombol masuk pill merah,
|                chips navigasi horizontal (Beranda, Paket, Trainer, Tentang Kami),
|                serta footer publik. Tidak menampilkan Bottom Navbar member/trainer.
|--------------------------------------------------------------------------
--}}

@props([
    'title' => 'FitCore Athletic - Gym & Performance Center',
    'activeTab' => 'home',
    'hideNav' => false,
])

@php
    $isAuth = $hideNav || request()->routeIs('mobile.login') || request()->routeIs('mobile.register');

    $isPackages = request()->routeIs('mobile.landing.packages') || $activeTab === 'packages';
    $isTrainers = request()->routeIs('mobile.landing.trainers') || $activeTab === 'trainers';
    $isAbout = request()->routeIs('mobile.landing.about') || $activeTab === 'about';
    $isHome = (request()->routeIs('mobile.landing.index') || $activeTab === 'home') && ! $isPackages && ! $isTrainers && ! $isAbout;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F6F6F7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#F6F6F7">
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
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#F6F6F7] text-[#16151A] font-sans antialiased selection:bg-[#ED1B45] selection:text-white flex flex-col justify-between {{ $isAuth ? 'py-6 px-4 sm:px-6 pt-safe pb-safe' : 'pt-safe' }}">

    @if ($isAuth)
        <!-- Konten Auth Khusus Login & Register -->
        <main class="w-full max-w-sm mx-auto my-auto">
            {{ $slot }}
        </main>
    @else
        <!-- 1. STICKY HEADER MOBILE GUEST -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E5E7EB] shadow-xs">
            <!-- Brand Bar & Tombol Masuk -->
            <div class="px-4 py-3 flex items-center justify-between">
                <a href="{{ route('mobile.landing.index') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-[#ED1B45] flex items-center justify-center text-white shadow-sm shadow-[#ED1B45]/30 shrink-0">
                        <svg width="18" height="18" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                            <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-poppins font-black text-sm tracking-tight text-[#16151A] leading-none">
                            FIT <span class="text-[#ED1B45]">CORE</span>
                        </span>
                        <span class="text-[8px] font-bold tracking-widest text-[#565A66] uppercase mt-0.5">ATHLETIC</span>
                    </div>
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('mobile.login') }}" class="px-4 py-1.5 rounded-full bg-[#ED1B45] hover:bg-[#d1123d] text-white text-xs font-bold shadow-sm shadow-[#ED1B45]/25 transition active:scale-95">
                        Masuk
                    </a>
                </div>
            </div>

            <!-- Horizontal Navigation Chips Bar -->
            <nav class="flex items-center gap-2 px-4 pb-2.5 overflow-x-auto no-scrollbar scroll-smooth text-xs">
                <a href="{{ route('mobile.landing.index') }}"
                    class="shrink-0 px-3.5 py-1.5 rounded-full font-bold transition duration-150 {{ $isHome ? 'bg-[#ED1B45] text-white shadow-sm' : 'bg-white text-[#565A66] hover:text-[#16151A] border border-[#E5E7EB]' }}">
                    Beranda
                </a>
                <a href="{{ route('mobile.landing.packages') }}"
                    class="shrink-0 px-3.5 py-1.5 rounded-full font-bold transition duration-150 {{ $isPackages ? 'bg-[#ED1B45] text-white shadow-sm' : 'bg-white text-[#565A66] hover:text-[#16151A] border border-[#E5E7EB]' }}">
                    Paket Gym
                </a>
                <a href="{{ route('mobile.landing.trainers') }}"
                    class="shrink-0 px-3.5 py-1.5 rounded-full font-bold transition duration-150 {{ $isTrainers ? 'bg-[#ED1B45] text-white shadow-sm' : 'bg-white text-[#565A66] hover:text-[#16151A] border border-[#E5E7EB]' }}">
                    Pelatih PT
                </a>
                <a href="{{ route('mobile.landing.about') }}"
                    class="shrink-0 px-3.5 py-1.5 rounded-full font-bold transition duration-150 {{ $isAbout ? 'bg-[#ED1B45] text-white shadow-sm' : 'bg-white text-[#565A66] hover:text-[#16151A] border border-[#E5E7EB]' }}">
                    Tentang Kami
                </a>
            </nav>
        </header>

        <!-- 2. KONTEN UTAMA HALAMAN GUEST -->
        <main class="flex-1 w-full max-w-md mx-auto">
            {{ $slot }}
        </main>

        <!-- 3. FOOTER MOBILE GUEST -->
        <footer class="bg-white border-t border-[#E5E7EB] mt-auto py-8 px-5 text-center">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-xl bg-[#ED1B45] flex items-center justify-center text-white shadow-sm shadow-[#ED1B45]/20 mb-3">
                    <svg width="20" height="20" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="currentColor"/>
                    </svg>
                </div>
                <span class="font-poppins font-black text-base text-[#16151A] tracking-tight">FITCORE <span class="text-[#ED1B45]">ATHLETIC</span></span>
                <p class="text-xs text-[#565A66] mt-1 max-w-xs leading-relaxed">
                    Pusat kebugaran berstandar kejuaraan dunia dengan pelatih berlisensi dan fasilitas pemulihan terbaik.
                </p>

                <div class="flex items-center justify-center gap-4 text-xs font-semibold text-[#565A66] my-4">
                    <a href="{{ route('mobile.landing.packages') }}" class="hover:text-[#ED1B45] transition">Paket</a>
                    <span>•</span>
                    <a href="{{ route('mobile.landing.trainers') }}" class="hover:text-[#ED1B45] transition">Pelatih</a>
                    <span>•</span>
                    <a href="{{ route('mobile.landing.about') }}" class="hover:text-[#ED1B45] transition">Tentang Kami</a>
                    <span>•</span>
                    <a href="{{ route('mobile.login') }}" class="hover:text-[#ED1B45] transition">Masuk Akun</a>
                </div>

                <div class="text-[11px] text-[#A1A1AA] pt-3 border-t border-[#E5E7EB] w-full">
                    © {{ date('Y') }} FitCore Athletic Gym. Hak Cipta Dilindungi.
                </div>
            </div>
        </footer>
    @endif

    <!-- Notifikasi Flash Global -->
    <x-toast />

</body>
</html>
