<x-member-layout title="FitCore Athletic - Katalog Paket Keanggotaan" active-nav="packages">
    <!-- Hero Header Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#0B0F17] via-[#111827] to-[#0B0F17] py-16 px-4 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute -top-40 right-0 h-96 w-96 rounded-full bg-[#BA0030]/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 left-0 h-96 w-96 rounded-full bg-rose-600/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-5xl text-center">
            <x-badge variant="primary" :dot="false" size="sm" class="mb-4 !bg-[#BA0030]/20 !text-rose-400 !ring-[#BA0030]/40">
                FITCORE ATHLETIC MEMBERSHIP
            </x-badge>
            <h1 class="font-heading text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                Pilih Paket Keanggotaan
            </h1>
            <p class="mx-auto mt-4 max-w-3xl text-base leading-relaxed text-slate-300 sm:text-lg">
                Tingkatkan performa fisik Anda dengan akses fasilitas latihan tak terbatas, kelas instruktur berlisensi, dan program terpersonalisasi.
            </p>
        </div>
    </section>

    <!-- Grid 3 Tier Pricing Cards -->
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if (session('success'))
            <x-alert type="success" class="mb-8">
                {{ session('success') }}
            </x-alert>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:items-stretch">
            @php
                // Sort or fallback logic to align cards nicely
                $sortedPackages = $packages->sortBy('price')->values();
                $firstPkg = $sortedPackages->get(0) ?? $packages->first();
                $highlightPkg = $packages->firstWhere('badge', 'BEST SELLER') 
                    ?? $packages->firstWhere('tier', 'Premium & VIP') 
                    ?? $sortedPackages->get(1) 
                    ?? $firstPkg;
                $thirdPkg = $sortedPackages->get(2) ?? $sortedPackages->last() ?? $firstPkg;
            @endphp

            @foreach ($packages as $pkg)
                @php
                    $isHighlighted = strtoupper($pkg->badge ?? '') === 'BEST SELLER' || $pkg->id === $highlightPkg?->id;
                    $hasPromo = $pkg->promo_price !== null && $pkg->price > $pkg->promo_price;
                    $effectivePrice = $hasPromo ? $pkg->promo_price : $pkg->price;
                    $discountPercent = $hasPromo ? (int) round((($pkg->price - $pkg->promo_price) / $pkg->price) * 100) : 0;

                    $buttonLabel = match (true) {
                        $isHighlighted => 'Gabung Elite Sekarang',
                        str_contains(strtolower($pkg->name), 'starter') || str_contains(strtolower($pkg->name), 'basic') || str_contains(strtolower($pkg->name), 'silver') => 'Pilih Paket Starter',
                        default => 'Pilih All-Access Pro',
                    };
                @endphp

                <div @class([
                    'relative flex flex-col justify-between rounded-3xl transition-all duration-300',
                    'bg-slate-900 border-2 border-[#BA0030] shadow-[0_0_35px_rgba(186,0,48,0.25)] text-white scale-[1.02] z-10 p-8' => $isHighlighted,
                    'bg-white text-slate-900 shadow-xl border border-slate-200 p-8 hover:shadow-2xl hover:border-slate-300' => ! $isHighlighted,
                ])>
                    @if ($isHighlighted)
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-[#BA0030] to-rose-600 px-4 py-1 text-[10px] font-extrabold tracking-wider uppercase text-white shadow-md">
                            PALING DIMINATI • {{ $hasPromo ? "HEMAT {$discountPercent}%" : 'REKOMENDASI ATLET' }}
                        </div>
                    @elseif ($pkg->badge)
                        <div class="mb-3">
                            <x-badge :status="$pkg->badge" :dot="false" size="xs" />
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center justify-between">
                            <h3 @class([
                                'font-heading text-xl font-extrabold',
                                'text-white' => $isHighlighted,
                                'text-slate-900' => ! $isHighlighted,
                            ])>
                                {{ $pkg->name }}
                            </h3>
                        </div>

                        <p @class([
                            'mt-2 text-xs leading-relaxed',
                            'text-slate-300' => $isHighlighted,
                            'text-slate-500' => ! $isHighlighted,
                        ])>
                            {{ $pkg->description ?? 'Akses lengkap ke area latihan gym &amp; fasilitas pendukung.' }}
                        </p>

                        <!-- Price display -->
                        <div class="mt-6 flex items-baseline gap-2">
                            <span @class([
                                'font-heading text-3xl font-extrabold sm:text-4xl',
                                'text-white' => $isHighlighted,
                                'text-slate-900' => ! $isHighlighted,
                            ])>
                                Rp {{ number_format($effectivePrice, 0, ',', '.') }}
                            </span>
                            <span @class([
                                'text-xs font-medium',
                                'text-slate-400' => $isHighlighted,
                                'text-slate-500' => ! $isHighlighted,
                            ])>
                                / {{ $pkg->duration_value }} {{ $pkg->duration_unit }}
                            </span>
                        </div>

                        @if ($hasPromo)
                            <div class="mt-1 flex items-center gap-2">
                                <span class="text-xs text-slate-400 line-through">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                <span class="rounded bg-rose-500/20 px-2 py-0.5 text-[10px] font-bold text-rose-400">Hemat {{ $discountPercent }}%</span>
                            </div>
                        @endif

                        <div class="mt-6 border-t border-slate-200/20 pt-6">
                            <p @class([
                                'text-xs font-bold uppercase tracking-wider',
                                'text-rose-400' => $isHighlighted,
                                'text-slate-700' => ! $isHighlighted,
                            ])>
                                Fasilitas &amp; Benefit Utama:
                            </p>
                            <ul class="mt-4 space-y-3">
                                @foreach ($pkg->facilities ?? [] as $facility)
                                    <li class="flex items-start gap-3 text-xs">
                                        <span @class([
                                            'flex h-5 w-5 shrink-0 items-center justify-center rounded-full font-bold text-[10px]',
                                            'bg-[#BA0030] text-white' => $isHighlighted,
                                            'bg-rose-100 text-[#BA0030]' => ! $isHighlighted,
                                        ])>✓</span>
                                        <span @class([
                                            'text-slate-200' => $isHighlighted,
                                            'text-slate-700' => ! $isHighlighted,
                                        ])>{{ $facility }}</span>
                                    </li>
                                @endforeach
                                @if ($pkg->pt_sessions)
                                    <li class="flex items-start gap-3 text-xs">
                                        <span @class([
                                            'flex h-5 w-5 shrink-0 items-center justify-center rounded-full font-bold text-[10px]',
                                            'bg-[#BA0030] text-white' => $isHighlighted,
                                            'bg-rose-100 text-[#BA0030]' => ! $isHighlighted,
                                        ])>✓</span>
                                        <span @class([
                                            'font-bold text-white' => $isHighlighted,
                                            'font-bold text-slate-900' => ! $isHighlighted,
                                        ])>{{ $pkg->pt_sessions }} Sesi Personal Trainer Gratis</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8">
                        <x-button
                            href="{{ route('member.membership.checkout', $pkg->id) }}"
                            :variant="$isHighlighted ? 'primary' : 'outline'"
                            class="w-full !py-3.5 text-center justify-center"
                        >
                            {{ $buttonLabel }}
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Section "SEMUA MEMBERSHIP SUDAH TERMASUK" -->
    <section class="bg-zinc-950 py-16 px-4 sm:px-6 lg:px-8 border-t border-b border-slate-800">
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                    SEMUA MEMBERSHIP SUDAH TERMASUK
                </h2>
                <p class="mt-2 text-sm text-slate-400">
                    Nikmati pengalaman fitness premium terlengkap dengan kenyamanan ekstra di setiap sesi latihan Anda.
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Feature 1 -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 transition hover:border-[#BA0030]/40">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/20 text-[#BA0030]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-heading text-base font-bold text-white">Peralatan Fitness Lengkap</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Mesin beban &amp; cardio standar internasional bersertifikasi untuk target otot maksimal.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 transition hover:border-[#BA0030]/40">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/20 text-[#BA0030]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3v18M5 8h14M8 13h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-heading text-base font-bold text-white">Kamar Bilas &amp; Locker</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Ruang ganti bersih, locker privat dengan sistem kunci otomatis, dan shower air hangat.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 transition hover:border-[#BA0030]/40">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/20 text-[#BA0030]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="5" y="5" width="14" height="14" rx="2" stroke="currentColor" stroke-width="2" />
                            <path d="M9 9h2v2H9V9Zm4 0h2v2h-2V9Zm-4 4h2v2H9v-2Zm4 0h2v2h-2v-2Z" fill="currentColor" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-heading text-base font-bold text-white">Akses Aplikasi &amp; Check-in QR</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Check-in instant tanpa antre menggunakan pemindaian QR Code lewat smartphone Anda.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 transition hover:border-[#BA0030]/40">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#BA0030]/20 text-[#BA0030]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-heading text-base font-bold text-white">Area Istirahat &amp; Air Minum</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">
                        Lounge santai dengan jaringan WiFi super cepat dan dispenser refill air mineral gratis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section FAQ (Alpine.js Accordion) -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-[#0B0F17]">
        <div class="mx-auto max-w-4xl" x-data="{ openFaq: 1 }">
            <div class="text-center">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                    Pertanyaan Sering Diajukan (FAQ)
                </h2>
                <p class="mt-2 text-sm text-slate-400">
                    Informasi mengenai keanggotaan dan proses pembayaran di FitCore Athletic.
                </p>
            </div>

            <div class="mt-10 space-y-4">
                <!-- FAQ Item 1 -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 overflow-hidden">
                    <button
                        type="button"
                        x-on:click="openFaq = (openFaq === 1 ? null : 1)"
                        class="flex w-full items-center justify-between p-5 text-left font-heading text-sm font-bold text-white transition hover:bg-slate-800/50"
                    >
                        <span>Kapan paket keanggotaan saya mulai aktif?</span>
                        <svg class="h-5 w-5 shrink-0 text-[#BA0030] transition-transform duration-200" x-bind:class="openFaq === 1 ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="border-t border-slate-800/60 p-5 text-xs leading-relaxed text-slate-300">
                        Paket keanggotaan Anda akan otomatis aktif secara langsung begitu bukti pembayaran Anda diverifikasi oleh tim admin FitCore Gym (proses verifikasi cepat maksimal 1x24 jam).
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 overflow-hidden">
                    <button
                        type="button"
                        x-on:click="openFaq = (openFaq === 2 ? null : 2)"
                        class="flex w-full items-center justify-between p-5 text-left font-heading text-sm font-bold text-white transition hover:bg-slate-800/50"
                    >
                        <span>Bagaimana jika saya sedang dinas ke luar kota atau sakit?</span>
                        <svg class="h-5 w-5 shrink-0 text-[#BA0030] transition-transform duration-200" x-bind:class="openFaq === 2 ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="border-t border-slate-800/60 p-5 text-xs leading-relaxed text-slate-300">
                        Anda dapat mengajukan pembekuan masa aktif sementara (freeze membership) melalui profil akun Anda atau dengan menghubungi layanan staf kami dengan melampirkan dokumen pendukung.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 overflow-hidden">
                    <button
                        type="button"
                        x-on:click="openFaq = (openFaq === 3 ? null : 3)"
                        class="flex w-full items-center justify-between p-5 text-left font-heading text-sm font-bold text-white transition hover:bg-slate-800/50"
                    >
                        <span>Metode pembayaran apa saja yang diterima?</span>
                        <svg class="h-5 w-5 shrink-0 text-[#BA0030] transition-transform duration-200" x-bind:class="openFaq === 3 ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="border-t border-slate-800/60 p-5 text-xs leading-relaxed text-slate-300">
                        Kami menerima transfer bank resmi (BCA, Mandiri, BRI) secara langsung ke rekening PT FitCore Gym. Cukup unggah struk/bukti transfer pada halaman checkout untuk verifikasi otomatis.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Dark "FitCore Athletic" -->
    <footer class="border-t border-slate-800 bg-zinc-950 text-slate-400 text-xs py-12 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3 md:col-span-2">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#BA0030] font-bold text-white text-xs">FC</span>
                    <span class="font-heading font-extrabold text-white text-base">FITCORE ATHLETIC</span>
                </div>
                <p class="text-slate-400 max-w-sm leading-relaxed">
                    Pusat kebugaran terlengkap dengan instruktur profesional dan teknologi check-in modern untuk mendukung perjalanan transformasi fisik Anda.
                </p>
            </div>
            <div>
                <h4 class="font-bold text-white uppercase text-xs tracking-wider mb-3">Lokasi &amp; Jam Operasional</h4>
                <p class="leading-relaxed">Sudirman Central Business District (SCBD), District 8, Jakarta Selatan</p>
                <p class="mt-2 text-rose-400 font-semibold">Senin - Minggu: 06:00 - 23:00 WIB</p>
            </div>
            <div>
                <h4 class="font-bold text-white uppercase text-xs tracking-wider mb-3">Kontak &amp; Bantuan</h4>
                <p>WhatsApp: +62 812-3456-7890</p>
                <p class="mt-1">Email: support@fitcore.id</p>
            </div>
        </div>
        <div class="mx-auto max-w-7xl mt-8 border-t border-slate-800/80 pt-6 text-center text-slate-500 text-[11px]">
            &copy; {{ date('Y') }} FitCore Athletic Gym. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </footer>
</x-member-layout>
