<div class="space-y-6">
    <!-- Google Calendar Container -->
    <div class="bg-white rounded-3xl border border-[#E5E7EB] p-4 sm:p-6 shadow-sm mb-8 space-y-4">

        
        <!-- Google Calendar Top Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <!-- Left: Today, Prev, Next, Month Year -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Today Button -->
                <button 
                    type="button" 
                    wire:click="goToToday"
                    class="px-5 py-2 text-sm font-semibold text-[#16151A] bg-white border border-[#E5E7EB] rounded-full hover:border-[#ED1B45] hover:text-[#ED1B45] active:bg-gray-100 transition shadow-2xs cursor-pointer"
                >
                    Today
                </button>

                <!-- Prev Arrow -->
                <button 
                    type="button" 
                    wire:click="previousPeriod"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 active:bg-gray-200 transition cursor-pointer"
                    aria-label="Previous period"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Next Arrow -->
                <button 
                    type="button" 
                    wire:click="nextPeriod"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 active:bg-gray-200 transition cursor-pointer"
                    aria-label="Next period"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Month, Week, or Day Title -->
                <h2 class="text-xl sm:text-2xl font-bold text-[#16151A] tracking-tight ml-2 font-['Poppins']">
                    @if($view === 'week')
                        {{ $this->weekDays[0]->format('M j') }} &ndash; {{ $this->weekDays[6]->format('M j, Y') }}
                    @elseif($view === 'day')
                        {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('F Y') }}
                    @endif
                </h2>
            </div>

            <!-- Right: View Dropdown, View Switcher -->
            <div class="flex items-center gap-2 self-end md:self-auto">
                <!-- View Dropdown Button (Day / Week / Month) -->
                <div class="relative" @click.away="$wire.closeViewDropdown()">
                    <button 
                        type="button" 
                        wire:click="toggleViewDropdown"
                        class="px-4 py-2 text-sm font-semibold text-[#16151A] bg-white border border-[#E5E7EB] rounded-full hover:border-[#ED1B45] flex items-center gap-2 shadow-2xs transition cursor-pointer"
                    >
                        <span class="capitalize">{{ $view === 'month' ? 'Month' : ($view === 'week' ? 'Week' : 'Day') }}</span>
                        <svg class="w-4 h-4 text-[#565A66] transition-transform duration-200 {{ $viewDropdownOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    @if($viewDropdownOpen)
                        <div class="absolute right-0 mt-2 w-36 bg-white rounded-2xl shadow-xl border border-[#E5E7EB] py-1.5 z-30">
                            <button 
                                type="button" 
                                wire:click="setView('day')" 
                                class="w-full text-left px-4 py-2 text-sm font-medium transition hover:bg-gray-50 flex items-center justify-between cursor-pointer {{ $view === 'day' ? 'text-[#ED1B45] font-bold bg-[#ED1B45]/10' : 'text-[#565A66] hover:text-[#16151A]' }}"
                            >
                                <span>Day</span>
                                @if($view === 'day')
                                    <svg class="w-4 h-4 text-[#ED1B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                            <button 
                                type="button" 
                                wire:click="setView('week')" 
                                class="w-full text-left px-4 py-2 text-sm font-medium transition hover:bg-gray-50 flex items-center justify-between cursor-pointer {{ $view === 'week' ? 'text-[#ED1B45] font-bold bg-[#ED1B45]/10' : 'text-[#565A66] hover:text-[#16151A]' }}"
                            >
                                <span>Week</span>
                                @if($view === 'week')
                                    <svg class="w-4 h-4 text-[#ED1B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                            <button 
                                type="button" 
                                wire:click="setView('month')" 
                                class="w-full text-left px-4 py-2 text-sm font-medium transition hover:bg-gray-50 flex items-center justify-between cursor-pointer {{ $view === 'month' ? 'text-[#ED1B45] font-bold bg-[#ED1B45]/10' : 'text-[#565A66] hover:text-[#16151A]' }}"
                            >
                                <span>Month</span>
                                @if($view === 'month')
                                    <svg class="w-4 h-4 text-[#ED1B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                        </div>
                    @endif
                </div>

                <!-- View Segmented Switcher (Calendar / Task) -->
                <div class="inline-flex rounded-full border border-[#E5E7EB] p-0.5 bg-gray-100 shadow-2xs">
                    <button 
                        type="button" 
                        wire:click="setMode('calendar')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center justify-center transition cursor-pointer {{ $mode === 'calendar' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        title="Tampilan Kalender"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button 
                        type="button" 
                        wire:click="setMode('tasks')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center justify-center transition cursor-pointer {{ $mode === 'tasks' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        title="Tampilan Tasks / Jadwal Sesi"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        @if($mode === 'tasks')
            <!-- Tampilan Tasks (Daftar Sesi Latihan) -->
            <div class="pt-2 space-y-5">
                <!-- Header Tasks & Filter Tabs -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-[#E5E7EB]">
                    <div>
                        <h3 class="text-xl font-bold text-[#16151A] font-heading">Daftar Sesi Latihan</h3>
                        <p class="text-xs text-[#565A66] mt-0.5">Kelola dan pantau seluruh sesi latihan privat bersama pelatih Anda</p>
                    </div>

                    <!-- Status Filter Pills -->
                    <div class="flex items-center gap-1.5 p-1 bg-gray-100 rounded-full border border-[#E5E7EB] overflow-x-auto self-start lg:self-auto max-w-full">
                        <!-- All -->
                        <button 
                            type="button" 
                            wire:click="setTaskStatusFilter('all')"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer {{ $taskStatusFilter === 'all' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        >
                            <span>Semua</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $taskStatusFilter === 'all' ? 'bg-white/20 text-white' : 'bg-gray-200 text-[#565A66]' }}">
                                {{ $this->taskStatusCounts['all'] }}
                            </span>
                        </button>

                        <!-- Menunggu (Pending) -->
                        <button 
                            type="button" 
                            wire:click="setTaskStatusFilter('pending')"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer {{ $taskStatusFilter === 'pending' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        >
                            <span>Menunggu</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $taskStatusFilter === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700 font-bold' }}">
                                {{ $this->taskStatusCounts['pending'] }}
                            </span>
                        </button>

                        <!-- Terkonfirmasi (Confirmed) -->
                        <button 
                            type="button" 
                            wire:click="setTaskStatusFilter('confirmed')"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer {{ $taskStatusFilter === 'confirmed' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        >
                            <span>Terkonfirmasi</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $taskStatusFilter === 'confirmed' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700 font-bold' }}">
                                {{ $this->taskStatusCounts['confirmed'] }}
                            </span>
                        </button>

                        <!-- Dibatalkan (Cancelled) -->
                        <button 
                            type="button" 
                            wire:click="setTaskStatusFilter('cancelled')"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer {{ $taskStatusFilter === 'cancelled' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        >
                            <span>Dibatalkan</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $taskStatusFilter === 'cancelled' ? 'bg-white/20 text-white' : 'bg-zinc-200 text-zinc-700 font-bold' }}">
                                {{ $this->taskStatusCounts['cancelled'] }}
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Tasks List Grouped by Date -->
                @if($this->tasksList->isEmpty())
                    <div class="py-12 text-center text-[#565A66]">
                        <div class="w-12 h-12 rounded-full bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="font-medium text-sm">
                            @if($taskStatusFilter === 'all')
                                Tidak ada jadwal sesi pada periode ini.
                            @else
                                Tidak ada sesi berstatus <strong>{{ $taskStatusFilter === 'pending' ? 'Menunggu' : ($taskStatusFilter === 'confirmed' ? 'Terkonfirmasi' : 'Dibatalkan') }}</strong> pada periode ini.
                            @endif
                        </p>
                        @if($taskStatusFilter !== 'all')
                            <button type="button" wire:click="setTaskStatusFilter('all')" class="mt-2.5 text-xs font-bold text-[#ED1B45] hover:underline cursor-pointer inline-flex items-center gap-1">
                                Lihat Semua Status Sesi
                            </button>
                        @endif
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($this->tasksList as $taskDate => $sessions)
                            <div class="space-y-3">
                                <!-- Date Header (e.g. Min, 18 Okt) -->
                                <div class="flex items-center gap-2">
                                    <div class="text-xs sm:text-sm font-bold text-[#16151A] uppercase tracking-wider font-heading">
                                        {{ $taskDate }}
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-[#565A66]">
                                        {{ $sessions->count() }} Sesi
                                    </span>
                                </div>

                                <!-- Session Tasks 2x2 Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($sessions as $taskSession)
                                        @php
                                            $statusEnum = $taskSession->status;
                                        @endphp
                                        <div 
                                            wire:key="task-session-{{ $taskSession->id }}"
                                            class="flex flex-col justify-between p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E7EB] hover:border-[#ED1B45]/50 hover:shadow-md transition-all duration-200 group shadow-2xs"
                                        >
                                            <div>
                                                <!-- Top Card Row: Trainer Info & Badge -->
                                                <div class="flex items-start justify-between gap-3 mb-3">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <div class="w-9 h-9 rounded-full bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-[#ED1B45] group-hover:text-white transition-colors duration-200">
                                                            {{ strtoupper(substr($taskSession->trainer->name ?? 'T', 0, 1)) }}
                                                        </div>
                                                        <div class="min-w-0">
                                                            <h4 class="text-sm font-bold text-[#16151A] group-hover:text-[#ED1B45] transition truncate">
                                                                {{ $taskSession->trainer->name ?? 'Coach Rama' }}
                                                            </h4>
                                                            <p class="text-[11px] text-[#565A66] truncate">
                                                                {{ $taskSession->trainer->trainerProfile->specialization ?? 'Personal Trainer' }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full shrink-0 {{ $statusEnum->badgeClasses() }}">
                                                        {{ $statusEnum->label() }}
                                                    </span>
                                                </div>

                                                <!-- Time Range Pill -->
                                                <div class="flex items-center gap-2 text-xs text-[#565A66] bg-[#F6F6F7] p-2.5 rounded-xl mb-3">
                                                    <svg class="w-4 h-4 text-[#ED1B45] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-bold text-[#16151A]">{{ $taskSession->time_range }}</span>
                                                </div>
                                            </div>

                                            <!-- Actions: Ubah Jadwal & Batal Sesi -->
                                            <div class="pt-2 border-t border-[#E5E7EB] flex items-center justify-end gap-2">
                                                @if($taskSession->can_be_changed)
                                                    <a href="{{ route('pt-sessions.edit', $taskSession->id) }}" 
                                                       class="px-3.5 py-1.5 rounded-full bg-white hover:bg-zinc-100 text-[#16151A] border border-[#E5E7EB] text-xs font-bold transition shadow-2xs">
                                                        Ubah Jadwal
                                                    </a>
                                                    <button type="button" 
                                                            wire:click="openCancelModal({{ $taskSession->id }})"
                                                            class="text-[#ED1B45] hover:underline text-xs font-bold px-2 py-1 cursor-pointer">
                                                        Batal Sesi
                                                    </button>
                                                @else
                                                    @if($statusEnum === \App\Enums\PtSessionStatus::Cancelled)
                                                        <span class="text-xs text-zinc-400 italic">Sesi Telah Dibatalkan</span>
                                                    @else
                                                        <span class="text-[11px] text-zinc-400 italic">Batas waktu lewat (&lt; 24 jam)</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <!-- Tampilan Kalender: Month, Week, atau Day -->
            @if($view === 'month')
                <!-- Month Calendar Grid -->
                <div class="rounded-2xl border border-[#E5E7EB] overflow-hidden bg-white">
                    <div class="overflow-x-auto scrollbar-thin">
                        <div class="min-w-[640px] md:min-w-0">
                            <!-- Day Names Header Row (SUN, MON, TUE, WED, THU, FRI, SAT) -->
                            <div class="grid grid-cols-7 border-b border-[#E5E7EB] bg-white">
                                @foreach(['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'] as $dayHeader)
                                    <div class="text-[11px] font-semibold text-[#565A66] uppercase tracking-wider text-center py-2.5">
                                        {{ $dayHeader }}
                                    </div>
                                @endforeach
                            </div>

                            <!-- 7 Columns Calendar Body Grid -->
                            <div class="grid grid-cols-7 border-t border-[#E5E7EB] divide-x divide-[#E5E7EB]">
                                @php
                                    $parsedDate = \Carbon\Carbon::parse($date);
                                @endphp

                                @foreach($this->monthGrid as $gridDate)
                                    @php
                                        $isCurrentMonth = $gridDate->isSameMonth($parsedDate);
                                        $isToday = $gridDate->isToday();
                                        $isSelected = $gridDate->toDateString() === $date;
                                        $dayDateString = $gridDate->toDateString();
                                        $daySessions = $this->monthSessions->get($dayDateString) ?? collect();
                                        $isFirstOfMonth = $gridDate->day === 1;
                                    @endphp

                                    <div 
                                        wire:key="grid-day-{{ $dayDateString }}"
                                        wire:click="goToDate('{{ $dayDateString }}')"
                                        class="min-h-[105px] sm:min-h-[125px] p-1.5 sm:p-2 border-b border-[#E5E7EB] flex flex-col transition cursor-pointer select-none {{ $isSelected ? 'bg-[#ED1B45]/5 ring-2 ring-inset ring-[#ED1B45]' : ($isCurrentMonth ? 'bg-white hover:bg-gray-50/80' : 'bg-gray-50/50 hover:bg-gray-100/70') }}"
                                    >
                                        <!-- Date Number Header in Cell -->
                                        <div class="flex items-center justify-center mb-1">
                                            @if($isToday)
                                                <!-- Today: Solid Red Circle Badge -->
                                                <div class="w-7 h-7 rounded-full bg-[#ED1B45] text-white font-medium text-xs sm:text-sm flex items-center justify-center shadow-xs">
                                                    {{ $gridDate->day }}
                                                </div>
                                            @elseif($isFirstOfMonth)
                                                <!-- 1st of Month: "Oct 1" format -->
                                                <div class="text-xs sm:text-sm font-semibold {{ $isCurrentMonth ? 'text-[#16151A]' : 'text-gray-400' }}">
                                                    {{ $gridDate->format('M j') }}
                                                </div>
                                            @else
                                                <div class="text-xs sm:text-sm {{ $isCurrentMonth ? 'text-[#16151A] font-medium' : 'text-gray-400 font-normal' }}">
                                                    {{ $gridDate->day }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Session Chips inside Day Cell -->
                                        <div class="space-y-1 mt-1 flex-1 overflow-hidden">
                                            @foreach($daySessions->take(2) as $sessionItem)
                                                @php
                                                    $chipStyle = match($sessionItem->status) {
                                                        \App\Enums\PtSessionStatus::Pending => 'bg-amber-100 text-amber-900 border border-amber-300 font-medium',
                                                        \App\Enums\PtSessionStatus::Confirmed => 'bg-emerald-600 text-white font-medium',
                                                        \App\Enums\PtSessionStatus::Cancelled => 'bg-gray-100 text-gray-400 line-through font-medium',
                                                        default => 'bg-gray-100 text-gray-700 font-medium',
                                                    };
                                                    $startTimeFormatted = \Carbon\Carbon::parse($sessionItem->start_time)->format('H:i');
                                                @endphp
                                                <div 
                                                    class="text-[10px] sm:text-[11px] leading-tight px-1.5 py-0.5 rounded truncate shadow-2xs {{ $chipStyle }}"
                                                    title="{{ $startTimeFormatted }} - {{ $sessionItem->trainer->name ?? 'Coach' }} ({{ $sessionItem->status->label() }})"
                                                >
                                                    <span class="font-bold whitespace-nowrap">{{ $startTimeFormatted }}</span>
                                                    <span>{{ Str::limit($sessionItem->trainer->name ?? 'Coach', 8) }}</span>
                                                </div>
                                            @endforeach

                                            @if($daySessions->count() > 2)
                                                <div class="text-[10px] text-[#565A66] font-semibold px-1">
                                                    +{{ $daySessions->count() - 2 }} more
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>



            @elseif($view === 'week')
                <!-- Week View -->
                <div x-data="{ mobileTab: 'agenda' }" class="space-y-4">
                    <!-- Mobile View Switcher Tab -->
                    <div class="flex items-center justify-between gap-1.5 md:hidden bg-gray-100 p-1 rounded-2xl border border-[#E5E7EB]">
                        <button 
                            type="button" 
                            @click="mobileTab = 'agenda'" 
                            :class="mobileTab === 'agenda' ? 'bg-white text-[#16151A] font-bold shadow-xs' : 'text-[#565A66] hover:text-[#16151A]'"
                            class="flex-1 py-1.5 px-3 rounded-xl text-xs transition cursor-pointer text-center flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 text-[#ED1B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>Agenda Harian</span>
                        </button>
                        <button 
                            type="button" 
                            @click="mobileTab = 'grid'" 
                            :class="mobileTab === 'grid' ? 'bg-white text-[#16151A] font-bold shadow-xs' : 'text-[#565A66] hover:text-[#16151A]'"
                            class="flex-1 py-1.5 px-3 rounded-xl text-xs transition cursor-pointer text-center flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 text-[#ED1B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                            <span>Tabel 7 Hari</span>
                        </button>
                    </div>

                    <!-- Mobile Agenda View: 7-Days Strip + Selected Day Sessions -->
                    <div class="md:hidden space-y-3" x-show="mobileTab === 'agenda'">
                        <div class="grid grid-cols-7 gap-1 p-1.5 bg-gray-50 rounded-2xl border border-[#E5E7EB]">
                            @foreach($this->weekDays as $wDate)
                                @php
                                    $isToday = $wDate->isToday();
                                    $isSelected = $wDate->toDateString() === $date;
                                    $wDateString = $wDate->toDateString();
                                    $daySessions = $this->monthSessions->get($wDateString) ?? collect();
                                @endphp
                                <button 
                                    type="button"
                                    wire:key="mobile-week-strip-{{ $wDateString }}"
                                    wire:click="goToDate('{{ $wDateString }}')"
                                    class="flex flex-col items-center justify-center py-2 px-0.5 rounded-xl transition cursor-pointer select-none {{ $isSelected ? 'bg-[#ED1B45] text-white shadow-xs font-bold' : ($isToday ? 'bg-white text-[#ED1B45] font-semibold border border-[#ED1B45]/40 shadow-2xs' : 'text-[#565A66] hover:bg-white') }}"
                                >
                                    <span class="text-[10px] uppercase tracking-wider {{ $isSelected ? 'text-white/80' : 'text-[#565A66]' }}">{{ $wDate->format('D') }}</span>
                                    <span class="text-sm font-bold mt-0.5">{{ $wDate->day }}</span>
                                    <div class="flex items-center gap-0.5 mt-1 h-1.5">
                                        @if($daySessions->isNotEmpty())
                                            <span class="w-1.5 h-1.5 rounded-full {{ $isSelected ? 'bg-white' : 'bg-[#ED1B45]' }}"></span>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Selected Day Sessions List -->
                        @php
                            $weekSelectedDaySessions = $this->monthSessions->get($date) ?? collect();
                        @endphp
                        <div class="rounded-2xl border border-[#E5E7EB] bg-white overflow-hidden shadow-2xs p-4 space-y-3">
                            <h4 class="text-sm font-bold text-[#16151A]">{{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }}</h4>
                            @forelse($weekSelectedDaySessions as $session)
                                <div class="p-3 rounded-xl border border-[#E5E7EB] bg-white space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-[#16151A]">{{ $session->trainer->name }}</span>
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $session->status->badgeClasses() }}">{{ $session->status->label() }}</span>
                                    </div>
                                    <div class="text-xs text-[#565A66]">{{ $session->time_range }}</div>
                                </div>
                            @empty
                                <p class="text-xs text-[#565A66] text-center py-4">Tidak ada sesi pada hari ini</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Desktop Grid 7 Hari -->
                    <div :class="mobileTab === 'grid' ? 'block' : 'hidden md:block'">
                        <div class="rounded-2xl border border-[#E5E7EB] overflow-hidden bg-white">
                            <div class="overflow-x-auto scrollbar-thin">
                                <div class="min-w-[700px] md:min-w-0">
                                    <div class="grid grid-cols-7 border-b border-[#E5E7EB] bg-white">
                                        @foreach(['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'] as $dayHeader)
                                            <div class="text-[11px] font-semibold text-[#565A66] uppercase tracking-wider text-center py-2.5">
                                                {{ $dayHeader }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="grid grid-cols-7 divide-x divide-[#E5E7EB]">
                                        @foreach($this->weekDays as $wDate)
                                            @php
                                                $isToday = $wDate->isToday();
                                                $isSelected = $wDate->toDateString() === $date;
                                                $wDateString = $wDate->toDateString();
                                                $daySessions = $this->monthSessions->get($wDateString) ?? collect();
                                            @endphp
                                            <div 
                                                wire:key="week-day-{{ $wDateString }}"
                                                wire:click="goToDate('{{ $wDateString }}')"
                                                class="min-h-[220px] p-2 flex flex-col transition cursor-pointer select-none {{ $isSelected ? 'bg-[#ED1B45]/5 ring-2 ring-inset ring-[#ED1B45]' : 'bg-white hover:bg-gray-50/80' }}"
                                            >
                                                <div class="flex items-center justify-center mb-2">
                                                    @if($isToday)
                                                        <div class="w-7 h-7 rounded-full bg-[#ED1B45] text-white font-medium text-xs sm:text-sm flex items-center justify-center shadow-xs">
                                                            {{ $wDate->day }}
                                                        </div>
                                                    @else
                                                        <div class="text-xs sm:text-sm {{ $isSelected ? 'text-[#ED1B45] font-bold' : 'text-[#16151A] font-medium' }}">
                                                            {{ $wDate->day }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="space-y-1.5 flex-1 overflow-hidden">
                                                    @forelse($daySessions as $sessionItem)
                                                        @php
                                                            $chipStyle = match($sessionItem->status) {
                                                                \App\Enums\PtSessionStatus::Pending => 'bg-amber-100 text-amber-900 border border-amber-300 font-medium',
                                                                \App\Enums\PtSessionStatus::Confirmed => 'bg-emerald-600 text-white font-medium',
                                                                \App\Enums\PtSessionStatus::Cancelled => 'bg-gray-100 text-gray-400 line-through font-medium',
                                                                default => 'bg-gray-100 text-gray-700 font-medium',
                                                            };
                                                            $startTimeFormatted = \Carbon\Carbon::parse($sessionItem->start_time)->format('H:i');
                                                            $endTimeFormatted = \Carbon\Carbon::parse($sessionItem->end_time)->format('H:i');
                                                        @endphp
                                                        <div 
                                                            class="text-[10px] leading-tight p-1.5 rounded-lg shadow-2xs {{ $chipStyle }}"
                                                            title="{{ $sessionItem->time_range }} - {{ $sessionItem->trainer->name ?? 'Coach' }}"
                                                        >
                                                            <div class="font-bold whitespace-nowrap">{{ $startTimeFormatted }} - {{ $endTimeFormatted }}</div>
                                                            <div class="truncate mt-0.5">{{ $sessionItem->trainer->name ?? 'Coach' }}</div>
                                                        </div>
                                                    @empty
                                                        <div class="text-[10px] text-gray-400 text-center py-4 italic">-</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Day View -->
                <div class="rounded-2xl border border-[#E5E7EB] overflow-hidden bg-white">
                    <div class="px-6 py-4 bg-gray-50/70 border-b border-[#E5E7EB] flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-[#16151A] font-['Poppins']">
                                {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }}
                            </h3>
                            <p class="text-xs text-[#565A66] mt-0.5">Daftar sesi latihan privat pada hari ini</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-[#ED1B45]/10 text-[#ED1B45] font-bold text-xs border border-[#ED1B45]/20">
                            {{ $this->sessions->count() }} Sesi
                        </span>
                    </div>

                    <div class="p-4 sm:p-6">
                        @if($this->sessions->isEmpty())
                            <div class="py-12 text-center text-[#565A66]">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="font-medium text-sm text-[#16151A]">Tidak ada sesi pada hari ini</p>
                                <p class="text-xs text-[#565A66] mt-1">Anda dapat memilih tanggal lain di kalender.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($this->sessions as $session)
                                    @php
                                        $statusEnum = $session->status;
                                        $classes = $statusEnum->badgeClasses();
                                    @endphp
                                    <div wire:key="day-session-{{ $session->id }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 p-4 rounded-2xl border border-[#E5E7EB] bg-white hover:bg-gray-50/60 transition shadow-2xs">
                                        <div class="flex items-center gap-3.5 min-w-0">
                                            <div class="w-11 h-11 rounded-full bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center font-bold text-sm shrink-0 border border-[#E5E7EB]">
                                                {{ strtoupper(substr($session->trainer->name ?? 'T', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h4 class="font-bold text-sm text-[#16151A] truncate">{{ $session->trainer->name }}</h4>
                                                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full shrink-0 {{ $classes }}">
                                                        {{ $statusEnum->label() }}
                                                    </span>
                                                </div>
                                                <div class="text-xs text-[#565A66] mt-0.5 flex items-center gap-2 flex-wrap">
                                                    <span class="font-semibold text-[#16151A] whitespace-nowrap">{{ $session->time_range }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 sm:self-auto shrink-0 w-full sm:w-auto pt-2 border-t border-[#E5E7EB] sm:border-0 sm:pt-0 justify-end">
                                            @if($session->can_be_changed)
                                                <a href="{{ route('pt-sessions.edit', $session->id) }}" 
                                                   class="px-3.5 py-1.5 rounded-full bg-white hover:bg-zinc-100 text-[#16151A] border border-[#E5E7EB] text-xs font-bold transition shadow-2xs">
                                                    Ubah Jadwal
                                                </a>
                                                <button type="button" 
                                                        wire:click="openCancelModal({{ $session->id }})"
                                                        class="text-[#ED1B45] hover:underline text-xs font-bold px-2 py-1 cursor-pointer">
                                                    Batal Sesi
                                                </button>
                                            @else
                                                @if($statusEnum === \App\Enums\PtSessionStatus::Cancelled)
                                                    <span class="text-xs text-zinc-400 italic">Dibatalkan</span>
                                                @else
                                                    <span class="text-[11px] text-zinc-400 italic">&lt; 24 jam</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Cancellation Confirmation Modal -->
    @if($cancelModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div @click.away="$wire.closeCancelModal()" class="max-w-md w-full bg-white border border-[#E5E7EB] rounded-3xl p-7 shadow-2xl space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 text-[#ED1B45] flex items-center justify-center font-bold text-lg shrink-0">
                        ✕
                    </div>
                    <div>
                        <h4 class="text-base font-extrabold text-[#16151A] font-heading">Batalkan Sesi Latihan?</h4>
                        <p class="text-xs text-[#565A66]">Konfirmasi pembatalan sesi PT</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 text-xs text-[#16151A] space-y-1">
                    <div class="font-bold text-sm">{{ $targetDate }}</div>
                    <div class="text-[#ED1B45] font-bold">{{ $targetTime }}</div>
                    <p class="text-[#565A66] pt-1">
                        Sesi ini akan dibatalkan dan <span class="text-emerald-600 font-bold">1 kuota</span> akan segera dikembalikan ke akun Anda.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#565A66] mb-1">Alasan Pembatalan (Opsional)</label>
                        <input type="text"
                               wire:model="cancelReason"
                               placeholder="Contoh: Ada keperluan mendadak"
                               class="w-full bg-white border border-[#E5E7EB] rounded-xl px-3 py-2 text-xs text-[#16151A] placeholder-zinc-400 focus:ring-[#ED1B45] focus:border-[#ED1B45]">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button"
                                wire:click="closeCancelModal"
                                class="px-5 py-2.5 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-bold transition-colors cursor-pointer">
                            Kembali
                        </button>
                        <button type="button"
                                wire:click="confirmCancel"
                                wire:loading.attr="disabled"
                                class="px-6 py-2.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-bold transition-colors shadow-md shadow-[#ED1B45]/20 cursor-pointer">
                            Ya, Batalkan Sesi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
