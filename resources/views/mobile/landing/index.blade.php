{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Landing Page Publik
| FILE         : resources/views/mobile/landing/index.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #ED1B45)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="FitCore Athletic - Mobile" active-tab="home">

    <div class="px-4 py-5 space-y-6">

        <!-- 1. HERO BANNER MOBILE -->
        <div class="relative overflow-hidden bg-[#141414] rounded-2xl p-6 text-white shadow-lg border border-white/10">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#ED1B45]/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative space-y-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold uppercase tracking-wider text-white border border-white/15">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#ED1B45] animate-pulse"></span>
                    PREMIER ATHLETIC GYM
                </span>

                <h1 class="font-poppins font-black text-2xl tracking-tight leading-tight">
                    TRANSFORMASI TOTAL <br>
                    <span class="text-[#ED1B45]">KEKUATAN ANDA</span>
                </h1>

                <p class="text-xs text-slate-300 leading-relaxed font-normal">
                    Fasilitas standar kejuaraan dunia, pelatih berlisensi, dan kultur disiplin tanpa kompromi.
                </p>

                <div class="pt-2 flex flex-col gap-2.5">
                    <a href="{{ route('mobile.landing.packages') }}" class="w-full py-3 rounded-full bg-[#ED1B45] hover:bg-[#d1123d] text-white text-xs font-bold text-center shadow-md shadow-[#ED1B45]/30 active:scale-98 transition">
                        Pilih Paket Membership
                    </a>
                    <a href="{{ route('mobile.landing.trainers') }}" class="w-full py-2.5 rounded-full bg-white/10 text-white text-xs font-bold text-center border border-white/20 active:scale-98 transition">
                        Lihat Pelatih PT
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. METRICS BANNER 2x2 GRID -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] text-center">
                <span class="font-poppins font-black text-2xl text-[#16151A]">15k+</span>
                <p class="text-[11px] font-bold text-[#565A66] mt-0.5">Member Aktif</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] text-center">
                <span class="font-poppins font-black text-2xl text-[#ED1B45]">98.7%</span>
                <p class="text-[11px] font-bold text-[#565A66] mt-0.5">Kepuasan Hasil</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] text-center">
                <span class="font-poppins font-black text-2xl text-[#16151A]">24/7</span>
                <p class="text-[11px] font-bold text-[#565A66] mt-0.5">Akses Gym</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] text-center">
                <span class="font-poppins font-black text-2xl text-[#16151A]">50+</span>
                <p class="text-[11px] font-bold text-[#565A66] mt-0.5">Pelatih Berlisensi</p>
            </div>
        </div>

        <!-- 3. KEUNGGULAN GYM -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-[#ED1B45] uppercase tracking-wider">KEUNGGULAN</span>
                    <h2 class="font-poppins font-bold text-lg text-[#16151A]">Kenapa FitCore?</h2>
                </div>
            </div>

            <div class="space-y-3">
                <!-- Item 1 -->
                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <div>
                        <h3 class="font-poppins font-bold text-sm text-[#16151A]">Peralatan Hammer &amp; Eleiko</h3>
                        <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">Peralatan beban standar kejuaraan dunia dengan kalibrasi presisi.</p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-poppins font-bold text-sm text-[#16151A]">Trainer Lisensi NASM / CSCS</h3>
                        <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">Bimbingan intensif dari pelatih berstandar internasional.</p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-poppins font-bold text-sm text-[#16151A]">Sauna &amp; Ice Bath Plunge</h3>
                        <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">Fasilitas pemulihan tubuh lengkap untuk hasil maksimal.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. PAKET TERPOPULER PREVIEW -->
        <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-[#ED1B45] uppercase tracking-wider">PAKET PILIHAN</span>
                    <h2 class="font-poppins font-bold text-lg text-[#16151A]">Paket Membership</h2>
                </div>
                <a href="{{ route('mobile.landing.packages') }}" class="text-xs font-bold text-[#ED1B45]">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 w-full">
                @foreach ($packages as $pkg)
                    <div class="w-full bg-white rounded-2xl p-3 sm:p-4 border {{ $loop->iteration === 2 ? 'border-[#ED1B45] ring-2 ring-[#ED1B45]/20 shadow-md' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]' }} flex flex-col justify-between h-full space-y-3 sm:space-y-4">
                        <div class="flex items-start justify-between gap-1">
                            <div>
                                <span class="text-[10px] sm:text-xs font-bold text-[#565A66] uppercase tracking-wider">{{ $pkg->type ?? 'Reguler' }}</span>
                                <h3 class="font-poppins font-bold text-sm sm:text-base text-[#16151A] mt-0.5 leading-tight">{{ $pkg->name }}</h3>
                            </div>
                            @if ($loop->iteration === 2)
                                <span class="px-2 py-0.5 rounded-full bg-[#ED1B45] text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-wider shrink-0">Populer</span>
                            @endif
                        </div>

                        <div class="bg-[#F6F6F7] p-2 sm:p-3 rounded-xl border border-[#E5E7EB]">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs font-bold text-[#ED1B45]">Rp</span>
                                <span class="font-poppins font-black text-base sm:text-lg text-[#16151A] tracking-tight">{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                <span class="text-[10px] sm:text-xs text-[#565A66] font-medium">/ bln</span>
                            </div>
                            <span class="text-xs text-[#565A66] block mt-0.5">Masa aktif {{ $pkg->duration_days ?? 30 }} Hari</span>
                        </div>

                        <ul class="space-y-1.5 text-xs leading-tight text-[#16151A] pt-2 border-t border-[#E5E7EB]">
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 sm:w-4 h-4 text-[#ED1B45] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-xs leading-tight">Akses Gym (05:30 - 22:00)</span>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 sm:w-4 h-4 text-[#ED1B45] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-xs leading-tight">Loker &amp; Shower</span>
                            </li>
                            @if ($pkg->pt_session_count > 0 || $pkg->pt_sessions > 0)
                                <li class="flex items-center gap-1.5 font-bold text-[#ED1B45]">
                                    <svg class="w-3 h-3 sm:w-4 h-4 text-[#ED1B45] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs leading-tight">{{ $pkg->pt_session_count ?? $pkg->pt_sessions }} Sesi PT Gratis</span>
                                </li>
                            @endif
                        </ul>

                        <div>
                            @auth
                                <a href="{{ route('mobile.member.packages.checkout', $pkg->id) }}" class="block w-full py-2 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25 hover:bg-[#d1123d]' : 'bg-white text-[#ED1B45] border-2 border-[#ED1B45] hover:bg-[#ED1B45] hover:text-white shadow-xs' }} active:scale-98 transition">
                                    Pilih Paket Ini
                                </a>
                            @else
                                <a href="{{ route('mobile.login', ['redirect' => route('mobile.member.packages.checkout', $pkg->id)]) }}" class="block w-full py-2 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25 hover:bg-[#d1123d]' : 'bg-white text-[#ED1B45] border-2 border-[#ED1B45] hover:bg-[#ED1B45] hover:text-white shadow-xs' }} active:scale-98 transition">
                                    Pilih Paket Ini
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 5. PELATIH PREVIEW -->
        <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-[#ED1B45] uppercase tracking-wider">TIM COACH</span>
                    <h2 class="font-poppins font-bold text-lg text-[#16151A]">Personal Trainer</h2>
                </div>
                <a href="{{ route('mobile.landing.trainers') }}" class="text-xs font-bold text-[#ED1B45]">Lihat Semua &rarr;</a>
            </div>

            <div class="space-y-3">
                @foreach ($trainers as $trainer)
                    <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#ED1B45] to-[#BA0030] text-white flex items-center justify-center font-poppins font-black text-sm shrink-0 border border-white/20 shadow-xs shadow-[#ED1B45]/25">
                                {{ strtoupper(substr($trainer->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-poppins font-bold text-sm text-[#16151A]">{{ $trainer->name }}</h3>
                                <p class="text-[11px] font-semibold text-[#ED1B45]">{{ $trainer->trainerProfile->specialization ?? 'Strength Coach' }}</p>
                                <div class="flex items-center gap-1 text-[11px] text-amber-500 font-bold mt-0.5">
                                    <span>★ {{ $trainer->trainerProfile->rating ?? '4.98' }}</span>
                                    <span class="text-[#565A66] font-normal">• {{ $trainer->trainerProfile->experience ?? 4 }} Thn</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            @auth
                                <a href="{{ route('mobile.member.pt-sessions.create', ['trainer_id' => $trainer->id]) }}" class="px-3.5 py-2 rounded-full bg-[#ED1B45] hover:bg-[#d1123d] text-white text-[11px] font-bold shadow-xs shadow-[#ED1B45]/25 active:scale-95 transition whitespace-nowrap">
                                    Booking
                                </a>
                            @else
                                <a href="{{ route('mobile.login', ['redirect' => route('mobile.member.pt-sessions.create', ['trainer_id' => $trainer->id])]) }}" class="px-3.5 py-2 rounded-full bg-[#ED1B45] hover:bg-[#d1123d] text-white text-[11px] font-bold shadow-xs shadow-[#ED1B45]/25 active:scale-95 transition whitespace-nowrap">
                                    Booking
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 6. FAQ ACCORDION -->
        <div class="space-y-3 pt-2" x-data="{ openFaq: null }">
            <div>
                <span class="text-[10px] font-bold text-[#ED1B45] uppercase tracking-wider">INFORMASI UMUM</span>
                <h2 class="font-poppins font-bold text-lg text-[#16151A]">Tanya Jawab (FAQ)</h2>
            </div>

            <div class="space-y-2">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl border border-[#E5E7EB] overflow-hidden">
                    <button type="button" @click="openFaq = openFaq === 1 ? null : 1" class="w-full p-4 text-left flex items-center justify-between text-xs font-bold text-[#16151A]">
                        <span>Apakah ada biaya joining fee?</span>
                        <svg class="w-3.5 h-3.5 text-[#ED1B45] transition" :class="openFaq === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="openFaq === 1" class="px-4 pb-4 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/50 pt-2">
                        Tidak ada biaya tersembunyi. Seluruh harga paket yang tertera sudah termasuk biaya pendaftaran dan akses fasilitas penuh.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl border border-[#E5E7EB] overflow-hidden">
                    <button type="button" @click="openFaq = openFaq === 2 ? null : 2" class="w-full p-4 text-left flex items-center justify-between text-xs font-bold text-[#16151A]">
                        <span>Bagaimana cara booking sesi PT?</span>
                        <svg class="w-3.5 h-3.5 text-[#ED1B45] transition" :class="openFaq === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="openFaq === 2" class="px-4 pb-4 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/50 pt-2">
                        Setelah memiliki akun member dan kuota sesi PT aktif, Anda dapat memilih trainer di menu Sesi PT dan menentukan jam latihan yang tersedia secara digital.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-2xl border border-[#E5E7EB] overflow-hidden">
                    <button type="button" @click="openFaq = openFaq === 3 ? null : 3" class="w-full p-4 text-left flex items-center justify-between text-xs font-bold text-[#16151A]">
                        <span>Apakah pemula boleh bergabung?</span>
                        <svg class="w-3.5 h-3.5 text-[#ED1B45] transition" :class="openFaq === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-cloak x-show="openFaq === 3" class="px-4 pb-4 text-xs text-[#565A66] leading-relaxed border-t border-[#E5E7EB]/50 pt-2">
                        Sangat boleh! Instruktur kami siap membantu pemula mempelajari teknik angkat beban yang benar dan aman sejak hari pertama.
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. BOTTOM CTA CARD -->
        <div class="bg-[#141414] rounded-2xl p-6 text-white text-center space-y-3">
            <h3 class="font-poppins font-black text-xl text-white">Mulai Latihan Hari Ini</h3>
            <p class="text-xs text-slate-300 leading-relaxed">Pilih paket dan nikmati fasilitas kelas dunia di FitCore Athletic.</p>
            <div class="pt-2">
                <a href="{{ route('mobile.login') }}" class="block w-full py-3 rounded-full bg-[#ED1B45] text-white text-xs font-bold shadow-md shadow-[#ED1B45]/30">
                    Masuk atau Daftar
                </a>
            </div>
        </div>

    </div>

</x-layouts.mobile-guest>

