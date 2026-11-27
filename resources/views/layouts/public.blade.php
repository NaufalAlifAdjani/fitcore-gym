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
    $isPtPricing = request()->routeIs('landing.pt-pricing') || $activeTab === 'pt-pricing';
    $isTrainers = request()->routeIs('landing.trainers') || $activeTab === 'trainers';
    $isAbout = request()->routeIs('landing.about') || $activeTab === 'about';
    $isHome = (request()->routeIs('landing.index') || $activeTab === 'home') && ! $isPackages && ! $isPtPricing && ! $isTrainers && ! $isAbout;
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
                <img src="{{ asset('images/logo/logo.png') }}" alt="FitCore Athletic" class="h-8 sm:h-9 w-auto object-contain">
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 sm:gap-2">
                <a href="{{ route('landing.index') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isHome ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                    Beranda
                </a>
                <a href="{{ route('landing.packages') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isPackages ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                    Paket Gym
                </a>
                <a href="{{ route('landing.pt-pricing') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isPtPricing ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                    Harga Sesi PT
                </a>
                <a href="{{ route('landing.trainers') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isTrainers ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                    Pelatih PT
                </a>
                <a href="{{ route('landing.about') }}"
                    class="px-5 py-2 text-xs font-bold rounded-full transition-all duration-200 {{ $isAbout ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                    Tentang Kami
                </a>
            </nav>

            <!-- Action Buttons (Masuk / Daftar / Dashboard) -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold transition shadow-xs">
                        Ke Dashboard
                    </a>
                @else
                    @if (! request()->routeIs('login'))
                        <a href="{{ route('login') }}" class="text-xs font-bold text-[#565A66] hover:text-[#16151A] transition px-2 py-2">
                            Masuk
                        </a>
                    @endif
                    @if (Route::has('register') && ! request()->routeIs('register'))
                        <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold transition shadow-xs">
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
            <a href="{{ route('landing.pt-pricing') }}" class="block px-4 py-2 text-xs font-bold rounded-xl {{ $isPtPricing ? 'bg-[#FF5722] text-white' : 'text-[#565A66]' }}">Harga Sesi PT</a>
            <a href="{{ route('landing.trainers') }}" class="block px-4 py-2 text-xs font-bold rounded-xl {{ $isTrainers ? 'bg-[#FF5722] text-white' : 'text-[#565A66]' }}">Pelatih PT</a>
            <a href="{{ route('landing.about') }}" class="block px-4 py-2 text-xs font-bold rounded-xl {{ $isAbout ? 'bg-[#FF5722] text-white' : 'text-[#565A66]' }}">Tentang Kami</a>
            <div class="pt-3 border-t border-[#E5E7EB] flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-center py-2.5 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-xs">Ke Dashboard</a>
                @else
                    @if (! request()->routeIs('login'))
                        <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-full border border-[#E5E7EB] text-xs font-bold text-[#16151A] hover:border-[#FF5722] hover:text-[#FF5722]">Masuk</a>
                    @endif
                    @if (Route::has('register') && ! request()->routeIs('register'))
                        <a href="{{ route('register') }}" class="block text-center py-2.5 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-xs">Daftar Member</a>
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
                    <a href="{{ route('landing.index') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="FitCore Athletic" class="h-9 w-auto object-contain">
                    </a>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Pusat kebugaran berstandar kejuaraan internasional, pelatih berlisensi tersertifikasi, dan kultur disiplin tanpa kompromi untuk performa atletik puncak.
                    </p>
                </div>

                <!-- Kolom 2: Jam Operasional -->
                <div class="space-y-3">
                    <h4 class="font-poppins font-bold text-sm text-[#16151A] uppercase tracking-wider">Jam Operasional</h4>
                    <div class="space-y-2 text-xs text-[#565A66]">
                        <div class="flex justify-between border-b border-[#E5E7EB] pb-1.5">
                            <span>Akses Member</span>
                            <span class="font-bold text-emerald-600">24 Jam Nonstop (24/7)</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E5E7EB] pb-1.5">
                            <span>Sesi Personal Trainer</span>
                            <span class="font-bold text-[#16151A]">06:00 – 22:00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Hari Libur Nasional</span>
                            <span class="font-bold text-emerald-600">Tetap Buka 24 Jam</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Navigasi Cepat -->
                <div class="space-y-3">
                    <h4 class="font-poppins font-bold text-sm text-[#16151A] uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2 text-xs text-[#565A66]">
                        <li><a href="{{ route('landing.packages') }}" class="hover:text-[#FF5722] transition">Katalog Paket Membership</a></li>
                        <li><a href="{{ route('landing.pt-pricing') }}" class="hover:text-[#FF5722] transition">Harga Sesi Personal Trainer</a></li>
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