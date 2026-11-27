{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Daftar Harga Sesi Personal Trainer Publik
| FILE         : resources/views/mobile/landing/pt-pricing.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m/pt-pricing
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="Harga Sesi PT - FitCore Mobile" active-tab="pt-pricing">

    <div class="px-4 py-5 space-y-5">

        <!-- 1. Header Ringkas -->
        <div class="space-y-1.5">
            <span class="text-[10px] font-bold text-[#FF5722] uppercase tracking-wider">PAKET SESI PRIVAT</span>
            <h1 class="font-poppins font-black text-2xl text-[#16151A]">Harga Sesi PT</h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Pilih kuota paket sesi personal trainer untuk bimbingan intensif 1-on-1 bersama instruktur berlisensi dunia.
            </p>
        </div>

        <!-- 2. Stack Kartu Paket Sesi PT -->
        <div class="space-y-4">
            @forelse ($ptPackages as $pkg)
                @php
                    $isPopular = in_array($pkg->pt_session_count, [16, 24]);
                    $perSession = $pkg->pt_session_count > 0 ? round($pkg->price / $pkg->pt_session_count) : $pkg->price;
                    $originalPrice = round($pkg->price * 1.25);
                @endphp
                <div class="relative bg-white rounded-2xl p-5 border {{ $isPopular ? 'border-[#FF5722] ring-1 ring-[#FF5722]/30 shadow-md' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]' }} flex flex-col justify-between">
                    @if ($isPopular)
                        <div class="absolute -top-2.5 right-4 bg-gradient-to-r from-[#FF5722] to-[#FFB703] text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full shadow-xs">
                            Populer
                        </div>
                    @endif

                    <div>
                        <!-- Header Baris Sesi & Masa Aktif -->
                        <div class="flex items-baseline justify-between gap-2">
                            <div class="flex items-baseline">
                                <span class="text-3xl font-black font-poppins text-[#FF5722]">
                                    {{ $pkg->pt_session_count }}
                                </span>
                                <span class="text-base font-bold ml-1 text-[#FF5722]">
                                    Sesi
                                </span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-[#565A66] text-[10px] font-bold shrink-0">
                                Masa Aktif {{ $pkg->validity_days }} Hari
                            </span>
                        </div>

                        <!-- Nama Paket -->
                        <div class="mt-0.5">
                            <span class="text-[11px] font-bold text-[#565A66] uppercase tracking-wider">
                                {{ $pkg->name }}
                            </span>
                        </div>

                        <!-- Total Harga & Coret -->
                        <div class="mt-3">
                            <div class="text-sm font-black text-[#16151A]">
                                Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-[#565A66]/60 line-through mt-0.5">
                                Rp {{ number_format($originalPrice, 0, ',', '.') }}
                            </div>
                        </div>

                        <hr class="my-3.5 border-[#E5E7EB]">

                        <!-- Harga Per Sesi -->
                        <div>
                            <div class="text-xl font-black text-[#16151A] tracking-tight">
                                Rp {{ number_format($perSession, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-[#565A66] font-semibold mt-0.5">
                                / Sesi
                            </div>
                        </div>

                        <!-- Accordion Interaktif Layanan Termasuk -->
                        <div class="mt-3" x-data="{ openBenefits: false }">
                            <button type="button" @click="openBenefits = !openBenefits" class="w-full flex items-center justify-between py-1.5 px-2.5 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200/70 text-[11px] font-bold text-[#565A66] hover:text-[#16151A] transition">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Layanan Termasuk</span>
                                </span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openBenefits }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="openBenefits" x-collapse x-cloak class="mt-2 space-y-1.5 text-[11px] text-[#565A66] bg-gray-50/80 p-2.5 rounded-xl border border-gray-100">
                                <div class="flex items-start gap-1.5">
                                    <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                    <span>Pendampingan 1-on-1 Intensif (60 Menit / Sesi)</span>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                    <span>Pilihan Instruktur Berlisensi CSCS / NASM</span>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                    <span>Jadwal Latihan Fleksibel (Pagi, Siang, &amp; Malam)</span>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="text-[#FF5722] font-black shrink-0">✓</span>
                                    <span>Konsultasi Nutrisi &amp; Evaluasi Progres Berkala</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-4">
                        @auth
                            <a href="{{ route('mobile.member.pt-packages.select-trainer', ['package_id' => $pkg->id]) }}" 
                               class="block w-full py-2.5 rounded-xl text-center text-xs font-bold {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-sm shadow-orange-500/25' : 'bg-[#16151A] hover:bg-[#FF5722] text-white shadow-xs' }} active:scale-98 transition">
                                Pilih Paket Sesi Ini &rarr;
                            </a>
                        @else
                            <a href="{{ route('mobile.login', ['redirect' => route('mobile.member.pt-packages.select-trainer', ['package_id' => $pkg->id])]) }}" 
                               class="block w-full py-2.5 rounded-xl text-center text-xs font-bold {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-sm shadow-orange-500/25' : 'bg-[#16151A] hover:bg-[#FF5722] text-white shadow-xs' }} active:scale-98 transition">
                                Pilih Paket Sesi Ini &rarr;
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-6 text-center border border-[#E5E7EB]">
                    <p class="text-xs text-[#565A66]">Saat ini belum ada paket sesi PT yang aktif.</p>
                </div>
            @endforelse
        </div>

        <!-- Banner Info Pelatih -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] space-y-2">
            <span class="text-xs font-bold text-[#16151A] block">Ingin Memilih Pelatih Terlebih Dahulu?</span>
            <p class="text-[11px] text-[#565A66] leading-relaxed">
                Anda juga dapat melihat profil, sertifikasi, dan spesialisasi instruktur kami di katalog pelatih.
            </p>
            <div class="pt-1">
                <a href="{{ route('mobile.landing.trainers') }}" class="text-xs font-bold text-[#FF5722]">
                    Lihat Profil Seluruh Pelatih &rarr;
                </a>
            </div>
        </div>

    </div>

</x-layouts.mobile-guest>
