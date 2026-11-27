{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Web Desktop UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Daftar Harga Sesi Personal Trainer Publik
| FILE         : resources/views/landing/pt-pricing.blade.php
| LAYOUT       : layouts/public.blade.php
| AKSES ROUTE  : /pt-pricing
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.public title="Daftar Harga Sesi Personal Trainer - FitCore Athletic" active-tab="pt-pricing">

    <div class="py-16 bg-[#F6F6F7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- 1. HEADER SECTION -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-[#FF5722]">TARIF &amp; PAKET SESI PRIVAT</span>
                <h1 class="font-poppins font-black text-3xl sm:text-5xl text-[#16151A] tracking-tight mt-2">
                    Investasi Terbaik Untuk Transformasi Fisik Terukur
                </h1>
                <p class="text-[#565A66] text-sm sm:text-base mt-4 leading-relaxed font-normal">
                    Pilih kuota paket sesi latihan privat bersama pelatih bersertifikasi internasional. Dapatkan program hipertrofi, koreksi biomekanika angkat beban, dan pantauan performa intensif.
                </p>

                <!-- Tombol Navigasi Cepat ke Profil Pelatih -->
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('landing.trainers') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white hover:bg-gray-50 border border-[#E5E7EB] text-xs font-bold text-[#16151A] hover:text-[#FF5722] shadow-2xs transition">
                        <svg class="w-4 h-4 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Lihat Profil Pelatih Kami &rarr;</span>
                    </a>
                    <a href="{{ route('landing.packages') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white hover:bg-gray-50 border border-[#E5E7EB] text-xs font-bold text-[#565A66] hover:text-[#16151A] shadow-2xs transition">
                        <span>Paket Membership Gym &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- 2. GRID PAKET SESI PT -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch mb-16">
                @forelse ($ptPackages as $pkg)
                    @php
                        $isPopular = in_array($pkg->pt_session_count, [16, 24]);
                        $perSession = $pkg->pt_session_count > 0 ? round($pkg->price / $pkg->pt_session_count) : $pkg->price;
                        $originalPrice = round($pkg->price * 1.25);
                    @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-7 flex flex-col justify-between relative transition-all duration-300 {{ $isPopular ? 'border-2 border-[#FF5722] shadow-[0_12px_36px_rgba(255,87,34,0.16)] md:-translate-y-1' : 'border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] hover:shadow-lg hover:border-[#FF5722]/40' }}">
                        @if ($isPopular)
                            <div class="absolute -top-3.5 right-6 bg-gradient-to-r from-[#FF5722] to-[#FFB703] text-white text-[11px] font-black tracking-wider uppercase px-3.5 py-1 rounded-full shadow-md shadow-[#FF5722]/30 flex items-center gap-1.5">
                                <span>🔥</span>
                                <span>PALING POPULER</span>
                            </div>
                        @endif

                        <div>
                            <!-- Header Baris Sesi & Masa Aktif -->
                            <div class="flex items-baseline justify-between gap-2">
                                <div class="flex items-baseline">
                                    <span class="text-4xl sm:text-5xl font-black tracking-tight text-[#FF5722]">
                                        {{ $pkg->pt_session_count }}
                                    </span>
                                    <span class="text-lg sm:text-xl font-bold ml-1.5 text-[#FF5722]">
                                        Sesi
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-100 text-[#565A66] text-xs font-bold shrink-0">
                                    Masa Aktif {{ $pkg->validity_days }} Hari
                                </span>
                            </div>

                            <!-- Nama Paket -->
                            <div class="mt-1">
                                <span class="text-xs font-bold text-[#565A66] uppercase tracking-wider">
                                    {{ $pkg->name }}
                                </span>
                            </div>

                            <!-- Total Harga & Coret -->
                            <div class="mt-4">
                                <div class="text-base font-black text-[#16151A]">
                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-[#565A66]/60 line-through mt-0.5">
                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                </div>
                            </div>

                            <hr class="my-5 border-[#E5E7EB]">

                            <!-- Harga Per Sesi -->
                            <div>
                                <div class="text-2xl font-black text-[#16151A] tracking-tight">
                                    Rp {{ number_format($perSession, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-[#565A66] font-semibold mt-0.5">
                                    / Sesi
                                </div>
                            </div>

                            <!-- Accordion Interaktif Layanan Termasuk -->
                            <div class="mt-5" x-data="{ openBenefits: false }">
                                <button type="button" @click="openBenefits = !openBenefits" class="w-full flex items-center justify-between py-2 px-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-200/70 text-xs font-bold text-[#565A66] hover:text-[#16151A] transition">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Layanan Termasuk</span>
                                    </span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openBenefits }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="openBenefits" x-collapse x-cloak class="mt-2 space-y-2 text-xs text-[#565A66] bg-gray-50/80 p-3.5 rounded-xl border border-gray-100">
                                    <div class="flex items-start gap-2">
                                        <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                        <span>Pendampingan 1-on-1 Intensif (60 Menit / Sesi)</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                        <span>Pilihan Instruktur Berlisensi CSCS / NASM</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                        <span>Jadwal Latihan Fleksibel (Pagi, Siang, &amp; Malam)</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                        <span>Konsultasi Nutrisi &amp; Evaluasi Progres Berkala</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-6">
                            @auth
                                <a href="{{ route('member.pt-packages.select-trainer', ['package_id' => $pkg->id]) }}" 
                                   class="block w-full py-3.5 rounded-xl text-center text-xs sm:text-sm font-extrabold {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-[#FF5722]/25' : 'bg-[#16151A] hover:bg-[#FF5722] text-white shadow-xs' }} transition active:scale-[0.99]">
                                    Pilih Paket Sesi Ini &rarr;
                                </a>
                            @else
                                <a href="{{ route('login', ['redirect' => route('member.pt-packages.select-trainer', ['package_id' => $pkg->id])]) }}" 
                                   class="block w-full py-3.5 rounded-xl text-center text-xs sm:text-sm font-extrabold {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-[#FF5722]/25' : 'bg-[#16151A] hover:bg-[#FF5722] text-white shadow-xs' }} transition active:scale-[0.99]">
                                    Pilih Paket Sesi Ini &rarr;
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-[#E5E7EB] p-8">
                        <p class="text-sm font-semibold text-[#565A66]">Saat ini belum ada paket sesi PT yang aktif.</p>
                    </div>
                @endforelse
            </div>

            <!-- 3. FOOTER INFO BANNER -->
            <div class="bg-white rounded-2xl p-8 sm:p-10 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1.5 text-center md:text-left">
                    <h3 class="font-poppins font-black text-xl text-[#16151A]">Butuh Informasi Lebih Lanjut?</h3>
                    <p class="text-xs text-[#565A66]">
                        Konsultasikan kebutuhan spesifik dan target fisik Anda langsung dengan tim konsultan kebugaran FitCore.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="https://wa.me/628119988771" target="_blank" class="px-6 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                        <span>Hubungi via WhatsApp</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</x-layouts.public>
