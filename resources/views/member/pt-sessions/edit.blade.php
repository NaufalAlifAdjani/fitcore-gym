<x-app-layout>
    <div x-data="ptReschedule({
        sessionId: {{ $session->id }},
        trainerId: {{ $trainer->id ?? 1 }},
        initialDate: '{{ $session->session_date->toDateString() }}',
        currentSlot: '{{ substr($session->start_time, 0, 5) }}',
        slotsUrl: '{{ route('pt-sessions.slots') }}',
        availabilityUrl: '{{ route('pt-sessions.availability') }}'
    })" class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row sm:items-center justify-end gap-2 text-xs text-[#565A66]">


                <a href="{{ route('pt-sessions.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white hover:bg-zinc-100 text-[#16151A] border border-[#E5E7EB] text-xs font-semibold shadow-xs transition-colors w-fit">
                    &larr; Batalkan & Kembali
                </a>
            </div>

            <!-- Error Notification Banner -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-xs text-red-800 space-y-1">
                    <div class="font-bold text-sm text-red-900 flex items-center gap-2">
                        <span>⚠️ Gagal Mengubah Jadwal</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Page Title Card with Notice -->
            <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 sm:p-8 shadow-sm space-y-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#16151A] font-heading tracking-tight">
                    Ubah Jadwal Sesi Latihan
                </h1>
                <p class="text-sm text-[#565A66] leading-relaxed">
                    Pilih tanggal dan jam pengganti untuk sesi latihan privat Anda bersama <strong
                        class="text-[#16151A]">{{ $trainer->name }}</strong>.
                </p>

                <div
                    class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 text-xs text-amber-800 flex items-center gap-2 mt-2">
                    <div>
                        Jadwal saat ini:
                        <strong>{{ $session->session_date->locale('id')->translatedFormat('l, d F Y') }}</strong> pukul
                        <strong>{{ $session->time_range }}</strong>.
                        Perubahan jadwal tidak akan memotong kuota baru.
                    </div>
                </div>
            </div>

            <!-- Main 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column (8 cols): Date & Slot Picker -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Card 1: Pilih Tanggal Sesi (Google Calendar Month Grid) -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-5 sm:p-6 shadow-sm space-y-4">
                        <!-- Calendar Header Toolbar -->
                        <div class="flex items-center gap-2 sm:gap-4 pb-2 border-b border-[#E5E7EB]">
                            <!-- Today Button -->
                            <button 
                                type="button" 
                                @click="goToToday()"
                                class="px-3 py-1.5 rounded-full border border-[#E5E7EB] text-xs font-semibold text-[#16151A] hover:bg-gray-50 transition cursor-pointer"
                            >
                                Hari Ini
                            </button>

                            <!-- Navigation with Month Title between Prev and Next -->
                            <div class="flex items-center gap-1 sm:gap-2">
                                <!-- Month Navigation Arrows -->
                                <button 
                                    type="button" 
                                    @click="prevMonth()"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 transition cursor-pointer"
                                    aria-label="Bulan Sebelumnya"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>

                                <!-- Month Title (di tengah tombol next dan previous) -->
                                <h2 class="text-base sm:text-xl font-bold text-[#16151A] tracking-tight font-['Poppins'] px-1.5 text-center min-w-[130px]" x-text="currentMonthYear">
                                    Oktober 2026
                                </h2>

                                <button 
                                    type="button" 
                                    @click="nextMonth()"
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 transition cursor-pointer"
                                    aria-label="Bulan Berikutnya"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tampilan Kalender Bulan -->
                        <div class="rounded-2xl border border-[#E5E7EB] overflow-hidden bg-white">
                            <!-- Day Names Header Row (SUN, MON, TUE, WED, THU, FRI, SAT) -->
                            <div class="grid grid-cols-7 border-b border-[#E5E7EB] bg-white text-center">
                                <template x-for="header in ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT']" :key="header">
                                    <div class="text-[11px] font-semibold text-[#565A66] uppercase tracking-wider py-2.5" x-text="header"></div>
                                </template>
                            </div>

                            <!-- 7 Columns Calendar Body Grid -->
                            <div class="grid grid-cols-7 border-t border-[#E5E7EB] divide-x divide-[#E5E7EB]">
                                <template x-for="day in monthDays" :key="day.date">
                                    <div 
                                        @click="!day.isPast && selectDate(day.date)"
                                        :class="{
                                            'bg-[#ED1B45]/10 ring-2 ring-inset ring-[#ED1B45] font-bold': selectedDate === day.date,
                                            'bg-white hover:bg-gray-50/80 cursor-pointer': selectedDate !== day.date && !day.isPast && day.isCurrentMonth,
                                            'bg-gray-50/50 hover:bg-gray-100/70 cursor-pointer': selectedDate !== day.date && !day.isPast && !day.isCurrentMonth,
                                            'bg-gray-50/70 opacity-40 cursor-not-allowed': day.isPast
                                        }"
                                        class="min-h-[68px] sm:min-h-[80px] p-1.5 border-b border-[#E5E7EB] flex flex-col items-center justify-between transition select-none"
                                    >
                                        <!-- Day Number Header -->
                                        <div class="flex items-center justify-center mt-0.5">
                                            <template x-if="selectedDate === day.date">
                                                <div class="w-7 h-7 rounded-full bg-[#ED1B45] text-white font-bold text-xs sm:text-sm flex items-center justify-center shadow-xs" x-text="day.dayNum"></div>
                                            </template>
                                            <template x-if="selectedDate !== day.date && day.isToday">
                                                <div class="w-7 h-7 rounded-full bg-white border-2 border-[#ED1B45] text-[#ED1B45] font-bold text-xs sm:text-sm flex items-center justify-center shadow-2xs" x-text="day.dayNum"></div>
                                            </template>
                                            <template x-if="selectedDate !== day.date && !day.isToday">
                                                <span class="text-xs sm:text-sm font-medium" :class="day.isCurrentMonth ? 'text-[#16151A]' : 'text-gray-400'" x-text="day.dayNum"></span>
                                            </template>
                                        </div>

                                        <!-- Slot Availability Indicator -->
                                        <div class="mt-1 flex items-center justify-center">
                                            <template x-if="!day.isPast && availabilityMap[day.date] === true">
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-semibold"
                                                      :class="selectedDate === day.date ? 'bg-[#ED1B45] text-white' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'">
                                                    <span class="w-1.5 h-1.5 rounded-full" :class="selectedDate === day.date ? 'bg-white' : 'bg-emerald-500'"></span>
                                                    <span class="hidden sm:inline">Ada slot</span>
                                                </span>
                                            </template>
                                            <template x-if="!day.isPast && availabilityMap[day.date] === false">
                                                <span class="text-[9px] text-zinc-400 font-medium">Penuh</span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Pilih Jam Sesi Pengganti -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-6">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E5E7EB]">
                            <div>
                                <h2 class="text-base font-bold text-[#16151A] font-heading">
                                    Pilih Jam Sesi Pengganti
                                </h2>
                            </div>
                        </div>

                        <!-- Loading Slots Indicator -->
                        <div x-show="isLoadingSlots" class="py-12 text-center space-y-3">
                            <div
                                class="inline-block w-8 h-8 border-4 border-[#ED1B45] border-t-transparent rounded-full animate-spin">
                            </div>
                            <p class="text-xs text-[#565A66]">Memeriksa ketersediaan jam latihan...</p>
                        </div>

                        <!-- Slots Grid -->
                        <div x-show="!isLoadingSlots" class="space-y-6">
                            <!-- Periode 1: Pagi (07:00 - 11:00) -->
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-[#16151A]">
                                    Sesi Pagi (07:00 - 11:00)
                                </h3>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <template x-for="slot in slots.morning" :key="slot.time">
                                        <div class="relative">
                                            <div x-show="selectedSlot === slot.time"
                                                class="absolute -top-2.5 right-2 bg-[#ED1B45] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full shadow-md z-10">
                                                JADWAL BARU
                                            </div>
                                            <button type="button"
                                                @click="slot.status === 'available' && selectSlot(slot.time)"
                                                :disabled="slot.status !== 'available'" :class="{
                                                        'bg-[#FFE8EC] border-2 border-[#ED1B45] text-[#ED1B45] shadow-sm': selectedSlot === slot.time,
                                                        'bg-white hover:bg-zinc-50 border border-[#E5E7EB] text-[#16151A] hover:border-teal-400': selectedSlot !== slot.time && slot.status === 'available',
                                                        'bg-[#F9FAFB] border border-[#E5E7EB] text-zinc-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                                    }"
                                                class="w-full flex flex-col items-center justify-center p-3.5 rounded-2xl transition-all duration-150">
                                                <span class="text-sm font-bold" x-text="slot.time"></span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Periode 2: Siang & Sore (13:00 - 17:00) -->
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-[#16151A]">
                                    Sesi Siang & Sore (13:00 - 17:00)
                                </h3>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <template x-for="slot in slots.afternoon" :key="slot.time">
                                        <div class="relative">
                                            <div x-show="selectedSlot === slot.time"
                                                class="absolute -top-2.5 right-2 bg-[#ED1B45] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full shadow-md z-10">
                                                JADWAL BARU
                                            </div>
                                            <button type="button"
                                                @click="slot.status === 'available' && selectSlot(slot.time)"
                                                :disabled="slot.status !== 'available'" :class="{
                                                        'bg-[#FFE8EC] border-2 border-[#ED1B45] text-[#ED1B45] shadow-sm': selectedSlot === slot.time,
                                                        'bg-white hover:bg-zinc-50 border border-[#E5E7EB] text-[#16151A] hover:border-teal-400': selectedSlot !== slot.time && slot.status === 'available',
                                                        'bg-[#F9FAFB] border border-[#E5E7EB] text-zinc-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                                    }"
                                                class="w-full flex flex-col items-center justify-center p-3.5 rounded-2xl transition-all duration-150">
                                                <span class="text-sm font-bold" x-text="slot.time"></span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Periode 3: Malam (18:00 - 22:00) -->
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-[#16151A]">
                                    Sesi Malam (18:00 - 22:00)
                                </h3>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <template x-for="slot in slots.evening" :key="slot.time">
                                        <div class="relative">
                                            <div x-show="selectedSlot === slot.time"
                                                class="absolute -top-2.5 right-2 bg-[#ED1B45] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full shadow-md z-10">
                                                JADWAL BARU
                                            </div>
                                            <button type="button"
                                                @click="slot.status === 'available' && selectSlot(slot.time)"
                                                :disabled="slot.status !== 'available'" :class="{
                                                        'bg-[#FFE8EC] border-2 border-[#ED1B45] text-[#ED1B45] shadow-sm': selectedSlot === slot.time,
                                                        'bg-white hover:bg-zinc-50 border border-[#E5E7EB] text-[#16151A] hover:border-teal-400': selectedSlot !== slot.time && slot.status === 'available',
                                                        'bg-[#F9FAFB] border border-[#E5E7EB] text-zinc-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                                    }"
                                                class="w-full flex flex-col items-center justify-center p-3.5 rounded-2xl transition-all duration-150">
                                                <span class="text-sm font-bold" x-text="slot.time"></span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (4 cols): Coach Card & Reschedule Summary -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Coach Info Card -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-5 shadow-sm space-y-4">
                        <div class="flex items-center gap-3.5">
                            <div
                                class="w-14 h-14 rounded-full overflow-hidden bg-zinc-800 shrink-0 border-2 border-[#E5E7EB]">
                                <div
                                    class="w-full h-full bg-gradient-to-tr from-zinc-800 to-zinc-600 flex items-center justify-center text-white font-black text-lg">
                                    {{ substr($trainer->name ?? 'Rama', 0, 2) }}
                                </div>
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-sm font-extrabold text-[#16151A]">
                                    {{ $trainer->name }}
                                </h4>
                                <p class="text-[11px] text-[#565A66]">
                                    {{ $trainer->trainerProfile->specialization ?? 'Hypertrophy & Strength Conditioning' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Perubahan Jadwal (Dark Card #141414) -->
                    <div class="bg-[#141414] rounded-2xl p-6 text-white space-y-5 shadow-xl relative overflow-hidden">
                        <div
                            class="absolute -top-12 -right-12 w-44 h-44 bg-[#ED1B45]/20 rounded-full blur-3xl pointer-events-none">
                        </div>

                        <h3 class="text-base font-extrabold font-heading text-white">
                            Ringkasan Perubahan
                        </h3>

                        <div class="space-y-3.5 text-xs text-zinc-300">
                            <!-- Jadwal Lama -->
                            <div>
                                <span class="text-[#A1A1AA] text-[11px] block">Jadwal Sebelumnya:</span>
                                <span class="font-medium text-zinc-400 line-through">
                                    {{ $session->session_date->locale('id')->translatedFormat('d M Y') }}
                                    ({{ substr($session->start_time, 0, 5) }})
                                </span>
                            </div>

                            <!-- Tanggal Baru -->
                            <div>
                                <span class="text-[#A1A1AA] text-[11px] block">Tanggal Baru:</span>
                                <span class="font-bold text-white text-xs mt-0.5"
                                    x-text="formattedSelectedDate || selectedDate"></span>
                            </div>

                            <!-- Jam Baru -->
                            <div>
                                <span class="text-[#A1A1AA] text-[11px] block">Jam Sesi Baru:</span>
                                <span class="font-bold text-[#ED1B45] text-xs mt-0.5"
                                    x-text="selectedSlot ? (selectedSlot + ' - ' + endSlotTime + ' WIB') : 'Pilih slot jam'"></span>
                            </div>


                        </div>

                        <!-- Form Reschedule -->
                        <form method="POST" action="{{ route('pt-sessions.update', $session) }}" class="pt-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="session_date" :value="selectedDate">
                            <input type="hidden" name="start_time" :value="selectedSlot">

                            <button type="submit" :disabled="!selectedDate || !selectedSlot" :class="{
                                         'bg-[#ED1B45] hover:bg-[#D1123D] text-white shadow-lg shadow-[#ED1B45]/30 cursor-pointer': selectedDate && selectedSlot,
                                         'bg-zinc-800 text-zinc-500 cursor-not-allowed': !selectedDate || !selectedSlot
                                     }"
                                class="w-full py-3.5 rounded-full font-bold uppercase tracking-wider text-xs transition-all duration-200 flex items-center justify-center gap-2">
                                <span>Simpan Jadwal Baru</span>
                                <span>&rarr;</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Alpine Reschedule Script -->
    <script>
        function formatLocalDate(dateObj) {
            const y = dateObj.getFullYear();
            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
            const d = String(dateObj.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function parseLocalDate(dateStr) {
            if (!dateStr) return new Date();
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
            }
            return new Date(dateStr);
        }

        function ptReschedule(config) {
            return {
                sessionId: config.sessionId,
                trainerId: config.trainerId,
                selectedDate: config.initialDate,
                selectedSlot: config.currentSlot,
                isLoadingSlots: false,
                calendarView: 'month',
                currentViewDate: parseLocalDate(config.initialDate),
                monthDays: [],
                availabilityMap: {},
                dateStrip: [],
                slots: { morning: [], afternoon: [], evening: [] },
                formattedSelectedDate: '',
                endSlotTime: '',
                currentMonthYear: '',

                async init() {
                    this.computeEndTime(this.selectedSlot);
                    this.generateMonthGrid();
                    await Promise.all([
                        this.fetchMonthAvailability(),
                        this.fetchAvailability(this.selectedDate)
                    ]);

                    if (this.selectedDate) {
                        await this.fetchSlots(this.selectedDate);
                    }
                },

                generateMonthGrid() {
                    const year = this.currentViewDate.getFullYear();
                    const month = this.currentViewDate.getMonth();

                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    this.currentMonthYear = `${months[month]} ${year}`;

                    const startOfMonth = new Date(year, month, 1);
                    const startDayOfWeek = startOfMonth.getDay(); // 0 is Sun

                    const endOfMonth = new Date(year, month + 1, 0);

                    const totalCells = (startDayOfWeek + endOfMonth.getDate() <= 35) ? 35 : 42;
                    const days = [];
                    const todayStr = formatLocalDate(new Date());

                    for (let i = 0; i < totalCells; i++) {
                        const d = new Date(year, month, (1 - startDayOfWeek) + i);
                        const dateStr = formatLocalDate(d);
                        const isCurrentMonth = d.getMonth() === month;
                        const isToday = dateStr === todayStr;
                        const isPast = dateStr < todayStr;

                        days.push({
                            date: dateStr,
                            dayNum: d.getDate(),
                            isCurrentMonth,
                            isToday,
                            isPast,
                        });
                    }
                    this.monthDays = days;
                },

                async fetchMonthAvailability() {
                    if (!this.monthDays.length) return;
                    const startDateStr = this.monthDays[0].date;
                    try {
                        const url = `${config.availabilityUrl}?trainer_id=${this.trainerId}&start_date=${startDateStr}&days=42`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            const map = {};
                            (data.days || []).forEach(d => {
                                map[d.date] = d.has_slots;
                            });
                            this.availabilityMap = map;
                        }
                    } catch (e) {
                        console.error('Failed fetching month availability', e);
                    }
                },

                async prevMonth() {
                    this.currentViewDate = new Date(this.currentViewDate.getFullYear(), this.currentViewDate.getMonth() - 1, 1);
                    this.generateMonthGrid();
                    await this.fetchMonthAvailability();
                },

                async nextMonth() {
                    this.currentViewDate = new Date(this.currentViewDate.getFullYear(), this.currentViewDate.getMonth() + 1, 1);
                    this.generateMonthGrid();
                    await this.fetchMonthAvailability();
                },

                async goToToday() {
                    this.currentViewDate = new Date();
                    this.generateMonthGrid();
                    await this.fetchMonthAvailability();
                    const todayStr = formatLocalDate(new Date());
                    this.selectDate(todayStr);
                },

                async fetchAvailability(startDate) {
                    const dateToUse = startDate || config.initialDate;
                    try {
                        const url = `${config.availabilityUrl}?trainer_id=${this.trainerId}&start_date=${dateToUse}&days=7`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.dateStrip = data.days || [];
                        }
                    } catch (e) {
                        console.error('Failed fetching availability', e);
                    }
                },

                async fetchSlots(date) {
                    if (!date) return;
                    this.isLoadingSlots = true;
                    try {
                        const url = `${config.slotsUrl}?trainer_id=${this.trainerId}&date=${date}&ignore_session_id=${this.sessionId}`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.formattedSelectedDate = data.formatted_date || date;
                            this.slots = data.groups || { morning: [], afternoon: [], evening: [] };
                        }
                    } catch (e) {
                        console.error('Failed fetching slots', e);
                    } finally {
                        this.isLoadingSlots = false;
                    }
                },

                selectDate(date) {
                    this.selectedDate = date;
                    this.selectedSlot = '';
                    this.endSlotTime = '';
                    this.fetchSlots(date);
                },

                selectSlot(time) {
                    this.selectedSlot = time;
                    this.computeEndTime(time);
                },

                computeEndTime(time) {
                    if (!time) return;
                    const [h, m] = time.split(':').map(Number);
                    const endH = String((h + 1) % 24).padStart(2, '0');
                    this.endSlotTime = `${endH}:${String(m).padStart(2, '0')}`;
                }
            };
        }
    </script>
</x-app-layout>