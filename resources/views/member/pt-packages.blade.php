<x-fitcore-layout :title="'Beli Paket Personal Trainer | FITCORE ATHLETIC'">

    {{-- HERO SECTION (Clean Crisp White) --}}
    <section class="bg-white border-b border-[#E5E7EB] py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#16151A] tracking-tight leading-tight">
                Beli Paket <span class="text-[#FF5722]">Personal</span><br>
                <span class="text-[#FF5722]">Trainer</span>
            </h1>
        </div>
    </section>

    {{-- MAIN SECTION (Background #F6F6F7) --}}
    <section class="bg-[#F6F6F7] py-12 md:py-16">
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

            {{-- Alert Jika Tidak Ada Paket Eligible --}}
            @if((isset($noEligiblePackages) && $noEligiblePackages && isset($activeMembership) && empty($isQuotaThresholdBlocked)) || (isset($packages) && $packages->isEmpty() && isset($activeMembership)))
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

            {{-- Alert Jika Kuota Sesi Masih di atas Threshold (> 2 Sesi) --}}
            @if(isset($isQuotaThresholdBlocked) && $isQuotaThresholdBlocked)
                <div class="bg-amber-50/90 border border-amber-200 text-[#16151A] p-6 rounded-3xl mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-black text-sm sm:text-base text-[#16151A]">Sisa Kuota Sesi Latihan Anda Masih Tersedia ({{ $activeRemainingSessions }} Sesi)</h4>
                            <p class="text-xs text-[#565A66] mt-0.5">
                                Pembelian paket sesi PT baru dapat dilakukan kembali saat sisa kuota aktif Anda maksimal <strong>2 sesi</strong>. Silakan gunakan sisa sesi latihan Anda terlebih dahulu.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('pt-sessions.index') }}" class="px-5 py-2.5 bg-[#16151A] hover:bg-[#FF5722] text-white font-bold text-xs rounded-xl transition shadow-xs shrink-0">
                        Lihat Jadwal Latihan &rarr;
                    </a>
                </div>
            @endif

            {{-- Smart Renewal Banner: Pelatih Anda Saat Ini --}}
            @if(isset($currentTrainer) && $currentTrainer)
                @php
                    $coachFirstName = explode(' ', trim(str_replace('Coach ', '', $currentTrainer->name)))[0];
                    $cPhotoCurrent = file_exists(public_path("images/trainers/coach-" . strtolower($coachFirstName) . ".jpg"))
                        ? asset("images/trainers/coach-" . strtolower($coachFirstName) . ".jpg")
                        : ($currentTrainer->trainerProfile?->photo_path ? asset('storage/'.$currentTrainer->trainerProfile->photo_path) : ($currentTrainer->avatar_url ?? null));
                @endphp
                <div class="bg-gradient-to-r from-orange-50 via-white to-amber-50 border border-orange-200/80 rounded-3xl p-5 sm:p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-5 shadow-xs">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-[#2C2828] border-2 border-[#FF5722] shrink-0 shadow-xs">
                            @if($cPhotoCurrent)
                                <img src="{{ $cPhotoCurrent }}" class="w-full h-full object-cover" alt="{{ $currentTrainer->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white font-black text-sm bg-[#FF5722]">
                                    {{ substr($coachFirstName, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black uppercase tracking-wider bg-[#FF5722] text-white px-2.5 py-0.5 rounded-full shadow-2xs">
                                    Pelatih Anda
                                </span>
                                <h3 class="font-extrabold text-base text-[#16151A]">{{ $currentTrainer->name }}</h3>
                            </div>
                            <p class="text-xs text-[#565A66] mt-1 leading-relaxed">
                                Anda sudah memiliki pelatih. Beli paket sesi tambahan untuk melanjutkan program latihan bersama <strong>{{ $currentTrainer->name }}</strong> tanpa perlu memilih ulang pelatih.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <a href="{{ route('member.pt-packages.select-trainer', ['package_id' => $packages->first()?->id ?? 1]) }}" class="text-xs font-bold text-[#565A66] hover:text-[#FF5722] hover:underline transition">
                            Ingin ganti pelatih? &rarr;
                        </a>
                    </div>
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

                <div class="inline-flex items-center gap-2 bg-orange-50 border border-orange-200 text-[#16151A] px-4 py-2 rounded-full shadow-xs self-start sm:self-center">
                    <span class="w-4 h-4 rounded-full bg-[#FF5722] text-white flex items-center justify-center text-[10px] font-black">✓</span>
                    <span class="text-xs font-extrabold tracking-wide text-[#16151A]">1 sesi = 60 menit</span>
                </div>
            </div>

            {{-- GRID PAKET (3 Kolom di Desktop) --}}
            @php
                $defaultFigmaPackages = [
                    ['id' => '1', 'name' => 'PT 4 Sesi', 'sessions' => 4, 'price' => 1155000, 'original_price' => 1443750, 'per_session' => 288750, 'is_popular' => false, 'validity_days' => 30],
                    ['id' => '2', 'name' => 'PT 8 Sesi', 'sessions' => 8, 'price' => 2072700, 'original_price' => 2590875, 'per_session' => 259088, 'is_popular' => false, 'validity_days' => 45],
                    ['id' => '3', 'name' => 'PT 16 Sesi', 'sessions' => 16, 'price' => 3582000, 'original_price' => 4515000, 'per_session' => 223875, 'is_popular' => true, 'validity_days' => 60],
                    ['id' => '4', 'name' => 'PT 24 Sesi', 'sessions' => 24, 'price' => 5040000, 'original_price' => 6300000, 'per_session' => 210000, 'is_popular' => false, 'validity_days' => 90],
                    ['id' => '5', 'name' => 'PT 36 Sesi', 'sessions' => 36, 'price' => 6804000, 'original_price' => 8505000, 'per_session' => 189000, 'is_popular' => false, 'validity_days' => 120],
                    ['id' => '6', 'name' => 'PT 48 Sesi', 'sessions' => 48, 'price' => 8316000, 'original_price' => 10395000, 'per_session' => 173250, 'is_popular' => false, 'validity_days' => 150],
                    ['id' => '7', 'name' => 'PT 72 Sesi', 'sessions' => 72, 'price' => 12168000, 'original_price' => 15210000, 'per_session' => 169000, 'is_popular' => false, 'validity_days' => 180],
                    ['id' => '8', 'name' => 'PT 96 Sesi', 'sessions' => 96, 'price' => 15840000, 'original_price' => 19800000, 'per_session' => 165000, 'is_popular' => false, 'validity_days' => 240],
                ];

                $displayPackages = $defaultFigmaPackages;
                if (isset($packages) && $packages->isNotEmpty()) {
                    $customPackages = [];
                    foreach ($packages as $pkg) {
                        $sessions = (int) ($pkg->pt_session_count ?? 10);
                        $price = (float) $pkg->price;
                        $perSession = round($price / max(1, $sessions));
                        $isPopular = ($sessions === 16);
                        $minDays = (int) ($pkg->validity_days ?? 0);
                        $isEligible = isset($pkg->is_eligible) ? (bool) $pkg->is_eligible : (($minDays === 0) || ($activeMembership && $remainingDays >= $minDays));

                        $customPackages[] = [
                            'id' => $pkg->id,
                            'name' => $pkg->name,
                            'sessions' => $sessions,
                            'price' => $price,
                            'per_session' => $perSession,
                            'is_popular' => $isPopular,
                            'min_days' => $minDays,
                            'validity_days' => (int) ($pkg->validity_days ?? 30),
                            'is_eligible' => $isEligible,
                            'projected_end_date' => $pkg->projected_end_date ?? null,
                            'is_membership_extension_needed' => (bool) ($pkg->is_membership_extension_needed ?? false),
                            'disabled_label' => $pkg->disabled_label ?? ($minDays > 0 ? "Min. Membership {$minDays} Hari" : 'Wajib Membership Aktif'),
                            'disabled_title' => $pkg->disabled_title ?? ($minDays > 0 ? "Min. Membership {$minDays} Hari" : 'Wajib Membership Aktif'),
                        ];
                    }
                    $displayPackages = $customPackages;
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($displayPackages as $pkg)
                    @php
                        $isPopular = !empty($pkg['is_popular']);
                        $userQuota = isset($myQuotas) ? $myQuotas->where('status', 'active')->where('remaining_sessions', '>', 0)->firstWhere('pt_package_id', $pkg['id']) : null;
                    @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-7 flex flex-col justify-between relative transition-all duration-300 {{ $isPopular ? 'border-2 border-[#FF5722] shadow-[0_12px_36px_rgba(255,87,34,0.16)] md:-translate-y-1' : 'border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] hover:shadow-lg hover:border-[#FF5722]/40' }}">
                        
                        {{-- Floating Badge Populer (Blaze Orange + Golden Amber Gradient) --}}
                        @if($isPopular)
                            <div class="absolute -top-3.5 right-6 bg-gradient-to-r from-[#FF5722] to-[#FFB703] text-white text-[11px] font-black tracking-wider uppercase px-3.5 py-1 rounded-full shadow-md shadow-[#FF5722]/30 flex items-center gap-1.5">
                                <span>🔥</span>
                                <span>PALING POPULER</span>
                            </div>
                        @endif

                        <div>
                            {{-- Header Paket: Angka Sesi Blaze Orange (#FF5722) & Masa Aktif --}}
                            <div class="flex items-baseline justify-between gap-2">
                                <div class="flex items-baseline">
                                    <span class="text-4xl sm:text-5xl font-black tracking-tight text-[#FF5722]">
                                        {{ $pkg['sessions'] }}
                                    </span>
                                    <span class="text-lg sm:text-xl font-bold ml-1.5 text-[#FF5722]">
                                        Sesi
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-100 text-[#565A66] text-xs font-bold shrink-0">
                                    Masa Aktif {{ $pkg['validity_days'] ?? 30 }} Hari
                                </span>
                            </div>

                            {{-- Nama Paket & Kuota Aktif --}}
                            <div class="flex items-center justify-between gap-2 mt-1">
                                <span class="text-xs font-bold text-[#565A66] uppercase tracking-wider">
                                    {{ $pkg['name'] ?? ('PT ' . $pkg['sessions'] . ' Sesi') }}
                                </span>
                                @if($userQuota)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full shrink-0">
                                        ✓ Sisa {{ $userQuota->remaining_sessions }} Sesi
                                    </span>
                                @endif
                            </div>

                            {{-- Total Harga --}}
                            <div class="mt-4">
                                <div class="text-base font-black text-[#16151A]">
                                    Rp {{ number_format($pkg['price'], 0, ',', '.') }}
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
                        @if ($pkg['is_eligible'] ?? true)
                            @if(isset($currentTrainer) && $currentTrainer)
                                <a href="{{ route('member.pt-packages.checkout', ['package_id' => $pkg['id'], 'trainer_id' => $currentTrainer->id]) }}" 
                                   class="mt-6 w-full py-3.5 rounded-xl font-extrabold text-xs sm:text-sm text-center block transition-all duration-200 active:scale-[0.99] bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-[#FF5722]/25">
                                    Lanjut dengan {{ $currentTrainer->name }} &rarr;
                                </a>
                                <a href="{{ route('member.pt-packages.select-trainer', ['package_id' => $pkg['id'], 'sessions' => $pkg['sessions']]) }}" 
                                   class="mt-2.5 text-center block text-[11px] font-bold text-[#565A66] hover:text-[#FF5722] transition">
                                    atau pilih pelatih lain
                                </a>
                            @else
                                <a href="{{ route('member.pt-packages.select-trainer', ['package_id' => $pkg['id'], 'sessions' => $pkg['sessions']]) }}" 
                                   class="mt-6 w-full py-3.5 rounded-xl font-extrabold text-xs sm:text-sm text-center block transition-all duration-200 active:scale-[0.99] {{ $isPopular ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-[#FF5722]/25' : 'bg-[#16151A] hover:bg-[#FF5722] text-white shadow-xs' }}">
                                    {{ $userQuota ? 'Beli Sesi Tambahan' : ($isPopular ? 'Paket Terpilih' : 'Pilih Paket') }}
                                </a>
                            @endif
                        @else
                            <button type="button" disabled
                               class="mt-6 w-full py-3.5 rounded-xl font-extrabold text-xs sm:text-sm text-center block bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed select-none shadow-none"
                               title="{{ $pkg['disabled_title'] ?? ($pkg['min_days'] > 0 ? 'Min. Membership ' . $pkg['min_days'] . ' Hari' : 'Wajib Membership Aktif') }}">
                                {{ $pkg['disabled_label'] ?? ($pkg['min_days'] > 0 ? 'Min. Membership ' . $pkg['min_days'] . ' Hari' : 'Wajib Membership Aktif') }}
                            </button>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>
    </section>

</x-fitcore-layout>
