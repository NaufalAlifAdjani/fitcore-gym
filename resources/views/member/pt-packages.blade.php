<x-fitcore-layout :title="'Beli Paket Personal Trainer | FITCORE ATHLETIC'">

<<<<<<< HEAD
    {{-- HERO SECTION (Background Putih) --}}
    <section class="bg-white border-b border-[#E5E7EB] py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#16151A] tracking-tight leading-tight">
                Booking Sesi <span class="text-[#ED1B45]">Personal</span><br>
                <span class="text-[#ED1B45]">Trainer</span>
=======
    {{-- HERO SECTION (Clean Crisp White) --}}
    <section class="bg-white border-b border-[#E5E7EB] py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#16151A] tracking-tight leading-tight">
                Beli Paket <span class="text-[#FF5722]">Personal</span><br>
                <span class="text-[#FF5722]">Trainer</span>
>>>>>>> origin/feature/theme-blaze-orange
            </h1>
        </div>
    </section>

<<<<<<< HEAD
    {{-- MAIN SECTION (Background #F6F6F7) --}}
    <section class="bg-[#F6F6F7] py-12 md:py-16">
=======
    {{-- MAIN SECTION (Light Gray #F8F9FA) --}}
    <section class="bg-[#F8F9FA] py-12 md:py-16">
>>>>>>> origin/feature/theme-blaze-orange
        <div class="max-w-7xl mx-auto px-6 md:px-12">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm font-semibold shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-sm font-semibold shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Alert Jika Tidak Ada Paket Eligible (Syarat Test 10) --}}
            @if(isset($packages) && $packages->isEmpty() && isset($activeMembership))
                <div class="bg-amber-50/80 border border-amber-200 text-[#16151A] p-6 rounded-2xl mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#FFB703] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h4 class="font-extrabold text-base text-[#16151A]">Sisa Masa Aktif Membership Tidak Mencukupi</h4>
                            <p class="text-xs text-[#565A66] mt-0.5">Sisa masa aktif membership Anda ({{ $remainingDays }} hari) kurang dari syarat minimum untuk mengambil paket PT yang tersedia.</p>
                        </div>
                    </div>
                    <a href="{{ route('member.membership.packages') }}" class="px-5 py-2.5 bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold text-xs rounded-xl transition shadow-xs shrink-0">
                        Perpanjang Membership Sekarang
                    </a>
                </div>
            @endif

            {{-- HEADER BARIS --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-[#16151A] tracking-tight">
                        Pilih sesi sesuai kebutuhanmu!
                    </h2>
                    <p class="text-xs sm:text-sm text-[#565A66] mt-1 font-inter">
                        Makin banyak sesi yang diambil, makin hemat harga per sesinya. Durasi aktif berlaku mulai sesi pertama.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 bg-[#FF5722]/10 border border-[#FF5722]/20 text-[#16151A] px-4 py-2 rounded-full shadow-xs self-start sm:self-center">
                    <span class="w-4 h-4 rounded-full bg-[#FF5722] text-white flex items-center justify-center text-[10px] font-black">✓</span>
                    <span class="text-xs font-extrabold tracking-wide text-[#16151A]">1 sesi = 60 menit</span>
                </div>
            </div>

            {{-- GRID PAKET (3 Kolom di Desktop) --}}
            @php
                $defaultFigmaPackages = [
                    ['id' => '1', 'name' => 'PT 4 Sesi', 'sessions' => 4, 'price' => 1155000, 'original_price' => 1443750, 'per_session' => 288750, 'is_popular' => false],
                    ['id' => '2', 'name' => 'PT 8 Sesi', 'sessions' => 8, 'price' => 2072700, 'original_price' => 2590875, 'per_session' => 259088, 'is_popular' => false],
                    ['id' => '3', 'name' => 'PT 16 Sesi', 'sessions' => 16, 'price' => 3582000, 'original_price' => 4515000, 'per_session' => 223875, 'is_popular' => true],
                    ['id' => '4', 'name' => 'PT 24 Sesi', 'sessions' => 24, 'price' => 5040000, 'original_price' => 6300000, 'per_session' => 210000, 'is_popular' => false],
                    ['id' => '5', 'name' => 'PT 36 Sesi', 'sessions' => 36, 'price' => 6804000, 'original_price' => 8505000, 'per_session' => 189000, 'is_popular' => false],
                    ['id' => '6', 'name' => 'PT 48 Sesi', 'sessions' => 48, 'price' => 8316000, 'original_price' => 10395000, 'per_session' => 173250, 'is_popular' => false],
                    ['id' => '7', 'name' => 'PT 72 Sesi', 'sessions' => 72, 'price' => 12168000, 'original_price' => 15210000, 'per_session' => 169000, 'is_popular' => false],
                    ['id' => '8', 'name' => 'PT 96 Sesi', 'sessions' => 96, 'price' => 15840000, 'original_price' => 19800000, 'per_session' => 165000, 'is_popular' => false],
                ];

                $displayPackages = $defaultFigmaPackages;
                if (isset($packages) && $packages->isNotEmpty()) {
                    $customPackages = [];
                    foreach ($packages as $pkg) {
                        $sessions = (int) ($pkg->pt_session_count ?? 10);
                        $price = (float) $pkg->price;
                        $originalPrice = round($price * 1.25);
                        $perSession = round($price / max(1, $sessions));
                        $isPopular = ($sessions === 16);

                        $customPackages[] = [
                            'id' => $pkg->id,
                            'name' => $pkg->name,
                            'sessions' => $sessions,
                            'price' => $price,
                            'original_price' => $originalPrice,
                            'per_session' => $perSession,
                            'is_popular' => $isPopular,
                        ];
                    }
                    $displayPackages = $customPackages;
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($displayPackages as $pkg)
<<<<<<< HEAD
                    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-[0_4px_12px_rgba(0,0,0,0.04)] border border-[#E5E7EB] flex flex-col justify-between relative hover:shadow-lg transition-all duration-200">
                        
                        {{-- Floating Badge Populer --}}
                        @if(!empty($pkg['is_popular']))
                            <div class="absolute -top-3 right-4 bg-[#ED1B45] text-white text-[11px] font-black tracking-wider uppercase px-3 py-1 rounded-full shadow-md shadow-[#ED1B45]/20 flex items-center gap-1">
=======
                    @php
                        $isPopular = !empty($pkg['is_popular']);
                        $userQuota = isset($myQuotas) ? $myQuotas->where('status', 'active')->where('remaining_sessions', '>', 0)->firstWhere('pt_package_id', $pkg['id']) : null;
                    @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-7 flex flex-col justify-between relative transition-all duration-300 {{ $isPopular ? 'border-2 border-[#FF5722] shadow-[0_12px_36px_rgba(255,87,34,0.16)] md:-translate-y-1' : 'border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] hover:shadow-lg hover:border-[#FF5722]/40' }}">
                        
                        {{-- Floating Badge Populer (Blaze Orange + Golden Amber Gradient) --}}
                        @if($isPopular)
                            <div class="absolute -top-3.5 right-6 bg-gradient-to-r from-[#FF5722] to-[#FFB703] text-white text-[11px] font-black tracking-wider uppercase px-3.5 py-1 rounded-full shadow-md shadow-[#FF5722]/30 flex items-center gap-1.5">
>>>>>>> origin/feature/theme-blaze-orange
                                <span>🔥</span>
                                <span>PALING POPULER</span>
                            </div>
                        @endif

                        <div>
                            {{-- Header Paket: Angka Sesi Blaze Orange (#FF5722) --}}
                            <div class="flex items-baseline">
<<<<<<< HEAD
                                <span class="text-4xl sm:text-5xl font-black tracking-tight text-[#00A8E8]">
                                    {{ $pkg['sessions'] }}
                                </span>
                                <span class="text-lg sm:text-xl font-bold ml-1.5 text-[#00A8E8]">
=======
                                <span class="text-4xl sm:text-5xl font-black tracking-tight text-[#FF5722]">
                                    {{ $pkg['sessions'] }}
                                </span>
                                <span class="text-lg sm:text-xl font-bold ml-1.5 text-[#FF5722]">
>>>>>>> origin/feature/theme-blaze-orange
                                    Sesi
                                </span>
                            </div>

                            {{-- Nama Paket --}}
                            <div class="flex items-center justify-between gap-2 mt-1">
<<<<<<< HEAD
                                <span class="text-xs font-bold text-[#565A66]">
=======
                                <span class="text-xs font-bold text-[#565A66] uppercase tracking-wider">
>>>>>>> origin/feature/theme-blaze-orange
                                    {{ $pkg['name'] ?? ('PT ' . $pkg['sessions'] . ' Sesi') }}
                                </span>
                                @if($userQuota)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full shrink-0">
                                        ✓ Sisa {{ $userQuota->remaining_sessions }} Sesi
                                    </span>
                                @endif
                            </div>

                            {{-- Total Harga Aktif & Coret --}}
                            <div class="mt-4">
                                <div class="text-base font-black text-[#16151A]">
                                    Rp {{ number_format($pkg['price'], 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-[#565A66]/60 line-through mt-0.5">
                                    Rp {{ number_format($pkg['original_price'], 0, ',', '.') }}
                                </div>
                            </div>

                            <hr class="my-5 border-[#E5E7EB]">

                            {{-- Harga Per Sesi --}}
                            <div>
                                <div class="text-2xl font-black text-[#16151A] tracking-tight">
                                    Rp {{ number_format($pkg['per_session'], 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-[#565A66] font-semibold mt-0.5">
                                    / Sesi
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Pilih Paket --}}
                        <a href="{{ route('member.pt-packages.select-trainer', ['package_id' => $pkg['id'], 'sessions' => $pkg['sessions']]) }}" 
<<<<<<< HEAD
                           class="mt-6 w-full py-3.5 rounded-xl font-extrabold text-xs sm:text-sm text-[#16151A] bg-[#F6F6F7] hover:bg-[#ED1B45] hover:text-white border border-[#E5E7EB] hover:border-[#ED1B45] active:scale-[0.99] transition text-center block shadow-2xs">
                            {{ $userQuota ? 'Beli Sesi Tambahan' : (!empty($pkg['is_popular']) ? 'Paket Terpilih' : 'Pilih Paket') }}
=======
                           class="mt-6 w-full py-3.5 rounded-xl font-extrabold text-xs sm:text-sm text-center block transition-all duration-200 active:scale-[0.99] {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-[#FF5722]/25' : 'bg-[#16151A] hover:bg-[#FF5722] text-white shadow-xs' }}">
                            {{ $userQuota ? 'Beli Sesi Tambahan' : ($isPopular ? 'Paket Terpilih' : 'Pilih Paket') }}
>>>>>>> origin/feature/theme-blaze-orange
                        </a>

                    </div>
                @endforeach
            </div>

        </div>
    </section>

</x-fitcore-layout>
