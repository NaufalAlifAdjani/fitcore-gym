@props([
    'active' => 'packages',
])

<header class="sticky top-0 z-40 w-full border-b border-slate-800 bg-[#0B0F17]/95 backdrop-blur-md" x-data="{ mobileMenuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        <!-- Logo -->
        <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 group">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 rounded-full shadow-[0_0_15px_rgba(186,0,48,0.5)] transition group-hover:scale-105">
                <rect width="36" height="36" rx="18" fill="#BA0030"/>
                <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="white"/>
            </svg>
            <div class="flex flex-col">
                <span class="font-heading text-lg font-extrabold tracking-wider text-white">
                    FITCORE <span class="text-[#BA0030]">ATHLETIC</span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-slate-400">Gym &amp; Performance Center</span>
            </div>
        </a>

        <!-- Desktop Navigation Items -->
        <nav class="hidden md:flex items-center gap-1 bg-slate-900/80 p-1.5 rounded-full border border-slate-800">
            <a href="{{ route('member.dashboard') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md' => request()->routeIs('member.dashboard') || $active === 'beranda',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => !request()->routeIs('member.dashboard') && $active !== 'beranda',
                ])>
                Beranda
            </a>
            <a href="{{ route('member.membership.packages') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md shadow-[#BA0030]/30' => (request()->routeIs('member.membership.*') || $active === 'packages') && !request()->routeIs('member.dashboard') && !request()->routeIs('pt-sessions.*') && $active !== 'beranda' && $active !== 'booking' && $active !== 'riwayat',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => (!request()->routeIs('member.membership.*') && $active !== 'packages') || request()->routeIs('member.dashboard') || request()->routeIs('pt-sessions.*') || $active === 'beranda' || $active === 'booking' || $active === 'riwayat',
                ])>
                Paket Membership
            </a>
            <a href="{{ route('member.pt-packages.index') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md' => request()->routeIs('member.pt-packages.*') || $active === 'pt',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => !request()->routeIs('member.pt-packages.*') && $active !== 'pt',
                ])>
                Personal Trainer
            </a>
            <a href="{{ route('pt-sessions.index') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md' => request()->routeIs('pt-sessions.*') || $active === 'booking',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => !request()->routeIs('pt-sessions.*') && $active !== 'booking',
                ])>
                Booking Sesi
            </a>
            <a href="{{ route('member.membership.packages') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md' => $active === 'riwayat',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => $active !== 'riwayat',
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
                    class="flex items-center gap-2.5 rounded-full bg-slate-900 border border-slate-800 p-1.5 pr-3 transition hover:border-[#BA0030]/50"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#BA0030] font-heading text-xs font-bold uppercase text-white shadow-sm">
                        {{ \Illuminate\Support\Str::of(Auth::user()?->name ?? 'M')->explode(' ')->map(fn ($p) => \Illuminate\Support\Str::substr($p, 0, 1))->take(2)->implode('') }}
                    </span>
                    <span class="hidden sm:inline-block text-xs font-bold text-slate-200">
                        {{ Auth::user()?->name ?? 'Member FitCore' }}
                    </span>
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m7 10 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div
                    x-cloak
                    x-show="userMenuOpen"
                    x-transition
                    class="absolute right-0 top-12 z-50 w-56 rounded-2xl border border-slate-800 bg-[#111827] p-2 shadow-2xl"
                >
                    <div class="border-b border-slate-800 px-3 py-2">
                        <p class="truncate text-xs font-bold text-white">{{ Auth::user()?->name }}</p>
                        <p class="truncate text-[10px] text-slate-400">{{ Auth::user()?->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white">
                        Pengaturan Profil
                    </a>
                    <a href="{{ route('member.membership.packages') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white">
                        Status Membership
                    </a>
                    @if (Auth::user()?->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-amber-400 hover:bg-slate-800">
                            Portal Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl px-3 py-2.5 text-left text-xs font-bold text-[#BA0030] hover:bg-rose-950/30">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button
                type="button"
                x-on:click="mobileMenuOpen = !mobileMenuOpen"
                class="inline-flex md:hidden items-center justify-center rounded-lg p-2 text-slate-400 hover:bg-slate-800 hover:text-white transition"
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
        class="md:hidden border-t border-slate-800 bg-[#0B0F17]/98 px-4 pt-3 pb-5 space-y-2"
    >
        <a href="{{ route('member.dashboard') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('member.dashboard') || $active === 'beranda') ? 'bg-[#BA0030] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            Beranda
        </a>
        <a href="{{ route('member.membership.packages') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ ((request()->routeIs('member.membership.*') || $active === 'packages') && !request()->routeIs('member.dashboard') && !request()->routeIs('pt-sessions.*') && $active !== 'beranda' && $active !== 'booking' && $active !== 'riwayat') ? 'bg-[#BA0030] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            Paket Membership
        </a>
        <a href="{{ route('member.pt-packages.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('member.pt-packages.*') || $active === 'pt') ? 'bg-[#BA0030] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            Personal Trainer
        </a>
        <a href="{{ route('pt-sessions.index') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('pt-sessions.*') || $active === 'booking') ? 'bg-[#BA0030] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            Booking Sesi
        </a>
        <a href="{{ route('member.membership.packages') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $active === 'riwayat' ? 'bg-[#BA0030] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            Riwayat Transaksi
        </a>
    </div>
</header>
