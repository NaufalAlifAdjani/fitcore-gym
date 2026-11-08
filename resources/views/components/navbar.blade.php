@props([
    'active' => null,
])

<header class="sticky top-0 z-50 w-full border-b border-gray-200/80 bg-white/95 backdrop-blur-md" x-data="{ mobileMenuOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 rounded-full shadow-[0_0_12px_rgba(255,87,34,0.35)] transition group-hover:scale-105">
                <rect width="36" height="36" rx="18" fill="#FF5722"/>
                <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="white"/>
            </svg>
            <div class="flex flex-col">
                <span class="font-heading text-lg font-extrabold tracking-wider text-zinc-900">
                    FITCORE <span class="text-[#FF5722]">ATHLETIC</span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-zinc-500">Gym &amp; Performance Center</span>
            </div>
        </a>

        <!-- Desktop Navigation Items -->
        <nav class="hidden md:flex items-center gap-1 bg-orange-50/70 p-1.5 rounded-full border border-orange-100">
            <a href="{{ Route::has('member.membership.packages') ? route('member.membership.packages') : '#' }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#FF5722] text-white shadow-md shadow-[#FF5722]/30' => request()->routeIs('member.membership.*') || $active === 'packages',
                    'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !request()->routeIs('member.membership.*') && $active !== 'packages',
                ])>
                Paket Membership
            </a>
            <a href="{{ Route::has('pt-sessions.index') ? route('pt-sessions.index') : '#' }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#FF5722] text-white shadow-md' => request()->routeIs('pt-sessions.*') || $active === 'pt',
                    'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => !request()->routeIs('pt-sessions.*') && $active !== 'pt',
                ])>
                Personal Trainer
            </a>
            <a href="#"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#FF5722] text-white shadow-md' => $active === 'about',
                    'text-zinc-600 hover:text-zinc-900 hover:bg-white/80' => $active !== 'about',
                ])>
                Tentang Kami
            </a>
        </nav>

        <!-- Right Side CTA / Profile -->
        <div class="flex items-center gap-3">
            @auth
                <!-- User Profile Avatar -->
                <div class="flex items-center gap-3" x-data="{ userMenuOpen: false }">
                    <div class="relative" x-on:click.outside="userMenuOpen = false">
                        <button
                            type="button"
                            x-on:click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2.5 rounded-full bg-white border border-gray-200 p-1.5 pr-3 transition hover:border-[#FF5722]/50 shadow-xs"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#FF5722] font-heading text-xs font-bold uppercase text-white shadow-sm">
                                {{ \Illuminate\Support\Str::of(Auth::user()?->name ?? 'M')->explode(' ')->map(fn ($p) => \Illuminate\Support\Str::substr($p, 0, 1))->take(2)->implode('') }}
                            </span>
                            <span class="hidden sm:inline-block text-xs font-bold text-zinc-800">
                                {{ Auth::user()?->name ?? 'Member FitCore' }}
                            </span>
                            <svg class="h-4 w-4 text-zinc-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m7 10 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <div
                            x-cloak
                            x-show="userMenuOpen"
                            x-transition
                            class="absolute right-0 top-12 z-50 w-56 rounded-2xl border border-gray-200 bg-white p-2 shadow-xl"
                        >
                            <div class="border-b border-gray-100 px-3 py-2">
                                <p class="truncate text-xs font-bold text-zinc-900">{{ Auth::user()?->name }}</p>
                                <p class="truncate text-[10px] text-zinc-500">{{ Auth::user()?->email }}</p>
                            </div>
                            @if (Auth::user()?->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-amber-600 hover:bg-amber-50">
                                    Portal Admin
                                </a>
                            @elseif (Auth::user()?->role === 'trainer')
                                <a href="{{ route('trainer.sessions.index') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">
                                    Portal Trainer
                                </a>
                            @else
                                <a href="{{ route('member.dashboard') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                                    Dashboard Member
                                </a>
                                <a href="{{ route('member.membership.packages') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                                    Status Membership
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-zinc-700 hover:bg-orange-50/70 hover:text-zinc-900">
                                Pengaturan Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-xl px-3 py-2.5 text-left text-xs font-bold text-[#FF5722] hover:bg-orange-50 cursor-pointer">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest Actions -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="{{ route('login') }}" class="px-3 sm:px-4 py-2 text-xs font-bold text-zinc-600 hover:text-zinc-900 transition">
                        Masuk
                    </a>
                    @if (Route::has('register'))
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
                class="inline-flex md:hidden items-center justify-center rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 transition"
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
        class="md:hidden border-t border-gray-200 bg-white px-4 pt-3 pb-5 space-y-2"
    >
        <a href="{{ Route::has('member.membership.packages') ? route('member.membership.packages') : '#' }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('member.membership.*') || $active === 'packages') ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Paket Membership
        </a>
        <a href="{{ Route::has('pt-sessions.index') ? route('pt-sessions.index') : '#' }}"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ (request()->routeIs('pt-sessions.*') || $active === 'pt') ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Personal Trainer
        </a>
        <a href="#"
            class="block rounded-xl px-4 py-2.5 text-xs font-bold {{ $active === 'about' ? 'bg-[#FF5722] text-white' : 'text-zinc-700 hover:bg-orange-50 hover:text-zinc-900' }}">
            Tentang Kami
        </a>
    </div>
</header>
