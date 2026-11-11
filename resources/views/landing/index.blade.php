{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Web Desktop UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Landing Page Publik
| FILE         : resources/views/landing/index.blade.php
| LAYOUT       : layouts/public.blade.php
| AKSES ROUTE  : /
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.public title="FitCore Athletic - Pusat Kebugaran & Pelatihan Performa Tinggi" active-tab="home">

    <!-- 1. HERO SECTION -->
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-[#F6F6F7] to-[#F6F6F7] text-[#16151A] py-20 lg:py-28 border-b border-[#E5E7EB]">
        <!-- Background Radial Glow Effect -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-orange-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-[#FF5722]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Kolom Kiri: Teks & CTA -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-200/80 text-xs font-bold text-[#FF5722]">
                        <span class="w-2 h-2 rounded-full bg-[#FF5722] animate-pulse"></span>
                        PUSAT KEBUGARAN PREMIER &amp; HIGH PERFORMANCE
                    </div>

                    <h1 class="font-poppins font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.1] text-[#16151A]">
                        TRANSFORMASI TOTAL <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#16151A] via-[#FF5722] to-[#FF8A00]">KEKUATAN &amp; PERFORMA</span>
                    </h1>

                    <p class="text-base sm:text-lg text-[#565A66] max-w-xl font-normal leading-relaxed">
                        Fasilitas berstandar kejuaraan dunia, pelatih berlisensi internasional, dan kultur disiplin tanpa kompromi untuk mencapai potensi fisik tertinggi Anda.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('landing.packages') }}" class="px-8 py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-sm font-bold shadow-lg shadow-orange-500/25 transition transform hover:-translate-y-0.5 active:translate-y-0">
                            Pilih Paket Membership
                        </a>
                        <a href="{{ route('landing.trainers') }}" class="px-7 py-3.5 rounded-full bg-white hover:bg-gray-50 border border-[#E5E7EB] text-[#16151A] hover:border-[#FF5722] hover:text-[#FF5722] text-sm font-bold shadow-xs transition">
                            Lihat Pelatih Kami
                        </a>
                    </div>

                    <!-- Highlight Tags -->
                    <div class="pt-4 flex flex-wrap items-center gap-6 text-xs text-[#565A66] font-medium">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FF5722]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Peralatan Hammer Strength &amp; Eleiko
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FF5722]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Pelatih Berlisensi CSCS / NASM
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FF5722]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Sauna &amp; Ice Bath Recovery
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Athletic Stat Hero Card -->
                <div class="lg:col-span-5">
                    <div class="relative bg-white p-8 rounded-3xl border border-[#E5E7EB] shadow-[0_12px_32px_rgba(0,0,0,0.06)]">
                        <div class="flex items-center justify-between pb-6 border-b border-[#E5E7EB]">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">STATUS PUSAT KEBUGARAN</span>
                                <h3 class="text-xl font-poppins font-black text-[#16151A] mt-1">FitCore SCBD Arena</h3>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Buka 05:30 - 22:00
                            </span>
                        </div>

                        <!-- Ringkasan Fitur Unggulan -->
                        <div class="grid grid-cols-2 gap-4 my-6">
                            <div class="bg-[#F6F6F7] p-4 rounded-2xl border border-[#E5E7EB]">
                                <span class="text-2xl font-poppins font-black text-[#16151A]">1.800+ m²</span>
                                <p class="text-xs text-[#565A66] mt-1">Luas Area Training Terbuka</p>
                            </div>
                            <div class="bg-[#F6F6F7] p-4 rounded-2xl border border-[#E5E7EB]">
                                <span class="text-2xl font-poppins font-black text-[#FF5722]">100%</span>
                                <p class="text-xs text-[#565A66] mt-1">Peralatan Terkalibrasi</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between text-xs text-[#565A66]">
                                <span>Kapasitas Latihan Real-time</span>
                                <span class="font-bold text-[#16151A]">42% (Optimal)</span>
                            </div>
                            <div class="w-full bg-[#E5E7EB] rounded-full h-2">
                                <div class="bg-[#FF5722] h-2 rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KEY METRICS STRIP -->
    <section class="bg-white border-y border-[#E5E7EB] py-10 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-[#E5E7EB]">
                <div class="pt-4 md:pt-0">
                    <span class="font-poppins font-black text-3xl sm:text-4xl text-[#16151A] tracking-tight">15.000+</span>
                    <p class="text-xs sm:text-sm font-semibold text-[#565A66] mt-1">Member Aktif Bertransformasi</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="font-poppins font-black text-3xl sm:text-4xl text-[#FF5722] tracking-tight">98.7%</span>
                    <p class="text-xs sm:text-sm font-semibold text-[#565A66] mt-1">Tingkat Kepuasan Member</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="font-poppins font-black text-3xl sm:text-4xl text-[#16151A] tracking-tight">24 / 7</span>
                    <p class="text-xs sm:text-sm font-semibold text-[#565A66] mt-1">Akses &amp; Layanan Terintegrasi</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="font-poppins font-black text-3xl sm:text-4xl text-[#16151A] tracking-tight">50+</span>
                    <p class="text-xs sm:text-sm font-semibold text-[#565A66] mt-1">Pelatih Berlisensi Resmi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. KEUNGGULAN FITCORE GYM (GYM FEATURES) -->
    <section class="py-20 bg-[#F6F6F7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold text-[#FF5722] uppercase tracking-widest">KENAPA MEMILIH FITCORE</span>
                <h2 class="font-poppins font-extrabold text-3xl sm:text-4xl text-[#16151A] mt-2">
                    Standar Baru Pusat Kebugaran Atletik
                </h2>
                <p class="text-[#565A66] text-sm sm:text-base mt-3 leading-relaxed">
                    Kami memadukan fasilitas elit, sains pelatihan modern, dan ekosistem digital untuk menjamin hasil optimal bagi setiap member.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] hover:border-[#FF5722]/50 transition duration-200">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="font-poppins font-bold text-lg text-[#16151A]">Peralatan Standar Dunia</h3>
                    <p class="text-xs text-[#565A66] mt-2 leading-relaxed">
                        Hammer Strength, Eleiko bar &amp; plates, dan Technogym Pure Strength yang dikalibrasi presisi untuk latihan beban maksimal.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] hover:border-[#FF5722]/50 transition duration-200">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <h3 class="font-poppins font-bold text-lg text-[#16151A]">Trainer Berlisensi</h3>
                    <p class="text-xs text-[#565A66] mt-2 leading-relaxed">
                        Seluruh pelatih kami mengantongi sertifikasi internasional (NASM, CSCS, ACE) dengan program periodisasi terukur.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] hover:border-[#FF5722]/50 transition duration-200">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="font-poppins font-bold text-lg text-[#16151A]">Recovery &amp; Sauna</h3>
                    <p class="text-xs text-[#565A66] mt-2 leading-relaxed">
                        Fasilitas pemulihan lengkap: sauna inframerah, ice bath plunge, serta kamar mandi air panas standar bintang lima.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] hover:border-[#FF5722]/50 transition duration-200">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="font-poppins font-bold text-lg text-[#16151A]">Aplikasi Digital Terpadu</h3>
                    <p class="text-xs text-[#565A66] mt-2 leading-relaxed">
                        Check-in instan dengan QR digital, booking jadwal sesi PT langsung dari smartphone, serta riwayat latihan transparan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FEATURED PACKAGES PREVIEW -->
    <section class="py-20 bg-white border-t border-[#E5E7EB]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <span class="text-xs font-bold text-[#FF5722] uppercase tracking-widest">PILIHAN MEMBERSHIP</span>
                    <h2 class="font-poppins font-extrabold text-3xl sm:text-4xl text-[#16151A] mt-2">
                        Paket Keanggotaan Terfavorit
                    </h2>
                </div>
                <a href="{{ route('landing.packages') }}" class="mt-4 md:mt-0 inline-flex items-center gap-1.5 text-sm font-bold text-[#FF5722] hover:text-[#E64A19] transition">
                    Lihat Seluruh Paket &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($packages as $pkg)
                    <div class="relative bg-white rounded-2xl p-8 border {{ $loop->iteration === 2 ? 'border-[#FF5722] ring-2 ring-[#FF5722]/20 shadow-xl' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]' }} flex flex-col justify-between">
                        @if ($loop->iteration === 2)
                            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-[#FF5722] text-white text-[11px] font-black uppercase tracking-wider shadow-sm">
                                Paling Populer
                            </span>
                        @endif

                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">{{ $pkg->type ?? 'Membership Reguler' }}</span>
                            <h3 class="font-poppins font-extrabold text-2xl text-[#16151A] mt-1">{{ $pkg->name }}</h3>
                            <p class="text-xs text-[#565A66] mt-1">{{ $pkg->description ?? 'Akses fasilitas lengkap dan bimbingan fitness terarah.' }}</p>

                            <div class="my-6">
                                <span class="font-poppins font-black text-3xl text-[#16151A]">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-[#565A66] font-medium">/ {{ $pkg->duration_days ?? 30 }} Hari</span>
                            </div>

                            <ul class="space-y-3 text-xs text-[#16151A] border-t border-[#E5E7EB] pt-6 mb-8">
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Akses Gym Penuh (05:30 - 22:00)</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Loker Harian &amp; Shower Air Panas</span>
                                </li>
                                @if ($pkg->pt_session_count > 0 || $pkg->pt_sessions > 0)
                                    <li class="flex items-center gap-2.5 font-bold text-[#FF5722]">
                                        <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Termasuk {{ $pkg->pt_session_count ?? $pkg->pt_sessions }} Sesi Personal Trainer</span>
                                    </li>
                                @endif
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Konsultasi InBody &amp; Komposisi Tubuh</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            @auth
                                <a href="{{ route('member.membership.checkout', $pkg->id) }}" class="block w-full py-3.5 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#FF5722] text-white hover:bg-[#E64A19] shadow-md shadow-orange-500/30' : 'bg-white text-[#FF5722] border-2 border-[#FF5722] hover:bg-[#FF5722] hover:text-white' }} transition">
                                    Pilih Paket Ini
                                </a>
                            @else
                                <a href="{{ route('login', ['redirect' => route('member.membership.checkout', $pkg->id)]) }}" class="block w-full py-3.5 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#FF5722] text-white hover:bg-[#E64A19] shadow-md shadow-orange-500/30' : 'bg-white text-[#FF5722] border-2 border-[#FF5722] hover:bg-[#FF5722] hover:text-white' }} transition">
                                    Pilih Paket Ini
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. FEATURED TRAINERS PREVIEW -->
    <section class="py-20 bg-[#F6F6F7] border-t border-[#E5E7EB]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <span class="text-xs font-bold text-[#FF5722] uppercase tracking-widest">PELATIH BERLISENSI</span>
                    <h2 class="font-poppins font-extrabold text-3xl sm:text-4xl text-[#16151A] mt-2">
                        Bimbingan Profesional dari Para Ahli
                    </h2>
                </div>
                <a href="{{ route('landing.trainers') }}" class="mt-4 md:mt-0 inline-flex items-center gap-1.5 text-sm font-bold text-[#FF5722] hover:text-[#E64A19] transition">
                    Lihat Seluruh Pelatih &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($trainers as $trainer)
                    <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#FF5722] to-[#FFB703] text-white flex items-center justify-center font-poppins font-black text-lg shadow-sm shadow-orange-500/25 border border-white/20 shrink-0">
                                    {{ strtoupper(substr($trainer->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-poppins font-extrabold text-lg text-[#16151A] leading-snug">{{ $trainer->name }}</h3>
                                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-orange-50 text-[#FF5722] text-[11px] font-bold mt-1">
                                        {{ $trainer->trainerProfile->specialization ?? 'General Fitness & Strength' }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-xs text-[#565A66] leading-relaxed mb-6">
                                {{ $trainer->trainerProfile->bio ?? 'Berpengalaman mendampingi ratusan member mencapai target kebugaran dan kekuatan atletik secara optimal.' }}
                            </p>

                            <div class="flex items-center justify-between text-xs text-[#565A66] pt-4 border-t border-[#E5E7EB] mb-6">
                                <div class="flex items-center gap-1 font-bold text-amber-500">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-[#16151A]">{{ $trainer->trainerProfile->rating ?? '4.98' }}</span>
                                    <span class="text-[#565A66] font-normal">({{ $trainer->trainerProfile->review_count ?? '150+' }})</span>
                                </div>
                                <span class="font-bold text-[#16151A]">{{ $trainer->trainerProfile->experience ?? 4 }}+ Tahun Pengalaman</span>
                            </div>
                        </div>

                        <div>
                            @auth
                                <a href="{{ route('pt-sessions.create', ['trainer_id' => $trainer->id]) }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-sm shadow-orange-500/25 transition">
                                    Booking Sesi Bersama {{ explode(' ', $trainer->name)[0] }}
                                </a>
                            @else
                                <a href="{{ route('login', ['redirect' => route('pt-sessions.create', ['trainer_id' => $trainer->id])]) }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-sm shadow-orange-500/25 transition">
                                    Booking Sesi Bersama {{ explode(' ', $trainer->name)[0] }}
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. FAQ ACCORDION -->
    <section class="py-20 bg-white border-t border-[#E5E7EB]" x-data="{ activeAccordion: 1 }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-[#FF5722] uppercase tracking-widest">TANYA JAWAB UMUM</span>
                <h2 class="font-poppins font-extrabold text-3xl text-[#16151A] mt-2">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-xs sm:text-sm text-[#565A66] mt-2">Segala informasi penting sebelum Anda memulai langkah pertama di FitCore.</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-[#F6F6F7] overflow-hidden transition">
                    <button type="button" @click="activeAccordion = activeAccordion === 1 ? null : 1" class="w-full px-6 py-4.5 text-left flex items-center justify-between font-poppins font-bold text-sm text-[#16151A]">
                        <span>Apakah ada biaya pendaftaran awal (joining fee)?</span>
                        <svg class="w-4 h-4 text-[#FF5722] transition-transform duration-200" :class="activeAccordion === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="activeAccordion === 1" class="px-6 pb-5 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/60 pt-3">
                        Tidak ada biaya tersembunyi. Seluruh harga paket yang tertera di FitCore sudah termasuk biaya registrasi akun dan akses penuh ke fasilitas sesuai tingkatan paket yang Anda pilih.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-[#F6F6F7] overflow-hidden transition">
                    <button type="button" @click="activeAccordion = activeAccordion === 2 ? null : 2" class="w-full px-6 py-4.5 text-left flex items-center justify-between font-poppins font-bold text-sm text-[#16151A]">
                        <span>Bagaimana alur pemesanan sesi Personal Trainer (PT)?</span>
                        <svg class="w-4 h-4 text-[#FF5722] transition-transform duration-200" :class="activeAccordion === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="activeAccordion === 2" class="px-6 pb-5 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/60 pt-3">
                        Setelah bergabung menjadi member dan memiliki kuota sesi PT, Anda dapat memilih pelatih di katalog, menentukan jam slot latihan yang tersedia, dan mengajukan booking secara instan melalui aplikasi atau website FitCore.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-[#F6F6F7] overflow-hidden transition">
                    <button type="button" @click="activeAccordion = activeAccordion === 3 ? null : 3" class="w-full px-6 py-4.5 text-left flex items-center justify-between font-poppins font-bold text-sm text-[#16151A]">
                        <span>Apakah fasilitas loker dan shower sudah termasuk dalam membership?</span>
                        <svg class="w-4 h-4 text-[#FF5722] transition-transform duration-200" :class="activeAccordion === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="activeAccordion === 3" class="px-6 pb-5 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/60 pt-3">
                        Ya, seluruh member aktif berhak menggunakan fasilitas loker harian gratis, kamar mandi dengan shower air panas bertekanan tinggi, handuk bersih, serta akses sauna pemulihan setelah berolahraga.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-[#F6F6F7] overflow-hidden transition">
                    <button type="button" @click="activeAccordion = activeAccordion === 4 ? null : 4" class="w-full px-6 py-4.5 text-left flex items-center justify-between font-poppins font-bold text-sm text-[#16151A]">
                        <span>Apakah pemula yang belum pernah gym sebelumnya bisa bergabung?</span>
                        <svg class="w-4 h-4 text-[#FF5722] transition-transform duration-200" :class="activeAccordion === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="activeAccordion === 4" class="px-6 pb-5 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/60 pt-3">
                        Tentu saja! FitCore sangat menyambut siapapun dari berbagai level kebugaran. Tim instruktur kami siap memandu teknik dasar angkat beban yang aman dan menyusun program yang sesuai dengan tujuan fisik Anda.
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-[#F6F6F7] overflow-hidden transition">
                    <button type="button" @click="activeAccordion = activeAccordion === 5 ? null : 5" class="w-full px-6 py-4.5 text-left flex items-center justify-between font-poppins font-bold text-sm text-[#16151A]">
                        <span>Metode pembayaran apa saja yang diterima?</span>
                        <svg class="w-4 h-4 text-[#FF5722] transition-transform duration-200" :class="activeAccordion === 5 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="activeAccordion === 5" class="px-6 pb-5 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/60 pt-3">
                        Kami menerima pembayaran transfer bank melalui Bank Mandiri, BCA, BNI, dan BRI, serta pembayaran instan melalui QRIS nasional dengan verifikasi bukti transfer yang cepat dan aman.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. BOTTOM CALL TO ACTION BANNER -->
    <section class="py-16 bg-gradient-to-r from-orange-50/70 via-amber-50/40 to-orange-50/70 border-t border-[#E5E7EB] text-[#16151A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="inline-block px-4 py-1 rounded-full bg-white text-[#FF5722] text-xs font-black uppercase tracking-widest border border-orange-200 shadow-xs">
                MULAI HARI INI
            </span>
            <h2 class="font-poppins font-black text-3xl sm:text-5xl tracking-tight text-[#16151A] max-w-2xl mx-auto">
                Siap Mengubah Batasan Diri Anda?
            </h2>
            <p class="text-[#565A66] text-sm sm:text-base max-w-xl mx-auto font-normal">
                Bergabunglah bersama ribuan member lain yang telah membuktikan transformasi kekuatan nyata di FitCore Athletic.
            </p>
            <div class="pt-4 flex items-center justify-center gap-4">
                <a href="{{ route('landing.packages') }}" class="px-8 py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-sm font-bold shadow-lg shadow-orange-500/25 transition transform hover:-translate-y-0.5">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

</x-layouts.public>

