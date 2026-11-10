@props([
    'active' => null,
])

@php
    $isBeranda = request()->routeIs('member.dashboard') || $active === 'beranda';
    $isCard = request()->routeIs('member.card*') || $active === 'card';
    $isPt = request()->routeIs('member.pt-packages.*') || $active === 'pt';
    $isBooking = request()->routeIs('pt-sessions.*') || $active === 'booking';
    $isRiwayat = request()->routeIs('member.riwayat-transaksi.*') || $active === 'riwayat';
    $isPackages = (request()->routeIs('member.membership.*') || $active === 'packages') && !$isBeranda && !$isCard && !$isPt && !$isBooking && !$isRiwayat;
@endphp

<header class="sticky top-0 z-40 w-full border-b border-gray-200/80 bg-white/95 backdrop-blur-md"
    x-data="{ mobileMenuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        <!-- Logo -->
        <a href="{{ route('member.dashboard') }}" class="flex items-center gap-2 group">
            <img src="{{ asset('images/logo/logo.png') }}" alt="FITCORE ATHLETIC"
                class="h-8 sm:h-9 w-auto object-contain transition duration-200 group-hover:scale-105">
            <span class="sr-only">FITCORE ATHLETIC</span>
        </a>

        <!-- Desktop Navigation Items -->
        <nav>
            <a href="{{ route('member.dashboard') }}" @class([
                'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                'bg-[#FF5722] text-white shadow-md' => $isBeranda,
                'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !$isBeranda,
            ])>
                Beranda
            </a>
            <a href="{{ route('member.card') }}" @class([
                'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                'bg-[#FF5722] text-white shadow-md' => $isCard,
                'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !$isCard,
            ])>
                Kartu Member
            </a>
            <a href="{{ route('member.membership.packages') }}" @class([
                'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                'bg-[#FF5722] text-white shadow-md shadow-[#FF5722]/30' => $isPackages,
                'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !$isPackages,
            ])>
                Paket Membership
            </a>
            <a href="{{ route('member.pt-packages.index') }}" @class([
                'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                'bg-[#FF5722] text-white shadow-md' => $isPt,
                'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !$isPt,
            ])>
                Personal Trainer
            </a>
            <a href="{{ route('pt-sessions.index') }}" @class([
                'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                'bg-[#FF5722] text-white shadow-md' => $isBooking,
                'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !$isBooking,
            ])>
                Booking Sesi
            </a>
            <a href="{{ route('member.riwayat-transaksi.index') }}" @class([
                'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                'bg-[#FF5722] text-white shadow-md' => $isRiwayat,
                'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !$isRiwayat,
            ])>
                Riwayat Transaksi
            </a>
        </nav>

        <!-- User Profile Avatar -->
        <div class="flex items-center gap-3" x-data="{ userMenuOpen: false }">
            <div class="relative" x-on:click.outside="userMenuOpen = false">
                <button type="button" x-on:click="userMenuOpen = !userMenuOpen"
                    class="flex items-center gap-2.5 rounded-full bg-white border border-gray-200 p-1.5 pr-3 transition hover:border-[#FF5722]/50 shadow-xs">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#FF5722] font-heading text-xs font-bold uppercase text-white shadow-sm">
                        {{ \Illuminate\Support\Str::of(Auth::user()?->name ?? 'M')->explode(' ')->map(fn($p) => \Illuminate\Support\Str::substr($p, 0, 1))->take(2)->implode('') }}
                    </span>
                    <span class="hidden sm:inline-block text-xs font-bold text-zinc-800">
                        {{ Auth::user()?->name ?? 'Member FitCore' }}
                    </span>
                    <svg class="h-4 w-4 text-zinc-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m7 10 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div x-cloak x-show="userMenuOpen" x-transition
                    class="absolute right-0 top-12 z-50 w-56 rounded-2xl border border-gray-200 bg-white p-2 shadow-xl">
                    <div class="border-b border-gray-100 px-3 py-2">
                        <p class="truncate text-xs font-bold text-zinc-900">{{ Auth::user()?->name }}</p>
                        <p class="truncate text-[10px] text-zinc-500">{{ Auth::user()?->email }}</p>
                    </div>
                    <a href="{{ route('member.card') }}"
                        class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                        Kartu Member Digital
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                        Pengaturan Profil
                    </a>
                    <a href="{{ route('member.membership.packages') }}"
                        class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                        Status Membership
                    </a>
                    @if (Auth::user()?->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-amber-600 hover:bg-amber-50">
                            Portal Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl px-3 py-2.5 text-left text-xs font-bold text-[#FF5722] hover:bg-orange-50 cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button type="button" x-on:click="mobileMenuOpen = !mobileMenuOpen"
                class="inline-flex md:hidden items-center justify-center rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 transition"
                aria-label="Toggle Navigation">
                <svg x-show="!mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-cloak x-show="mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-cloak x-show="mobileMenuOpen" x-transition
        class="md:hidden border-t border-gray-200 bg-white px-4 pt-3 pb-5 space-y-2">
        <a href="{{ route('member.dashboard') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isBeranda ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Beranda
        </a>
        <a href="{{ route('member.card') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isCard ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Kartu Member
        </a>
        <a href="{{ route('member.membership.packages') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isPackages ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Paket Membership
        </a>
        <a href="{{ route('member.pt-packages.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isPt ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Personal Trainer
        </a>
        <a href="{{ route('pt-sessions.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isBooking ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Booking Sesi
        </a>
        <a href="{{ route('member.riwayat-transaksi.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isRiwayat ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Riwayat Transaksi
        </a>
    </div>
</header>