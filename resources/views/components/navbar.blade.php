@props([
    'active' => null,
])

<header class="sticky top-0 z-50 w-full border-b border-gray-200/80 bg-white/95 backdrop-blur-md shadow-xs" x-data="{ mobileMenuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-2 group">
            <img src="{{ asset('images/logo/logo.png') }}" alt="FITCORE ATHLETIC"
                class="h-8 sm:h-9 w-auto object-contain transition duration-200 group-hover:scale-105">
            <span class="sr-only">FITCORE ATHLETIC</span>
        </a>

        <!-- Desktop Navigation Items -->
        <nav class="hidden md:flex items-center gap-1 bg-orange-50/70 p-1.5 rounded-full border border-orange-100">
            <a href="{{ Route::has('landing.packages') ? route('landing.packages') : (Route::has('member.membership.packages') ? route('member.membership.packages') : '#') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#FF5722] text-white shadow-md shadow-[#FF5722]/30' => request()->routeIs('landing.packages') || request()->routeIs('member.membership.*') || $active === 'packages',
                    'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !request()->routeIs('landing.packages') && !request()->routeIs('member.membership.*') && $active !== 'packages',
                ])>
                Paket Membership
            </a>
            <a href="{{ Route::has('landing.trainers') ? route('landing.trainers') : (Route::has('pt-sessions.index') ? route('pt-sessions.index') : '#') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#FF5722] text-white shadow-md shadow-[#FF5722]/30' => request()->routeIs('landing.trainers') || request()->routeIs('pt-sessions.*') || $active === 'pt',
                    'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !request()->routeIs('landing.trainers') && !request()->routeIs('pt-sessions.*') && $active !== 'pt',
                ])>
                Personal Trainer
            </a>
            <a href="{{ Route::has('landing.about') ? route('landing.about') : '#' }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#FF5722] text-white shadow-md shadow-[#FF5722]/30' => request()->routeIs('landing.about') || $active === 'about',
                    'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !request()->routeIs('landing.about') && $active !== 'about',
                ])>
                Tentang Kami
            </a>
        </nav>

        <!-- Right Side: Auth / CTA Buttons -->
        <div class="flex items-center gap-3">
            @auth
                <!-- User Profile Avatar -->
                <div class="flex items-center gap-3" x-data="{ userMenuOpen: false }">
                    <div class="relative" x-on:click.outside="userMenuOpen = false">
                        <button type="button" x-on:click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2.5 rounded-full bg-white border border-gray-200 p-1.5 pr-3 transition hover:border-[#FF5722]/50 shadow-xs cursor-pointer">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-[#FF5722] font-heading text-xs font-bold uppercase text-white shadow-xs">
                                {{ \Illuminate\Support\Str::of(Auth::user()?->name ?? 'U')->explode(' ')->map(fn($p) => \Illuminate\Support\Str::substr($p, 0, 1))->take(2)->implode('') }}
                            </span>
                            <span class="hidden sm:inline-block text-xs font-bold text-zinc-800">
                                {{ Auth::user()?->name }}
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
                            @if (Auth::user()?->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-amber-600 hover:bg-amber-50">
                                    Portal Admin
                                </a>
                            @elseif (Auth::user()?->role === 'trainer')
                                <a href="{{ route('trainer.dashboard') }}"
                                    class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-emerald-600 hover:bg-emerald-50">
                                    Portal Trainer
                                </a>
                            @else
                                <a href="{{ route('member.dashboard') }}"
                                    class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                                    Dashboard Member
                                </a>
                                <a href="{{ route('member.card') }}"
                                    class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                                    Kartu Member Digital
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                                class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                                Pengaturan Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-xl px-3 py-2.5 text-left text-xs font-bold text-[#FF5722] hover:bg-orange-50 cursor-pointer">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-2 sm:gap-3">
                    @if (!request()->routeIs('login'))
                        <a href="{{ route('login') }}"
                            class="px-3 sm:px-4 py-2 text-xs font-bold text-zinc-700 hover:text-zinc-900 transition">
                            Masuk
                        </a>
                    @endif
                    @if (Route::has('register') && ! request()->routeIs('register'))
                        <a href="{{ route('register') }}" class="rounded-full bg-[#FF5722] px-4 sm:px-5 py-2 text-xs font-bold text-white shadow-md shadow-[#FF5722]/30 transition hover:bg-[#E64A19] hover:shadow-lg">
                            Daftar
                        </a>
                    @endif
                </div>
            @endauth

            <!-- Mobile Menu Toggle Button -->
            <button
                type="button"
                x-on:click="mobileMenuOpen = !mobileMenuOpen"
                class="inline-flex md:hidden items-center justify-center rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 transition cursor-pointer"
                aria-label="Toggle Navigation"
            >
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
    <div
        x-cloak
        x-show="mobileMenuOpen"
        x-transition
        class="md:hidden border-t border-gray-200 bg-white px-4 pt-3 pb-5 space-y-2 shadow-lg"
    >
        <a href="{{ Route::has('landing.packages') ? route('landing.packages') : (Route::has('member.membership.packages') ? route('member.membership.packages') : '#') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('landing.packages') || request()->routeIs('member.membership.*') || $active === 'packages') ? 'bg-[#FF5722] text-white shadow-xs' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Paket Membership
        </a>
        <a href="{{ Route::has('landing.trainers') ? route('landing.trainers') : (Route::has('pt-sessions.index') ? route('pt-sessions.index') : '#') }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('landing.trainers') || request()->routeIs('pt-sessions.*') || $active === 'pt') ? 'bg-[#FF5722] text-white shadow-xs' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Personal Trainer
        </a>
        <a href="{{ Route::has('landing.about') ? route('landing.about') : '#' }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('landing.about') || $active === 'about') ? 'bg-[#FF5722] text-white shadow-xs' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Tentang Kami
        </a>

        @guest
            <div class="pt-3 border-t border-gray-200 flex flex-col gap-2">
                @if (! request()->routeIs('login'))
                    <a href="{{ route('login') }}" class="block text-center py-2 text-xs font-bold text-zinc-600 hover:text-zinc-900">Masuk</a>
                @endif
                @if (Route::has('register') && ! request()->routeIs('register'))
                    <a href="{{ route('register') }}" class="block text-center py-2 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-xs hover:bg-[#E64A19]">Daftar</a>
                @endif
            </div>
        @endguest
    </div>
</header>
