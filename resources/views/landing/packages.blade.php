{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Web Desktop UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Katalog Paket Membership Publik
| FILE         : resources/views/landing/packages.blade.php
| LAYOUT       : layouts/public.blade.php
| AKSES ROUTE  : /packages
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.public title="Katalog Paket Membership - FitCore Athletic" active-tab="packages">

    <div class="py-16 bg-[#F6F6F7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- 1. HEADER SECTION -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3.5 py-1 rounded-full bg-orange-50 text-[#FF5722] text-xs font-bold uppercase tracking-widest mb-3">
                    KATALOG MEMBERSHIP RESMI
                </span>
                <h1 class="font-poppins font-black text-3xl sm:text-5xl text-[#16151A] tracking-tight">
                    Investasi Terbaik Untuk Performa &amp; Kekuatan Anda
                </h1>
                <p class="text-[#565A66] text-sm sm:text-base mt-4 leading-relaxed font-normal">
                    Pilih paket yang selaras dengan target kebugaran Anda. Seluruh paket mencakup akses penuh ke seluruh zona latihan, fasilitas loker harian, dan shower air panas.
                </p>
            </div>

            <!-- 2. GRID PAKET MEMBERSHIP -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch mb-16">
                @forelse ($packages as $pkg)
                    <div class="relative bg-white rounded-2xl p-8 border {{ $loop->iteration === 2 ? 'border-[#FF5722] ring-2 ring-[#FF5722]/20 shadow-xl' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]' }} flex flex-col justify-between transition hover:-translate-y-1 duration-200">
                        @if ($loop->iteration === 2)
                            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-[#FF5722] text-white text-[11px] font-black uppercase tracking-wider shadow-sm">
                                Pilihan Terpopuler
                            </span>
                        @endif

                        <div>
                            <!-- Header Paket -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">{{ $pkg->type ?? 'Membership Reguler' }}</span>
                                @if ($pkg->badge)
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-[#16151A] text-[10px] font-bold uppercase">{{ $pkg->badge }}</span>
                                @endif
                            </div>

                            <h3 class="font-poppins font-extrabold text-2xl text-[#16151A] mt-2">{{ $pkg->name }}</h3>
                            <p class="text-xs text-[#565A66] mt-1 leading-relaxed">{{ $pkg->description ?? 'Akses fasilitas gym terlengkap dengan standar kejuaraan dunia.' }}</p>

                            <!-- Harga -->
                            <div class="my-6 pb-6 border-b border-[#E5E7EB]">
                                <div class="flex items-baseline gap-1">
                                    <span class="font-poppins font-black text-3xl sm:text-4xl text-[#16151A]">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-xs text-[#565A66] font-medium block mt-1">Masa aktif {{ $pkg->duration_days ?? $pkg->duration_in_days ?? 30 }} Hari kalender</span>
                            </div>

                            <!-- Fitur & Fasilitas -->
                            <div class="space-y-3 text-xs text-[#16151A] mb-8">
                                <div class="font-bold text-[11px] uppercase tracking-wider text-[#565A66]">Fasilitas Termasuk:</div>
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Akses Gym Tak Terbatas (05:30 - 22:00 WIB)</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Loker Elektronik Harian &amp; Shower Air Panas</span>
                                </div>
                                @if ($pkg->pt_session_count > 0 || $pkg->pt_sessions > 0)
                                    <div class="flex items-center gap-2.5 font-bold text-[#FF5722]">
                                        <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Termasuk {{ $pkg->pt_session_count ?? $pkg->pt_sessions }} Sesi Personal Trainer</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Akses Area Recovery &amp; Sauna Inframerah</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Digital QR Code Check-in via Mobile App</span>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div>
                            @auth
                                <a href="{{ route('member.membership.checkout', $pkg->id) }}" class="block w-full py-3.5 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#FF5722] text-white hover:bg-[#E64A19] shadow-md shadow-orange-500/30' : 'bg-white text-[#FF5722] border-2 border-[#FF5722] hover:bg-[#FF5722] hover:text-white shadow-xs' }} transition">
                                    Pilih Paket Ini
                                </a>
                            @else
                                <a href="{{ route('login', ['redirect' => route('member.membership.checkout', $pkg->id)]) }}" class="block w-full py-3.5 rounded-full text-center text-xs font-bold {{ $loop->iteration === 2 ? 'bg-[#FF5722] text-white hover:bg-[#E64A19] shadow-md shadow-orange-500/30' : 'bg-white text-[#FF5722] border-2 border-[#FF5722] hover:bg-[#FF5722] hover:text-white shadow-xs' }} transition">
                                    Pilih Paket Ini
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-[#E5E7EB] p-8">
                        <p class="text-sm font-semibold text-[#565A66]">Saat ini belum ada paket membership yang aktif.</p>
                    </div>
                @endforelse
            </div>

            <!-- 3. INFORMASI TAMBAHAN / JAMINAN -->
            <div class="bg-white rounded-2xl p-8 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="space-y-2">
                    <span class="font-poppins font-bold text-sm text-[#16151A]">Tanpa Biaya Tersembunyi</span>
                    <p class="text-xs text-[#565A66] leading-relaxed">Harga yang tertera adalah biaya total bersih tanpa biaya pendaftaran tambahan.</p>
                </div>
                <div class="space-y-2">
                    <span class="font-poppins font-bold text-sm text-[#16151A]">Aktivasi Cepat &amp; Aman</span>
                    <p class="text-xs text-[#565A66] leading-relaxed">Upload bukti pembayaran langsung diverifikasi oleh staf front-desk dalam hitungan menit.</p>
                </div>
                <div class="space-y-2">
                    <span class="font-poppins font-bold text-sm text-[#16151A]">Dukungan Penuh Instruktur</span>
                    <p class="text-xs text-[#565A66] leading-relaxed">Konsultasi dasar penyesuaian alat dan bimbingan keselamatan gym tersedia setiap saat.</p>
                </div>
            </div>

        </div>
    </div>

</x-layouts.public>

