@php
    $adminNavigationItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'dashboard'],
        ['label' => 'Paket & Harga', 'route' => 'admin.packages.index', 'active' => request()->routeIs('admin.packages.*'), 'icon' => 'packages'],
        ['label' => 'Paket Sesi & Jadwal PT', 'route' => 'admin.pt-sessions.index', 'active' => request()->routeIs('admin.pt-sessions.*'), 'icon' => 'calendar'],
        ['label' => 'Verifikasi Pembayaran', 'route' => 'admin.payments.index', 'active' => request()->routeIs('admin.payments.*'), 'icon' => 'payments'],
        ['label' => 'Scanner Check-in', 'route' => 'admin.checkin.index', 'active' => request()->routeIs('admin.checkin.*') || request()->routeIs('admin.check-in.*'), 'icon' => 'scan'],
        ['label' => 'Manajemen Member', 'route' => 'admin.members.index', 'active' => request()->routeIs('admin.members.*'), 'icon' => 'members'],
        ['label' => 'Pengaturan', 'route' => 'admin.settings.index', 'active' => request()->routeIs('admin.settings.*'), 'icon' => 'settings'],
    ];
@endphp

<!-- Mobile Backdrop Overlay -->
<div
    x-show="sidebarOpen"
    x-transition.opacity
    x-on:click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-[#16151A]/50 lg:hidden"
    aria-hidden="true"
></div>

<!-- 1. Sidebar (Navigasi Kiri) -->
<aside
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-[#E5E7EB] bg-white transition-transform duration-200 lg:translate-x-0"
    aria-label="Navigasi utama"
>
    <!-- Brand Logo / Teks -->
    <a href="{{ route('dashboard') }}" class="flex h-16 shrink-0 items-center gap-3 border-b border-[#E5E7EB] px-6">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#ED1B45] text-white shadow-sm">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h1.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 012.25 13.5v-3A1.5 1.5 0 013.75 9zm15 0h1.5A1.5 1.5 0 0121.75 10.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 0117.25 13.5v-3a1.5 1.5 0 011.5-1.5zM6.75 12h10.5M5.25 7.5v9M18.75 7.5v9" />
            </svg>
        </span>
        <span class="font-poppins font-extrabold text-2xl text-[#16151A] tracking-tight">
            FIT<span class="text-[#ED1B45]">CORE</span>
        </span>
    </a>

    <!-- Menu Navigation Items -->
    <div class="flex-1 overflow-y-auto px-4 py-6">
        <!-- Kategori Menu -->
        <p class="font-inter text-[11px] font-bold text-[#565A66] uppercase tracking-wider mb-2 px-4">Navigasi Portal</p>
        <nav class="space-y-1">
            @if (Auth::user()->role === 'admin')
                @foreach ($adminNavigationItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                            'bg-[#ED1B45] text-white font-semibold shadow-sm' => $item['active'],
                            'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => ! $item['active'],
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
            @elseif (Auth::user()->role === 'trainer')
                <a href="{{ route('dashboard') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('trainer.*') || request()->routeIs('dashboard'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !(request()->routeIs('trainer.*') || request()->routeIs('dashboard')),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="13" y="3" width="8" height="5" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="13" y="10" width="8" height="11" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="3" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                    </svg>
                    <span>Dashboard Trainer</span>
                </a>
                <a href="{{ route('admin.checkin.index') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('admin.checkin.*'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !request()->routeIs('admin.checkin.*'),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M8 4H5a1 1 0 0 0-1 1v3m12-4h3a1 1 0 0 1 1 1v3M8 20H5a1 1 0 0 1-1-1v-3m12 4h3a1 1 0 0 0 1-1v-3M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <span>Scanner Check-in</span>
                </a>
                <a href="{{ route('profile.edit') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('profile.*'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !request()->routeIs('profile.*'),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8" />
                        <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <span>Profil</span>
                </a>
            @else
                {{-- Member role navigation --}}
                <a href="{{ route('dashboard') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('member.dashboard') || request()->routeIs('dashboard'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !(request()->routeIs('member.dashboard') || request()->routeIs('dashboard')),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="13" y="3" width="8" height="5" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="13" y="10" width="8" height="11" rx="2" stroke="currentColor" stroke-width="1.8" />
                        <rect x="3" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8" />
                    </svg>
                    <span>Dashboard Member</span>
                </a>
                <a href="{{ route('member.card') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('member.card'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !request()->routeIs('member.card'),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9" cy="10" r="2"/>
                        <path d="M15 8h2M15 12h2M7 16h10"/>
                    </svg>
                    <span>Kartu Member</span>
                </a>
                <a href="{{ route('member.membership.packages') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('member.membership.*'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !request()->routeIs('member.membership.*'),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" stroke-linejoin="round"/>
                        <path d="m4.5 7.8 7.5 4.4 7.5-4.4M12 12.2V21" stroke-linejoin="round"/>
                    </svg>
                    <span>Paket Membership</span>
                </a>
                <a href="{{ route('member.pt-packages.index') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('member.pt-packages.*'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !request()->routeIs('member.pt-packages.*'),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M5.5 21v-2a6.5 6.5 0 0 1 13 0v2"/>
                    </svg>
                    <span>Sewa PT</span>
                </a>
                <a href="{{ route('profile.edit') }}" @class([
                    'flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm transition duration-200 cursor-pointer',
                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => request()->routeIs('profile.*'),
                    'text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] font-medium' => !request()->routeIs('profile.*'),
                ]) x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8" />
                        <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <span>Profil</span>
                </a>
            @endif
        </nav>

        @if (Auth::user()->role === 'admin')
            <div class="mt-8 border-t border-[#E5E7EB] pt-5">
                <p class="font-inter text-[11px] font-bold text-[#565A66] uppercase tracking-wider mb-2 px-4">Bantuan</p>
                <a href="{{ route('admin.help.index') }}" class="flex items-center gap-3 rounded-full px-4 py-3 font-inter text-sm font-medium text-[#565A66] transition duration-200 hover:bg-[#F6F6F7] hover:text-[#16151A]" x-on:click="sidebarOpen = false">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                        <path d="M9.6 9a2.5 2.5 0 1 1 4.2 1.8c-1.1 1-1.8 1.3-1.8 2.7M12 17h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <span>Bantuan &amp; SOP</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Tombol Keluar Sidebar -->
    <div class="border-t border-[#E5E7EB] p-4">
        <button
            type="button"
            x-on:click="showLogoutModal = true"
            class="flex w-full items-center gap-3 rounded-full px-4 py-3 text-left font-inter text-sm font-semibold text-[#ED1B45] transition hover:bg-[#ED1B45]/10 cursor-pointer"
        >
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Keluar</span>
        </button>
    </div>
</aside>

<!-- 2. Topbar (Navigasi Atas) -->
<header class="fixed left-0 right-0 top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-[#E5E7EB] bg-white px-6 sm:px-8 lg:left-72">
    <!-- Kiri: Mobile Hamburger + Judul Halaman -->
    <div class="flex min-w-0 flex-1 items-center gap-3 sm:gap-4">
        <!-- 3. Tombol Hamburger Menu Mobile -->
        <button
            type="button"
            x-on:click="sidebarOpen = true"
            aria-label="Buka navigasi"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-[#F6F6F7] focus:text-[#16151A] transition lg:hidden"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
        </button>

        <!-- Judul Halaman di Topbar -->
        <p class="truncate font-poppins font-bold text-lg text-[#16151A]">
            {{ Auth::user()->role === 'admin' ? 'Portal Admin' : (Auth::user()->role === 'trainer' ? 'Portal Trainer' : 'Portal FitCore') }}
        </p>
    </div>

    <!-- Kanan: Notifikasi + User Profile Dropdown -->
    <div class="flex shrink-0 items-center gap-2 sm:gap-4">
        <!-- Ikon Notifikasi -->
        <button
            type="button"
            aria-label="Notifikasi"
            class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-[#F6F6F7] transition cursor-pointer"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9ZM10 21h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <!-- Red Dot Indicator -->
            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-[#ED1B45] ring-2 ring-white"></span>
        </button>

        <!-- User Profile Dropdown -->
        <div class="relative" x-on:click.outside="profileOpen = false">
            <button
                type="button"
                x-on:click="profileOpen = !profileOpen"
                x-bind:aria-expanded="profileOpen"
                aria-label="Menu profil"
                class="flex items-center gap-2 rounded-full p-1.5 transition hover:bg-[#F6F6F7] sm:gap-3 sm:pl-2 cursor-pointer"
            >
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ED1B45] font-inter text-xs font-bold uppercase text-white shadow-sm">
                    {{ \Illuminate\Support\Str::of(Auth::user()->name)->explode(' ')->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))->take(2)->implode('') }}
                </span>
                <span class="hidden text-left sm:block">
                    <!-- User Profile Dropdown Teks -->
                    <span class="block max-w-36 truncate font-inter text-sm font-semibold text-[#16151A]">{{ Auth::user()->name }}</span>
                    <!-- User Profile Role / Email -->
                    <span class="block max-w-36 truncate font-inter text-xs text-[#565A66]">{{ Auth::user()->email ?: 'user@fitcore.id' }}</span>
                </span>
                <svg class="hidden h-4 w-4 text-[#565A66] sm:block" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m7 10 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <!-- Dropdown Menu Box -->
            <div
                x-cloak
                x-show="profileOpen"
                x-transition
                class="absolute right-0 top-12 z-40 w-56 rounded-[16px] border border-[#E5E7EB] bg-white p-2 shadow-lg"
            >
                <div class="border-b border-[#E5E7EB] px-3 py-2 sm:hidden">
                    <p class="truncate font-inter text-sm font-semibold text-[#16151A]">{{ Auth::user()->name }}</p>
                    <p class="truncate font-inter text-xs text-[#565A66]">{{ Auth::user()->email ?: 'user@fitcore.id' }}</p>
                </div>
                <a
                    href="{{ route('profile.edit') }}"
                    class="block rounded-full px-4 py-2.5 font-inter text-sm font-medium text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition"
                >
                    Profil
                </a>
                <button
                    type="button"
                    x-on:click="profileOpen = false; showLogoutModal = true"
                    class="w-full rounded-full px-4 py-2.5 text-left font-inter text-sm font-semibold text-[#ED1B45] hover:bg-[#ED1B45]/10 transition cursor-pointer"
                >
                    Keluar
                </button>
            </div>
        </div>
    </div>
</header>
