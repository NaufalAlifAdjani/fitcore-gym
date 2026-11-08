{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Katalog Paket Membership Publik
| FILE         : resources/views/mobile/landing/packages.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m/packages-info
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #ED1B45)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="Katalog Paket - FitCore Mobile" active-tab="packages">

    <div class="px-4 py-5 space-y-5">

        <!-- Header Ringkas -->
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-[#ED1B45] uppercase tracking-wider">PILIHAN MEMBERSHIP</span>
            <h1 class="font-poppins font-black text-2xl text-[#16151A]">Paket Keanggotaan</h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Pilih paket membership untuk akses tak terbatas ke seluruh peralatan dan fasilitas recovery.
            </p>
        </div>

        <!-- Daftar Paket Stacked Cards (Grid 2 Kolom) -->
        <div class="grid grid-cols-2 gap-3 sm:gap-4 w-full">
            @forelse ($packages as $pkg)
                <div class="w-full bg-white rounded-2xl p-3 sm:p-4 border {{ $loop->iteration === 2 ? 'border-[#ED1B45] ring-2 ring-[#ED1B45]/20 shadow-md' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]' }} flex flex-col justify-between h-full space-y-3 sm:space-y-4">
                    <div class="flex items-start justify-between gap-1">
                        <div>
                            <span class="text-[10px] sm:text-xs font-bold text-[#565A66] uppercase tracking-wider">{{ $pkg->type ?? 'Reguler' }}</span>
                            <h2 class="font-poppins font-bold text-sm sm:text-base text-[#16151A] mt-0.5 leading-tight">{{ $pkg->name }}</h2>
                        </div>
                        @if ($loop->iteration === 2)
                            <span class="px-2 py-0.5 rounded-full bg-[#ED1B45] text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-wider shrink-0">
                                Populer
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-[#565A66] leading-tight">
                        {{ $pkg->description ?? 'Akses fasilitas latihan lengkap berstandar internasional.' }}
                    </p>

                    <div class="bg-[#F6F6F7] p-2 sm:p-3 rounded-xl border border-[#E5E7EB]">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs font-bold text-[#ED1B45]">Rp</span>
                            <span class="font-poppins font-black text-base sm:text-lg text-[#16151A] tracking-tight">{{ number_format($pkg->price, 0, ',', '.') }}</span>
                            <span class="text-[10px] sm:text-xs text-[#565A66] font-medium">/ bln</span>
                        </div>
                        <span class="text-xs text-[#565A66] block mt-0.5">Masa aktif {{ $pkg->duration_days ?? $pkg->duration_in_days ?? 30 }} Hari</span>
                    </div>

                    <div class="space-y-1.5 text-xs leading-tight text-[#16151A] pt-2 border-t border-[#E5E7EB]">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] mb-1">Fasilitas:</div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 sm:w-4 h-4 text-[#ED1B45] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-xs leading-tight">Akses Gym Tak Terbatas (05:30 - 22:00)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 sm:w-4 h-4 text-[#ED1B45] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-xs leading-tight">Loker Harian &amp; Shower Air Panas</span>
                        </div>
                        @if ($pkg->pt_session_count > 0 || $pkg->pt_sessions > 0)
                            <div class="flex items-center gap-1.5 font-bold text-[#ED1B45]">
                                <svg class="w-3 h-3 sm:w-4 h-4 text-[#ED1B45] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-xs leading-tight">{{ $pkg->pt_session_count ?? $pkg->pt_sessions }} Sesi Personal Trainer</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 sm:w-4 h-4 text-[#ED1B45] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-xs leading-tight">Area Sauna &amp; Recovery Infrasound</span>
                        </div>
                    </div>

                    <div>
                        @auth
                            <a href="{{ route('mobile.member.packages.checkout', $pkg->id) }}" class="block w-full py-2 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#ED1B45] hover:bg-[#d1123d] text-white shadow-md shadow-[#ED1B45]/30' : 'bg-white text-[#ED1B45] border-2 border-[#ED1B45] hover:bg-[#ED1B45] hover:text-white shadow-xs' }} active:scale-98 transition">
                                Pilih Paket Ini
                            </a>
                        @else
                            <a href="{{ route('mobile.login', ['redirect' => route('mobile.member.packages.checkout', $pkg->id)]) }}" class="block w-full py-2 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#ED1B45] hover:bg-[#d1123d] text-white shadow-md shadow-[#ED1B45]/30' : 'bg-white text-[#ED1B45] border-2 border-[#ED1B45] hover:bg-[#ED1B45] hover:text-white shadow-xs' }} active:scale-98 transition">
                                Pilih Paket Ini
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-6 text-center border border-[#E5E7EB]">
                    <p class="text-xs text-[#565A66]">Belum ada paket membership aktif.</p>
                </div>
            @endforelse
        </div>

        <!-- Banner Info Jaminan -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] text-center space-y-1">
            <span class="text-xs font-bold text-[#16151A]">Transparansi Penuh Tanpa Biaya Tersembunyi</span>
            <p class="text-[11px] text-[#565A66] leading-relaxed">Seluruh proses registrasi dan aktivasi kartu member diverifikasi cepat via sistem digital FitCore.</p>
        </div>

    </div>

</x-layouts.mobile-guest>

