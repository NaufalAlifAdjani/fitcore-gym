{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Katalog Paket Membership Publik
| FILE         : resources/views/mobile/landing/packages.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m/packages-info
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="Katalog Paket - FitCore Mobile" active-tab="packages">

    <div class="px-4 py-5 space-y-5">

        <!-- Header Ringkas -->
        <div class="space-y-1">
            <h1 class="font-poppins font-black text-2xl text-[#16151A]">Paket Keanggotaan</h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Pilih paket membership untuk akses tak terbatas ke seluruh peralatan dan fasilitas recovery.
            </p>
        </div>

        <!-- Daftar Paket Stacked Cards (Grid 1 Kolom Sesuai Standar Mobile) -->
        <div class="grid grid-cols-1 gap-4 w-full">
            @forelse ($packages as $pkg)
                @php
                    $isPopular = ($loop->iteration === 2) || str_contains(strtoupper($pkg->badge ?? ''), 'POPULER') || str_contains(strtoupper($pkg->badge ?? ''), 'POPULAR');
                @endphp
                <div class="w-full bg-white rounded-2xl p-4 sm:p-5 border {{ $isPopular ? 'border-2 border-[#FF5722] shadow-lg shadow-orange-500/10' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]' }} relative flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-[#565A66] uppercase tracking-wider">{{ $pkg->type ?? 'Reguler' }}</span>
                                <h2 class="font-poppins font-black text-base sm:text-lg text-[#16151A] mt-0.5 leading-snug">{{ $pkg->name }}</h2>
                            </div>
                            @if ($isPopular)
                                <span class="px-2.5 py-0.5 rounded-full bg-[#FF5722] text-white text-[10px] font-bold uppercase tracking-wider shrink-0 shadow-xs">
                                    Populer
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-[#565A66] leading-relaxed">
                            {{ $pkg->description ?? 'Akses fasilitas latihan lengkap berstandar internasional.' }}
                        </p>

                        <div class="bg-[#F6F6F7] p-3 rounded-xl border border-[#E5E7EB] flex items-center justify-between">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs font-bold text-[#FF5722]">Rp</span>
                                <span class="font-poppins font-black text-xl text-[#16151A] tracking-tight">{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-[#565A66] font-medium">/ bln</span>
                            </div>
                            <span class="text-[11px] font-semibold text-[#565A66] bg-white px-2.5 py-1 rounded-md border border-[#E5E7EB]">
                                Masa aktif {{ $pkg->duration_days ?? $pkg->duration_in_days ?? 30 }} Hari
                            </span>
                        </div>

                        <div class="space-y-2 text-xs text-[#16151A] pt-2 border-t border-[#E5E7EB]">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] mb-1">Fasilitas Termasuk:</div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Akses Gym Tak Terbatas (05:30 - 22:00)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Loker Harian &amp; Shower Air Panas</span>
                            </div>
                            @if ($pkg->pt_session_count > 0 || $pkg->pt_sessions > 0)
                                <div class="flex items-center gap-2 font-bold text-[#FF5722]">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>{{ $pkg->pt_session_count ?? $pkg->pt_sessions }} Sesi Personal Trainer</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Area Sauna &amp; Recovery Infrasound</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-1">
                        @auth
                            <a href="{{ route('mobile.member.packages.checkout', $pkg->id) }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-orange-500/25' : 'bg-white text-[#FF5722] border-2 border-[#FF5722] hover:bg-[#FF5722] hover:text-white shadow-xs' }} active:scale-98 transition">
                                Pilih Paket Ini
                            </a>
                        @else
                            <a href="{{ route('mobile.login', ['redirect' => route('mobile.member.packages.checkout', $pkg->id)]) }}" class="block w-full py-2.5 rounded-full text-center text-xs font-bold {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-orange-500/25' : 'bg-white text-[#FF5722] border-2 border-[#FF5722] hover:bg-[#FF5722] hover:text-white shadow-xs' }} active:scale-98 transition">
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