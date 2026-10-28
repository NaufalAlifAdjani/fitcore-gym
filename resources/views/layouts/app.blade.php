<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FitCore Gym') }}</title>

        <!-- Google Fonts: Poppins (Headings) & Inter (Body) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
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
                <x-admin-navigation />

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
                <x-trainer-navbar />

                <div class="flex flex-1">
                    <!-- Desktop Sidebar -->
                    <div class="hidden lg:block shrink-0">
                        <x-trainer-sidebar />
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
                        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" @click="sidebarOpen = false"></div>
                        <div class="relative flex flex-col w-full max-w-xs h-full bg-white shadow-2xl z-10">
                            <div class="flex items-center justify-between p-4 border-b border-[#E5E7EB]">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-[#ED1B45] to-[#FF4567] flex items-center justify-center text-white font-black text-sm shadow-md shadow-[#ED1B45]/20">
                                        M
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
                                <x-trainer-sidebar />
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <main class="flex-1 min-w-0 flex flex-col">
                        <x-toast />
                        {{ $slot }}
                    </main>
                </div>
            @else
                <!-- Member Navbar -->
                <x-member-navbar />

                <!-- Page Heading (Optional) -->
                @isset($header)
                    <header class="bg-white border-b border-[#E5E7EB]">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            @endif

            <!-- Modal Konfirmasi Logout -->
            <div
                x-cloak
                x-show="showLogoutModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs"
                role="dialog"
                aria-modal="true"
                aria-labelledby="logout-title"
            >
                <div class="fixed inset-0" x-on:click="showLogoutModal = false"></div>

                <section
                    x-show="showLogoutModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-md rounded-3xl bg-white p-7 text-center shadow-2xl"
                    x-on:click.stop
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ED1B45]/10 text-[#ED1B45]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3" />
                        </svg>
                    </div>

                    <div class="mt-4">
                        <h3 id="logout-title" class="font-poppins text-lg font-bold text-[#16151A]">Konfirmasi Keluar</h3>
                        <p class="mt-2 font-inter text-sm text-[#565A66]">
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
        @stack('scripts')
    </body>
</html>
