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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>
            body {
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
            h1, h2, h3, h4, .font-heading {
                font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    </head>
    @php
        $isTrainer = request()->routeIs('trainer.*') || (auth()->check() && auth()->user()->role === 'trainer' && !request()->routeIs('pt-sessions.*'));
    @endphp
    <body class="bg-[#F6F6F7] text-[#16151A] antialiased min-h-screen selection:bg-[#ED1B45] selection:text-white flex flex-col"
          x-data="{ sidebarOpen: false }">

        @if($isTrainer)
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

        @livewireScripts
    </body>
</html>
