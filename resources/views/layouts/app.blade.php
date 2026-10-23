<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Fitcore Gym') }} - Personal Trainer Session</title>

        <!-- Google Fonts: Poppins (Headings) & Inter (Body) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>
            [x-cloak] {
                display: none !important;
            }
            body {
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
            h1, h2, h3, h4, .font-heading {
                font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    </head>
    @php
        $isAdmin = auth()->check() && (auth()->user()->role === 'admin' || request()->routeIs('admin.*'));
        $isTrainer = request()->routeIs('trainer.*') || (auth()->check() && auth()->user()->role === 'trainer' && !request()->routeIs('pt-sessions.*'));
    @endphp
    <body class="bg-[#F6F6F7] text-[#16151A] antialiased min-h-screen selection:bg-[#ED1B45] selection:text-white flex flex-col font-sans">
        <div
            x-data="{ sidebarOpen: false, profileOpen: false, showLogoutModal: false }"
            x-on:keydown.escape.window="showLogoutModal = false"
            class="min-h-screen bg-[#F6F6F7] flex flex-col flex-1"
        >
            @if($isAdmin)
                @include('layouts.navigation')

                <main class="min-h-screen pt-16 lg:pl-72 flex-1">
                    @isset($header)
                        <header class="bg-white px-4 py-5 shadow-sm sm:px-6 lg:px-8">
                            {{ $header }}
                        </header>
                    @endisset
                    {{ $slot }}
                </main>
            @elseif($isTrainer)
                <!-- Top Navbar for Trainer Portal -->
                @include('layouts.navigation-trainer')

                <div class="flex flex-1">
                    <!-- Desktop Sidebar -->
                    <div class="hidden lg:block shrink-0">
                        @include('layouts.sidebar-trainer')
                    </div>

                    <!-- Mobile Off-Canvas Sidebar Drawer -->
                    <div x-show="sidebarOpen" 
                         class="fixed inset-0 z-50 lg:hidden" 
                         style="display: none;"
                         x-transition:enter="transition-opacity ease-linear duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity ease-linear duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <!-- Backdrop -->
                        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="sidebarOpen = false"></div>

                        <!-- Drawer panel -->
                        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl flex flex-col z-10"
                             x-transition:enter="transition ease-in-out duration-300 transform"
                             x-transition:enter-start="-translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transition ease-in-out duration-300 transform"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="-translate-x-full">
                            <div class="h-[72px] px-5 border-b border-[#E5E7EB] flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center text-[#ED1B45]">
                                        <svg class="h-6 w-auto" viewBox="0 0 32 16" fill="currentColor">
                                            <rect x="0" y="0" width="4" height="16" rx="2" />
                                            <rect x="5" y="2" width="2" height="12" rx="1" />
                                            <rect x="7" y="6.5" width="18" height="3" rx="1" />
                                            <rect x="25" y="2" width="2" height="12" rx="1" />
                                            <rect x="28" y="0" width="4" height="16" rx="2" />
                                        </svg>
                                    </div>
                                    <span class="text-lg font-black tracking-tight text-[#16151A] font-heading uppercase">
                                        MAXFIT<span class="text-[#ED1B45]">.</span>
                                    </span>
                                </div>
                                <button @click="sidebarOpen = false" class="text-[#565A66] hover:text-[#16151A] p-2 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto">
                                @include('layouts.sidebar-trainer')
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <main class="flex-1 min-w-0 flex flex-col">
                        <x-fitcore.toast />
                        {{ $slot }}
                    </main>
                </div>
            @else
                <!-- Fitcore Navbar (Member) -->
                @include('layouts.navigation-fitcore')

                <!-- Page Heading (Optional) -->
                @isset($header)
                    <header class="bg-white border-b border-[#E5E7EB]">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Toast Notifications -->
                <x-fitcore.toast />

                <!-- Page Content -->
                <main class="flex-grow">
                    {{ $slot }}
                </main>

                <!-- Fitcore Footer -->
                <x-fitcore.footer />
            @endif

            <!-- Modal Logout Konfirmasi -->
            <div
                x-cloak
                x-show="showLogoutModal"
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
                role="dialog"
                aria-modal="true"
                aria-labelledby="logout-modal-title"
                x-on:click.self="showLogoutModal = false"
            >
                <section
                    x-transition
                    class="w-full max-w-md rounded-[16px] border-t-4 border-[#ED1B45] bg-white p-6 shadow-2xl sm:p-8"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#ED1B45]/10 text-[#ED1B45]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3.5 2.9 19.25a1.5 1.5 0 0 0 1.3 2.25h15.6a1.5 1.5 0 0 0 1.3-2.25L12 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="M12 9v5m0 3h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    <div class="mt-5 text-center">
                        <h2 id="logout-modal-title" class="font-poppins text-xl font-bold text-[#16151A]">Konfirmasi Keluar</h2>
                        <p class="mt-2 font-inter text-sm leading-6 text-[#565A66]">
                            Apakah Anda yakin ingin keluar dari akun ini? Sesi Anda saat ini akan diakhiri.
                        </p>
                    </div>

                    <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                        <button
                            type="button"
                            x-on:click="showLogoutModal = false"
                            class="rounded-full bg-[#F6F6F7] px-5 py-3 font-inter text-sm font-semibold text-[#565A66] transition hover:bg-[#E5E7EB] hover:text-[#16151A] focus:outline-none cursor-pointer"
                        >
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full rounded-full bg-[#ED1B45] px-5 py-3 font-inter text-sm font-semibold text-white transition hover:bg-[#D1123D] focus:outline-none focus:ring-2 focus:ring-[#ED1B45] focus:ring-offset-2 cursor-pointer"
                            >
                                Ya, Keluar
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>

        @livewireScriptConfig
    </body>
</html>
