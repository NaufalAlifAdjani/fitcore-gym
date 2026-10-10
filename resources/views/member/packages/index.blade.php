<x-member-layout title="Pilih Paket Keanggotaan - FitCore Athletic" active-nav="packages">
    <!-- Header Section (Nuansa Putih / Light Bersih) -->
    <section class="bg-white border-b border-slate-200/90 pt-16 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl text-center">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 text-[11px] font-extrabold uppercase tracking-widest text-[#BA0030] bg-rose-50 border border-rose-200 rounded-full mb-4">
                FITCORE ATHLETIC MEMBERSHIP
            </span>
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                Pilih Paket Keanggotaan
            </h1>
            <p class="mt-3.5 max-w-2xl mx-auto text-sm sm:text-base text-slate-600 leading-relaxed font-medium">
                Tingkatkan performa fisik Anda dengan fasilitas gym kelas dunia, bimbingan pelatih tersertifikasi, dan program terstruktur.
            </p>
        </div>

        <!-- Grid 3 Paket Sejajar -->
        <div class="mt-14 mx-auto max-w-7xl">
            @if (session('success'))
                <x-alert type="success" class="mb-8 max-w-3xl mx-auto">
                    {{ session('success') }}
                </x-alert>
            @endif

            @php
                $activePkgs = $packages->values();

                // Identifikasi paket untuk 3 slot kartu (Kiri, Tengah, Kanan)
                $starterPkg = $activePkgs->first(function ($p) {
                    $name = strtolower($p->name);
                    return str_contains($name, 'starter') || str_contains($name, 'silver') || str_contains($name, 'basic') || $p->duration_value == 1;
                }) ?? $activePkgs->first();

                $elitePkg = $activePkgs->first(function ($p) use ($starterPkg) {
                    $name = strtolower($p->name);
                    $badge = strtoupper($p->badge ?? '');
                    return ($badge === 'BEST SELLER' || str_contains($name, 'elite') || str_contains($name, 'annual') || $p->duration_value == 12) && $p->id !== $starterPkg?->id;
                }) ?? $activePkgs->skip(1)->first() ?? $starterPkg;

                $proPkg = $activePkgs->first(function ($p) use ($starterPkg, $elitePkg) {
                    $name = strtolower($p->name);
                    return $p->id !== $starterPkg?->id && $p->id !== $elitePkg?->id;
                }) ?? $activePkgs->skip(2)->first() ?? $starterPkg;

                // 3 Card Slot Configurations
                $slots = [
                    [
                        'key' => 'starter',
                        'pkg' => $starterPkg,
                        'name' => $starterPkg?->name ?? 'Starter Pass',
                        'price_str' => $starterPkg ? 'Rp ' . number_format($starterPkg->promo_price ?? $starterPkg->price, 0, ',', '.') : 'Rp 450.000',
                        'period' => '/ bln',
                        'commitment' => $starterPkg ? 'Komitmen ' . $starterPkg->duration_value . ' ' . $starterPkg->duration_unit : 'Komitmen 1 Bulan',
                        'button_text' => 'Pilih Paket Starter',
                        'button_variant' => 'outline',
                        'highlight' => false,
                        'badge_text' => null,
                        'facilities' => ($starterPkg && !empty($starterPkg->facilities)) ? $starterPkg->facilities : [
                            'Akses Penuh Area Gym & Cardio',
                            'Loker Harian & Kamar Bilas',
                            'Free Sesi Konsultasi Fitness',
                            'Akses Check-in Aplikasi FitCore',
                        ],
                    ],
                    [
                        'key' => 'elite',
                        'pkg' => $elitePkg,
                        'name' => $elitePkg?->name ?? 'Elite Annual Champion',
                        'price_str' => $elitePkg ? 'Rp ' . number_format($elitePkg->promo_price ?? $elitePkg->price, 0, ',', '.') : 'Rp 299.000',
                        'period' => '/ bln',
                        'commitment' => $elitePkg ? 'Komitmen ' . $elitePkg->duration_value . ' ' . $elitePkg->duration_unit : 'Komitmen 12 Bulan',
                        'button_text' => 'Gabung Elite Sekarang',
                        'button_variant' => 'primary',
                        'highlight' => true,
                        'badge_text' => 'PALING DIMINATI • HEMAT 35%',
                        'facilities' => ($elitePkg && !empty($elitePkg->facilities)) ? $elitePkg->facilities : [
                            'Akses 24 Jam Seluruh Cabang FitCore',
                            'Loker VIP & Kamar Mandi Air Panas',
                            '5 Sesi Personal Trainer Berlisensi',
                            'Akses Bebas Semua Kelas Studio',
                            'Diskon Kafe & Merchandise 20%',
                        ],
                    ],
                    [
                        'key' => 'pro',
                        'pkg' => $proPkg,
                        'name' => $proPkg?->name ?? 'All-Access Pro',
                        'price_str' => $proPkg ? 'Rp ' . number_format($proPkg->promo_price ?? $proPkg->price, 0, ',', '.') : 'Rp 380.000',
                        'period' => '/ bln',
                        'commitment' => $proPkg ? 'Komitmen ' . $proPkg->duration_value . ' ' . $proPkg->duration_unit : 'Komitmen 6 Bulan',
                        'button_text' => 'Pilih All-Access Pro',
                        'button_variant' => 'outline',
                        'highlight' => false,
                        'badge_text' => null,
                        'facilities' => ($proPkg && !empty($proPkg->facilities)) ? $proPkg->facilities : [
                            'Akses Fleksibel 06:00 - 23:00 WIB',
                            'Loker Pribadi & Kamar Mandi Bersih',
                            '2 Sesi Personal Trainer Gratis',
                            'Akses Kelas Studio Reguler',
                        ],
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:items-stretch pt-4">
                @foreach ($slots as $slot)
                    @php
                        $targetPackageId = $slot['pkg']?->id ?? ($packages->first()?->id ?? 1);
                        $checkoutUrl = route('member.membership.checkout', $targetPackageId);
                    @endphp

                    @if ($slot['highlight'])
                        <!-- Tengah - Elite Annual Champion (Highlighted Dark Navy / Dark Slate) -->
                        <div class="relative flex flex-col justify-between rounded-3xl bg-[#0B0F17] p-8 border-2 border-[#BA0030] shadow-[0_12px_45px_rgba(186,0,48,0.28)] text-white scale-[1.02] sm:scale-105 z-10 transition-all duration-300">
                            <!-- Badge Lengkung Merah Atas -->
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 rounded-full bg-[#BA0030] px-4 py-1.5 text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-white shadow-md shadow-[#BA0030]/40 whitespace-nowrap">
                                {{ $slot['badge_text'] }}
                            </div>

                            <div>
                                <div class="mt-2">
                                    <h3 class="font-heading text-xl sm:text-2xl font-extrabold text-white">
                                        {{ $slot['name'] }}
                                    </h3>
                                    <p class="mt-1 text-xs text-slate-300 font-medium">
                                        {{ $slot['commitment'] }}
                                    </p>
                                </div>

                                <!-- Price -->
                                <div class="mt-6 flex items-baseline gap-1.5">
                                    <span class="font-heading text-3xl sm:text-4xl font-black text-white tracking-tight">
                                        {{ $slot['price_str'] }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400">
                                        {{ $slot['period'] }}
                                    </span>
                                </div>

                                <!-- Facilities Checklist -->
                                <div class="mt-7 border-t border-slate-800 pt-6">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-rose-400 mb-4">
                                        Fasilitas &amp; Keuntungan Termasuk:
                                    </p>
                                    <ul class="space-y-3.5">
                                        @foreach ($slot['facilities'] as $facility)
                                            <li class="flex items-start gap-3 text-xs leading-relaxed text-slate-200">
                                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#BA0030] text-white text-[10px] font-bold shadow-sm">
                                                    ✓
                                                </span>
                                                <span class="font-medium">{{ $facility }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-8 pt-4">
                                <x-button
                                    href="{{ $checkoutUrl }}"
                                    variant="primary"
                                    class="w-full !py-4 font-bold shadow-lg shadow-[#BA0030]/30 hover:scale-[1.02] transition"
                                >
                                    {{ $slot['button_text'] }}
                                </x-button>
                            </div>
                        </div>
                    @else
                        <!-- Kiri (Starter) & Kanan (All-Access Pro) - Kartu Putih Bersih -->
                        <div class="flex flex-col justify-between rounded-3xl bg-white p-8 border border-slate-200/90 shadow-md hover:shadow-xl transition-all duration-300 text-slate-900">
                            <div>
                                <div>
                                    <h3 class="font-heading text-xl sm:text-2xl font-extrabold text-slate-900">
                                        {{ $slot['name'] }}
                                    </h3>
                                    <p class="mt-1 text-xs text-slate-500 font-medium">
                                        {{ $slot['commitment'] }}
                                    </p>
                                </div>

                                <!-- Price -->
                                <div class="mt-6 flex items-baseline gap-1.5">
                                    <span class="font-heading text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                                        {{ $slot['price_str'] }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-500">
                                        {{ $slot['period'] }}
                                    </span>
                                </div>

                                <!-- Facilities Checklist -->
                                <div class="mt-7 border-t border-slate-100 pt-6">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-4">
                                        Fasilitas &amp; Keuntungan Termasuk:
                                    </p>
                                    <ul class="space-y-3.5">
                                        @foreach ($slot['facilities'] as $facility)
                                            <li class="flex items-start gap-3 text-xs leading-relaxed text-slate-700">
                                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#BA0030] text-white text-[10px] font-bold shadow-sm">
                                                    ✓
                                                </span>
                                                <span class="font-medium">{{ $facility }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-8 pt-4">
                                <x-button
                                    href="{{ $checkoutUrl }}"
                                    variant="outline"
                                    class="w-full !py-4 font-bold border-slate-300 text-slate-800 hover:bg-slate-50 hover:border-slate-400 transition"
                                >
                                    {{ $slot['button_text'] }}
                                </x-button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section Bawah: Section Gelap "SEMUA MEMBERSHIP SUDAH TERMASUK" -->
    <section class="bg-[#0B0F17] py-20 px-4 sm:px-6 lg:px-8 border-t border-slate-800 text-white">
        <div class="mx-auto max-w-7xl">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-block text-[11px] font-extrabold uppercase tracking-widest text-[#BA0030] mb-2">
                    FASILITAS STANDAR INTERNASIONAL
                </span>
                <h2 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                    SEMUA MEMBERSHIP SUDAH TERMASUK
                </h2>
                <p class="mt-3 text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Setiap paket membership memberikan Anda akses menyeluruh ke ekosistem gym performa tinggi tanpa biaya tersembunyi.
                </p>
            </div>

            <!-- 4 Box Benefit -->
            <div class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Benefit 1 -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 transition duration-200 hover:border-[#BA0030]/50 hover:bg-slate-900">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/15 text-[#BA0030] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-heading text-base font-bold text-white">Peralatan Fitness Lengkap</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Mesin beban, cardio zone, dan free weights berstandar Olimpiade untuk efektivitas latihan maksimal.
                    </p>
                </div>

                <!-- Benefit 2 -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 transition duration-200 hover:border-[#BA0030]/50 hover:bg-slate-900">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/15 text-[#BA0030] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3v18M5 8h14M8 13h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-heading text-base font-bold text-white">Kamar Bilas &amp; Locker</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Kamar mandi bersih dengan air panas, pengering rambut, dan loker aman dengan kunci elektronik.
                    </p>
                </div>

                <!-- Benefit 3 -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 transition duration-200 hover:border-[#BA0030]/50 hover:bg-slate-900">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/15 text-[#BA0030] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="5" y="5" width="14" height="14" rx="2" stroke="currentColor" stroke-width="2" />
                            <path d="M9 9h2v2H9V9Zm4 0h2v2h-2V9Zm-4 4h2v2H9v-2Zm4 0h2v2h-2v-2Z" fill="currentColor" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-heading text-base font-bold text-white">Akses Aplikasi &amp; Check-in QR</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Scan QR instan tanpa kartu fisik, pantau riwayat latihan, dan kelola jadwal sesi PT langsung lewat ponsel.
                    </p>
                </div>

                <!-- Benefit 4 -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 p-6 transition duration-200 hover:border-[#BA0030]/50 hover:bg-slate-900">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/15 text-[#BA0030] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-heading text-base font-bold text-white">Area Istirahat</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Lounge santai ber-AC, dispenser air mineral gratis, area charging station, dan koneksi Wi-Fi berkecepatan tinggi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dark Footer Resmi FitCore Athletic -->
    <footer class="border-t border-slate-800 bg-[#0B0F17] text-slate-400 text-xs py-14 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="space-y-3.5 md:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#BA0030] font-heading font-black text-white text-sm shadow-md shadow-[#BA0030]/30">
                        FC
                    </span>
                    <div>
                        <span class="font-heading font-extrabold text-white text-base tracking-wider">FITCORE <span class="text-[#BA0030]">ATHLETIC</span></span>
                        <p class="text-[9px] uppercase tracking-widest text-slate-400">Gym &amp; Performance Center</p>
                    </div>
                </div>
                <p class="text-slate-400 max-w-sm leading-relaxed text-xs">
                    Pusat kebugaran premium dengan peralatan standar internasional, instruktur bersertifikasi, dan teknologi modern untuk memaksimalkan potensi tubuh Anda.
                </p>
            </div>
            <div>
                <h4 class="font-heading font-bold text-white uppercase text-xs tracking-wider mb-3.5">Lokasi &amp; Jam Operasional</h4>
                <p class="leading-relaxed text-slate-400">Sudirman Central Business District (SCBD), District 8, Jakarta Selatan</p>
                <p class="mt-2.5 text-[#BA0030] font-bold">Senin - Minggu: 06:00 - 23:00 WIB</p>
            </div>
            <div>
                <h4 class="font-heading font-bold text-white uppercase text-xs tracking-wider mb-3.5">Kontak &amp; Bantuan</h4>
                <p class="text-slate-400">WhatsApp: +62 812-3456-7890</p>
                <p class="mt-1 text-slate-400">Email: cs@fitcoreathletic.id</p>
                <p class="mt-2.5 text-[11px] text-slate-500">Layanan CS beroperasi setiap hari 24 jam.</p>
            </div>
        </div>
        <div class="mx-auto max-w-7xl mt-10 border-t border-slate-800/80 pt-6 text-center text-slate-500 text-[11px]">
            &copy; {{ date('Y') }} FitCore Athletic Gym &amp; Performance Center. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </footer>
</x-member-layout>
