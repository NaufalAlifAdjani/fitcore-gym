@php
    $navigationItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'dashboard'],
        ['label' => 'Paket & Harga', 'route' => 'admin.packages.index', 'active' => request()->routeIs('admin.packages.*'), 'icon' => 'packages'],
        ['label' => 'Paket Sesi & Jadwal PT', 'route' => 'admin.pt-sessions.index', 'active' => request()->routeIs('admin.pt-sessions.*'), 'icon' => 'calendar'],
        ['label' => 'Verifikasi Pembayaran', 'route' => 'admin.payments.index', 'active' => request()->routeIs('admin.payments.*'), 'icon' => 'payments'],
        ['label' => 'Scanner Check-in', 'route' => 'admin.check-in.index', 'active' => request()->routeIs('admin.check-in.*'), 'icon' => 'scan'],
        ['label' => 'Manajemen Member', 'route' => 'admin.members.index', 'active' => request()->routeIs('admin.members.*'), 'icon' => 'members'],
        ['label' => 'Pengaturan', 'route' => 'admin.settings.index', 'active' => request()->routeIs('admin.settings.*'), 'icon' => 'settings'],
    ];
@endphp

<div
    x-show="sidebarOpen"
    x-transition.opacity
    x-on:click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-[#16151A]/50 lg:hidden"
    aria-hidden="true"
></div>

<aside
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-[#EEEEEF] bg-white transition-transform duration-200 lg:translate-x-0"
    aria-label="Navigasi utama"
>
    <a href="{{ route('admin.dashboard') }}" class="flex h-20 shrink-0 items-center gap-3 border-b border-[#F0F0F1] px-6">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#BA0030] text-sm font-extrabold text-white">FC</span>
        <span class="font-heading text-sm font-extrabold leading-tight tracking-wide text-[#16151A]">
            FITCORE
            <span class="block text-[10px] font-semibold tracking-[0.2em] text-[#565A66]">MAXFIT GYM</span>
        </span>
    </a>

    <div class="flex-1 overflow-y-auto px-4 py-6">
        <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-[#565A66]">Navigasi Portal</p>
        <nav class="space-y-1">
            @if (Auth::user()->role === 'admin')
                @foreach ($navigationItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition',
                            'bg-[#BA0030] text-white shadow-sm' => $item['active'],
                            'text-[#565A66] hover:bg-[#F9F9FA] hover:text-[#16151A]' => ! $item['active'],
                        ])
                        aria-current="{{ $item['active'] ? 'page' : 'false' }}"
                        x-on:click="sidebarOpen = false"
                    >
                    @switch($item['icon'])
                        @case('dashboard')
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                                <rect x="13" y="3" width="8" height="5" rx="2" stroke="currentColor" stroke-width="1.8" />
                                <rect x="13" y="10" width="8" height="11" rx="2" stroke="currentColor" stroke-width="1.8" />
                                <rect x="3" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                            </svg>
                            @break
                        @case('packages')
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <path d="m4.5 7.8 7.5 4.4 7.5-4.4M12 12.2V21" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            </svg>
                            @break
                        @case('calendar')
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3.5" y="5" width="17" height="16" rx="2.5" stroke="currentColor" stroke-width="1.8" />
                                <path d="M7.5 3v4M16.5 3v4M4 9.5h16M8 13h2M14 13h2M8 17h2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            @break
                        @case('payments')
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8" />
                                <path d="M3 9h18M7 14h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            @break
                        @case('scan')
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M8 4H5a1 1 0 0 0-1 1v3m12-4h3a1 1 0 0 1 1 1v3M8 20H5a1 1 0 0 1-1-1v-3m12 4h3a1 1 0 0 0 1-1v-3M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            @break
                        @case('members')
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="9" cy="8" r="3.25" stroke="currentColor" stroke-width="1.8" />
                                <path d="M3.5 20v-1.5A4.5 4.5 0 0 1 8 14h2a4.5 4.5 0 0 1 4.5 4.5V20M16 5a3.25 3.25 0 0 1 0 6.2M17 14.3a4.5 4.5 0 0 1 3.5 4.2V20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            @break
                        @case('settings')
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                                <path d="m19.4 15 .1.1a1.8 1.8 0 1 1-2.5 2.5l-.1-.1a1.8 1.8 0 0 0-3 .9v.2a1.8 1.8 0 1 1-3.6 0v-.2a1.8 1.8 0 0 0-3-.9l-.1.1a1.8 1.8 0 1 1-2.5-2.5l.1-.1a1.8 1.8 0 0 0-.9-3H3.8a1.8 1.8 0 1 1 0-3.6H4a1.8 1.8 0 0 0 .9-3l-.1-.1a1.8 1.8 0 1 1 2.5-2.5l.1.1a1.8 1.8 0 0 0 3-.9v-.2a1.8 1.8 0 1 1 3.6 0V2a1.8 1.8 0 0 0 3 .9l.1-.1a1.8 1.8 0 1 1 2.5 2.5l-.1.1a1.8 1.8 0 0 0 .9 3h.2a1.8 1.8 0 1 1 0 3.6H20a1.8 1.8 0 0 0-.6 3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            @break
                    @endswitch
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @else
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-[#565A66] transition hover:bg-[#F9F9FA] hover:text-[#16151A]" x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="13" y="3" width="8" height="5" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="13" y="10" width="8" height="11" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="3" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-[#565A66] transition hover:bg-[#F9F9FA] hover:text-[#16151A]" x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8" />
                        <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    Profil
                </a>
            @endif
        </nav>

        @if (Auth::user()->role === 'admin')
            <div class="mt-8 border-t border-[#F0F0F1] pt-5">
                <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-[#565A66]">Bantuan</p>
                <a href="{{ route('admin.help.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-[#565A66] transition hover:bg-[#F9F9FA] hover:text-[#16151A]" x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                        <path d="M9.6 9a2.5 2.5 0 1 1 4.2 1.8c-1.1 1-1.8 1.3-1.8 2.7M12 17h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    Bantuan &amp; SOP
                </a>
            </div>
        @endif
    </div>

    <div class="border-t border-[#F0F0F1] p-4">
        <button
            type="button"
            x-on:click="showLogoutModal = true"
            class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-semibold text-[#BA0030] transition hover:bg-rose-50"
        >
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Keluar
        </button>
    </div>
</aside>

<header class="fixed left-0 right-0 top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-[#EEEEEF] bg-white/95 px-4 backdrop-blur sm:px-6 lg:left-72 lg:px-8">
    <div class="flex min-w-0 flex-1 items-center gap-3">
        <button
            type="button"
            x-on:click="sidebarOpen = true"
            aria-label="Buka navigasi"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-[#565A66] hover:bg-[#F4F4F5] lg:hidden"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
        </button>

        <p class="truncate font-heading text-sm font-bold text-[#16151A] sm:text-base">
            {{ Auth::user()->role === 'admin' ? 'Portal Admin' : 'Portal FitCore' }}
        </p>
    </div>

    <div class="flex shrink-0 items-center gap-2 sm:gap-4">
        <button type="button" aria-label="Notifikasi" class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-[#565A66] transition hover:bg-[#F4F4F5]">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9ZM10 21h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="absolute right-2 top-2 h-2 w-2 rounded-full border-2 border-white bg-[#E61241]"></span>
        </button>

        <div class="relative" x-on:click.outside="profileOpen = false">
            <button
                type="button"
                x-on:click="profileOpen = !profileOpen"
                x-bind:aria-expanded="profileOpen"
                aria-label="Menu profil"
                class="flex items-center gap-2 rounded-full p-1.5 transition hover:bg-[#F4F4F5] sm:gap-3 sm:pl-2"
            >
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#BA0030] text-xs font-bold uppercase text-white">
                    {{ \Illuminate\Support\Str::of(Auth::user()->name)->explode(' ')->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))->take(2)->implode('') }}
                </span>
                <span class="hidden text-left sm:block">
                    <span class="block max-w-36 truncate text-xs font-semibold text-[#16151A]">{{ Auth::user()->name }}</span>
                    <span class="block max-w-36 truncate text-[10px] text-[#858894]">{{ Auth::user()->email ?: 'admin@fitcore.id' }}</span>
                </span>
                <svg class="hidden h-4 w-4 text-[#858894] sm:block" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m7 10 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div
                x-cloak
                x-show="profileOpen"
                x-transition
                class="absolute right-0 top-12 z-40 w-56 rounded-2xl border border-[#EEEEEF] bg-white p-2 shadow-lg"
            >
                <div class="border-b border-[#F0F0F1] px-3 py-2 sm:hidden">
                    <p class="truncate text-xs font-semibold text-[#16151A]">{{ Auth::user()->name }}</p>
                    <p class="truncate text-[10px] text-[#858894]">{{ Auth::user()->email ?: 'admin@fitcore.id' }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-[#565A66] hover:bg-[#F9F9FA] hover:text-[#16151A]">Profil</a>
                <button
                    type="button"
                    x-on:click="profileOpen = false; showLogoutModal = true"
                    class="w-full rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-[#BA0030] hover:bg-rose-50"
                >
                    Keluar
                </button>
            </div>
        </div>
    </div>
</header>
