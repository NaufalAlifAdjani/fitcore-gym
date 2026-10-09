<div class="min-h-screen bg-gray-50 pb-12">
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center sticky top-0 z-20">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight uppercase">Jadwal Sesi Personal Trainer</h1>
        
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="font-bold text-gray-900">{{ auth()->user()->name }}</div>
                <div class="text-sm text-gray-500">{{ auth()->user()->trainerProfile?->tier ?? 'Trainer' }}</div>
            </div>
            @if(auth()->user()->trainerProfile?->photo_path)
                <img src="{{ asset('storage/' . auth()->user()->trainerProfile->photo_path) }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
            @else
                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
            @endif
        </div>
    </header>

    <div class="max-w-6xl mx-auto mt-8 px-4 sm:px-6">
        
        <!-- Card: Pilih Tanggal Sesi (Design Gambar 1 & Gambar 2) -->
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-5 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-base font-bold text-[#16151A] font-heading">
                    Pilih Tanggal Sesi
                </h2>

                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Date Picker Input (Gambar 2) -->
                    <div wire:ignore x-data="{
                        picker: null,
                        init() {
                            this.picker = flatpickr($refs.dateInput, {
                                dateFormat: 'Y-m-d',
                                defaultDate: @js($date),
                                onChange: (selectedDates, dateStr) => {
                                    if (dateStr) {
                                        $wire.goToDate(dateStr);
                                    }
                                }
                            });

                            this.$watch('$wire.date', (newDate) => {
                                if (this.picker && newDate) {
                                    this.picker.setDate(newDate, false);
                                }
                            });
                        }
                    }">
                        <input 
                            type="text" 
                            x-ref="dateInput"
                            class="text-xs sm:text-sm rounded-xl border-[#E5E7EB] text-[#16151A] font-medium py-1.5 px-3 focus:ring-[#ED1B45] focus:border-[#ED1B45] cursor-pointer shadow-sm hover:border-gray-400 transition" 
                            placeholder="Pilih Tanggal"
                        >
                    </div>

                    <!-- Month Navigation (< Bulan Tahun >) -->
                    <div class="flex items-center gap-3 text-xs font-bold text-[#16151A]">
                        <button type="button" wire:click="previousPeriod"
                            class="p-1 hover:text-[#ED1B45] transition-colors text-base font-bold"
                            aria-label="Previous week">
                            &lsaquo;
                        </button>
                        <span class="font-heading capitalize text-xs sm:text-sm">{{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}</span>
                        <button type="button" wire:click="nextPeriod"
                            class="p-1 hover:text-[#ED1B45] transition-colors text-base font-bold"
                            aria-label="Next week">
                            &rsaquo;
                        </button>
                    </div>
                </div>
            </div>

            <!-- 7 Days Strip (Gambar 1) -->
            <div class="grid grid-cols-7 gap-2.5 sm:gap-3">
                @foreach($this->weekDates as $weekDate)
                    @php 
                        $isActive = $weekDate->toDateString() === $date;
                    @endphp
                    <button 
                        type="button"
                        wire:click="goToDate('{{ $weekDate->toDateString() }}')"
                        class="flex flex-col items-center justify-center py-4 px-1 rounded-2xl transition-all duration-200 text-center {{ $isActive 
                            ? 'bg-[#ED1B45] text-white shadow-lg shadow-[#ED1B45]/30 ring-2 ring-[#ED1B45]' 
                            : 'bg-white hover:bg-zinc-50 text-[#16151A] border border-[#E5E7EB]' }}"
                    >
                        <span class="text-xs font-semibold {{ $isActive ? 'text-white' : 'text-[#565A66]' }}">
                            {{ $weekDate->translatedFormat('D') }}
                        </span>
                        <span class="text-xl font-extrabold my-1 font-heading {{ $isActive ? 'text-white' : 'text-[#16151A]' }}">
                            {{ $weekDate->format('j') }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <div wire:loading class="w-full text-center py-10">
            <div class="animate-pulse flex flex-col items-center gap-4">
                <div class="h-6 w-32 bg-gray-200 rounded"></div>
                <div class="h-4 w-48 bg-gray-200 rounded"></div>
            </div>
        </div>

        <div wire:loading.remove>
            @if($this->sessions->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada sesi pada periode ini</h3>
                    <p class="text-gray-500">Anda dapat beristirahat atau mengecek jadwal di hari lain.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($this->sessions as $session)
                        @php
                            $statusEnum = $session->status;
                            $classes = $statusEnum->badgeClasses();
                            $isLive = $statusEnum === \App\Enums\PtSessionStatus::Ongoing;
                        @endphp
                        
                        <div wire:key="session-{{ $session->id }}" class="bg-white rounded-2xl p-5 shadow-sm border relative overflow-hidden flex flex-col transition hover:shadow-md {{ $isLive ? 'border-[#ED1B45] ring-2 ring-[#ED1B45]/15' : 'border-gray-100' }}">
                            
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                                    <svg class="w-4 h-4 {{ $isLive ? 'text-[#ED1B45]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="{{ $isLive ? 'font-bold text-gray-900' : '' }}">{{ $session->time_range }}</span>
                                </div>
                                
                                @if($isLive)
                                    <div class="px-3 py-1 text-xs font-bold rounded-full bg-[#ED1B45]/10 text-[#ED1B45] border border-[#ED1B45]/20 flex items-center gap-1.5 shadow-2xs">
                                        Sedang Berjalan
                                    </div>
                                @elseif(!empty($statusEnum->label()))
                                    <div class="px-3 py-1 text-xs font-bold rounded-full border {{ $classes }}">
                                        {{ $statusEnum->label() }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 mb-6">
                                <div class="relative">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-700 text-xl overflow-hidden shrink-0 border border-gray-200">
                                        @if($session->member->memberProfile?->photo_path)
                                             <img src="{{ asset('storage/' . $session->member->memberProfile->photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($session->member->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    @if($isLive)
                                        <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full" title="Member sedang sesi aktif"></div>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="font-bold text-lg text-gray-900 leading-tight mb-1">{{ $session->member->name }}</h4>
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 {{ $isLive ? 'text-[#ED1B45]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                        {{ $session->session_info }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow"></div>

                            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                                @if($statusEnum === \App\Enums\PtSessionStatus::Completed || $statusEnum === \App\Enums\PtSessionStatus::Done)
                                    <button type="button" class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-xl text-sm transition">
                                        Catatan
                                    </button>
                                    <button type="button" class="w-1/2 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold rounded-xl text-sm border border-amber-200 transition flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span>Rating{{ $session->rating ? ' ' . number_format($session->rating->rating, 1) : '' }}</span>
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::Scheduled)
                                    <button type="button" class="w-full py-2.5 bg-[#ED1B45]/10 hover:bg-[#ED1B45]/20 text-[#ED1B45] font-semibold rounded-xl text-sm transition">
                                        Detail & Asesmen
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::PendingConfirmation)
                                    <button type="button" class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                                        Tolak
                                    </button>
                                    <button type="button" class="w-1/2 py-2.5 bg-[#ED1B45] hover:bg-[#D1123D] text-white font-semibold rounded-xl text-sm transition shadow-sm">
                                        Konfirmasi
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::Rescheduled)
                                    <button type="button" class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-xl text-sm transition">
                                        Lihat Alasan
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::Ongoing)
                                    <button type="button" class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-xl text-sm transition">
                                        Catatan
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
