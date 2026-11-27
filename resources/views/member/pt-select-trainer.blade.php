<x-fitcore-layout :title="'Pilih Personal Trainer | FITCORE ATHLETIC'">

    <section class="bg-[#F6F6F7] min-h-[calc(100vh-80px-350px)]">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-10 md:py-14" x-data="{
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
        }">

            {{-- TOMBOL KEMBALI KE LANGKAH 1 --}}
            <div class="mb-6">
                <a href="{{ route('member.pt-packages.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-[#565A66] bg-white hover:bg-gray-50 hover:text-[#16151A] border border-[#E5E7EB] rounded-full shadow-2xs transition active:scale-95 group">
                    <svg class="w-4 h-4 text-[#565A66] group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>&larr; Kembali ke Pilih Paket Sesi</span>
                </a>
            </div>

            {{-- HEADER JUDUL --}}
            <h1 class="text-3xl sm:text-4xl font-black text-[#16151A] tracking-tight uppercase">
                PILIH PERSONAL TRAINER ANDA
            </h1>
            <p class="text-xs sm:text-sm text-[#565A66] mt-1.5">
                Pilih pelatih profesional berlisensi untuk mendampingi target kebugaran Anda.
            </p>

            {{-- PELATIH ANDA SAAT INI (SMART RENEWAL) --}}
            @if(isset($currentTrainer) && $currentTrainer)
                @php
                    $coachFirst = explode(' ', trim(str_replace('Coach ', '', $currentTrainer->name)))[0];
                    $cPhotoCurr = file_exists(public_path("images/trainers/coach-" . strtolower($coachFirst) . ".jpg"))
                        ? asset("images/trainers/coach-" . strtolower($coachFirst) . ".jpg")
                        : ($currentTrainer->trainerProfile?->photo_path ? asset('storage/'.$currentTrainer->trainerProfile->photo_path) : ($currentTrainer->avatar_url ?? null));
                    $currSummary = $currentTrainer->getWorkingScheduleSummary();
                @endphp
                <div class="mt-6 bg-gradient-to-r from-orange-50 via-white to-amber-50 border-2 border-[#FF5722]/40 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-[#2C2828] border-2 border-[#FF5722] shrink-0 shadow-md">
                            @if($cPhotoCurr)
                                <img src="{{ $cPhotoCurr }}" class="w-full h-full object-cover" alt="{{ $currentTrainer->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white font-black text-xl bg-[#FF5722]">
                                    {{ substr($coachFirst, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#FF5722] text-white shadow-2xs">
                                ★ Pelatih Anda Saat Ini
                            </div>
                            <h2 class="text-lg sm:text-xl font-black text-[#16151A] mt-1">{{ $currentTrainer->name }}</h2>
                            <p class="text-xs text-[#565A66] mt-0.5">
                                {{ $currentTrainer->trainerProfile?->specialization ?? 'Personal Trainer' }} &bull; Jadwal: {{ $currSummary['days_label'] }} ({{ $currSummary['periods_label'] }})
                            </p>
                            <p class="text-[11px] text-[#FF5722] font-semibold mt-1">
                                Klik tombol di samping untuk langsung melanjutkan pesanan bersama pelatih ini.
                            </p>
                        </div>
                    </div>
                    <div class="w-full md:w-auto shrink-0">
                        <a href="{{ route('member.pt-packages.checkout', ['package_id' => $packageId, 'trainer_id' => $currentTrainer->id]) }}" 
                           class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#FF5722] hover:bg-[#E64A19] text-white font-extrabold text-xs sm:text-sm rounded-xl text-center shadow-md shadow-[#FF5722]/30 transition active:scale-95">
                            <span>Lanjut bersama {{ $currentTrainer->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <div class="relative my-8 text-center">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[#E5E7EB]"></div></div>
                    <span class="relative bg-[#F6F6F7] px-4 text-xs font-bold uppercase tracking-wider text-[#565A66]">
                        Atau Pilih Pelatih Lain Di Bawah Ini
                    </span>
                </div>
            @endif

            {{-- SEARCH BAR & FILTER WAKTU --}}
            <div class="mt-6 flex flex-col gap-3">
                <div class="max-w-md">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#565A66]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               x-model="searchQuery"
                               placeholder="Cari nama atau spesialis..." 
                               class="w-full pl-11 pr-10 py-3 bg-white border border-[#E5E7EB] rounded-full text-xs font-semibold placeholder-[#565A66]/60 text-[#16151A] shadow-xs focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition">
                        <button type="button" 
                                x-show="searchQuery.length > 0" 
                                @click="searchQuery = ''" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#565A66] hover:text-[#16151A] transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Quick Filter Preferensi Waktu Latihan --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-[#565A66] mr-1">Preferensi Waktu:</span>
                    <button type="button" 
                            @click="selectedPeriod = 'all'" 
                            :class="selectedPeriod === 'all' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] hover:bg-gray-100 border border-[#E5E7EB]'" 
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer">
                        Semua Waktu
                    </button>
                    <button type="button" 
                            @click="selectedPeriod = 'morning'" 
                            :class="selectedPeriod === 'morning' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] hover:bg-gray-100 border border-[#E5E7EB]'" 
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer">
                        Sedia Pagi
                    </button>
                    <button type="button" 
                            @click="selectedPeriod = 'afternoon'" 
                            :class="selectedPeriod === 'afternoon' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] hover:bg-gray-100 border border-[#E5E7EB]'" 
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer">
                        Sedia Siang
                    </button>
                    <button type="button" 
                            @click="selectedPeriod = 'evening'" 
                            :class="selectedPeriod === 'evening' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] hover:bg-gray-100 border border-[#E5E7EB]'" 
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer">
                        Sedia Malam
                    </button>
                </div>
            </div>

            {{-- GRID PELATIH (4 Kolom di Desktop) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($trainers as $trainer)
                    <div x-show="matches({{ Js::from($trainer) }})"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="rounded-2xl overflow-hidden shadow-[0_4px_12px_rgba(0,0,0,0.04)] border border-[#E5E7EB] flex flex-col bg-white hover:shadow-lg transition duration-200 group">
                        
                        {{-- 1. Foto PT (Bagian Atas) --}}
                        <div class="aspect-[4/3] w-full overflow-hidden bg-[#2C2828] relative flex items-center justify-center">
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

                        {{-- 2. Data Pelatih (Bagian Bawah) --}}
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4 {{ !empty($trainer['is_active_highlight']) ? 'bg-orange-50/70 border-t border-orange-200' : 'bg-[#F6F6F7] border-t border-[#E5E7EB]' }}">
                            
                            <div class="space-y-2">
                                <!-- Nama -->
                                <h3 class="text-base sm:text-lg font-black text-[#16151A] leading-snug font-poppins">
                                    {{ $trainer['name'] }}
                                </h3>

                                <!-- Specialization -->
                                <div class="text-xs font-bold text-[#FF5722]">
                                    {{ $trainer['specialization'] }}
                                </div>

                                <!-- Experience -->
                                <div class="text-xs font-semibold text-[#565A66] flex items-center gap-1.5 pt-0.5">
                                    <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $trainer['experience'] ?? 5 }}+ Tahun Jam Terbang</span>
                                </div>

                                <!-- Bio -->
                                <p class="text-xs text-[#565A66] leading-relaxed line-clamp-3 pt-0.5">
                                    {{ $trainer['bio'] ?? 'Berpengalaman mendampingi member mencapai target kebugaran dan kekuatan atletik secara optimal.' }}
                                </p>

                                {{-- Badge Ketersediaan Jam Kerja PT --}}
                                <div class="mt-3.5 pt-3 border-t border-gray-200/80 flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-[#16151A]">
                                        <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                            <polyline points="12 6 12 12 16 14" stroke-width="2"/>
                                        </svg>
                                        <span>{{ $trainer['periods_label'] ?? 'Pagi – Malam' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] font-medium text-[#565A66]">
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"/>
                                            <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"/>
                                        </svg>
                                        <span>{{ $trainer['days_label'] ?? 'Senin – Sabtu' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Pilih Pelatih --}}
                            <div class="pt-2">
                                <a href="{{ route('member.pt-packages.checkout', [
                                        'package_id' => $packageId, 
                                        'trainer_id' => $trainer['id'], 
                                        'sessions' => $sessionCount
                                    ]) }}" 
                                   class="w-full py-3 px-4 rounded-full font-bold text-xs text-white bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] transition text-center shadow-md shadow-orange-500/20 block cursor-pointer">
                                    Pilih Pelatih &rarr;
                                </a>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pesan Ketika Tidak Ada Hasil Pencarian --}}
            <div x-show="visibleCount === 0" 
                 x-cloak 
                 class="bg-white rounded-2xl p-10 text-center border border-[#E5E7EB] mt-8 shadow-[0_4px_12px_rgba(0,0,0,0.04)] max-w-md mx-auto">
                <div class="w-12 h-12 rounded-full bg-orange-50 text-[#FF5722] mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-[#16151A]">Tidak ada pelatih yang cocok</h4>
                <p class="text-xs text-[#565A66] mt-1">Tidak ditemukan pelatih dengan kata kunci "<span x-text="searchQuery" class="font-bold text-[#16151A]"></span>".</p>
                <button type="button" @click="searchQuery = ''" class="mt-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-[#16151A] font-bold text-xs rounded-full transition cursor-pointer">
                    Reset Pencarian
                </button>
            </div>

        </div>
    </section>

</x-fitcore-layout>
