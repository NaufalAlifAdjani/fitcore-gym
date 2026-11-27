{{--
ROLE: MEMBER
MODUL: Booking Sesi PT 3-Step Wizard Mobile (Tersinkronisasi Alur Bisnis Website)
VIEW: mobile.member.pt-sessions.create
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@php
    $defaultTrainer = $trainer ?? $trainers->first();
    $initialDate = now()->addDay()->toDateString();
    $initialDateLabel = now()->addDay()->translatedFormat('l, d F Y');
    $remainingQuota = $quota->remaining_sessions ?? 0;
@endphp

<x-layouts.mobile-app title="Booking Sesi PT - FitCore Athletic" active-tab="pt-sessions">
    <div x-data="{
        step: 1,
        selectedTrainerId: '{{ $defaultTrainer->id ?? 1 }}',
        selectedTrainerName: '{{ $defaultTrainer->name ?? 'Coach Rama' }}',
        selectedTrainerSpec: '{{ $defaultTrainer->trainerProfile->specialization ?? 'Hypertrophy & Conditioning' }}',
        selectedFocus: 'Chest & Triceps Hypertrophy',
        notes: '',
        agreedPolicy: false,
        activeFilter: 'semua',

        // Kalender & Slot Interaktif Sesuai Web Desktop
        selectedDate: '{{ $initialDate }}',
        formattedSelectedDate: '{{ $initialDateLabel }}',
        selectedSlot: '08:00',
        endSlotTime: '09:00',
        selectedTimeLabel: '08:00 - 09:00 WIB (60 Menit)',
        isLoadingSlots: false,
        currentViewDate: new Date({{ now()->year }}, {{ now()->month - 1 }}, {{ now()->day }}),
        monthDays: [],
        availabilityMap: {},
        slots: { morning: [], afternoon: [], evening: [] },
        currentMonthYear: '',

        async init() {
            this.generateMonthGrid();
            await this.fetchMonthAvailability();
            if (this.selectedDate) {
                await this.fetchSlots(this.selectedDate);
            }
        },

        generateMonthGrid() {
            const year = this.currentViewDate.getFullYear();
            const month = this.currentViewDate.getMonth();

            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            this.currentMonthYear = months[month] + ' ' + year;

            const startOfMonth = new Date(year, month, 1);
            const startDayOfWeek = startOfMonth.getDay(); // 0 is Sun

            const endOfMonth = new Date(year, month + 1, 0);

            const totalCells = (startDayOfWeek + endOfMonth.getDate() <= 35) ? 35 : 42;
            const days = [];

            const now = new Date();
            const todayStr = this.formatDate(now);

            for (let i = 0; i < totalCells; i++) {
                const d = new Date(year, month, (1 - startDayOfWeek) + i);
                const dateStr = this.formatDate(d);
                const isCurrentMonth = d.getMonth() === month;
                const isToday = dateStr === todayStr;
                const isPast = dateStr < todayStr;

                days.push({
                    date: dateStr,
                    dayNum: d.getDate(),
                    isCurrentMonth: isCurrentMonth,
                    isToday: isToday,
                    isPast: isPast,
                });
            }
            this.monthDays = days;
        },

        formatDate(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        },

        async fetchMonthAvailability() {
            if (!this.monthDays.length || !this.selectedTrainerId) return;
            const startDateStr = this.monthDays[0].date;
            try {
                const url = `{{ route('pt-sessions.availability') }}?trainer_id=${this.selectedTrainerId}&start_date=${startDateStr}&days=42`;
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
            const todayStr = this.formatDate(new Date());
            this.selectDate(todayStr);
        },

        async fetchSlots(date) {
            if (!date || !this.selectedTrainerId) return;
            this.isLoadingSlots = true;
            try {
                const url = `{{ route('pt-sessions.slots') }}?trainer_id=${this.selectedTrainerId}&date=${date}`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    const data = await res.json();
                    this.formattedSelectedDate = data.formatted_date || date;
                    const groups = data.groups || (data.data?.groups) || { morning: [], afternoon: [], evening: [] };
                    this.slots = groups;

                    const allSlots = [...(groups.morning || []), ...(groups.afternoon || []), ...(groups.evening || [])];
                    const activeStillValid = allSlots.some(s => s.time === this.selectedSlot && s.status === 'available');
                    if (!activeStillValid) {
                        const firstAvail = allSlots.find(s => s.status === 'available');
                        if (firstAvail) {
                            this.selectSlot(firstAvail.time);
                        } else {
                            this.selectedSlot = '';
                            this.endSlotTime = '';
                            this.selectedTimeLabel = 'Slot belum dipilih';
                        }
                    }
                }
            } catch (e) {
                console.error('Failed fetching slots', e);
            } finally {
                this.isLoadingSlots = false;
            }
        },

        selectDate(date) {
            this.selectedDate = date;
            this.fetchSlots(date);
        },

        selectSlot(time) {
            this.selectedSlot = time;
            const parts = time.split(':').map(Number);
            const h = parts[0] || 0;
            const m = parts[1] || 0;
            const endH = String((h + 1) % 24).padStart(2, '0');
            const endM = String(m).padStart(2, '0');
            this.endSlotTime = `${endH}:${endM}`;
            this.selectedTimeLabel = `${time} - ${endH}:${endM} WIB (60 Menit)`;
        },

        selectTrainer(id, name, spec) {
            this.selectedTrainerId = id;
            this.selectedTrainerName = name;
            this.selectedTrainerSpec = spec;
            this.fetchMonthAvailability();
            if (this.selectedDate) {
                this.fetchSlots(this.selectedDate);
            }
        },

        filterTrainer(spec) {
            if (this.activeFilter === 'semua') return true;
            const s = (spec || '').toLowerCase();
            if (this.activeFilter === 'hypertrophy') return s.includes('hypertrophy') || s.includes('muscle') || s.includes('cscs') || s.includes('conditioning') || s.includes('strength');
            if (this.activeFilter === 'fatloss') return s.includes('fat') || s.includes('loss') || s.includes('hiit') || s.includes('cardio') || s.includes('weight');
            if (this.activeFilter === 'rehab') return s.includes('rehab') || s.includes('mobility') || s.includes('recovery') || s.includes('posture');
            return true;
        }
    }" class="space-y-4">

        <!-- 1. Top Header with Back Navigation -->
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
            <button type="button"
                    @click="if (step > 1) { step-- } else { window.history.back(); }"
                    class="p-1.5 text-[#565A66] hover:text-[#16151A] transition flex items-center gap-1 cursor-pointer"
                    aria-label="Kembali">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-xs font-semibold" x-text="step > 1 ? 'Langkah Sebelumnya' : 'Kembali'">Kembali</span>
            </button>
            <h1 class="text-sm font-black font-poppins text-[#16151A] tracking-wide">
                Booking Sesi PT
            </h1>
            <div class="w-8"></div>
        </div>

        <!-- 2. Alert Notifikasi Error -->
        @if ($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Terjadi kesalahan saat memproses booking:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 3. Stepper Pills Row (1. Pilih Trainer, 2. Waktu, 3. Konfirmasi) -->
        <div class="flex items-center gap-1 p-1 bg-white rounded-xl border border-[#E5E7EB] text-[11px] font-bold text-center shadow-xs">
            <div class="flex-1 py-1.5 rounded-lg transition-all"
                 :class="step === 1 ? 'bg-[#FF5722] text-white shadow-xs' : (step > 1 ? 'text-[#16151A] bg-gray-100' : 'text-[#565A66]')">
                1. Pilih Trainer
            </div>
            <div class="flex-1 py-1.5 rounded-lg transition-all"
                 :class="step === 2 ? 'bg-[#FF5722] text-white shadow-xs' : (step > 2 ? 'text-[#16151A] bg-gray-100' : 'text-[#565A66]')">
                2. Waktu
            </div>
            <div class="flex-1 py-1.5 rounded-lg transition-all"
                 :class="step === 3 ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66]'">
                3. Konfirmasi
            </div>
        </div>

        <!-- 4. Banner Kuota Paket Sesi PT (Direct Warning & Action if Quota 0) -->
        @if ($remainingQuota > 0)
            <div class="p-3.5 rounded-2xl bg-white border border-[#E5E7EB] flex items-center justify-between text-xs shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center font-bold shrink-0 border border-orange-200">
                        <svg width="18" height="18" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="#FF5722"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-[#565A66] uppercase">Saldo Kuota PT Aktif</span>
                        <span class="font-bold text-[#16151A] text-xs">Sisa Kuota: <strong class="text-[#FF5722]">{{ $remainingQuota }} Sesi</strong></span>
                    </div>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-[10px]">
                    Aktif
                </span>
            </div>
        @else
            <!-- Notice Banner Kuota 0: Langsung Mengarahkan ke Pembelian Sesi PT -->
            <div class="p-4 rounded-2xl bg-orange-50 border border-orange-200 text-xs shadow-xs space-y-3">
                <div class="flex items-center gap-2 text-orange-950 font-bold">
                    <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Saldo Kuota 0 Sesi: Wajib Beli Paket Terlebih Dahulu</span>
                </div>
                <p class="text-[11px] text-[#565A66] leading-relaxed">
                    Anda belum memiliki saldo sesi latihan aktif. Beli paket sesi PT terlebih dahulu agar Anda dapat memilih pelatih dan menjadwalkan sesi latihan privat.
                </p>
                <div>
                    <a href="{{ route('mobile.member.pt-packages.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-orange-500/20 active:scale-98 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Beli Paket Sesi PT Sekarang &rarr;</span>
                    </a>
                </div>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- STEP 1: PILIH TRAINER & TARGET FOKUS       -->
        <!-- ========================================== -->
        <div x-show="step === 1" x-transition class="space-y-4">
            
            <!-- Filter Kategori Pelatih -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                    Pilih Pelatih Pendamping
                </label>
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
                    <button type="button" @click="activeFilter = 'semua'"
                        :class="activeFilter === 'semua' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer text-xs whitespace-nowrap">
                        Semua
                    </button>
                    <button type="button" @click="activeFilter = 'hypertrophy'"
                        :class="activeFilter === 'hypertrophy' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer text-xs whitespace-nowrap">
                        Hypertrophy
                    </button>
                    <button type="button" @click="activeFilter = 'fatloss'"
                        :class="activeFilter === 'fatloss' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer text-xs whitespace-nowrap">
                        Fat Loss
                    </button>
                    <button type="button" @click="activeFilter = 'rehab'"
                        :class="activeFilter === 'rehab' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer text-xs whitespace-nowrap">
                        Rehab
                    </button>
                </div>
            </div>

            <!-- List Horizontal Kartu Trainer -->
            <div class="flex gap-3 overflow-x-auto pb-2 pt-1 no-scrollbar">
                @forelse ($trainers as $t)
                    @php
                        $cFirstName = strtolower(explode(' ', trim(str_replace('Coach ', '', $t->name)))[0]);
                        $cPhoto = file_exists(public_path("images/trainers/coach-{$cFirstName}.jpg"))
                            ? asset("images/trainers/coach-{$cFirstName}.jpg")
                            : ($t->trainerProfile?->photo_path ? asset('storage/' . $t->trainerProfile->photo_path) : null);
                    @endphp
                    <div x-show="filterTrainer('{{ addslashes($t->trainerProfile->specialization ?? '') }}')"
                         @click="selectTrainer('{{ $t->id }}', '{{ $t->name }}', '{{ $t->trainerProfile->specialization ?? 'CSCS Certified' }}')"
                         class="min-w-[190px] max-w-[210px] rounded-2xl border transition-all cursor-pointer shrink-0 relative flex flex-col justify-between overflow-hidden"
                         :class="selectedTrainerId == '{{ $t->id }}' ? 'bg-white border-[#FF5722] shadow-md shadow-orange-500/15 ring-2 ring-[#FF5722]' : 'bg-white border-[#E5E7EB] hover:border-gray-300 shadow-[0_4px_12px_rgba(0,0,0,0.04)]'">
                        
                        <!-- 1. Foto PT -->
                        <div class="w-full aspect-[4/3] bg-[#2C2828] relative overflow-hidden flex items-center justify-center">
                            @if ($cPhoto)
                                <img src="{{ $cPhoto }}" alt="{{ $t->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-b from-[#383332] to-[#252222] flex flex-col items-center justify-center text-zinc-300 p-2">
                                    <svg class="w-8 h-8 text-zinc-500 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-[9px] font-semibold tracking-wider uppercase text-zinc-400">Foto PT</span>
                                </div>
                            @endif

                            <!-- Checkmark Terpilih Pill -->
                            <div x-show="selectedTrainerId == '{{ $t->id }}'" class="absolute top-2 right-2 px-2 py-0.5 rounded-full bg-[#FF5722] text-white text-[9px] font-bold shadow-xs">
                                Terpilih
                            </div>
                        </div>

                        <!-- 2. Data Pelatih -->
                        <div class="p-3 bg-[#F6F6F7] flex-1 flex flex-col justify-between space-y-2">
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold text-[#16151A] truncate font-poppins">
                                    {{ $t->name }}
                                </h4>
                                <div class="text-[10px] font-bold text-[#FF5722] truncate">
                                    {{ $t->trainerProfile->specialization ?? 'CSCS Certified' }}
                                </div>
                                <div class="text-[10px] text-[#565A66] font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $t->trainerProfile->experience ?? 1 }} Thn Jam Terbang</span>
                                </div>
                                <p class="text-[10px] text-[#565A66] line-clamp-2 leading-relaxed pt-0.5">
                                    {{ $t->trainerProfile->bio ?? 'Membimbing program latihan presisi tinggi.' }}
                                </p>
                            </div>

                            <div class="pt-2 border-t border-[#E5E7EB] flex items-center justify-between text-[10px]">
                                <span class="text-emerald-600 font-semibold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Tersedia</span>
                                </span>
                                <span class="text-[#FF5722] font-bold text-[10px]">Pilih &rarr;</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] text-xs text-[#565A66]">
                        Tidak ada pelatih yang tersedia saat ini.
                    </div>
                @endforelse
            </div>

            <!-- Fokus Target Latihan Sesi Ini -->
            <div class="space-y-2 pt-1">
                <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                    Fokus Target Latihan Sesi Ini
                </label>

                <div class="space-y-2">
                    <div @click="selectedFocus = 'Chest & Triceps Hypertrophy'"
                         class="p-3.5 rounded-2xl border transition cursor-pointer flex items-center justify-between"
                         :class="selectedFocus === 'Chest & Triceps Hypertrophy' ? 'bg-orange-50/50 border-[#FF5722] ring-1 ring-[#FF5722]' : 'bg-white border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]'">
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-[#16151A] block">Chest &amp; Triceps Hypertrophy</span>
                            <span class="text-[11px] text-[#565A66] block">Fokus pembentukan dada, bahu depan, &amp; tricep</span>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0"
                             :class="selectedFocus === 'Chest & Triceps Hypertrophy' ? 'border-[#FF5722] bg-[#FF5722]' : 'border-gray-300'">
                            <div x-show="selectedFocus === 'Chest & Triceps Hypertrophy'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>

                    <div @click="selectedFocus = 'Legs & Core Power'"
                         class="p-3.5 rounded-2xl border transition cursor-pointer flex items-center justify-between"
                         :class="selectedFocus === 'Legs & Core Power' ? 'bg-orange-50/50 border-[#FF5722] ring-1 ring-[#FF5722]' : 'bg-white border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]'">
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-[#16151A] block">Legs &amp; Core Power</span>
                            <span class="text-[11px] text-[#565A66] block">Peningkatan kekuatan paha, glutes, &amp; otot inti</span>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0"
                             :class="selectedFocus === 'Legs & Core Power' ? 'border-[#FF5722] bg-[#FF5722]' : 'border-gray-300'">
                            <div x-show="selectedFocus === 'Legs & Core Power'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>

                    <div @click="selectedFocus = 'Full Body HIIT Conditioning'"
                         class="p-3.5 rounded-2xl border transition cursor-pointer flex items-center justify-between"
                         :class="selectedFocus === 'Full Body HIIT Conditioning' ? 'bg-orange-50/50 border-[#FF5722] ring-1 ring-[#FF5722]' : 'bg-white border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]'">
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-[#16151A] block">Full Body HIIT Conditioning</span>
                            <span class="text-[11px] text-[#565A66] block">Kardio intensitas tinggi &amp; ketahanan metabolik</span>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0"
                             :class="selectedFocus === 'Full Body HIIT Conditioning' ? 'border-[#FF5722] bg-[#FF5722]' : 'border-gray-300'">
                            <div x-show="selectedFocus === 'Full Body HIIT Conditioning'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>

                    <div @click="selectedFocus = 'Mobility & Rehab Flexibility'"
                         class="p-3.5 rounded-2xl border transition cursor-pointer flex items-center justify-between"
                         :class="selectedFocus === 'Mobility & Rehab Flexibility' ? 'bg-orange-50/50 border-[#FF5722] ring-1 ring-[#FF5722]' : 'bg-white border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]'">
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-[#16151A] block">Mobility &amp; Rehab Flexibility</span>
                            <span class="text-[11px] text-[#565A66] block">Peregangan sendi, fleksibilitas, &amp; pemulihan cedera</span>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0"
                             :class="selectedFocus === 'Mobility & Rehab Flexibility' ? 'border-[#FF5722] bg-[#FF5722]' : 'border-gray-300'">
                            <div x-show="selectedFocus === 'Mobility & Rehab Flexibility'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Lanjut ke Step 2 -->
            <div class="pt-3">
                <button type="button"
                        @click="if (selectedTrainerId && selectedFocus) { step = 2; fetchSlots(selectedDate); }"
                        :disabled="!selectedTrainerId || !selectedFocus"
                        :class="(!selectedTrainerId || !selectedFocus) ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] cursor-pointer shadow-md shadow-orange-500/25'"
                        class="w-full py-3.5 rounded-full text-white font-semibold text-sm flex items-center justify-center gap-2 transition">
                    <span>Lanjut ke Pilih Waktu</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 2: KALENDER BULAN & PILIH JAM SESI    -->
        <!-- ========================================== -->
        <div x-show="step === 2" x-transition class="space-y-4" x-cloak>
            
            <!-- Summary Pelatih Terpilih -->
            <div class="p-3 rounded-2xl bg-white border border-[#E5E7EB] flex items-center justify-between shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center font-bold text-xs border border-orange-200">
                        PT
                    </div>
                    <div>
                        <span class="text-xs font-bold text-[#16151A] block font-poppins" x-text="'Coach ' + selectedTrainerName">Coach</span>
                        <span class="text-[10px] text-[#565A66] block font-sans" x-text="selectedFocus">Fokus Latihan</span>
                    </div>
                </div>
                <button type="button" @click="step = 1" class="text-xs text-[#FF5722] font-semibold hover:underline cursor-pointer">
                    Ubah
                </button>
            </div>

            <!-- Kalender Bulanan Interaktif (Sesuai Website) -->
            <div class="bg-white rounded-2xl border border-[#E5E7EB] p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
                <!-- Navigasi Bulan & Tombol Hari Ini -->
                <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                    <button type="button"
                            @click="goToToday()"
                            class="px-3 py-1 text-[11px] font-semibold text-[#16151A] bg-white border border-[#E5E7EB] rounded-full hover:border-[#FF5722] hover:text-[#FF5722] active:bg-gray-100 transition shadow-2xs cursor-pointer">
                        Hari Ini
                    </button>

                    <div class="flex items-center gap-1.5">
                        <button type="button"
                                @click="prevMonth()"
                                class="w-7 h-7 flex items-center justify-center rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 active:bg-gray-200 transition cursor-pointer"
                                aria-label="Bulan sebelumnya">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <span class="text-xs font-bold font-poppins text-[#16151A] min-w-[110px] text-center" x-text="currentMonthYear">
                            Bulan Tahun
                        </span>

                        <button type="button"
                                @click="nextMonth()"
                                class="w-7 h-7 flex items-center justify-center rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 active:bg-gray-200 transition cursor-pointer"
                                aria-label="Bulan berikutnya">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Grid Kalender 7 Kolom -->
                <div class="rounded-xl border border-[#E5E7EB] overflow-hidden bg-white">
                    <!-- Header Nama Hari (MIN, SEN, SEL, RAB, KAM, JUM, SAB) -->
                    <div class="grid grid-cols-7 border-b border-[#E5E7EB] bg-gray-50/70 text-center">
                        <template x-for="header in ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB']" :key="header">
                            <div class="text-[9px] font-bold text-[#565A66] py-1.5" x-text="header"></div>
                        </template>
                    </div>

                    <!-- Cells Kalender -->
                    <div class="grid grid-cols-7 divide-x divide-[#E5E7EB]">
                        <template x-for="day in monthDays" :key="day.date">
                            <div @click="!day.isPast && selectDate(day.date)"
                                 :class="{
                                     'bg-[#FF5722]/10 ring-2 ring-inset ring-[#FF5722] font-bold': selectedDate === day.date,
                                     'bg-white hover:bg-gray-50/80 cursor-pointer': selectedDate !== day.date && !day.isPast && day.isCurrentMonth,
                                     'bg-gray-50/40 hover:bg-gray-100/70 cursor-pointer': selectedDate !== day.date && !day.isPast && !day.isCurrentMonth,
                                     'bg-gray-100/60 opacity-40 cursor-not-allowed': day.isPast
                                 }"
                                 class="min-h-[50px] p-1 border-b border-[#E5E7EB] flex flex-col items-center justify-between transition select-none">
                                
                                <div class="mt-0.5">
                                    <template x-if="selectedDate === day.date">
                                        <div class="w-6 h-6 rounded-full bg-[#FF5722] text-white font-bold text-[11px] flex items-center justify-center shadow-xs" x-text="day.dayNum"></div>
                                    </template>
                                    <template x-if="selectedDate !== day.date && day.isToday">
                                        <div class="w-6 h-6 rounded-full bg-white border-2 border-[#FF5722] text-[#FF5722] font-bold text-[11px] flex items-center justify-center" x-text="day.dayNum"></div>
                                    </template>
                                    <template x-if="selectedDate !== day.date && !day.isToday">
                                        <span class="text-[11px] font-medium" :class="day.isCurrentMonth ? 'text-[#16151A]' : 'text-gray-400'" x-text="day.dayNum"></span>
                                    </template>
                                </div>

                                <!-- Indikator Ketersediaan Slot -->
                                <div class="mb-0.5">
                                    <template x-if="!day.isPast && availabilityMap[day.date] === true">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" :class="selectedDate === day.date ? 'bg-[#FF5722]' : 'bg-emerald-500'"></span>
                                    </template>
                                    <template x-if="!day.isPast && availabilityMap[day.date] === false">
                                        <span class="text-[8px] text-gray-400 font-medium">Full</span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Label Tanggal Aktif Terpilih -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <span class="text-[#565A66]">Tanggal Dipilih:</span>
                    <span class="font-bold text-[#FF5722]" x-text="formattedSelectedDate || selectedDate"></span>
                </div>
            </div>

            <!-- Pemilihan Slot Jam (Sesuai Web Desktop: Pagi, Siang & Sore, Malam) -->
            <div class="bg-white rounded-2xl border border-[#E5E7EB] p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                            Pilih Jam Sesi Latihan
                        </h3>
                        <p class="text-[10px] text-[#565A66] mt-0.5">Durasi 60 Menit per Sesi</p>
                    </div>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                        Slot Real-Time
                    </span>
                </div>

                <!-- Loader Saat Memuat Slot -->
                <div x-show="isLoadingSlots" class="py-8 text-center space-y-2">
                    <div class="inline-block w-6 h-6 border-3 border-[#FF5722] border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-[11px] text-[#565A66]">Memeriksa ketersediaan jam latihan...</p>
                </div>

                <!-- Slot Groups Dinamis -->
                <div x-show="!isLoadingSlots" class="space-y-4">

                    <!-- Periode 1: Pagi (07:00 - 11:00) -->
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Sesi Pagi (07:00 - 11:00)</span>
                        <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                            <template x-for="slot in slots.morning" :key="slot.time">
                                <div class="relative">
                                    <div x-show="selectedSlot === slot.time"
                                         class="absolute -top-2 right-2 bg-[#FF5722] text-white text-[8px] font-extrabold uppercase px-1.5 py-0.2 rounded-full shadow-xs z-10">
                                        DIPILIH
                                    </div>
                                    <button type="button"
                                            @click="slot.status === 'available' && selectSlot(slot.time)"
                                            :disabled="slot.status !== 'available'"
                                            :class="{
                                                'bg-[#FFE8EC] border-2 border-[#FF5722] text-[#FF5722] shadow-xs font-bold': selectedSlot === slot.time,
                                                'bg-white text-[#16151A] border border-[#E5E7EB] hover:border-orange-300 shadow-xs cursor-pointer': selectedSlot !== slot.time && slot.status === 'available',
                                                'bg-gray-50 border border-gray-200 text-gray-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                            }"
                                            class="w-full p-2.5 rounded-xl text-center transition">
                                        <span x-text="slot.time"></span>
                                        <template x-if="slot.status === 'booked'">
                                            <span class="block text-[9px] font-normal text-rose-500 mt-0.5">Penuh</span>
                                        </template>
                                        <template x-if="slot.status === 'passed'">
                                            <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Lewat</span>
                                        </template>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Periode 2: Siang & Sore (13:00 - 17:00) -->
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Sesi Siang &amp; Sore (13:00 - 17:00)</span>
                        <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                            <template x-for="slot in slots.afternoon" :key="slot.time">
                                <div class="relative">
                                    <div x-show="selectedSlot === slot.time"
                                         class="absolute -top-2 right-2 bg-[#FF5722] text-white text-[8px] font-extrabold uppercase px-1.5 py-0.2 rounded-full shadow-xs z-10">
                                        DIPILIH
                                    </div>
                                    <button type="button"
                                            @click="slot.status === 'available' && selectSlot(slot.time)"
                                            :disabled="slot.status !== 'available'"
                                            :class="{
                                                'bg-[#FFE8EC] border-2 border-[#FF5722] text-[#FF5722] shadow-xs font-bold': selectedSlot === slot.time,
                                                'bg-white text-[#16151A] border border-[#E5E7EB] hover:border-orange-300 shadow-xs cursor-pointer': selectedSlot !== slot.time && slot.status === 'available',
                                                'bg-gray-50 border border-gray-200 text-gray-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                            }"
                                            class="w-full p-2.5 rounded-xl text-center transition">
                                        <span x-text="slot.time"></span>
                                        <template x-if="slot.status === 'booked'">
                                            <span class="block text-[9px] font-normal text-rose-500 mt-0.5">Penuh</span>
                                        </template>
                                        <template x-if="slot.status === 'passed'">
                                            <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Lewat</span>
                                        </template>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Periode 3: Malam (18:00 - 22:00) -->
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Sesi Malam (18:00 - 22:00)</span>
                        <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                            <template x-for="slot in slots.evening" :key="slot.time">
                                <div class="relative">
                                    <div x-show="selectedSlot === slot.time"
                                         class="absolute -top-2 right-2 bg-[#FF5722] text-white text-[8px] font-extrabold uppercase px-1.5 py-0.2 rounded-full shadow-xs z-10">
                                        DIPILIH
                                    </div>
                                    <button type="button"
                                            @click="slot.status === 'available' && selectSlot(slot.time)"
                                            :disabled="slot.status !== 'available'"
                                            :class="{
                                                'bg-[#FFE8EC] border-2 border-[#FF5722] text-[#FF5722] shadow-xs font-bold': selectedSlot === slot.time,
                                                'bg-white text-[#16151A] border border-[#E5E7EB] hover:border-orange-300 shadow-xs cursor-pointer': selectedSlot !== slot.time && slot.status === 'available',
                                                'bg-gray-50 border border-gray-200 text-gray-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                            }"
                                            class="w-full p-2.5 rounded-xl text-center transition">
                                        <span x-text="slot.time"></span>
                                        <template x-if="slot.status === 'booked'">
                                            <span class="block text-[9px] font-normal text-rose-500 mt-0.5">Penuh</span>
                                        </template>
                                        <template x-if="slot.status === 'passed'">
                                            <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Lewat</span>
                                        </template>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Tombol Navigasi Step 2 -->
            <div class="flex items-center gap-3 pt-2">
                <button type="button" @click="step = 1" class="w-1/3 py-3 rounded-full bg-white border border-[#E5E7EB] text-[#16151A] text-xs font-bold text-center cursor-pointer hover:bg-gray-50 transition shadow-xs">
                    Kembali
                </button>
                <button type="button"
                        @click="if (selectedSlot) { step = 3; }"
                        :disabled="!selectedSlot"
                        :class="!selectedSlot ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] cursor-pointer shadow-md shadow-orange-500/25'"
                        class="w-2/3 py-3 rounded-full text-white text-xs font-semibold transition">
                    Lanjut ke Konfirmasi →
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 3: KONFIRMASI RINGKASAN & SUBMIT     -->
        <!-- ========================================== -->
        <div x-show="step === 3" x-transition class="space-y-4" x-cloak>
            
            <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] text-[#16151A] space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-2.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#FF5722]">Tinggal 1 Langkah Lagi</span>
                    <span class="text-xs font-bold text-[#565A66]">Ringkasan Sesi</span>
                </div>

                <div class="space-y-2.5 text-xs font-sans">
                    <div class="flex items-center justify-between">
                        <span class="text-[#565A66]">Personal Trainer</span>
                        <span class="font-bold text-[#16151A] font-poppins" x-text="'Coach ' + selectedTrainerName">Coach</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#565A66]">Tanggal Latihan</span>
                        <span class="font-bold text-[#16151A]" x-text="formattedSelectedDate || selectedDate">{{ $initialDateLabel }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#565A66]">Jam Sesi</span>
                        <span class="font-bold text-[#FF5722]" x-text="selectedTimeLabel">08:00 - 09:00 WIB (60 Menit)</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#565A66]">Target &amp; Fokus</span>
                        <span class="font-bold text-[#16151A] truncate max-w-[200px]" x-text="selectedFocus">Chest &amp; Triceps</span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-[#E5E7EB]">
                        <span class="text-[#565A66]">Potong Kuota</span>
                        @if ($remainingQuota > 0)
                            <span class="font-bold text-emerald-700">1 Sesi dari Saldo Paket</span>
                        @else
                            <span class="font-bold text-rose-600">Saldo 0 Sesi (Wajib Beli Paket)</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Submit POST ke PtSessionController@store -->
            <form method="POST" action="{{ route('pt-sessions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="trainer_id" :value="selectedTrainerId">
                <input type="hidden" name="session_date" :value="selectedDate">
                <input type="hidden" name="start_time" :value="selectedSlot">
                <input type="hidden" name="workout_focus" :value="selectedFocus">

                <!-- Catatan Tambahan untuk Coach (Opsional) -->
                <div class="space-y-1">
                    <label for="notes" class="block text-xs font-bold text-[#16151A]">
                        Catatan untuk Pelatih <span class="text-[#565A66] font-normal">(Opsional)</span>
                    </label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Misal: Otot paha agak tegang, ingin fokus pemanasan lebih lama..."
                              class="w-full p-3 rounded-xl bg-white border border-[#E5E7EB] text-xs text-[#16151A] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] shadow-xs"></textarea>
                </div>

                <!-- Checkbox Kebijakan Reschedule/Batal -->
                <label class="flex items-start gap-2.5 cursor-pointer text-xs text-[#565A66] pt-1">
                    <input type="checkbox" required name="agreed_policy" x-model="agreedPolicy"
                           class="w-4 h-4 mt-0.5 text-[#FF5722] border-[#E5E7EB] bg-white rounded focus:ring-[#FF5722] shrink-0 cursor-pointer" />
                    <span class="text-[11px] leading-tight select-none">
                        Saya menyetujui kebijakan pembatalan &amp; reschedule sesi maksimal <strong class="text-[#16151A]">24 jam sebelum jadwal latihan</strong> dimulai.
                    </span>
                </label>

                <!-- Tombol Submit Akhir -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="step = 2" class="w-1/3 py-3.5 rounded-full bg-white border border-[#E5E7EB] text-[#16151A] text-xs font-bold text-center cursor-pointer hover:bg-gray-50 transition shadow-xs">
                        Kembali
                    </button>
                    @if ($remainingQuota > 0)
                        <button type="submit"
                                :disabled="!agreedPolicy"
                                :class="!agreedPolicy ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#FF5722] hover:bg-[#E64A19] cursor-pointer shadow-md shadow-orange-500/25 active:scale-[0.98]'"
                                class="w-2/3 py-3.5 rounded-full text-white text-xs font-bold flex items-center justify-center gap-1.5 transition">
                            <span>Konfirmasi Booking</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    @else
                        <a href="{{ route('mobile.member.pt-packages.index') }}"
                           class="w-2/3 py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/25 active:scale-[0.98] transition">
                            <span>Beli Paket Sesi Dulu &rarr;</span>
                        </a>
                    @endif
                </div>
            </form>

        </div>

    </div>
</x-layouts.mobile-app>
