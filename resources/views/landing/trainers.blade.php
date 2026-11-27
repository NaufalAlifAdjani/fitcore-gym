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
                <h1 class="font-poppins font-black text-3xl sm:text-5xl text-[#16151A] tracking-tight">
                    Bimbingan Eksklusif dari Pelatih Berlisensi Dunia
                </h1>
                <p class="text-[#565A66] text-sm sm:text-base mt-4 leading-relaxed font-normal">
                    Setiap instruktur FitCore mengantongi akreditasi resmi dan dedikasi mendalam untuk memastikan teknik angkat beban Anda tepat, terukur, dan bebas cedera.
                </p>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('landing.pt-pricing') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-orange-50/60 border border-[#E5E7EB] hover:border-[#FF5722] text-xs font-bold text-[#16151A] hover:text-[#FF5722] shadow-xs transition group">
                        <svg class="w-4 h-4 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Lihat Daftar Harga Paket Sesi PT &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- 2. GRID PELATIH -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @forelse ($trainers as $trainer)
                    @php
                        $cFirstName = strtolower(explode(' ', trim(str_replace('Coach ', '', $trainer->name)))[0]);
                        $cPhoto = file_exists(public_path("images/trainers/coach-{$cFirstName}.jpg"))
                            ? asset("images/trainers/coach-{$cFirstName}.jpg")
                            : ($trainer->trainerProfile?->photo_path ? asset('storage/' . $trainer->trainerProfile->photo_path) : ($trainer->avatar_url ?? null));
                    @endphp
                    <div class="rounded-2xl overflow-hidden border border-[#E5E7EB] bg-white shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:shadow-xl transition-all duration-300 flex flex-col group">
                        <!-- 1. Foto PT -->
                        <div class="w-full aspect-[4/3] bg-[#2C2828] relative overflow-hidden flex items-center justify-center">
                            @if ($cPhoto)
                                <img src="{{ $cPhoto }}" 
                                     alt="{{ $trainer->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                     loading="lazy">
                            @else
                                <div class="w-full h-full bg-gradient-to-b from-[#383332] to-[#252222] flex flex-col items-center justify-center text-zinc-300 p-4">
                                    <svg class="w-10 h-10 text-zinc-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-xs font-semibold tracking-wider uppercase text-zinc-400">Foto PT</span>
                                </div>
                            @endif
                        </div>

                        <!-- 2. Data Pelatih -->
                        <div class="p-6 bg-[#F6F6F7] flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <!-- Nama -->
                                <h3 class="font-poppins font-extrabold text-xl text-[#16151A] leading-snug">
                                    {{ $trainer->name }}
                                </h3>

                                <!-- Specialization -->
                                <div class="text-xs font-bold text-[#FF5722] tracking-wide">
                                    {{ $trainer->trainerProfile->specialization ?? 'Strength & Performance' }}
                                </div>

                                <!-- Experience -->
                                <div class="text-xs font-semibold text-[#565A66] flex items-center gap-1.5 pt-0.5">
                                    <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $trainer->trainerProfile->experience ?? 5 }}+ Tahun Jam Terbang</span>
                                </div>

                                <!-- Bio -->
                                <p class="text-xs text-[#565A66] leading-relaxed line-clamp-3 pt-1">
                                    {{ $trainer->trainerProfile->bio ?? 'Berpengalaman melatih atlet dan pegiat kebugaran umum dengan fokus pada hipertrofi, biomekanika beban, dan pencegahan cedera.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-[#E5E7EB] p-8">
                        <p class="text-sm font-semibold text-[#565A66]">Belum ada pelatih yang terdaftar saat ini.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</x-layouts.public>

