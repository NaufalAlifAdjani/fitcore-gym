{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Profil Pelatih Personal Trainer Publik
| FILE         : resources/views/mobile/landing/trainers.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m/trainers
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #ED1B45)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="Pelatih Personal Trainer - FitCore Mobile" active-tab="trainers">

    <div class="px-4 py-5 space-y-5">

        <!-- Header Ringkas -->
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-[#ED1B45] uppercase tracking-wider">PELATIH BERLISENSI</span>
            <h1 class="font-poppins font-black text-2xl text-[#16151A]">Personal Trainer</h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Bimbingan intensif dari instruktur berlisensi internasional untuk hasil latihan terukur dan bebas cedera.
            </p>
        </div>

        <!-- Stack Daftar Pelatih -->
        <div class="space-y-4">
            @forelse ($trainers as $trainer)
                <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#ED1B45] to-[#BA0030] text-white flex items-center justify-center font-poppins font-black text-base shrink-0 border-2 border-white shadow-md shadow-[#ED1B45]/25">
                            {{ strtoupper(substr($trainer->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="font-poppins font-bold text-base text-[#16151A] truncate">{{ $trainer->name }}</h2>
                            <span class="inline-block px-2.5 py-0.5 rounded-md bg-[#ED1B45]/10 text-[#ED1B45] text-[11px] font-bold mt-1">
                                {{ $trainer->trainerProfile->specialization ?? 'Strength Coach' }}
                            </span>
                            <p class="text-[11px] text-[#565A66] mt-0.5">
                                {{ $trainer->trainerProfile->tier ?? 'Senior PT' }} • {{ $trainer->trainerProfile->studio ?? 'SCBD' }}
                            </p>
                        </div>
                    </div>

                    <!-- Rating & Experience Tag -->
                    <div class="flex items-center justify-between text-xs py-2.5 px-3 bg-[#F6F6F7] rounded-xl border border-[#E5E7EB]">
                        <div class="flex items-center gap-1 font-bold text-amber-500">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-[#16151A]">{{ $trainer->trainerProfile->rating ?? '4.98' }}</span>
                            <span class="text-[#565A66] font-normal">({{ $trainer->trainerProfile->review_count ?? '150+' }})</span>
                        </div>
                        <span class="font-bold text-[#16151A]">{{ $trainer->trainerProfile->experience ?? 4 }}+ Tahun Jam Terbang</span>
                    </div>

                    <p class="text-xs text-[#565A66] leading-relaxed">
                        {{ $trainer->trainerProfile->bio ?? 'Mendampingi program hipertrofi, pembentukan massa otot, dan peningkatan kekuatan fungsional.' }}
                    </p>

                    <div>
                        @auth
                            <a href="{{ route('mobile.member.pt-sessions.create', ['trainer_id' => $trainer->id]) }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold bg-[#ED1B45] hover:bg-[#d1123d] text-white shadow-sm shadow-[#ED1B45]/25 active:scale-98 transition">
                                Booking Sesi Bersama {{ explode(' ', $trainer->name)[0] }}
                            </a>
                        @else
                            <a href="{{ route('mobile.login', ['redirect' => route('mobile.member.pt-sessions.create', ['trainer_id' => $trainer->id])]) }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold bg-[#ED1B45] hover:bg-[#d1123d] text-white shadow-sm shadow-[#ED1B45]/25 active:scale-98 transition">
                                Booking Sesi Bersama {{ explode(' ', $trainer->name)[0] }}
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-6 text-center border border-[#E5E7EB]">
                    <p class="text-xs text-[#565A66]">Belum ada pelatih terdaftar saat ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Banner Info Alur Booking -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] space-y-2">
            <span class="text-xs font-bold text-[#16151A] block">Cara Booking Sesi PT di Mobile</span>
            <p class="text-[11px] text-[#565A66] leading-relaxed">
                Masuk ke akun Anda &gt; buka tab Sesi PT &gt; pilih pelatih favorit Anda &gt; tentukan jam latihan yang masih tersedia.
            </p>
        </div>

    </div>

</x-layouts.mobile-guest>

