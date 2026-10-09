@props([
    'active' => 'packages',
])

<header class="sticky top-0 z-40 w-full border-b border-slate-800 bg-[#0B0F17]/95 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#BA0030] text-white shadow-[0_0_15px_rgba(186,0,48,0.5)] transition group-hover:scale-105">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </span>
            <div class="flex flex-col">
                <span class="font-heading text-lg font-extrabold tracking-wider text-white">
                    FITCORE <span class="text-[#BA0030]">ATHLETIC</span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-slate-400">Gym &amp; Performance Center</span>
            </div>
        </a>

        <!-- Desktop Navigation Items -->
        <nav class="hidden md:flex items-center gap-1 bg-slate-900/80 p-1.5 rounded-full border border-slate-800">
            <a href="{{ route('dashboard') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md' => $active === 'beranda',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => $active !== 'beranda',
                ])>
                Beranda
            </a>
            <a href="{{ route('member.membership.packages') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md shadow-[#BA0030]/30' => $active === 'packages',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => $active !== 'packages',
                ])>
                Paket Membership
            </a>
            <a href="{{ route('admin.pt-sessions.index') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md' => $active === 'pt',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => $active !== 'pt',
                ])>
                Personal Trainer
            </a>
            <a href="{{ route('admin.check-in.index') }}"
                @class([
                    'px-4 py-2 text-xs font-bold rounded-full transition-all duration-200',
                    'bg-[#BA0030] text-white shadow-md' => $active === 'booking',
                    'text-slate-300 hover:text-white hover:bg-slate-800/60' => $active !== 'booking',
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
        </div>
    </div>
</header>
