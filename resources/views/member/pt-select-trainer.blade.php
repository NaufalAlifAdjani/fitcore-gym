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

<<<<<<< HEAD
            {{-- SEARCH BAR (Filter Rating dihapus karena tidak ada rating di database) --}}
            <div class="mt-6 max-w-md">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#565A66]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           x-model="searchQuery"
                           placeholder="Cari nama atau spesialis..." 
                           class="w-full pl-11 pr-10 py-3 bg-white border border-[#E5E7EB] rounded-full text-xs font-semibold placeholder-[#565A66]/60 text-[#16151A] shadow-xs focus:outline-none focus:ring-2 focus:ring-[#ED1B45] focus:border-transparent transition">
                    <button type="button" 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#565A66] hover:text-[#16151A] transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
=======
            {{-- SEARCH BAR & FILTER WAKTU --}}
            <div class="mt-6 flex flex-col gap-3">
                <div class="max-w-md">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               x-model="searchQuery"
                               placeholder="Cari nama atau spesialis..." 
                               class="w-full pl-11 pr-10 py-3 bg-white border border-gray-200 rounded-full text-xs font-semibold placeholder-gray-400 text-zinc-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition">
                        <button type="button" 
                                x-show="searchQuery.length > 0" 
                                @click="searchQuery = ''" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Quick Filter Preferensi Waktu Latihan --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-gray-500 mr-1">Preferensi Waktu:</span>
                    <button type="button" 
                            @click="selectedPeriod = 'all'" 
                            :class="selectedPeriod === 'all' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-zinc-700 hover:bg-gray-100 border border-gray-200'" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold transition">
                        Semua Waktu
                    </button>
                    <button type="button" 
                            @click="selectedPeriod = 'morning'" 
                            :class="selectedPeriod === 'morning' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-zinc-700 hover:bg-gray-100 border border-gray-200'" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold transition flex items-center gap-1">
                        <span>🌅 Sedia Pagi</span>
                    </button>
                    <button type="button" 
                            @click="selectedPeriod = 'afternoon'" 
                            :class="selectedPeriod === 'afternoon' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-zinc-700 hover:bg-gray-100 border border-gray-200'" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold transition flex items-center gap-1">
                        <span>☀️ Sedia Siang</span>
                    </button>
                    <button type="button" 
                            @click="selectedPeriod = 'evening'" 
                            :class="selectedPeriod === 'evening' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-zinc-700 hover:bg-gray-100 border border-gray-200'" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold transition flex items-center gap-1">
                        <span>🌙 Sedia Malam</span>
>>>>>>> origin/feature/theme-blaze-orange
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
                        
                        {{-- Foto Portrait Pelatih (Aspect 4/5) --}}
<<<<<<< HEAD
                        <div class="aspect-[4/5] w-full overflow-hidden bg-gray-100 relative">
=======
                        <div class="aspect-[4/5] w-full overflow-hidden bg-orange-50/60 relative">
>>>>>>> origin/feature/theme-blaze-orange
                            <img src="{{ $trainer['photo'] }}" 
                                 alt="{{ $trainer['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        </div>

                        {{-- Body Card --}}
<<<<<<< HEAD
                        <div class="p-5 flex-1 flex flex-col justify-between {{ !empty($trainer['is_active_highlight']) ? 'bg-[#ED1B45]/5 border-t border-[#ED1B45]/20' : 'bg-white border-t border-[#E5E7EB]' }}">
=======
                        <div class="p-5 flex-1 flex flex-col justify-between {{ !empty($trainer['is_active_highlight']) ? 'bg-orange-50/70 border-t border-orange-200' : 'bg-white border-t border-gray-100' }}">
>>>>>>> origin/feature/theme-blaze-orange
                            
                            <div class="mb-4">
                                <h3 class="text-base sm:text-lg font-black text-[#16151A] leading-snug">
                                    {{ $trainer['name'] }}
                                </h3>
                                <p class="text-xs text-[#565A66] font-semibold mt-1">
                                    {{ $trainer['specialization'] }}
                                </p>

                                {{-- Badge Ketersediaan Jam Kerja PT --}}
                                <div class="mt-3.5 pt-3 border-t border-gray-100/90 flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-zinc-800">
                                        <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                            <polyline points="12 6 12 12 16 14" stroke-width="2"/>
                                        </svg>
                                        <span>{{ $trainer['periods_label'] ?? 'Pagi – Malam' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] font-medium text-gray-500">
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
                            <a href="{{ route('member.pt-packages.checkout', [
                                    'package_id' => $packageId, 
                                    'trainer_id' => $trainer['id'], 
                                    'sessions' => $sessionCount
                                ]) }}" 
<<<<<<< HEAD
                               class="w-full py-3 px-4 rounded-full font-bold text-xs text-white bg-[#ED1B45] hover:bg-[#BA0030] active:scale-[0.99] transition text-center shadow-md shadow-[#ED1B45]/20 block cursor-pointer">
=======
                               class="w-full py-3 px-4 rounded-full font-bold text-xs text-white bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] transition text-center shadow-md shadow-orange-500/20 block">
>>>>>>> origin/feature/theme-blaze-orange
                                Pilih Pelatih &rarr;
                            </a>

                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pesan Ketika Tidak Ada Hasil Pencarian --}}
            <div x-show="visibleCount === 0" 
                 x-cloak 
<<<<<<< HEAD
                 class="bg-white rounded-2xl p-10 text-center border border-[#E5E7EB] mt-8 shadow-[0_4px_12px_rgba(0,0,0,0.04)] max-w-md mx-auto">
                <div class="w-12 h-12 rounded-full bg-[#ED1B45]/10 text-[#ED1B45] mx-auto flex items-center justify-center mb-3">
=======
                 class="bg-white rounded-2xl p-10 text-center border border-gray-200 mt-8 shadow-sm max-w-md mx-auto">
                <div class="w-12 h-12 rounded-full bg-orange-50 text-[#FF5722] mx-auto flex items-center justify-center mb-3">
>>>>>>> origin/feature/theme-blaze-orange
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
