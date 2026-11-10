{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Web Desktop UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Profil Pelatih Personal Trainer Publik
| FILE         : resources/views/landing/trainers.blade.php
| LAYOUT       : layouts/public.blade.php
| AKSES ROUTE  : /trainers
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.public title="Pelatih Personal Trainer - FitCore Athletic" active-tab="trainers">

    <div class="py-16 bg-[#F6F6F7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- 1. HEADER SECTION -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3.5 py-1 rounded-full bg-orange-50 text-[#FF5722] text-xs font-bold uppercase tracking-widest mb-3">
                    TIM INSTRUKTUR ELIT
                </span>
                <h1 class="font-poppins font-black text-3xl sm:text-5xl text-[#16151A] tracking-tight">
                    Bimbingan Eksklusif dari Pelatih Berlisensi Dunia
                </h1>
                <p class="text-[#565A66] text-sm sm:text-base mt-4 leading-relaxed font-normal">
                    Setiap instruktur FitCore mengantongi akreditasi resmi dan dedikasi mendalam untuk memastikan teknik angkat beban Anda tepat, terukur, dan bebas cedera.
                </p>
            </div>

            <!-- 2. GRID PELATIH -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @forelse ($trainers as $trainer)
                    <div class="bg-white rounded-2xl p-7 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex flex-col justify-between transition hover:-translate-y-1 duration-200">
                        <div>
                            <!-- Top Profile Info -->
                            <div class="flex items-start gap-4 mb-5">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#FF5722] to-[#FFB703] text-white flex items-center justify-center font-poppins font-black text-xl border-2 border-white shadow-md shadow-orange-500/25 shrink-0">
                                    {{ strtoupper(substr($trainer->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-poppins font-extrabold text-xl text-[#16151A] leading-tight">{{ $trainer->name }}</h3>
                                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-orange-50 text-[#FF5722] text-xs font-bold mt-1.5">
                                        {{ $trainer->trainerProfile->specialization ?? 'Strength & Performance' }}
                                    </span>
                                    <p class="text-[11px] text-[#565A66] font-medium mt-1">
                                        {{ $trainer->trainerProfile->tier ?? 'Senior PT Tier III' }} • {{ $trainer->trainerProfile->studio ?? 'SCBD Studio' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Rating & Pengalaman Bar -->
                            <div class="flex items-center justify-between text-xs py-3 px-3.5 bg-[#F6F6F7] rounded-xl border border-[#E5E7EB] mb-5">
                                <div class="flex items-center gap-1 font-bold text-amber-500">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-[#16151A]">{{ $trainer->trainerProfile->rating ?? '4.98' }}</span>
                                    <span class="text-[#565A66] font-normal">({{ $trainer->trainerProfile->review_count ?? '184' }} sesi)</span>
                                </div>
                                <span class="font-bold text-[#16151A]">{{ $trainer->trainerProfile->experience ?? 5 }}+ Tahun Jam Terbang</span>
                            </div>

                            <!-- Bio & Filosofi -->
                            <p class="text-xs text-[#565A66] leading-relaxed mb-6 font-normal">
                                {{ $trainer->trainerProfile->bio ?? 'Berpengalaman melatih atlet dan pegiat kebugaran umum dengan fokus pada hipertrofi, biomekanika beban, dan pencegahan cedera.' }}
                            </p>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            @auth
                                <a href="{{ route('pt-sessions.create', ['trainer_id' => $trainer->id]) }}" class="block w-full py-3 rounded-full text-center text-xs font-bold bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-sm shadow-orange-500/25 transition">
                                    Booking Sesi Bersama {{ explode(' ', $trainer->name)[0] }}
                                </a>
                            @else
                                <a href="{{ route('login', ['redirect' => route('pt-sessions.create', ['trainer_id' => $trainer->id])]) }}" class="block w-full py-3 rounded-full text-center text-xs font-bold bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-sm shadow-orange-500/25 transition">
                                    Booking Sesi Bersama {{ explode(' ', $trainer->name)[0] }}
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-[#E5E7EB] p-8">
                        <p class="text-sm font-semibold text-[#565A66]">Belum ada pelatih yang terdaftar saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- 3. HOW PT BOOKING WORKS BANNER -->
            <div class="bg-white rounded-2xl p-8 sm:p-10 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]">
                <div class="max-w-2xl mx-auto text-center space-y-3 mb-8">
                    <span class="text-xs font-bold text-[#FF5722] uppercase tracking-wider">CARA KERJA</span>
                    <h3 class="font-poppins font-black text-2xl text-[#16151A]">3 Langkah Memulai Sesi Personal Trainer</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div class="space-y-2 p-4">
                        <span class="w-9 h-9 rounded-full bg-[#FF5722] text-white font-black text-sm shadow-sm shadow-orange-500/30 inline-flex items-center justify-center">1</span>
                        <h4 class="font-poppins font-bold text-sm text-[#16151A]">Miliki Paket PT Aktif</h4>
                        <p class="text-xs text-[#565A66]">Pilih paket membership yang menyertakan kuota PT atau beli paket sesi tambahan di portal.</p>
                    </div>
                    <div class="space-y-2 p-4">
                        <span class="w-9 h-9 rounded-full bg-[#FF5722] text-white font-black text-sm shadow-sm shadow-orange-500/30 inline-flex items-center justify-center">2</span>
                        <h4 class="font-poppins font-bold text-sm text-[#16151A]">Pilih Pelatih &amp; Waktu</h4>
                        <p class="text-xs text-[#565A66]">Tentukan instruktur pilihan dan pilih jam slot latihan yang cocok dengan rutinitas Anda.</p>
                    </div>
                    <div class="space-y-2 p-4">
                        <span class="w-9 h-9 rounded-full bg-[#FF5722] text-white font-black text-sm shadow-sm shadow-orange-500/30 inline-flex items-center justify-center">3</span>
                        <h4 class="font-poppins font-bold text-sm text-[#16151A]">Latihan Terarah</h4>
                        <p class="text-xs text-[#565A66]">Pelatih mengonfirmasi jadwal dan mendampingi sesi Anda secara intensif di gym.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-layouts.public>

