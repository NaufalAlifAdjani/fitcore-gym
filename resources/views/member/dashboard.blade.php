<x-member-layout title="Beranda Member - FitCore Athletic" active-nav="beranda">
    <div class="min-h-screen bg-[#F6F6F7] text-[#16151A] py-8 sm:py-10 px-4 sm:px-6 lg:px-8 font-inter">
        <div class="mx-auto max-w-6xl space-y-8">

            <!-- 1. Header Sambutan -->
            <header class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-[#FF5722] text-xs font-inter font-bold uppercase tracking-wider border border-orange-100">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#FF5722]"></span>
                    Member Portal FitCore
                </div>
                <h1 class="font-poppins font-extrabold text-3xl sm:text-4xl text-[#16151A] tracking-tight leading-tight">
                    Selamat Datang, {{ Auth::user()->name }}!
                </h1>
                <p class="font-inter text-xs sm:text-sm text-[#565A66] max-w-2xl leading-relaxed">
                    Kelola keanggotaan gym Anda, jadwalkan sesi latihan bersama pelatih pribadi, dan akses kartu digital untuk check-in mandiri.
                </p>
            </header>

            <!-- 2. Navigasi Aksi Cepat (3 Card Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Kartu Member Digital -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col justify-between hover:border-[#FF5722]/40 transition space-y-5">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <h3 class="font-poppins font-bold text-lg text-[#16151A]">Kartu Member Digital</h3>
                        <p class="text-xs text-[#565A66] leading-relaxed">
                            Tunjukkan QR Code dinamis Anda kepada staf resepsionis untuk akses check-in pintu turnstile instan.
                        </p>
                    </div>
                    <a href="{{ route('member.card') }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold bg-[#FF5722] text-white hover:bg-[#E64A19] transition shadow-md shadow-orange-500/20 cursor-pointer">
                        Buka Kartu Digital &rarr;
                    </a>
                </div>

                <!-- Card 2: Paket Membership -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col justify-between hover:border-[#FF5722]/40 transition space-y-5">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="font-poppins font-bold text-lg text-[#16151A]">Paket Membership</h3>
                        <p class="text-xs text-[#565A66] leading-relaxed">
                            Pilih atau perpanjang paket keanggotaan gym Anda untuk menikmati akses penuh seluruh zona kebugaran.
                        </p>
                    </div>
                    <a href="{{ route('member.membership.packages') }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold border-2 border-[#FF5722] text-[#FF5722] hover:bg-[#FF5722] hover:text-white transition shadow-xs cursor-pointer">
                        Lihat Katalog Paket &rarr;
                    </a>
                </div>

                <!-- Card 3: Personal Trainer -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col justify-between hover:border-[#FF5722]/40 transition space-y-5">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-poppins font-bold text-lg text-[#16151A]">Sesi Personal Trainer</h3>
                        <p class="text-xs text-[#565A66] leading-relaxed">
                            Jadwalkan sesi latihan bersama pelatih pribadi tersertifikasi NASM / CSCS untuk akselerasi target fisik Anda.
                        </p>
                    </div>
                    <a href="{{ route('pt-sessions.index') }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold border-2 border-[#FF5722] text-[#FF5722] hover:bg-[#FF5722] hover:text-white transition shadow-xs cursor-pointer">
                        Jadwal &amp; Sesi PT &rarr;
                    </a>
                </div>
            </div>

            <!-- 3. Panel Ringkasan Informasi Fasilitas -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#E5E7EB] pb-5">
                    <div>
                        <h2 class="font-poppins font-bold text-xl text-[#16151A]">Konten Beranda Member</h2>
                        <p class="text-xs text-[#565A66] mt-1 leading-relaxed">Panduan fasilitas resmi dan informasi operasional pusat kebugaran FitCore Athletic.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Gym Buka 24 Jam Nonstop
                    </span>
                </div>

                <!-- 4 Keunggulan Fasilitas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-poppins font-bold text-sm text-[#16151A]">Peralatan Hammer &amp; Eleiko</h4>
                            <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">Area angkat beban kalibrasi presisi standar kompetisi internasional.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-poppins font-bold text-sm text-[#16151A]">Loker &amp; Shower Air Hangat</h4>
                            <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">Fasilitas loker harian aman dan ruang bilas higienis bersabun.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-poppins font-bold text-sm text-[#16151A]">Sauna &amp; Ice Bath Plunge</h4>
                            <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">Fasilitas pemulihan tubuh intensif untuk regenerasi otot maksimal.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-poppins font-bold text-sm text-[#16151A]">Customer Service &amp; Help Desk</h4>
                            <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">Dukungan staf front-desk responsif setiap saat selama jam gym beroperasi.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-member-layout>