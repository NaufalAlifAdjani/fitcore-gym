<x-fitcore-layout :title="'Pilih Personal Trainer | FITCORE ATHLETIC'">

    <section class="bg-[#F8F9FA] min-h-[calc(100vh-80px-350px)]">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-10 md:py-14" x-data="{
            searchQuery: '',
            matches(name, specialization) {
                if (!this.searchQuery.trim()) return true;
                const q = this.searchQuery.toLowerCase().trim();
                return (name && name.toLowerCase().includes(q)) || 
                       (specialization && specialization.toLowerCase().includes(q));
            },
            get visibleCount() {
                const trainers = {{ Js::from($trainers) }};
                return trainers.filter(t => this.matches(t.name, t.specialization)).length;
            }
        }">

            {{-- TOMBOL KEMBALI KE LANGKAH 1 --}}
            <div class="mb-6">
                <a href="{{ route('member.pt-packages.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-zinc-700 bg-white hover:bg-zinc-100 hover:text-zinc-900 border border-gray-200 rounded-full shadow-sm transition active:scale-95 group">
                    <svg class="w-4 h-4 text-zinc-500 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>&larr; Kembali ke Pilih Paket Sesi</span>
                </a>
            </div>

            {{-- HEADER JUDUL --}}
            <h1 class="text-3xl sm:text-4xl font-black text-zinc-900 tracking-tight uppercase">
                PILIH PERSONAL TRAINER ANDA
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1.5">
                Pilih pelatih profesional berlisensi untuk mendampingi target kebugaran Anda.
            </p>

            {{-- SEARCH BAR (Filter Rating dihapus karena tidak ada rating di database) --}}
            <div class="mt-6 max-w-md">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           x-model="searchQuery"
                           placeholder="Cari nama atau spesialis..." 
                           class="w-full pl-11 pr-10 py-3 bg-white border border-gray-200 rounded-full text-xs font-semibold placeholder-gray-400 text-zinc-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#E11D48] focus:border-transparent transition">
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

            {{-- GRID PELATIH (4 Kolom di Desktop) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($trainers as $trainer)
                    <div x-show="matches('{{ addslashes($trainer['name']) }}', '{{ addslashes($trainer['specialization']) }}')"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex flex-col bg-white hover:shadow-md transition duration-200 group">
                        
                        {{-- Foto Portrait Pelatih (Aspect 4/5) --}}
                        <div class="aspect-[4/5] w-full overflow-hidden bg-zinc-900 relative">
                            <img src="{{ $trainer['photo'] }}" 
                                 alt="{{ $trainer['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        </div>

                        {{-- Body Card --}}
                        <div class="p-5 flex-1 flex flex-col justify-between {{ !empty($trainer['is_active_highlight']) ? 'bg-[#FFF1F2] border-t border-rose-100' : 'bg-white border-t border-gray-100' }}">
                            
                            <div class="mb-4">
                                <h3 class="text-base sm:text-lg font-black text-zinc-900 leading-snug">
                                    {{ $trainer['name'] }}
                                </h3>
                                <p class="text-xs text-gray-500 font-semibold mt-1">
                                    {{ $trainer['specialization'] }}
                                </p>
                            </div>

                            {{-- Tombol Pilih Pelatih --}}
                            <a href="{{ route('member.pt-packages.checkout', [
                                    'package_id' => $packageId, 
                                    'trainer_id' => $trainer['id'], 
                                    'sessions' => $sessionCount
                                ]) }}" 
                               class="w-full py-3 px-4 rounded-full font-bold text-xs text-white bg-[#18181B] hover:bg-zinc-800 active:scale-[0.99] transition text-center shadow-sm block">
                                Pilih Pelatih &rarr;
                            </a>

                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pesan Ketika Tidak Ada Hasil Pencarian --}}
            <div x-show="visibleCount === 0" 
                 x-cloak 
                 class="bg-white rounded-2xl p-10 text-center border border-gray-200 mt-8 shadow-sm max-w-md mx-auto">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-[#E11D48] mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-zinc-900">Tidak ada pelatih yang cocok</h4>
                <p class="text-xs text-gray-500 mt-1">Tidak ditemukan pelatih dengan kata kunci "<span x-text="searchQuery" class="font-bold text-zinc-700"></span>".</p>
                <button type="button" @click="searchQuery = ''" class="mt-4 px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-bold text-xs rounded-full transition">
                    Reset Pencarian
                </button>
            </div>

        </div>
    </section>

</x-fitcore-layout>
