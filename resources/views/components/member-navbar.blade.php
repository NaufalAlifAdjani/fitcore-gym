@props([
    'active' => null,
])

@php
    $isBeranda = request()->routeIs('member.dashboard') || $active === 'beranda';
    $isCard = request()->routeIs('member.card*') || $active === 'card';
    $isPt = request()->routeIs('member.pt-packages.*') || $active === 'pt';
    $isBooking = request()->routeIs('pt-sessions.*') || $active === 'booking';
    $isRiwayat = request()->routeIs('member.riwayat-transaksi.*') || $active === 'riwayat';
    $isPackages = (request()->routeIs('member.membership.*') || $active === 'packages') && ! $isBeranda && ! $isCard && ! $isPt && ! $isBooking && ! $isRiwayat;
@endphp

<header class="sticky top-0 z-40 w-full border-b border-[#E5E7EB] bg-white/95 backdrop-blur-md shadow-xs" x-data="{ mobileMenuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        <!-- Logo -->
        <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 group">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 rounded-full shadow-md shadow-[#ED1B45]/25 transition group-hover:scale-105">
                <rect width="36" height="36" rx="18" fill="#ED1B45"/>
                <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="white"/>
            </svg>
            <div class="flex flex-col">
                <span class="font-heading text-lg font-extrabold tracking-wider text-[#16151A]">
                    FITCORE <span class="text-[#ED1B45]">ATHLETIC</span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-[#565A66]">Gym &amp; Performance Center</span>
            </div>
        </a>

        <!-- Desktop Navigation Items -->
        <nav class="hidden md:flex items-center gap-1 bg-[#F6F6F7] p-1.5 rounded-full border border-[#E5E7EB]">
            <a href="{{ route('member.dashboard') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' => $isBeranda,
                    'text-[#565A66] hover:text-[#16151A] hover:bg-white' => ! $isBeranda,
                ])>
                Beranda
            </a>
            <a href="{{ route('member.card') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' => $isCard,
                    'text-[#565A66] hover:text-[#16151A] hover:bg-white' => ! $isCard,
                ])>
                Kartu Member
            </a>
            <a href="{{ route('member.membership.packages') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' => $isPackages,
                    'text-[#565A66] hover:text-[#16151A] hover:bg-white' => ! $isPackages,
                ])>
                Paket Membership
            </a>
            <a href="{{ route('member.pt-packages.index') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' => $isPt,
                    'text-[#565A66] hover:text-[#16151A] hover:bg-white' => ! $isPt,
                ])>
                Personal Trainer
            </a>
            <a href="{{ route('pt-sessions.index') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' => $isBooking,
                    'text-[#565A66] hover:text-[#16151A] hover:bg-white' => ! $isBooking,
                ])>
                Booking Sesi
            </a>
            <a href="{{ route('member.riwayat-transaksi.index') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' => $isRiwayat,
                    'text-[#565A66] hover:text-[#16151A] hover:bg-white' => ! $isRiwayat,
                ])>
                Riwayat Transaksi
            </a>
        </nav>

        <!-- User Profile Avatar -->
        <div class="flex items-center gap-3" x-data="{ userMenuOpen: false }">
            <div class="relative" x-on:click.outside="userMenuOpen = false">
                <button
                    type="button"
                    x-on:click="userMenuOpen = !userMenuOpen"
                    class="flex items-center gap-2.5 rounded-full bg-white border border-[#E5E7EB] p-1.5 pr-3 transition hover:border-[#ED1B45]/50 shadow-xs cursor-pointer"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#ED1B45] font-poppins text-xs font-bold uppercase text-white shadow-xs">
                        {{ \Illuminate\Support\Str::of(Auth::user()?->name ?? 'M')->explode(' ')->map(fn ($p) => \Illuminate\Support\Str::substr($p, 0, 1))->take(2)->implode('') }}
                    </span>
                    <span class="hidden sm:inline-block text-xs font-bold text-[#16151A]">
                        {{ Auth::user()?->name ?? 'Member FitCore' }}
                    </span>
                    <svg class="h-4 w-4 text-[#565A66]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m7 10 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div
                    x-cloak
                    x-show="userMenuOpen"
                    x-transition
                    class="absolute right-0 top-12 z-50 w-56 rounded-2xl border border-[#E5E7EB] bg-white p-2 shadow-xl"
                >
                    <div class="border-b border-[#E5E7EB] px-3 py-2">
                        <p class="truncate text-xs font-bold text-[#16151A]">{{ Auth::user()?->name }}</p>
                        <p class="truncate text-[10px] text-[#565A66]">{{ Auth::user()?->email }}</p>
                    </div>
                    <a href="{{ route('member.card') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]">
                        Kartu Member Digital
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]">
                        Pengaturan Profil
                    </a>
                    <a href="{{ route('member.membership.packages') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]">
                        Status Membership
                    </a>
                    @if (Auth::user()?->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-amber-700 hover:bg-amber-50">
                            Portal Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl px-3 py-2.5 text-left text-xs font-bold text-[#ED1B45] hover:bg-rose-50 cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button
                type="button"
                x-on:click="mobileMenuOpen = !mobileMenuOpen"
                class="inline-flex md:hidden items-center justify-center rounded-lg p-2 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition cursor-pointer"
                aria-label="Toggle Navigation"
            >
                <svg x-show="!mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-cloak x-show="mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div
        x-cloak
        x-show="mobileMenuOpen"
        x-transition
        class="md:hidden border-t border-[#E5E7EB] bg-white px-4 pt-3 pb-5 space-y-2 shadow-lg"
    >
        <a href="{{ route('member.dashboard') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isBeranda ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]' }}">
            Beranda
        </a>
        <a href="{{ route('member.card') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isCard ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]' }}">
            Kartu Member
        </a>
        <a href="{{ route('member.membership.packages') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isPackages ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]' }}">
            Paket Membership
        </a>
        <a href="{{ route('member.pt-packages.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isPt ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]' }}">
            Personal Trainer
        </a>
        <a href="{{ route('pt-sessions.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isBooking ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]' }}">
            Booking Sesi
        </a>
        <a href="{{ route('member.riwayat-transaksi.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $isRiwayat ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]' }}">
            Riwayat Transaksi
        </a>
    </div>
</header>
