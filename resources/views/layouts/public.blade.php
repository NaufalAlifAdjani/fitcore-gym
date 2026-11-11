{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Web Desktop UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK & AUTHENTICATED
| MODUL        : Public Desktop Layout Shell
| FILE         : resources/views/layouts/public.blade.php
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
| DESKRIPSI    : Layout shell web desktop untuk halaman informasi publik
|                (Landing Page, Katalog Paket, Profil Trainer, Tentang Kami).
|--------------------------------------------------------------------------
--}}

@props([
    'title' => 'FitCore Athletic - Gym & Performance Center',
    'activeTab' => 'home',
])

@php
    $isPackages = request()->routeIs('landing.packages') || $activeTab === 'packages';
    $isTrainers = request()->routeIs('landing.trainers') || $activeTab === 'trainers';
    $isAbout = request()->routeIs('landing.about') || $activeTab === 'about';
    $isHome = (request()->routeIs('landing.index') || $activeTab === 'home') && ! $isPackages && ! $isTrainers && ! $isAbout;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F6F6F7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Google Fonts: Inter & Poppins (DESIGN.md) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-[#F6F6F7] text-[#16151A] font-sans antialiased selection:bg-[#FF5722] selection:text-white">

    <!-- 1. STICKY TOP NAVBAR DESKTOP -->
    <header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-[#E5E7EB] shadow-xs" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ route('landing.index') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#FF5722] to-[#FFB703] flex items-center justify-center text-white shadow-md shadow-[#FF5722]/30 transition group-hover:scale-105 shrink-0">
                    <svg width="22" height="22" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="currentColor"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-poppins font-black text-xl tracking-tight text-[#16151A]">
                        FIT <span class="text-[#FF5722]">CORE</span>
                    </span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-[#565A66]">ATHLETIC CENTER</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 bg-[#F6F6F7] p-1.5 rounded-full border border-[#E5E7EB]">
                <a href="{{ route('landing.index') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isHome ? 'bg-[#FF5722] text-white shadow-sm shadow-[#FF5722]/25' : 'text-[#565A66] hover:text-[#FF5722]' }}">
                    Beranda
                </a>
                <a href="{{ route('landing.packages') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isPackages ? 'bg-[#FF5722] text-white shadow-sm shadow-[#FF5722]/25' : 'text-[#565A66] hover:text-[#FF5722]' }}">
                    Paket Gym
                </a>
                <a href="{{ route('landing.trainers') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isTrainers ? 'bg-[#FF5722] text-white shadow-sm shadow-[#FF5722]/25' : 'text-[#565A66] hover:text-[#FF5722]' }}">
                    Pelatih PT
                </a>
                <a href="{{ route('landing.about') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isAbout ? 'bg-[#FF5722] text-white shadow-sm shadow-[#FF5722]/25' : 'text-[#565A66] hover:text-[#FF5722]' }}">
                    Tentang Kami
                </a>
            </nav>

            <!-- Action Buttons (Masuk / Daftar / Dashboard) -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold transition shadow-sm shadow-[#FF5722]/30">
                        Ke Dashboard ({{ Auth::user()->name }})
                    </a>
                @else
                    @if (! request()->routeIs('login'))
                        <a href="{{ route('login') }}" class="px-4 py-2.5 text-xs font-bold text-[#565A66] hover:text-[#FF5722] transition">
                            Masuk
                        </a>
                    @endif
                    @if (Route::has('register') && ! request()->routeIs('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold transition shadow-sm shadow-[#FF5722]/30">
                            Daftar Member
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Mobile Hamburger Toggle -->
            <div class="md:hidden flex items-center">
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-[#16151A] hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu Dropdown -->
        <div x-cloak x-show="mobileMenuOpen" class="md:hidden border-t border-[#E5E7EB] bg-white px-4 py-4 space-y-2">
            <a href="{{ route('landing.index') }}" class="block px-4 py-2 text-xs font-bold rounded-xl {{ $isHome ? 'bg-[#FF5722] text-white' : 'text-[#565A66]' }}">Beranda</a>
            <a href="{{ route('landing.packages') }}" class="block px-4 py-2 text-xs font-bold rounded-xl {{ $isPackages ? 'bg-[#FF5722] text-white' : 'text-[#565A66]' }}">Paket Gym</a>
            <a href="{{ route('landing.trainers') }}" class="block px-4 py-2 text-xs font-bold rounded-xl {{ $isTrainers ? 'bg-[#FF5722] text-white' : 'text-[#565A66]' }}">Pelatih PT</a>
            <a href="{{ route('landing.about') }}" class="block px-4 py-2 text-xs font-bold rounded-xl {{ $isAbout ? 'bg-[#FF5722] text-white' : 'text-[#565A66]' }}">Tentang Kami</a>
            <div class="pt-3 border-t border-[#E5E7EB] flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-center py-2.5 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-sm shadow-[#FF5722]/30">Ke Dashboard</a>
                @else
                    @if (! request()->routeIs('login'))
                        <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-full border border-[#E5E7EB] text-xs font-bold text-[#16151A] hover:border-[#FF5722] hover:text-[#FF5722]">Masuk</a>
                    @endif
                    @if (Route::has('register') && ! request()->routeIs('register'))
                        <a href="{{ route('register') }}" class="block text-center py-2.5 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-sm shadow-[#FF5722]/30">Daftar Member</a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <!-- 2. MAIN CONTENT -->
    <main class="flex-1 w-full">
        {{ $slot }}
    </main>

    <!-- 3. FOOTER DESKTOP -->
    <footer class="bg-white text-[#16151A] pt-16 pb-12 border-t border-[#E5E7EB] mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-[#E5E7EB]">
                <!-- Kolom 1: Brand & Filosofi -->
                <div class="md:col-span-1 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF5722] to-[#FFB703] flex items-center justify-center text-white shadow-md shadow-[#FF5722]/25">
                            <svg width="20" height="20" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                                <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="currentColor"/>
                            </svg>
                        </div>
                        <span class="font-poppins font-black text-xl text-[#16151A] tracking-wider">FITCORE</span>
                    </div>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Pusat kebugaran berstandar kejuaraan internasional, pelatih berlisensi tersertifikasi, dan kultur disiplin tanpa kompromi untuk performa atletik puncak.
                    </p>
                </div>

                <!-- Kolom 2: Jam Operasional -->
                <div class="space-y-3">
                    <h4 class="font-poppins font-bold text-sm text-[#16151A] uppercase tracking-wider">Jam Operasional</h4>
                    <div class="space-y-2 text-xs text-[#565A66]">
                        <div class="flex justify-between border-b border-[#E5E7EB] pb-1.5">
                            <span>Senin – Jumat</span>
                            <span class="font-bold text-[#16151A]">05:30 – 22:00 WIB</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E5E7EB] pb-1.5">
                            <span>Sabtu – Minggu</span>
                            <span class="font-bold text-[#16151A]">06:00 – 21:00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Hari Libur Nasional</span>
                            <span class="font-bold text-[#FF5722]">07:00 – 20:00 WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Navigasi Cepat -->
                <div class="space-y-3">
                    <h4 class="font-poppins font-bold text-sm text-[#16151A] uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2 text-xs text-[#565A66]">
                        <li><a href="{{ route('landing.packages') }}" class="hover:text-[#FF5722] transition">Katalog Paket Membership</a></li>
                        <li><a href="{{ route('landing.trainers') }}" class="hover:text-[#FF5722] transition">Daftar Personal Trainer</a></li>
                        <li><a href="{{ route('landing.about') }}" class="hover:text-[#FF5722] transition">Visi &amp; Galeri Fasilitas</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[#FF5722] transition">Portal Masuk Member</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Lokasi Studio -->
                <div class="space-y-3">
                    <h4 class="font-poppins font-bold text-sm text-[#16151A] uppercase tracking-wider">Studio SCBD</h4>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        FitCore Athletic Center, Sudirman Central Business District (SCBD) Lot 8, Jakarta Selatan 12190.
                    </p>
                    <p class="text-xs text-[#565A66]">
                        WhatsApp: <span class="font-bold text-[#16151A]">+62 812-0000-0001</span><br>
                        Email: <span class="font-bold text-[#16151A]">contact@fitcore.test</span>
                    </p>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#565A66]">
                <p>© {{ date('Y') }} FitCore Athletic Gym Indonesia. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-[#FF5722] transition">Syarat &amp; Ketentuan</a>
                    <a href="#" class="hover:text-[#FF5722] transition">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Notifikasi Global Toast -->
    <x-toast />

</body>
</html>