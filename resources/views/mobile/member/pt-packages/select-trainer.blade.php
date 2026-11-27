{{--
ROLE: MEMBER
MODUL: Pilih Personal Trainer Mobile (Langkah 2)
VIEW: mobile.member.pt-packages.select-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-app title="Pilih Personal Trainer - FitCore Athletic" active-tab="packages">
    <div x-data="{
        searchQuery: '',
        selectedPeriod: 'all',
        matches(trainer) {
            if (this.selectedPeriod !== 'all' && (!trainer.periods || !trainer.periods.includes(this.selectedPeriod))) {
                return false;
            }
            if (!this.searchQuery.trim()) return true;
            const q = this.searchQuery.toLowerCase().trim();
            return (trainer.name && trainer.name.toLowerCase().includes(q)) || 
                   (trainer.specialization && trainer.specialization.toLowerCase().includes(q)) ||
                   (trainer.days_label && trainer.days_label.toLowerCase().includes(q));
        },
        get visibleCount() {
            const trainers = {{ Js::from($trainers) }};
            return trainers.filter(t => this.matches(t)).length;
        }
    }" class="space-y-4">

        <!-- 1. Header & Back Navigation -->
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
            <a href="{{ route('mobile.member.pt-packages.index') }}"
               class="p-1.5 text-[#565A66] hover:text-[#16151A] transition flex items-center gap-1 cursor-pointer"
               aria-label="Kembali ke Pilih Paket">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-xs font-semibold">Pilih Paket</span>
            </a>
            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-[#FF5722] border border-orange-200">
                Langkah 2 dari 4
            </span>
        </div>

        <!-- 2. Judul Halaman -->
        <div class="space-y-1">
            <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                Pilih Personal Trainer
            </h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Pilih pelatih profesional bersertifikasi untuk mendampingi target kebugaran Anda.
            </p>
        </div>

        <!-- Pelatih Anda Saat Ini (Smart Renewal) -->
        @if(isset($currentTrainer) && $currentTrainer)
            @php
                $coachFirstName = explode(' ', trim(str_replace('Coach ', '', $currentTrainer->name)))[0];
                $cPhotoCurrent = file_exists(public_path("images/trainers/coach-" . strtolower($coachFirstName) . ".jpg"))
                    ? asset("images/trainers/coach-" . strtolower($coachFirstName) . ".jpg")
                    : ($currentTrainer->trainerProfile?->photo_path ? asset('storage/'.$currentTrainer->trainerProfile->photo_path) : ($currentTrainer->avatar_url ?? null));
            @endphp
            <div class="p-3.5 bg-gradient-to-r from-orange-50 via-white to-amber-50 border-2 border-[#FF5722]/50 rounded-2xl shadow-xs space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-[#2C2828] border-2 border-[#FF5722] shrink-0 shadow-2xs">
                        @if($cPhotoCurrent)
                            <img src="{{ $cPhotoCurrent }}" class="w-full h-full object-cover" alt="{{ $currentTrainer->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white font-black text-sm bg-[#FF5722]">
                                {{ substr($coachFirstName, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="inline-block text-[9px] font-black uppercase tracking-wider bg-[#FF5722] text-white px-2 py-0.5 rounded-full">
                            ★ Pelatih Anda Saat Ini
                        </span>
                        <h3 class="font-extrabold text-sm text-[#16151A] truncate mt-0.5">{{ $currentTrainer->name }}</h3>
                        <p class="text-[10px] text-[#565A66] truncate">{{ $currentTrainer->trainerProfile?->specialization ?? 'Personal Trainer' }}</p>
                    </div>
                </div>
                <a href="{{ route('mobile.member.pt-packages.checkout', ['package_id' => $packageId, 'trainer_id' => $currentTrainer->id]) }}"
                   class="w-full py-2.5 px-3 bg-[#FF5722] hover:bg-[#E64A19] text-white text-center font-bold text-xs rounded-xl shadow-xs transition block active:scale-95">
                    Lanjut bersama {{ $currentTrainer->name }} &rarr;
                </a>
            </div>

            <div class="relative py-2 text-center">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[#E5E7EB]"></div></div>
                <span class="relative bg-[#F6F6F7] px-3 text-[10px] font-bold uppercase tracking-wider text-[#565A66]">
                    Atau Pilih Pelatih Lain
                </span>
            </div>
        @endif

        <!-- 3. Search Bar & Filter Waktu (Mobile Ergonomics) -->
        <div class="space-y-2.5">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#565A66]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari nama pelatih atau spesialisasi..."
                       class="w-full pl-10 pr-9 py-2.5 bg-white border border-[#E5E7EB] rounded-xl text-xs font-medium placeholder-[#565A66]/60 text-[#16151A] shadow-xs focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition">
                <button type="button"
                        x-show="searchQuery.length > 0"
                        @click="searchQuery = ''"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#565A66] hover:text-[#16151A] transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Horizontal Scroll Filter Chips -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
                <button type="button"
                        @click="selectedPeriod = 'all'"
                        :class="selectedPeriod === 'all' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition text-xs whitespace-nowrap cursor-pointer">
                    Semua Waktu
                </button>
                <button type="button"
                        @click="selectedPeriod = 'morning'"
                        :class="selectedPeriod === 'morning' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition text-xs whitespace-nowrap cursor-pointer">
                    Sedia Pagi
                </button>
                <button type="button"
                        @click="selectedPeriod = 'afternoon'"
                        :class="selectedPeriod === 'afternoon' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition text-xs whitespace-nowrap cursor-pointer">
                    Sedia Siang
                </button>
                <button type="button"
                        @click="selectedPeriod = 'evening'"
                        :class="selectedPeriod === 'evening' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition text-xs whitespace-nowrap cursor-pointer">
                    Sedia Malam
                </button>
            </div>
        </div>

        <!-- 4. Daftar Kartu Pelatih (Mobile Native Card) -->
        <div class="space-y-4">
            @foreach($trainers as $trainer)
                <div x-show="matches({{ Js::from($trainer) }})"
                     x-transition
                     class="rounded-2xl overflow-hidden border border-[#E5E7EB] bg-white shadow-[0_4px_12px_rgba(0,0,0,0.06)] flex flex-col group">
                    
                    <!-- 1. Foto PT (Bagian Atas) -->
                    <div class="w-full aspect-[4/3] bg-[#2C2828] relative overflow-hidden flex items-center justify-center">
                        @if (!empty($trainer['photo']))
                            <img src="{{ $trainer['photo'] }}"
                                 alt="{{ $trainer['name'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-b from-[#383332] to-[#252222] flex flex-col items-center justify-center text-zinc-300 p-4">
                                <svg class="w-10 h-10 text-zinc-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-xs font-semibold tracking-wider uppercase text-zinc-400">Foto PT</span>
                            </div>
                        @endif
                    </div>

                    <!-- 2. Data Pelatih (Bagian Bawah) -->
                    <div class="p-4 bg-[#F6F6F7] flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1.5">
                            <!-- Nama -->
                            <h3 class="font-poppins font-black text-base text-[#16151A] leading-snug">
                                {{ $trainer['name'] }}
                            </h3>

                            <!-- Specialization -->
                            <div class="text-xs font-bold text-[#FF5722]">
                                {{ $trainer['specialization'] }}
                            </div>

                            <!-- Experience -->
                            <div class="text-xs font-semibold text-[#565A66] flex items-center gap-1.5 pt-0.5">
                                <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $trainer['experience'] ?? 5 }}+ Tahun Jam Terbang</span>
                            </div>

                            <!-- Bio -->
                            <p class="text-xs text-[#565A66] leading-relaxed line-clamp-3 pt-0.5">
                                {{ $trainer['bio'] ?? 'Berpengalaman mendampingi member mencapai target kebugaran dan kekuatan atletik secara optimal.' }}
                            </p>

                            <!-- Ketersediaan Waktu -->
                            <div class="mt-2.5 pt-2.5 border-t border-gray-200/80 space-y-1 text-[11px] text-[#565A66]">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <span class="font-semibold text-[#16151A]">{{ $trainer['periods_label'] ?? 'Pagi – Malam' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-gray-400">
                                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                    </svg>
                                    <span>{{ $trainer['days_label'] ?? 'Senin – Sabtu' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Pilih Pelatih -->
                        <div class="pt-2 border-t border-[#E5E7EB]">
                            <a href="{{ route('mobile.member.pt-packages.checkout', [
                                    'package_id' => $packageId,
                                    'trainer_id' => $trainer['id'],
                                    'sessions' => $sessionCount
                                ]) }}"
                               class="w-full py-2.5 px-4 rounded-xl font-bold font-poppins text-xs text-white bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] transition text-center shadow-md shadow-orange-500/20 block cursor-pointer">
                                Pilih Pelatih &rarr;
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- 5. Empty State Ketika Pencarian Tidak Ditemukan -->
        <div x-show="visibleCount === 0"
             x-cloak
             class="bg-white rounded-2xl p-8 text-center border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-2">
            <div class="w-12 h-12 rounded-full bg-orange-50 text-[#FF5722] mx-auto flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h4 class="text-sm font-black font-poppins text-[#16151A]">Pelatih Tidak Ditemukan</h4>
            <p class="text-xs text-[#565A66]">
                Tidak ada pelatih yang cocok dengan filter atau kata kunci "<span x-text="searchQuery" class="font-bold text-[#16151A]"></span>".
            </p>
            <button type="button"
                    @click="searchQuery = ''; selectedPeriod = 'all'"
                    class="mt-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-[#16151A] font-bold text-xs rounded-xl transition cursor-pointer">
                Reset Pencarian
            </button>
        </div>

    </div>
</x-layouts.mobile-app>

