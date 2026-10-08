<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div
            x-data="{ sidebarOpen: false, profileOpen: false, showLogoutModal: false }"
            x-on:keydown.escape.window="showLogoutModal = false"
            class="min-h-screen bg-[#F9F9FA]"
        >
            @include('layouts.navigation')

            <main class="min-h-screen pt-16 lg:pl-72">
                @isset($header)
                    <header class="bg-white px-4 py-5 shadow-sm sm:px-6 lg:px-8">
                        {{ $header }}
                    </header>
                @endisset
                {{ $slot }}
            </main>

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
                    class="w-full max-w-md rounded-2xl border-t-4 border-[#BA0030] bg-white p-6 shadow-2xl sm:p-8"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-[#BA0030]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3.5 2.9 19.25a1.5 1.5 0 0 0 1.3 2.25h15.6a1.5 1.5 0 0 0 1.3-2.25L12 3.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="M12 9v5m0 3h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    <div class="mt-5 text-center">
                        <h2 id="logout-modal-title" class="font-heading text-xl font-bold text-[#16151A]">Konfirmasi Keluar</h2>
                        <p class="mt-2 text-sm leading-6 text-[#565A66]">
                            Apakah Anda yakin ingin keluar dari akun ini? Sesi Anda saat ini akan diakhiri.
                        </p>
                    </div>

                    <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                        <button
                            type="button"
                            x-on:click="showLogoutModal = false"
                            class="rounded-full bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2"
                        >
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full rounded-full bg-[#BA0030] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#970027] focus:outline-none focus:ring-2 focus:ring-[#BA0030] focus:ring-offset-2"
                            >
                                Ya, Keluar
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>
