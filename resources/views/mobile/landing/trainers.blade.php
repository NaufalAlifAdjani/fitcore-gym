{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Profil Pelatih Personal Trainer Publik
| FILE         : resources/views/mobile/landing/trainers.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m/trainers
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="Pelatih Personal Trainer - FitCore Mobile" active-tab="trainers">

    <div class="px-4 py-5 space-y-5">

        <!-- Header Ringkas -->
        <div class="space-y-2">
            <h1 class="font-poppins font-black text-2xl text-[#16151A]">Personal Trainer</h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Bimbingan intensif dari instruktur berlisensi internasional untuk hasil latihan terukur dan bebas cedera.
            </p>
            <div class="pt-1">
                <a href="{{ route('mobile.landing.pt-pricing') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-[#E5E7EB] text-xs font-bold text-[#FF5722] hover:bg-orange-50 shadow-2xs transition">
                    <span>Lihat Daftar Harga Sesi PT &rarr;</span>
                </a>
            </div>
        </div>

        <!-- Stack Daftar Pelatih -->
        <div class="space-y-4">
            @forelse ($trainers as $trainer)
                @php
                    $cFirstName = strtolower(explode(' ', trim(str_replace('Coach ', '', $trainer->name)))[0]);
                    $cPhoto = file_exists(public_path("images/trainers/coach-{$cFirstName}.jpg"))
                        ? asset("images/trainers/coach-{$cFirstName}.jpg")
                        : ($trainer->trainerProfile?->photo_path ? asset('storage/' . $trainer->trainerProfile->photo_path) : ($trainer->avatar_url ?? null));
                @endphp
                <div class="rounded-2xl overflow-hidden border border-[#E5E7EB] bg-white shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex flex-col group">
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
                    <div class="p-4 bg-[#F6F6F7] flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1.5">
                            <!-- Nama -->
                            <h2 class="font-poppins font-extrabold text-base text-[#16151A] leading-snug">
                                {{ $trainer->name }}
                            </h2>

                            <!-- Specialization -->
                            <div class="text-xs font-bold text-[#FF5722]">
                                {{ $trainer->trainerProfile->specialization ?? 'Strength Coach' }}
                            </div>

                            <!-- Experience -->
                            <div class="text-xs font-semibold text-[#565A66] flex items-center gap-1.5 pt-0.5">
                                <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $trainer->trainerProfile->experience ?? 4 }}+ Tahun Jam Terbang</span>
                            </div>

                            <!-- Bio -->
                            <p class="text-xs text-[#565A66] leading-relaxed line-clamp-3 pt-0.5">
                                {{ $trainer->trainerProfile->bio ?? 'Mendampingi program hipertrofi, pembentukan massa otot, dan peningkatan kekuatan fungsional.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-6 text-center border border-[#E5E7EB]">
                    <p class="text-xs text-[#565A66]">Belum ada pelatih terdaftar saat ini.</p>
                </div>
            @endforelse
        </div>

    </div>

</x-layouts.mobile-guest>

