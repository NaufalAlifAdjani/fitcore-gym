<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F8F9FA]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Fitcore Athletic - Personal Trainer' }}</title>

    <!-- Google Fonts: Inter, Montserrat, Poppins & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#F8F9FA] text-zinc-900 antialiased selection:bg-[#E11D48] selection:text-white">

    <!-- NAVBAR -->
    <x-member-navbar :active="$activeNav ?? 'pt'" />

    <!-- CONTENT WRAPPER -->
    <main class="flex-1 w-full">
        {{ $slot }}
    </main>

    <!-- FOOTER (Background #0F0F12, Teks Putih & Abu-abu #9CA3AF) -->
    <footer class="bg-[#0F0F12] text-zinc-400 border-t border-zinc-900 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                <!-- Kolom 1 (Brand & Jam Operasional) -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#E11D48] flex items-center justify-center text-white shadow-md">
                            <svg class="w-5 h-5 rotate-[-45deg]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V5zm9 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V5zM4 9a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9zm12 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V9zM8 11h8v2H8v-2z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-black text-white tracking-wider">FITCORE</span>
                    </div>

                    <p class="text-xs text-zinc-400 leading-relaxed max-w-sm">
                        Pusat kebugaran dan pelatihan berintensitas tinggi dengan peralatan standar kejuaraan internasional, pelatih berlisensi tersertifikasi, dan kultur disiplin tanpa kompromi.
                    </p>

                    <div class="bg-[#18181C] border border-[#27272A] rounded-xl p-4 shadow-sm max-w-sm">
                        <div class="flex items-center gap-2 text-xs font-bold text-white mb-3">
                            <svg class="w-4 h-4 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                            </svg>
                            <span class="uppercase tracking-wider">JAM OPERASIONAL GYM</span>
                        </div>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-zinc-400">Senin – Jumat</span>
                                <span class="font-extrabold text-white">05:30 – 22:00 WIB</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-zinc-400">Sabtu – Minggu</span>
                                <span class="font-extrabold text-white">06:00 – 21:00 WIB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2 (Lokasi) -->
                <div>
                    <div class="flex items-center gap-2 text-xs font-black text-white tracking-widest uppercase mb-4">
                        <span class="text-[#E11D48] text-base leading-none">●</span>
                        <span>LOKASI</span>
                    </div>
                    <div class="bg-[#18181C] border border-[#27272A] rounded-xl p-5 shadow-sm">
                        <h4 class="text-white font-extrabold text-sm mb-1.5">Maxfit SCBD Mega Tower</h4>
                        <p class="text-xs text-[#9CA3AF] leading-relaxed">
                            Lt. B1 &amp; Mezzanine, Pacific Century Place, SCBD Lot 11, Jakarta Selatan
                        </p>
                    </div>
                </div>

                <!-- Kolom 3 (Kontak & Dukungan) -->
                <div>
                    <div class="flex items-center gap-2 text-xs font-black text-white tracking-widest uppercase mb-4">
                        <span class="text-[#E11D48] text-base leading-none">●</span>
                        <span>KONTAK &amp; DUKUNGAN</span>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-[#18181C] border border-[#27272A] rounded-xl p-4 flex items-center gap-3.5 shadow-sm">
                            <div class="w-10 h-10 rounded-lg bg-zinc-900 border border-zinc-800 flex items-center justify-center text-emerald-400 shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.073.043.42-.101.825z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 uppercase tracking-wider block">WhatsApp Concierge 24 Jam</span>
                                <a href="https://wa.me/628119988771" target="_blank" class="text-xs font-black text-emerald-400 hover:underline">
                                    +62 811-9988-771
                                </a>
                            </div>
                        </div>

                        <div class="bg-[#18181C] border border-[#27272A] rounded-xl p-4 flex items-center gap-3.5 shadow-sm">
                            <div class="w-10 h-10 rounded-lg bg-zinc-900 border border-zinc-800 flex items-center justify-center text-rose-500 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 uppercase tracking-wider block">Email Resmi Member &amp; Kerjasama</span>
                                <a href="mailto:support@maxfit.id" class="text-xs font-black text-white hover:underline">
                                    support@maxfit.id
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block mb-3">IKUTI KOMUNITAS ATLETIK</span>
                        <div class="flex items-center gap-2.5">
                            <!-- Instagram -->
                            <a href="#" class="w-9 h-9 rounded-full bg-[#18181C] border border-[#27272A] flex items-center justify-center text-zinc-400 hover:text-white hover:border-zinc-500 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <!-- YouTube -->
                            <a href="#" class="w-9 h-9 rounded-full bg-[#18181C] border border-[#27272A] flex items-center justify-center text-zinc-400 hover:text-white hover:border-zinc-500 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                            <!-- TikTok -->
                            <a href="#" class="w-9 h-9 rounded-full bg-[#18181C] border border-[#27272A] flex items-center justify-center text-zinc-400 hover:text-white hover:border-zinc-500 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                            </a>
                            <!-- LinkedIn -->
                            <a href="#" class="w-9 h-9 rounded-full bg-[#18181C] border border-[#27272A] flex items-center justify-center text-zinc-400 hover:text-white hover:border-zinc-500 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Bar Footer -->
            <div class="border-t border-zinc-800/80 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-zinc-400 gap-4">
                <p>&copy; 2025 PT Maxfit Kebugaran Indonesia. Hak Cipta Dilindungi.</p>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="#" class="hover:text-zinc-300 transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-zinc-300 transition">Syarat &amp; Ketentuan</a>
                    <a href="#" class="hover:text-zinc-300 transition">Protokol Keselamatan</a>
                    <a href="#" class="hover:text-zinc-300 transition">SOP Pelatih CSCS</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
