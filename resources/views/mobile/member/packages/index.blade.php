{{--
ROLE: MEMBER
MODUL: Katalog Paket Membership Mobile
VIEW: mobile.member.packages.index
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #ED1B45, Text #16151A / #565A66)
--}}

<x-layouts.mobile-app title="Katalog Paket Membership - FitCore Athletic" active-tab="packages">
    <div class="space-y-4">

        <!-- 1. Header Banner (Clean Light Theme) -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] relative overflow-hidden">
            <div class="relative z-10">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider text-[#ED1B45] bg-[#ED1B45]/10 border border-[#ED1B45]/20 mb-2">
                    FITCORE MEMBERSHIP
                </span>
                <h1 class="font-poppins text-lg font-black text-[#16151A] tracking-tight">
                    Pilih Paket Keanggotaan
                </h1>
                <p class="text-xs text-[#565A66] mt-1 leading-relaxed">
                    Akses fasilitas gym kelas dunia, loker privat, dan sesi latihan bersama pelatih tersertifikasi.
                </p>
            </div>
            <!-- Subtle decorative background accent -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-[#ED1B45]/5 blur-xl pointer-events-none"></div>
        </div>

        <!-- 2. Alert Notifikasi -->
        @if (session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-800 flex items-start gap-2.5 shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 flex items-start gap-2.5 shadow-xs">
                <svg class="w-4 h-4 text-[#ED1B45] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- 3. Daftar Kartu Paket Membership (Grid 2 Kolom) -->
        <div class="grid grid-cols-2 gap-3 sm:gap-4 w-full">
            @forelse ($packages as $package)
                @php
                    $isFeatured = str_contains(strtoupper($package->badge ?? ''), 'BEST')
                        || str_contains(strtoupper($package->badge ?? ''), 'POPULAR')
                        || ($loop->count === 3 && $loop->iteration === 2)
                        || ($loop->count > 3 && str_contains(strtoupper($package->badge ?? ''), 'BEST'));

                    $facilities = is_array($package->facilities)
                        ? $package->facilities
                        : (is_string($package->facilities) ? json_decode($package->facilities, true) ?? [] : []);

                    if (empty($facilities)) {
                        $facilities = [
                            'Akses Penuh Area Gym & Cardio',
                            'Loker Harian & Kamar Bilas',
                            'Akses Check-in QR FitCore',
                        ];
                    }

                    if ($package->pt_session_count > 0) {
                        $facilities = array_filter($facilities, function ($item) {
                            return !preg_match('/(sesi\s*pt|personal\s*trainer)/i', $item);
                        });
                    }

                    $effectivePrice = ($package->promo_price && $package->promo_price < $package->price)
                        ? $package->promo_price
                        : $package->price;
                    $checkoutUrl = route('mobile.member.packages.checkout', $package->id);
                @endphp

                <div class="w-full bg-white rounded-2xl p-3 sm:p-4 border {{ $isFeatured ? 'border-2 border-[#ED1B45] shadow-lg shadow-[#ED1B45]/10' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]' }} relative flex flex-col justify-between h-full transition-all">
                    
                    @if ($package->badge)
                        <div class="absolute -top-3 right-4 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider z-10 shadow-xs {{ $isFeatured ? 'bg-[#ED1B45] text-white shadow-[#ED1B45]/30' : 'bg-slate-100 text-[#16151A] border border-[#E5E7EB]' }}">
                            {{ $package->badge }}
                        </div>
                    @endif

                    <div>
                        <!-- Header Paket -->
                        <div class="mb-3">
                            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-[#565A66]">
                                {{ $package->tier ?? 'Membership' }}
                            </span>
                            <h2 class="text-sm sm:text-base font-bold font-poppins text-[#16151A] mt-0.5 leading-tight">
                                {{ $package->name }}
                            </h2>
                            <p class="text-xs text-[#565A66] mt-0.5">
                                Masa Berlaku: <strong class="text-[#16151A] font-semibold">{{ $package->duration_days }} Hari</strong>
                            </p>
                        </div>

                        <!-- Harga Paket -->
                        <div class="mb-3 bg-[#F6F6F7] p-2 sm:p-3 rounded-xl border border-[#E5E7EB]">
                            @if ($package->promo_price && $package->promo_price < $package->price)
                                <p class="text-[10px] sm:text-[11px] font-semibold text-[#565A66] line-through">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </p>
                            @endif
                            <div class="flex items-baseline gap-1 mt-0.5">
                                <span class="text-xs font-bold text-[#ED1B45]">Rp</span>
                                <span class="font-poppins font-black text-base sm:text-lg text-[#16151A] tracking-tight">
                                    {{ number_format($effectivePrice, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] sm:text-xs text-[#565A66] font-medium">
                                    / {{ strtolower($package->duration_unit ?? 'bln') }}
                                </span>
                            </div>
                        </div>

                        <!-- Daftar Fasilitas -->
                        <div class="mb-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-[#16151A] mb-1.5 font-poppins">
                                Fasilitas yang Didapat:
                            </span>
                            <ul class="space-y-1.5">
                                @foreach ($facilities as $facility)
                                    <li class="flex items-start gap-1.5 text-xs leading-tight text-[#565A66]">
                                        <svg class="w-3 h-3 sm:w-4 h-4 shrink-0 text-[#10B981] mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="leading-tight">{{ $facility }}</span>
                                    </li>
                                @endforeach

                                @if ($package->pt_session_count > 0)
                                    <li class="flex items-start gap-1.5 text-xs leading-tight text-[#16151A] font-medium bg-rose-50/70 p-1.5 rounded-lg border border-rose-100">
                                        <svg class="w-3 h-3 sm:w-4 h-4 shrink-0 text-[#ED1B45] mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="leading-tight">
                                            <strong class="text-[#ED1B45] font-bold">{{ $package->pt_session_count }} Sesi</strong> PT Gratis
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Tombol Action CTA (Pill Button per DESIGN.md & Prompt #2) -->
                    <div class="pt-2 border-t border-[#E5E7EB]">
                        @if ($isFeatured)
                            <a href="{{ $checkoutUrl }}"
                               class="w-full block text-center py-2 rounded-full font-bold text-xs uppercase tracking-wider bg-[#ED1B45] hover:bg-[#D1123D] text-white shadow-md shadow-[#ED1B45]/25 active:scale-[0.98] transition">
                                Gabung Sekarang
                            </a>
                        @else
                            <a href="{{ $checkoutUrl }}"
                               class="w-full block text-center py-2 rounded-full font-bold text-xs uppercase tracking-wider border-2 border-[#ED1B45] text-[#ED1B45] hover:bg-[#ED1B45] hover:text-white active:scale-[0.98] transition">
                                Pilih Paket Ini
                            </a>
                        @endif
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 border border-[#E5E7EB] text-center shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-[#565A66] flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="font-poppins font-bold text-sm text-[#16151A]">Belum Ada Paket Aktif</h3>
                    <p class="text-xs text-[#565A66] mt-1">Silakan hubungi administrator gym untuk info paket keanggotaan terbaru.</p>
                </div>
            @endforelse
        </div>

        <!-- 4. Benefit Inklusif (Fasilitas Standar Internasional) -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins mb-3">
                Semua Membership Sudah Termasuk:
            </h3>

            <div class="grid grid-cols-2 gap-2.5">
                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-[#16151A]">Akses Gym 24/7</span>
                        <span class="text-[9px] text-[#565A66]">Cardio &amp; Beban</span>
                    </div>
                </div>

                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-[#16151A]">Loker &amp; Shower</span>
                        <span class="text-[9px] text-[#565A66]">Kamar Mandi Air Hangat</span>
                    </div>
                </div>

                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-[#16151A]">QR Pass Digital</span>
                        <span class="text-[9px] text-[#565A66]">Check-in Tanpa Antre</span>
                    </div>
                </div>

                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] flex items-start gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-[#16151A]">Free Water Station</span>
                        <span class="text-[9px] text-[#565A66]">Hidrasi Tanpa Batas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Bantuan & CS Front Desk -->
        <div class="p-4 bg-white border border-[#E5E7EB] rounded-2xl flex items-center justify-between shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#16151A] font-poppins">Butuh Bantuan?</h4>
                    <p class="text-[10px] text-[#565A66]">Hubungi Customer Service FitCore</p>
                </div>
            </div>
            <a href="https://wa.me/6281234567890?text=Halo%20FitCore%2C%20saya%20ingin%20tanya%20mengenai%20paket%20membership"
               target="_blank"
               class="px-3 py-1.5 rounded-full text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-xs">
                WhatsApp
            </a>
        </div>

    </div>
</x-layouts.mobile-app>

