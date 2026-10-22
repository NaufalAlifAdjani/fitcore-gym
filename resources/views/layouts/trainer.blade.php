<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'FitCore PT Portal - Personal Trainer' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-[#FAFAFA] text-gray-900">
        <div
            x-data="{ sidebarOpen: false, showLogoutModal: false }"
            x-on:keydown.escape.window="showLogoutModal = false"
            class="min-h-screen flex"
        >
            <!-- Mobile Backdrop Overlay -->
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition.opacity
                x-on:click="sidebarOpen = false"
                class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-xs lg:hidden"
                aria-hidden="true"
            ></div>

            <!-- Left Sidebar -->
            <aside
                x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col justify-between border-r border-gray-100 bg-white transition-transform duration-200 lg:translate-x-0"
                aria-label="Navigasi PT Portal"
            >
                <div class="flex flex-col">
                    <!-- Brand Logo -->
                    <div class="flex items-center gap-3 px-6 pt-6 pb-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#ED1B45] text-white shadow-sm">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6.5 6.5 11 11" />
                                <path d="m21 21-1-1" />
                                <path d="m3 3 1 1" />
                                <path d="m18 22 4-4" />
                                <path d="m2 6 4-4" />
                                <path d="m3 10 7-7" />
                                <path d="m14 21 7-7" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-poppins font-extrabold text-xl leading-tight text-gray-900 tracking-tight">
                                FitCore
                            </span>
                            <span class="font-inter text-[10px] font-extrabold tracking-wider text-[#ED1B45] uppercase">
                                PT PORTAL
                            </span>
                        </div>
                    </div>

                    <!-- Floor Active Status Pill -->
                    <div class="px-5 mb-5">
                        <div class="flex items-center justify-between rounded-xl bg-gray-100/80 px-3.5 py-2.5 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span class="font-inter font-bold tracking-wider text-gray-800 text-[11px] uppercase">
                                    FLOOR ACTIVE
                                </span>
                            </div>
                            <span class="text-gray-400 text-xs" title="Live Floor Status">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 0 1 0-5.304m5.304 0a3.75 3.75 0 0 1 0 5.304m-7.425 2.121a6.75 6.75 0 0 1 0-9.546m9.546 0a6.75 6.75 0 0 1 0 9.546M12 12h.008v.008H12V12Z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Main Menu Header -->
                    <div class="px-5 mb-2.5">
                        <span class="text-[11px] font-bold text-[#565A66] tracking-wider uppercase font-inter">
                            MAIN MENU
                        </span>
                    </div>

                    <!-- Navigation Items List -->
                    <nav class="space-y-1 px-3">
                        <!-- 1. Dashboard -->
                        <a
                            href="{{ route('trainer.dashboard') }}"
                            class="flex items-center gap-3.5 rounded-xl px-4 py-3 font-inter text-sm font-semibold transition duration-150 {{ request()->routeIs('trainer.dashboard') ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}"
                        >
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('trainer.dashboard') ? 'text-white' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- 2. Jadwal Sesi PT -->
                        <a
                            href="{{ route('trainer.sessions.index') }}"
                            class="flex items-center gap-3.5 rounded-xl px-4 py-3 font-inter text-sm font-semibold transition duration-150 {{ request()->routeIs('trainer.sessions.*') ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}"
                        >
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('trainer.sessions.*') ? 'text-white' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Jadwal Sesi PT</span>
                        </a>

                        <!-- 3. Klien Saya -->
                        <a
                            href="{{ route('trainer.clients.index') }}"
                            class="flex items-center gap-3.5 rounded-xl px-4 py-3 font-inter text-sm font-semibold transition duration-150 {{ request()->routeIs('trainer.clients.*') ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}"
                        >
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('trainer.clients.*') ? 'text-white' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Klien Saya</span>
                        </a>

                        <!-- 4. Ketersediaan Waktu -->
                        <a
                            href="{{ route('trainer.availability.index') }}"
                            class="flex items-center gap-3.5 rounded-xl px-4 py-3 font-inter text-sm font-semibold transition duration-150 {{ request()->routeIs('trainer.availability.*') ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}"
                        >
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('trainer.availability.*') ? 'text-white' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Ketersediaan Waktu</span>
                        </a>

                        <!-- 5. Pendapatan / Komisi -->
                        <a
                            href="{{ route('trainer.commissions.index') }}"
                            class="flex items-center gap-3.5 rounded-xl px-4 py-3 font-inter text-sm font-semibold transition duration-150 {{ request()->routeIs('trainer.commissions.*') ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}"
                        >
                            <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('trainer.commissions.*') ? 'text-white' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Pendapatan / Komisi</span>
                        </a>
                    </nav>
                </div>

                <!-- Bottom Footer (Bantuan & SOP, Keluar) -->
                <div class="p-4">
                    <div class="rounded-2xl bg-gray-50 p-2 space-y-1">
                        <a
                            href="#"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-inter text-xs font-medium text-gray-600 transition hover:bg-white hover:text-gray-900 hover:shadow-xs"
                        >
                            <svg class="h-4 w-4 shrink-0 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                <line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>
                            <span>Bantuan & SOP</span>
                        </a>

                        <button
                            type="button"
                            x-on:click="showLogoutModal = true"
                            class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 font-inter text-xs font-semibold text-gray-700 transition hover:bg-rose-50 hover:text-[#ED1B45] cursor-pointer"
                        >
                            <svg class="h-4 w-4 shrink-0 text-gray-500 hover:text-[#ED1B45]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area with Header -->
            <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
                <!-- Top Header -->
                <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-gray-100/80 bg-white/95 px-6 sm:px-10 backdrop-blur-md">
                    <!-- Left: Hamburger button for Mobile -->
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            x-on:click="sidebarOpen = true"
                            aria-label="Buka navigasi"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 lg:hidden"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Right Header: Notifications & Coach Profile -->
                    <div class="flex items-center gap-5 sm:gap-6 ml-auto">
                        <!-- Notification Bell -->
                        <button
                            type="button"
                            class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition cursor-pointer"
                            aria-label="Notifikasi"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <!-- Red indicator dot -->
                            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-[#ED1B45] ring-2 ring-white"></span>
                        </button>

                        <!-- Coach Profile -->
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="font-poppins font-bold text-sm text-gray-900 leading-tight">
                                    {{ str_starts_with(Auth::user()->name, 'Coach') ? Auth::user()->name : 'Coach ' . Auth::user()->name }}
                                </p>
                                <p class="font-inter text-xs text-gray-400">
                                    {{ Auth::user()->trainerProfile?->specialization ?: 'Master Trainer' }}
                                </p>
                            </div>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#BA0030] text-white shadow-sm ring-2 ring-rose-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </header>

                <x-fitcore.toast />

                <!-- Page Body -->
                <main class="flex-1 px-6 py-8 sm:px-10 max-w-7xl w-full mx-auto">
                    {{ $slot }}
                </main>
            </div>

            <!-- Logout Confirmation Modal -->
            <div
                x-cloak
                x-show="showLogoutModal"
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-xs"
                role="dialog"
                aria-modal="true"
                aria-labelledby="logout-modal-title"
                x-on:click.self="showLogoutModal = false"
            >
                <div
                    x-transition
                    class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl sm:p-8 border-t-4 border-[#ED1B45]"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-[#ED1B45]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3.5 2.9 19.25a1.5 1.5 0 0 0 1.3 2.25h15.6a1.5 1.5 0 0 0 1.3-2.25L12 3.5Z" stroke-linejoin="round" />
                            <path d="M12 9v5m0 3h.01" stroke-linecap="round" />
                        </svg>
                    </div>

                    <div class="mt-5 text-center">
                        <h2 id="logout-modal-title" class="font-poppins text-xl font-bold text-gray-900">Konfirmasi Keluar</h2>
                        <p class="mt-2 font-inter text-sm leading-6 text-gray-500">
                            Apakah Anda yakin ingin keluar dari sesi Trainer FitCore?
                        </p>
                    </div>

                    <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                        <button
                            type="button"
                            x-on:click="showLogoutModal = false"
                            class="rounded-full bg-gray-100 px-5 py-2.5 font-inter text-sm font-semibold text-gray-600 transition hover:bg-gray-200 cursor-pointer"
                        >
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full rounded-full bg-[#ED1B45] px-5 py-2.5 font-inter text-sm font-semibold text-white transition hover:bg-[#D1123D] shadow-sm cursor-pointer"
                            >
                                Ya, Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
