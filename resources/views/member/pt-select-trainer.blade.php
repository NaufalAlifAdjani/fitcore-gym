<x-fitcore-layout :title="'Pilih Personal Trainer | FITCORE ATHLETIC'">

    <section class="bg-[#F8F9FA] py-12 min-h-[calc(100vh-80px-350px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER JUDUL --}}
            <h1 class="text-3xl sm:text-4xl font-black text-zinc-900 tracking-tight uppercase">
                PILIH PERSONAL TRAINER ANDA
            </h1>

            {{-- FILTER BAR --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 mt-6" x-data="{ searchQuery: '', sortBy: 'Rating Tertinggi' }">
                
                {{-- Input Pencarian --}}
                <div class="relative w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           x-model="searchQuery"
                           placeholder="Cari nama atau spesialis..." 
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-full text-xs font-semibold placeholder-gray-400 text-zinc-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#E11D48] focus:border-transparent transition">
                </div>

                {{-- Dropdown Sort --}}
                <div class="relative inline-block">
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-5 py-2.5 text-xs font-bold text-zinc-800 shadow-sm cursor-pointer hover:border-gray-300 transition">
                        <span>Rating Tertinggi</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

            </div>

            {{-- GRID PELATIH (4 Kolom di Desktop) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-9">
                @foreach($trainers as $trainer)
                    <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex flex-col bg-white hover:shadow-md transition duration-200 group">
                        
                        {{-- Foto Portrait Pelatih (Aspect 4/5) --}}
                        <div class="aspect-[4/5] w-full overflow-hidden bg-zinc-900 relative">
                            <img src="{{ $trainer['photo'] }}" 
                                 alt="{{ $trainer['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        </div>

                        {{-- Body Card (Coach Rama diberi tint pink #FFF1F2 sesuai desain) --}}
                        <div class="p-5 flex-1 flex flex-col justify-between {{ !empty($trainer['is_active_highlight']) ? 'bg-[#FFF1F2] border-t border-rose-100' : 'bg-white border-t border-gray-100' }}">
                            
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-zinc-900 leading-snug mb-4">
                                    {{ $trainer['name'] }}
                                </h3>
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

        </div>
    </section>

</x-fitcore-layout>
