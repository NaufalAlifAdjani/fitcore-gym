<x-member-layout title="Pilih Paket Keanggotaan - FitCore Athletic" active-nav="packages">
    <!-- Header Section (Nuansa Putih / Light Bersih) -->
    <section class="bg-white border-b border-[#E5E7EB] pt-14 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl text-center">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 text-[11px] font-extrabold uppercase tracking-widest text-[#ED1B45] bg-[#ED1B45]/10 border border-[#ED1B45]/20 rounded-full mb-4">
                FITCORE ATHLETIC MEMBERSHIP
            </span>
            <h1 class="font-poppins text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#16151A] tracking-tight">
                Pilih Paket Keanggotaan
            </h1>
            <p class="mt-3.5 max-w-2xl mx-auto text-sm sm:text-base text-[#565A66] leading-relaxed font-inter font-normal">
                Tingkatkan performa fisik Anda dengan fasilitas gym kelas dunia, bimbingan pelatih tersertifikasi, dan program terstruktur.
            </p>
        </div>

        <!-- Alert Notifications -->
        <div class="max-w-6xl mx-auto mt-6">
            @if (session('success'))
                <x-alert type="success" class="mb-6 max-w-3xl mx-auto">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert type="danger" class="mb-6 max-w-3xl mx-auto">
                    {{ session('error') }}
                </x-alert>
            @endif
        </div>

        <!-- Static 3-Column Responsive Grid -->
        <div class="mt-10 max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                @forelse ($packages as $package)
                    @php
                        $isFeatured = str_contains(strtoupper($package->badge ?? ''), 'BEST')
                            || ($loop->count === 3 && $loop->iteration === 2)
                            || ($loop->count > 3 && str_contains(strtoupper($package->badge ?? ''), 'BEST'));

                        $facilities = is_array($package->facilities)
                            ? $package->facilities
                            : (is_string($package->facilities) ? json_decode($package->facilities, true) ?? [] : []);

                        if (empty($facilities)) {
                            $facilities = [
                                'Akses Penuh Area Gym & Cardio',
                                'Loker Harian & Kamar Bilas',
                                'Akses Check-in Aplikasi FitCore',
                            ];
                        }

                        // Bersihkan duplikasi mention PT pada fasilitas agar tidak terulang
                        if ($package->pt_session_count > 0) {
                            $facilities = array_filter($facilities, function ($item) {
                                return !preg_match('/(sesi\s*pt|personal\s*trainer)/i', $item);
                            });
                        }

                        $effectivePrice = ($package->promo_price && $package->promo_price < $package->price)
                            ? $package->promo_price
                            : $package->price;
                        $checkoutUrl = route('member.membership.checkout', $package->id);
                    @endphp

                    <!-- Card Paket (Kondisional: Best Seller vs Standard/VIP) -->
                    <div class="relative flex flex-col justify-between rounded-3xl p-8 transition-all duration-300 {{ $isFeatured ? 'bg-[#141414] text-white border-2 border-[#ED1B45] shadow-2xl md:-translate-y-2 md:scale-[1.03] z-10' : 'bg-white border border-[#E5E7EB] text-[#16151A] shadow-[0_4px_12px_rgba(0,0,0,0.06)] hover:shadow-xl hover:border-[#ED1B45]/40' }}">
                        
                        @if ($package->badge)
                            <!-- Badge Paket -->
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full px-4 py-1 text-[10px] font-black uppercase tracking-wider whitespace-nowrap z-20 shadow-md {{ $isFeatured ? 'bg-[#ED1B45] text-white shadow-[#ED1B45]/30' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ $package->badge }}
                            </div>
                        @endif

                        <div>
                            <!-- Header Paket -->
                            <div class="mb-6 mt-1">
                                <span class="text-xs font-bold tracking-wider uppercase {{ $isFeatured ? 'text-slate-400' : 'text-[#565A66]' }}">
                                    {{ $package->tier ?? 'Membership' }}
                                </span>
                                <h3 class="text-2xl font-bold mt-2 font-poppins tracking-tight {{ $isFeatured ? 'text-white' : 'text-[#16151A]' }}">
                                    {{ $package->name }}
                                </h3>
                                <p class="text-xs sm:text-sm mt-1 {{ $isFeatured ? 'text-slate-400' : 'text-[#565A66]' }}">
                                    Komitmen {{ $package->duration_days }} Hari
                                </p>
                            </div>

                            <!-- Harga -->
                            <div class="mb-8">
                                @if ($package->promo_price && $package->promo_price < $package->price)
                                    <p class="text-xs font-semibold text-slate-400 line-through mb-1">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </p>
                                @endif
                                <div class="flex items-baseline gap-1.5 flex-wrap">
                                    <span class="font-poppins font-extrabold text-3xl sm:text-4xl tracking-tight {{ $isFeatured ? 'text-white' : 'text-[#16151A]' }}">
                                        <span class="text-2xl font-bold font-poppins">Rp</span> {{ number_format($effectivePrice, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs font-semibold {{ $isFeatured ? 'text-slate-400' : 'text-[#565A66]' }}">
                                        / {{ strtolower($package->duration_unit ?? 'bln') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Daftar Fasilitas -->
                            <ul class="space-y-4 mb-8 flex-1">
                                @foreach ($facilities as $facility)
                                    <li class="flex items-start gap-3 text-sm">
                                        <svg class="w-5 h-5 shrink-0 text-[#10B981] mt-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="leading-relaxed {{ $isFeatured ? 'text-slate-300' : 'text-[#565A66]' }}">{{ $facility }}</span>
                                    </li>
                                @endforeach
                                @if ($package->pt_session_count > 0)
                                    <li class="flex items-start gap-3 text-sm">
                                        <svg class="w-5 h-5 shrink-0 text-[#10B981] mt-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="leading-relaxed {{ $isFeatured ? 'text-slate-300' : 'text-[#565A66]' }}">
                                            <strong class="{{ $isFeatured ? 'text-white' : 'text-[#16151A]' }} font-semibold">{{ $package->pt_session_count }} Sesi</strong> Personal Trainer Gratis
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Tombol Action -->
                        <div class="mt-4 pt-2">
                            @if ($isFeatured)
                                <a
                                    href="{{ $checkoutUrl }}"
                                    class="block w-full text-center py-3.5 rounded-full font-bold text-sm bg-[#ED1B45] hover:bg-[#D1123D] text-white transition-all shadow-lg shadow-[#ED1B45]/30 hover:shadow-[#ED1B45]/50 hover:scale-[1.02]"
                                >
                                    Gabung Elite Sekarang
                                </a>
                            @else
                                <a
                                    href="{{ $checkoutUrl }}"
                                    class="block w-full text-center py-3.5 rounded-full font-semibold text-sm border-2 border-[#ED1B45] text-[#ED1B45] hover:bg-[#ED1B45] hover:text-white transition-colors"
                                >
                                    Pilih Paket Ini
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <p class="font-poppins text-lg font-bold text-[#16151A]">Belum ada paket membership aktif</p>
                        <p class="mt-2 text-sm text-[#565A66]">Silakan hubungi administrator gym untuk info paket terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Section Bawah: Section Gelap "SEMUA MEMBERSHIP SUDAH TERMASUK" (#141414 Solid Black) -->
    <section class="bg-[#141414] py-20 px-4 sm:px-6 lg:px-8 border-t border-[#262626] text-white">
        <div class="mx-auto max-w-7xl">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-block text-[11px] font-extrabold uppercase tracking-widest text-[#ED1B45] mb-2">
                    FASILITAS STANDAR INTERNASIONAL
                </span>
                <h2 class="font-poppins text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                    SEMUA MEMBERSHIP SUDAH TERMASUK
                </h2>
                <p class="mt-3 text-xs sm:text-sm text-slate-400 leading-relaxed font-inter">
                    Setiap paket membership memberikan Anda akses menyeluruh ke ekosistem gym performa tinggi tanpa biaya tersembunyi.
                </p>
            </div>

            <!-- 4 Box Benefit (Flat Surface-dark #1E1E1E) -->
            <div class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Benefit 1 -->
                <div class="rounded-2xl bg-[#1E1E1E] p-6 transition duration-200 hover:bg-[#252525]">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#ED1B45]/15 text-[#ED1B45] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-poppins text-base font-bold text-white">Peralatan Fitness Lengkap</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400 font-inter">
                        Mesin beban, cardio zone, dan free weights berstandar Olimpiade untuk efektivitas latihan maksimal.
                    </p>
                </div>

                <!-- Benefit 2 -->
                <div class="rounded-2xl bg-[#1E1E1E] p-6 transition duration-200 hover:bg-[#252525]">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#ED1B45]/15 text-[#ED1B45] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3v18M5 8h14M8 13h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-poppins text-base font-bold text-white">Kamar Bilas &amp; Locker</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400 font-inter">
                        Kamar mandi bersih dengan air panas, pengering rambut, dan loker aman dengan kunci elektronik.
                    </p>
                </div>

                <!-- Benefit 3 -->
                <div class="rounded-2xl bg-[#1E1E1E] p-6 transition duration-200 hover:bg-[#252525]">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#ED1B45]/15 text-[#ED1B45] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="5" y="5" width="14" height="14" rx="2" stroke="currentColor" stroke-width="2" />
                            <path d="M9 9h2v2H9V9Zm4 0h2v2h-2V9Zm-4 4h2v2H9v-2Zm4 0h2v2h-2v-2Z" fill="currentColor" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-poppins text-base font-bold text-white">Akses Aplikasi &amp; Check-in QR</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400 font-inter">
                        Scan QR instan tanpa kartu fisik, pantau riwayat latihan, dan kelola jadwal sesi PT langsung lewat ponsel.
                    </p>
                </div>

                <!-- Benefit 4 -->
                <div class="rounded-2xl bg-[#1E1E1E] p-6 transition duration-200 hover:bg-[#252525]">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#ED1B45]/15 text-[#ED1B45] shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </div>
                    <h3 class="mt-5 font-poppins text-base font-bold text-white">Area Istirahat</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400 font-inter">
                        Lounge santai ber-AC, dispenser air mineral gratis, area charging station, dan koneksi Wi-Fi berkecepatan tinggi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dark Footer Resmi FitCore Athletic (#141414) -->
    <footer class="border-t border-[#262626] bg-[#141414] text-slate-400 text-xs py-14 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="space-y-3.5 md:col-span-2">
                <div class="flex items-center gap-3">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 shadow-md shadow-[#ED1B45]/30 rounded-full">
                        <rect width="36" height="36" rx="18" fill="#ED1B45"/>
                        <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="white"/>
                    </svg>
                    <div>
                        <span class="font-poppins font-extrabold text-white text-base tracking-wider">FITCORE <span class="text-[#ED1B45]">ATHLETIC</span></span>
                        <p class="text-[9px] uppercase tracking-widest text-slate-400">Gym &amp; Performance Center</p>
                    </div>
                </div>
                <p class="text-slate-400 max-w-sm leading-relaxed text-xs">
                    Pusat kebugaran premium dengan peralatan standar internasional, instruktur bersertifikasi, dan teknologi modern untuk memaksimalkan potensi tubuh Anda.
                </p>
            </div>
            <div>
                <h4 class="font-poppins font-bold text-white uppercase text-xs tracking-wider mb-3.5">Lokasi &amp; Jam Operasional</h4>
                <p class="leading-relaxed text-slate-400">Sudirman Central Business District (SCBD), District 8, Jakarta Selatan</p>
                <p class="mt-2.5 text-[#ED1B45] font-bold">Senin - Minggu: 06:00 - 23:00 WIB</p>
            </div>
            <div>
                <h4 class="font-poppins font-bold text-white uppercase text-xs tracking-wider mb-3.5">Kontak &amp; Bantuan</h4>
                <p class="text-slate-400">WhatsApp: +62 812-3456-7890</p>
                <p class="mt-1 text-slate-400">Email: cs@fitcoreathletic.id</p>
                <p class="mt-2.5 text-[11px] text-slate-500">Layanan CS beroperasi setiap hari 24 jam.</p>
            </div>
        </div>
        <div class="mx-auto max-w-7xl mt-10 border-t border-[#262626] pt-6 text-center text-slate-500 text-[11px]">
            &copy; {{ date('Y') }} FitCore Athletic Gym &amp; Performance Center. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </footer>
</x-member-layout>
