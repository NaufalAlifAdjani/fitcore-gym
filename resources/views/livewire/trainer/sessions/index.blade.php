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
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-8 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button wire:click="previousPeriod" class="p-2 hover:bg-gray-100 rounded-lg text-gray-600 transition" aria-label="Previous">
                    &larr;
                </button>
                <div class="text-xl font-bold text-gray-900">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}
                </div>
                <button wire:click="nextPeriod" class="p-2 hover:bg-gray-100 rounded-lg text-gray-600 transition" aria-label="Next">
                    &rarr;
                </button>
            </div>
            
            <div class="flex items-center gap-3">
                <div wire:ignore>
                    <input type="text" x-data x-init="
                        flatpickr($el, {
                            dateFormat: 'Y-m-d',
                            defaultDate: '{{ $date }}',
                            onChange: function(selectedDates, dateStr) {
                                $wire.goToDate(dateStr);
                            }
                        });
                    " class="text-sm rounded-lg border-gray-300 focus:ring-[#B80029] focus:border-[#B80029]" placeholder="Pilih Tanggal">
                </div>
                
                <div class="bg-gray-100 p-1 rounded-lg flex text-sm font-medium">
                    <button wire:click="setView('daily')" class="px-4 py-1.5 rounded-md transition-colors {{ $view === 'daily' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500' }}">Hari Ini</button>
                    <button wire:click="setView('weekly')" class="px-4 py-1.5 rounded-md transition-colors {{ $view === 'weekly' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500' }}">Minggu Ini</button>
                </div>
            </div>
        </div>

        @if($view === 'daily')
        <div class="flex gap-2 mb-8 overflow-x-auto pb-2 scrollbar-hide">
            @foreach($this->weekDates as $weekDate)
                @php 
                    $isActive = $weekDate->toDateString() === $date;
                    $isToday = $weekDate->isToday();
                @endphp
                <button 
                    wire:click="goToDate('{{ $weekDate->toDateString() }}')"
                    class="flex-shrink-0 flex flex-col items-center justify-center w-20 h-24 rounded-2xl transition-all border {{ $isActive ? 'bg-[#B80029] text-white border-[#B80029] shadow-md' : 'bg-white text-gray-600 border-gray-100 hover:border-gray-300' }}"
                >
                    <span class="text-xs font-semibold uppercase mb-1 {{ $isActive ? 'text-red-100' : 'text-gray-400' }}">{{ $weekDate->translatedFormat('D') }}</span>
                    <span class="text-2xl font-bold">{{ $weekDate->format('d') }}</span>
                    @if($isActive)
                        <div class="w-1.5 h-1.5 bg-white rounded-full mt-2"></div>
                    @endif
                </button>
            @endforeach
        </div>
        @endif

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
                        
                        <div wire:key="session-{{ $session->id }}" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 relative overflow-hidden flex flex-col transition hover:shadow-md {{ $isLive ? 'bg-zinc-900 text-white border-zinc-800' : '' }}">
                            
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center gap-2 text-sm font-medium {{ $isLive ? 'text-gray-300' : 'text-gray-600' }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $session->time_range }}
                                </div>
                                
                                @if($isLive)
                                    <div x-data="{
                                            start: new Date('{{ $session->session_date->format('Y-m-d') }}T{{ $session->start_time }}'),
                                            now: new Date(),
                                            get duration() { return Math.floor((this.now - this.start) / 60000); }
                                        }" 
                                        x-init="setInterval(() => { now = new Date() }, 60000)"
                                        class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full flex items-center gap-1.5"
                                    >
                                        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                        LIVE (<span x-text="duration"></span>')
                                    </div>
                                @else
                                    <div class="px-3 py-1 text-xs font-bold rounded-full border {{ $classes }}">
                                        {{ $statusEnum->label() }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 mb-6">
                                <div class="relative">
                                    <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600 text-xl overflow-hidden shrink-0">
                                        @if($session->member->memberProfile?->photo_path)
                                            <img src="{{ asset('storage/' . $session->member->memberProfile->photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($session->member->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    @if($isLive)
                                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-red-500 border-2 border-zinc-900 rounded-full"></div>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="font-bold text-lg {{ $isLive ? 'text-white' : 'text-gray-900' }} leading-tight mb-1">{{ $session->member->name }}</h4>
                                    <div class="flex items-center gap-1 text-xs {{ $isLive ? 'text-gray-400' : 'text-gray-500' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                        {{ $session->session_info }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow"></div>

                            <div class="flex gap-2 mt-4 pt-4 border-t {{ $isLive ? 'border-zinc-800' : 'border-gray-100' }}">
                                @if($statusEnum === \App\Enums\PtSessionStatus::Completed || $statusEnum === \App\Enums\PtSessionStatus::Done)
                                    <button class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-xl text-sm transition">
                                        Catatan Latihan
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::Scheduled)
                                    <button class="w-full py-2.5 bg-[#B80029]/10 hover:bg-[#B80029]/20 text-[#B80029] font-semibold rounded-xl text-sm transition">
                                        Detail & Asesmen
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::PendingConfirmation)
                                    <button class="w-1/2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                                        Tolak
                                    </button>
                                    <button class="w-1/2 py-2.5 bg-[#B80029] hover:bg-[#9a0022] text-white font-semibold rounded-xl text-sm transition shadow-sm">
                                        Konfirmasi
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::Rescheduled)
                                    <button class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-xl text-sm transition">
                                        Lihat Alasan
                                    </button>
                                @elseif($statusEnum === \App\Enums\PtSessionStatus::Ongoing)
                                    <button class="w-1/2 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white font-semibold rounded-xl text-sm transition">
                                        Timer
                                    </button>
                                    <button class="w-1/2 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                                        Selesai
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
