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

            @if (session('error'))
                <x-alert type="danger" class="mb-8 max-w-3xl mx-auto">
                    {{ session('error') }}
                </x-alert>
            @endif


            <style>
                .hide-scrollbar::-webkit-scrollbar {
                    display: none;
                }
            </style>

            <!-- Wrapper Slider dengan Deteksi Active Slide Berdasarkan Posisi Scroll -->
            <div
                x-data="{
                    activeSlide: 1,
                    scrollNext() { this.$refs.slider.scrollBy({ left: 380, behavior: 'smooth' }); },
                    scrollPrev() { this.$refs.slider.scrollBy({ left: -380, behavior: 'smooth' }); },
                    checkCenter() {
                        const container = this.$refs.slider;
                        const containerCenter = container.scrollLeft + (container.clientWidth / 2);
                        let closestIndex = 1;
                        let minDistance = Infinity;

                        const cards = container.querySelectorAll('.snap-center-card');
                        cards.forEach(card => {
                            // Hitung posisi tengah masing-masing kartu
                            const cardCenter = card.offsetLeft + (card.clientWidth / 2) - container.offsetLeft;
                            const distance = Math.abs(containerCenter - cardCenter);

                            if (distance < minDistance) {
                                minDistance = distance;
                                closestIndex = parseInt(card.dataset.index);
                            }
                        });
                        this.activeSlide = closestIndex;
                    },
                    init() {
                        this.$nextTick(() => {
                            // Geser otomatis ke paket ke-2 di awal jika paket >= 3 agar yang tengah langsung aktif
                            if (this.$refs.slider.children.length >= 3) {
                                this.scrollNext();
                            } else {
                                this.checkCenter(); // Panggil sekali di awal
                            }
                        });
                    }
                }"
                class="relative w-full max-w-[1400px] mx-auto py-10 px-16 group"
            >
                <!-- Tombol Panah Kiri (Ubah posisinya agar lebih keluar) -->
                <button
                    type="button"
                    @click="scrollPrev()"
                    aria-label="Geser ke paket sebelumnya"
                    class="absolute -left-4 md:left-2 top-1/2 z-30 -translate-y-1/2 bg-white text-gray-800 shadow-xl rounded-full p-4 hover:bg-gray-50 focus:outline-none transition-transform hover:scale-110 border border-gray-100"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>

                <!-- Container Slider -->
                <div
                    x-ref="slider"
                    @scroll.debounce.10ms="checkCenter()"
                    class="flex overflow-x-auto snap-x snap-mandatory gap-8 scroll-smooth pb-16 pt-12 px-8 sm:px-12 hide-scrollbar"
                    style="scrollbar-width: none; -ms-overflow-style: none;"
                >
                    @forelse ($packages as $package)
                        @php
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

                            $effectivePrice = $package->promo_price && $package->promo_price < $package->price
                                ? $package->promo_price
                                : $package->price;
                            $checkoutUrl = route('member.membership.checkout', $package->id);
                        @endphp

                        <!-- Wrapper Kartu dengan Efek Skala Pop-out -->
                        <div
                            data-index="{{ $loop->iteration }}"
                            class="snap-center-card snap-center shrink-0 w-[300px] md:w-[350px] transition-all duration-500 ease-out"
                            :class="activeSlide === {{ $loop->iteration }} ? 'scale-105 z-20' : 'scale-95 opacity-60 hover:opacity-100'"
                        >
                            <!-- Kartu Dinamis (Warna Berubah Sesuai Active Slide) -->
                            <div
                                class="relative h-full rounded-3xl border transition-colors duration-500 p-8 flex flex-col justify-between"
                                :class="activeSlide === {{ $loop->iteration }} ? 'bg-[#0B0F17] border-[#BA0030] text-white shadow-2xl' : 'bg-white border-gray-200 text-gray-900 shadow-md'"
                            >
                                @if ($package->badge)
                                    <!-- Badge Dinamis -->
                                    <div
                                        class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full px-4 py-1 text-[10px] font-black uppercase tracking-wider shadow-md whitespace-nowrap z-10 transition-colors"
                                        :class="activeSlide === {{ $loop->iteration }} ? 'bg-[#BA0030] text-white shadow-[#BA0030]/40' : 'bg-slate-100 text-slate-700 border border-slate-200'"
                                    >
                                        {{ $package->badge }}
                                    </div>
                                @endif

                                <div>
                                    <!-- Header Paket -->
                                    <div class="mb-6 mt-2">
                                        <span
                                            class="text-xs font-bold tracking-wider uppercase transition-colors"
                                            :class="activeSlide === {{ $loop->iteration }} ? 'text-gray-400' : 'text-gray-500'"
                                        >
                                            {{ $package->tier }}
                                        </span>
                                        <h3 class="text-2xl font-bold mt-2 font-heading tracking-tight">{{ $package->name }}</h3>
                                        <p
                                            class="text-sm mt-1 transition-colors"
                                            :class="activeSlide === {{ $loop->iteration }} ? 'text-gray-300' : 'text-gray-500'"
                                        >
                                            Komitmen {{ $package->duration_days }} Hari
                                        </p>
                                    </div>

                                    <!-- Harga -->
                                    <div class="mb-8">
                                        @if ($package->promo_price && $package->promo_price < $package->price)
                                            <p
                                                class="text-xs font-semibold line-through transition-colors"
                                                :class="activeSlide === {{ $loop->iteration }} ? 'text-slate-400' : 'text-slate-400'"
                                            >
                                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                            </p>
                                        @endif
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="text-4xl font-extrabold font-heading tracking-tight">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</span>
                                            <span
                                                class="text-xs font-bold transition-colors"
                                                :class="activeSlide === {{ $loop->iteration }} ? 'text-slate-400' : 'text-slate-500'"
                                            >
                                                / {{ strtolower($package->duration_unit ?? 'bln') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Fasilitas -->
                                    <ul class="space-y-4 mb-8 flex-1">
                                        @foreach ($facilities as $facility)
                                            <li class="flex items-start gap-3 text-sm">
                                                <svg
                                                    class="w-5 h-5 shrink-0 text-[#BA0030]"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                    aria-hidden="true"
                                                >
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="leading-relaxed">{{ $facility }}</span>
                                            </li>
                                        @endforeach
                                        @if ($package->pt_session_count > 0)
                                            <li
                                                class="flex items-start gap-3 text-sm font-semibold transition-colors"
                                                :class="activeSlide === {{ $loop->iteration }} ? 'text-rose-300' : 'text-[#BA0030]'"
                                            >
                                                <svg
                                                    class="w-5 h-5 shrink-0 text-[#BA0030]"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                    aria-hidden="true"
                                                >
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="leading-relaxed">{{ $package->pt_session_count }} Sesi Personal Trainer Gratis</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Tombol Action -->
                                <div class="mt-4 pt-2">
                                    <a
                                        href="{{ $checkoutUrl }}"
                                        class="block w-full text-center py-3.5 rounded-full font-bold transition-all shadow-md"
                                        :class="activeSlide === {{ $loop->iteration }} ? 'bg-[#BA0030] text-white hover:bg-red-800 shadow-[#BA0030]/30 hover:scale-[1.02]' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'"
                                    >
                                        <span x-text="activeSlide === {{ $loop->iteration }} ? 'Gabung Elite Sekarang' : 'Pilih Paket Ini'">Pilih Paket Ini</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full py-16 text-center">
                            <p class="font-heading text-lg font-bold text-slate-700">Belum ada paket membership aktif</p>
                            <p class="mt-2 text-sm text-slate-500">Silakan hubungi administrator gym untuk info paket terbaru.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Tombol Panah Kanan (Ubah posisinya agar lebih keluar) -->
                <button
                    type="button"
                    @click="scrollNext()"
                    aria-label="Geser ke paket berikutnya"
                    class="absolute -right-4 md:right-2 top-1/2 z-30 -translate-y-1/2 bg-white text-gray-800 shadow-xl rounded-full p-4 hover:bg-gray-50 focus:outline-none transition-transform hover:scale-110 border border-gray-100"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
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
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 shadow-md shadow-[#BA0030]/30 rounded-full">
                        <rect width="36" height="36" rx="18" fill="#BA0030"/>
                        <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="white"/>
                    </svg>
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
