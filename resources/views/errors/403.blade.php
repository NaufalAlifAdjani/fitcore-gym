<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | FitCore Athletic</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800;900&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B0F17] font-sans antialiased text-slate-100 min-h-screen flex items-center justify-center p-4 selection:bg-[#ED1B45] selection:text-white relative overflow-hidden">
    <!-- Background Glow Effects -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#ED1B45]/15 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-red-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-xl mx-auto text-center relative z-10">
        <!-- Brand Logo -->
        <div class="mb-8 flex items-center justify-center gap-2">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#ED1B45] text-white shadow-lg shadow-[#ED1B45]/30">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h1.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 012.25 13.5v-3A1.5 1.5 0 013.75 9zm15 0h1.5A1.5 1.5 0 0121.75 10.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 0117.25 13.5v-3a1.5 1.5 0 011.5-1.5zM6.75 12h10.5M5.25 7.5v9M18.75 7.5v9" />
                </svg>
            </span>
            <span class="font-poppins font-extrabold text-2xl tracking-tight text-white">
                FIT<span class="text-[#ED1B45]">CORE</span>
            </span>
        </div>

        <!-- 403 Card Container -->
        <div class="bg-[#131B26]/90 border border-slate-800/80 rounded-3xl p-8 sm:p-10 shadow-2xl backdrop-blur-xl">
            <!-- Icon with Shield / Lock -->
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-red-500/10 border border-[#ED1B45]/20 text-[#ED1B45] shadow-inner mb-6">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>

            <!-- Error Code Tag -->
            <div class="inline-flex items-center gap-1.5 rounded-full bg-[#ED1B45]/15 border border-[#ED1B45]/30 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-[#ED1B45] mb-4">
                <span>HTTP 403</span>
                <span>•</span>
                <span>Akses Ditolak</span>
            </div>

            <!-- Heading & Description -->
            <h1 class="font-poppins text-2xl sm:text-3xl font-bold text-white tracking-tight">
                Area Khusus & Terbatas
            </h1>
            <p class="mt-3 font-inter text-slate-400 text-sm sm:text-base leading-relaxed">
                {{ $exception->getMessage() ?: 'Mohon maaf, peran akun Anda tidak memiliki izin atau otorisasi untuk mengakses halaman ini.' }}
            </p>

            @auth
                <!-- User Role Info -->
                <div class="mt-5 p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-xs font-inter text-slate-400 flex items-center justify-between">
                    <span>Login sebagai: <strong class="text-slate-200">{{ auth()->user()->name }}</strong></span>
                    <span class="capitalize px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-300 font-semibold uppercase tracking-wider text-[10px]">
                        Role: {{ auth()->user()->role }}
                    </span>
                </div>
            @endauth

            <!-- Actions -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-[#ED1B45] px-6 py-3 font-inter text-sm font-semibold text-white shadow-lg shadow-[#ED1B45]/25 hover:bg-[#D1123D] transition focus:outline-none focus:ring-2 focus:ring-[#ED1B45] focus:ring-offset-2 focus:ring-offset-[#131B26]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>

                <button onclick="window.history.back()" type="button" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 px-6 py-3 font-inter text-sm font-semibold text-slate-300 hover:text-white transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Halaman Sebelumnya</span>
                </button>
            </div>
        </div>

        <p class="mt-6 text-xs text-slate-500 font-inter">
            &copy; {{ date('Y') }} FitCore Athletic. Hak cipta dilindungi undang-undang.
        </p>
    </div>
</body>
</html>
