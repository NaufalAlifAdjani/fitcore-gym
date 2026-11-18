{{--
ROLE: MEMBER
MODUL: Katalog Pembelian Paket Personal Trainer Mobile
VIEW: mobile.member.pt-packages.index
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-app title="Beli Paket PT - FitCore Athletic" active-tab="packages">
    <div class="space-y-4">

        <!-- 1. Header Banner (Clean Light Theme) -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="font-poppins text-lg font-black text-[#16151A] tracking-tight">
                    Pilih Paket <span class="text-[#FF5722]">Personal Trainer</span>
                </h1>
                <p class="text-xs text-[#565A66] mt-1 leading-relaxed">
                    Makin banyak sesi yang diambil, makin hemat harga per sesinya. 1 sesi = 60 menit latihan terarah.
                </p>
            </div>
            <!-- Decorative Accent -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-[#FF5722]/5 blur-xl pointer-events-none"></div>
        </div>

        <!-- 2. Tab Switcher (Paket Gym vs Sesi PT) -->
        <div class="grid grid-cols-2 p-1 bg-white border border-[#E5E7EB] rounded-2xl shadow-xs">
            <a href="{{ route('mobile.member.packages') }}" class="py-2.5 text-center text-xs font-bold rounded-xl text-[#565A66] hover:text-[#FF5722] transition">
                Paket Gym
            </a>
            <a href="{{ route('mobile.member.pt-packages.index') }}" class="py-2.5 text-center text-xs font-bold rounded-xl bg-[#FF5722] text-white shadow-sm shadow-[#FF5722]/25 transition">
                Sesi PT
            </a>
        </div>

        <!-- 3. Alert Status Membership -->
        @if(isset($packages) && $packages->isEmpty() && isset($activeMembership))
            <div class="bg-amber-50 border border-amber-200 text-[#16151A] p-4 rounded-2xl space-y-2 shadow-xs">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="font-extrabold text-xs text-[#16151A]">Sisa Masa Aktif Tidak Mencukupi</h4>
                        <p class="text-[11px] text-[#565A66] mt-0.5 leading-relaxed">
                            Sisa masa aktif membership Anda ({{ $remainingDays }} hari) kurang dari syarat minimum untuk paket PT yang tersedia.
                        </p>
                    </div>
                </div>
                <a href="{{ route('mobile.member.packages') }}" class="block w-full py-2 bg-[#FF5722] text-white text-center font-bold text-xs rounded-xl shadow-xs hover:bg-[#E64A19] transition">
                    Perpanjang Membership
                </a>
            </div>
        @elseif(isset($activeMembership))
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-[11px] text-emerald-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="font-bold">Membership Aktif: {{ $activeMembership->package->name ?? 'Member' }}</span>
                </div>
                <span class="font-medium text-emerald-700">Sisa {{ $remainingDays }} Hari</span>
            </div>
        @else
            <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-800 flex items-start gap-2.5 shadow-xs">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div class="space-y-1">
                    <p class="font-bold">Membership Belum Aktif</p>
                    <p class="text-[11px] text-[#565A66] leading-relaxed">
                        Anda dapat memesan paket PT setelah memiliki keanggotaan gym yang aktif.
                    </p>
                    <a href="{{ route('mobile.member.packages') }}" class="inline-block mt-1 font-bold text-[#FF5722] hover:underline">
                        Beli Membership &rarr;
                    </a>
                </div>
            </div>
        @endif

        <!-- 4. Flash Messages -->
        @if (session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- 5. Perhitungan & Grid Daftar Paket PT -->
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

        <div class="grid grid-cols-2 gap-3 sm:gap-4 w-full">
            @foreach($displayPackages as $pkg)
                @php
                    $isPopular = !empty($pkg['is_popular']);
                    $userQuota = isset($myQuotas) ? $myQuotas->where('status', 'active')->where('remaining_sessions', '>', 0)->firstWhere('pt_package_id', $pkg['id']) : null;
                @endphp

                <div class="bg-white rounded-2xl p-3.5 sm:p-4 border {{ $isPopular ? 'border-[#FF5722] ring-2 ring-[#FF5722]/20 shadow-md' : 'border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]' }} flex flex-col justify-between relative overflow-hidden">
                    
                    {{-- Badge Populer --}}
                    @if($isPopular)
                        <div class="absolute -top-2.5 right-2 bg-gradient-to-r from-[#FF5722] to-[#FFB703] text-white text-[9px] font-black tracking-wider uppercase px-2 py-0.5 rounded-full shadow-xs">
                            POPULER
                        </div>
                    @endif

                    <div>
                        {{-- Jumlah Sesi --}}
                        <div class="flex items-baseline mt-1">
                            <span class="text-3xl font-black font-poppins tracking-tight text-[#FF5722]">
                                {{ $pkg['sessions'] }}
                            </span>
                            <span class="text-sm font-bold ml-1 text-[#FF5722]">
                                Sesi
                            </span>
                        </div>

                        {{-- Nama Paket & Quota Sisa --}}
                        <div class="mt-1">
                            <span class="text-[11px] font-bold text-[#16151A] block truncate">
                                {{ $pkg['name'] ?? ('PT ' . $pkg['sessions'] . ' Sesi') }}
                            </span>
                            @if($userQuota)
                                <span class="inline-block mt-1 text-[9px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-md">
                                    ✓ Sisa {{ $userQuota->remaining_sessions }} Sesi
                                </span>
                            @endif
                        </div>

                        {{-- Total Harga & Coret --}}
                        <div class="mt-3 pt-2.5 border-t border-[#E5E7EB]">
                            <div class="text-xs font-black text-[#16151A]">
                                Rp {{ number_format($pkg['price'], 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-[#565A66]/60 line-through">
                                Rp {{ number_format($pkg['original_price'], 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Harga Per Sesi --}}
                        <div class="mt-2 p-2 bg-[#F6F6F7] rounded-xl border border-[#E5E7EB]/80 text-center">
                            <span class="text-xs font-black text-[#16151A] block">
                                Rp {{ number_format($pkg['per_session'], 0, ',', '.') }}
                            </span>
                            <span class="text-[9px] text-[#565A66] font-medium block">
                                per sesi
                            </span>
                        </div>
                    </div>

                    {{-- Tombol Pilih Trainer & Checkout --}}
                    <div class="mt-3 pt-2 border-t border-[#E5E7EB]/60">
                        <a href="{{ route('mobile.member.pt-packages.select-trainer', ['package_id' => $pkg['id'], 'sessions' => $pkg['sessions']]) }}"
                           class="w-full py-2 px-2 rounded-xl text-center font-bold text-xs block transition active:scale-95 shadow-xs {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-[#FF5722]/20' : 'bg-[#16151A] hover:bg-[#FF5722] text-white' }}">
                            {{ $userQuota ? 'Beli Lagi' : 'Pilih Paket' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 6. Info Garansi & Layanan -->
        <div class="p-4 bg-white border border-[#E5E7EB] rounded-2xl shadow-xs space-y-2 text-center">
            <span class="text-xs font-bold text-[#16151A] block">Keunggulan Sesi Personal Trainer</span>
            <p class="text-[11px] text-[#565A66] leading-relaxed">
                Seluruh pelatih FitCore tersertifikasi internasional (NASM, CSCS). Program latihan dipersonalisasi sesuai postur, biomekanika, dan target kekuatan Anda.
            </p>
        </div>

    </div>
</x-layouts.mobile-app>

