<x-app-layout>
    <div x-data="ptBooking({
        trainerId: {{ $trainer->id ?? 1 }},
        initialDate: '{{ now()->toDateString() }}',
        slotsUrl: '{{ route('pt-sessions.slots') }}',
        availabilityUrl: '{{ route('pt-sessions.availability') }}'
    })"
    class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs & Status Sync -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-[#565A66]">
                <nav class="flex items-center gap-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#16151A] transition-colors">Beranda</a>
                    <span>/</span>
                    <a href="#" class="hover:text-[#16151A] transition-colors">Personal Trainer</a>
                    <span>/</span>
                    <span class="text-[#16151A] font-bold">Pilih Jadwal</span>
                </nav>

                <div class="flex items-center gap-2 text-emerald-600 font-semibold text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sistem Sinkronisasi Real-Time</span>
                </div>
            </div>

            <!-- Page Title Card -->
            <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 sm:p-8 shadow-sm">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#16151A] font-heading tracking-tight">
                    Pilih Jadwal & Jam Sesi Personal Trainer
                </h1>
                <p class="text-sm text-[#565A66] mt-1.5 leading-relaxed">
                    Pilih tanggal dan slot waktu latihan bersama pelatih pribadi Anda sesuai ketersediaan kuota paket aktif.
                </p>
            </div>

            <!-- Main 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column (8 cols): Date & Slot Picker -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Card 1: Pilih Tanggal Sesi -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-5">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-bold text-[#16151A] font-heading">
                                Pilih Tanggal Sesi
                            </h2>

                            <!-- Month Navigation -->
                            <div class="flex items-center gap-3 text-xs font-bold text-[#16151A]">
                                <button type="button" @click="changeWeek(-7)" class="p-1 hover:text-[#ED1B45] transition-colors">
                                    &lsaquo;
                                </button>
                                <span x-text="currentMonthYear">Oktober 2026</span>
                                <button type="button" @click="changeWeek(7)" class="p-1 hover:text-[#ED1B45] transition-colors">
                                    &rsaquo;
                                </button>
                            </div>
                        </div>

                        <!-- 7 Days Strip -->
                        <div class="grid grid-cols-7 gap-2.5 sm:gap-3">
                            <template x-for="day in dateStrip" :key="day.date">
                                <button type="button"
                                        @click="selectDate(day.date)"
                                        :class="{
                                            'bg-[#ED1B45] text-white shadow-lg shadow-[#ED1B45]/30 ring-2 ring-[#ED1B45] scale-102': selectedDate === day.date,
                                            'bg-white hover:bg-zinc-50 text-[#16151A] border border-[#E5E7EB]': selectedDate !== day.date && day.has_slots,
                                            'bg-zinc-50 text-zinc-400 border border-zinc-200 cursor-not-allowed opacity-60': !day.has_slots
                                        }"
                                        class="flex flex-col items-center justify-center py-4 px-1 rounded-2xl transition-all duration-200 text-center">
                                    <span class="text-xs font-semibold"
                                          :class="selectedDate === day.date ? 'text-white' : 'text-[#565A66]'"
                                          x-text="day.day_name"></span>
                                    <span class="text-xl font-extrabold my-1 font-heading"
                                          :class="selectedDate === day.date ? 'text-white' : 'text-[#16151A]'"
                                          x-text="day.day_num"></span>

                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Card 2: Pilih Jam Sesi -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E5E7EB]">
                            <div>
                                <h2 class="text-base font-bold text-[#16151A] font-heading">
                                    Pilih Jam Sesi
                                </h2>
                                <p class="text-xs text-[#565A66] mt-0.5">
                                    Durasi 60 Menit — Jadwal Real-Time Coach Rama Prasetya
                                </p>
                            </div>

                            <!-- Legend -->
                            <div class="flex items-center gap-4 text-xs font-medium">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                                    <span class="text-[#565A66]">Tersedia</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#ED1B45]"></span>
                                    <span class="text-[#ED1B45] font-bold">Pilihan Anda</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-400"></span>
                                    <span class="text-zinc-400">Penuh / Tutup</span>
                                </div>
                            </div>
                        </div>

                        <!-- Loading Slots Indicator -->
                        <div x-show="isLoadingSlots" class="py-12 text-center space-y-3">
                            <div class="inline-block w-8 h-8 border-4 border-[#ED1B45] border-t-transparent rounded-full animate-spin"></div>
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
                                            <!-- SLOT DIPILIH Badge -->
                                            <div x-show="selectedSlot === slot.time"
                                                 class="absolute -top-2.5 right-2 bg-[#ED1B45] text-white text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full shadow-md z-10">
                                                SLOT DIPILIH
                                            </div>
                                            <button type="button"
                                                    @click="slot.status === 'available' && selectSlot(slot.time)"
                                                    :disabled="slot.status !== 'available'"
                                                    :class="{
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
                                                SLOT DIPILIH
                                            </div>
                                            <button type="button"
                                                    @click="slot.status === 'available' && selectSlot(slot.time)"
                                                    :disabled="slot.status !== 'available'"
                                                    :class="{
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
                                                SLOT DIPILIH
                                            </div>
                                            <button type="button"
                                                    @click="slot.status === 'available' && selectSlot(slot.time)"
                                                    :disabled="slot.status !== 'available'"
                                                    :class="{
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

                <!-- Right Column (4 cols): Coach Card & Ringkasan Booking -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Coach & Quota Card (White card) -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-5 shadow-sm space-y-5">
                        <!-- Coach Info -->
                        <div class="flex items-center gap-3.5">
                            <div class="w-14 h-14 rounded-full overflow-hidden bg-zinc-800 shrink-0 border-2 border-[#E5E7EB] relative">
                                <div class="w-full h-full bg-gradient-to-tr from-zinc-800 to-zinc-600 flex items-center justify-center text-white font-black text-lg">
                                    {{ substr($trainer->name ?? 'Rama', 0, 2) }}
                                </div>
                            </div>
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-1.5">
                                    <h4 class="text-sm font-extrabold text-[#16151A] leading-tight">
                                        {{ $trainer->name ?? 'Coach Rama Prasetya, CSCS' }}
                                    </h4>
                                    <span class="text-[#ED1B45] text-xs">✓</span>
                                </div>
                                <p class="text-[11px] text-[#565A66]">
                                    {{ $trainer->trainerProfile->tier ?? 'Senior PT Tier III' }} • {{ $trainer->trainerProfile->studio ?? 'SCBD Studio' }}
                                </p>
                                <p class="text-[11px] text-amber-500 font-bold">
                                    ★ {{ number_format($trainer->trainerProfile->rating ?? 4.98, 2) }} <span class="text-[#565A66] font-normal">({{ $trainer->trainerProfile->review_count ?? 184 }} ulasan member)</span>
                                </p>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-[#E5E7EB] pt-4 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-[#16151A]">Sisa Kuota Sesi</span>
                                <div class="font-extrabold text-sm">
                                    <span class="text-[#ED1B45]">{{ $quota->remaining_sessions ?? 8 }}</span>
                                    <span class="text-[#565A66] font-normal text-xs">/ {{ $quota->total_sessions ?? 12 }} Sesi</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            @php
                                $totalSess = $quota->total_sessions ?? 12;
                                $remSess = $quota->remaining_sessions ?? 8;
                                $pct = $totalSess > 0 ? round(($remSess / $totalSess) * 100) : 0;
                            @endphp
                            <div class="w-full bg-zinc-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-[#ED1B45] h-full rounded-full" style="width: {{ $pct }}%"></div>
                            </div>

                            <p class="text-[11px] text-[#565A66] pt-1">
                                Berlaku s/d {{ $quota && $quota->end_date ? \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d M Y') : '28 Nov 2025' }}
                            </p>
                        </div>
                    </div>

                    <!-- Ringkasan Booking Card (Dark Card #141414) -->
                    <div class="bg-[#141414] rounded-2xl p-6 text-white space-y-5 shadow-xl relative overflow-hidden">
                        <!-- Top-right Ambient Red Gradient -->
                        <div class="absolute -top-12 -right-12 w-44 h-44 bg-[#ED1B45]/20 rounded-full blur-3xl pointer-events-none"></div>

                        <h3 class="text-base font-extrabold font-heading text-white tracking-wide">
                            Ringkasan Booking
                        </h3>

                        <div class="space-y-3.5 text-xs text-zinc-300">
                            <!-- Tanggal Latihan -->
                            <div class="flex items-start gap-2.5">

                                <div>
                                    <div class="text-[#A1A1AA] text-[11px]">Tanggal Latihan</div>
                                    <div class="font-bold text-white text-xs mt-0.5" x-text="formattedSelectedDate || 'Pilih tanggal'"></div>
                                </div>
                            </div>

                            <!-- Jam Sesi -->
                            <div class="flex items-start gap-2.5">

                                <div>
                                    <div class="text-[#A1A1AA] text-[11px]">Jam Sesi</div>
                                    <div class="font-bold text-white text-xs mt-0.5" x-text="selectedSlot ? (selectedSlot + ' - ' + endSlotTime + ' WIB (60 Menit)') : 'Pilih slot jam'"></div>
                                </div>
                            </div>

                            <!-- Pelatih -->
                            <div class="flex items-start gap-2.5">

                                <div>
                                    <div class="text-[#A1A1AA] text-[11px]">Pelatih</div>
                                    <div class="font-bold text-white text-xs mt-0.5">{{ $trainer->name ?? 'Coach Rama Prasetya' }}</div>
                                </div>
                            </div>

                            <!-- Kuota Terpakai -->
                            <div class="flex items-start gap-2.5">

                                <div>
                                    <div class="text-[#A1A1AA] text-[11px]">Kuota Terpakai</div>
                                    <div class="font-bold text-white text-xs mt-0.5">1 Sesi</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Submit -->
                        <form method="POST" action="{{ route('pt-sessions.store') }}" class="pt-2">
                            @csrf
                            <input type="hidden" name="trainer_id" :value="trainerId">
                            <input type="hidden" name="session_date" :value="selectedDate">
                            <input type="hidden" name="start_time" :value="selectedSlot">

                            <button type="submit"
                                    :disabled="!selectedDate || !selectedSlot"
                                    :class="{
                                        'bg-[#ED1B45] hover:bg-[#D1123D] text-white shadow-lg shadow-[#ED1B45]/30 cursor-pointer': selectedDate && selectedSlot,
                                        'bg-zinc-800 text-zinc-500 cursor-not-allowed': !selectedDate || !selectedSlot
                                    }"
                                    class="w-full py-3.5 rounded-full font-bold uppercase tracking-wider text-xs transition-all duration-200 flex items-center justify-center gap-2">
                                <span>Konfirmasi Booking Sesi</span>
                                <span>&rarr;</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Modal Booking Berhasil Dikonfirmasi (Reference Image 1) -->
            <div x-show="showSuccessModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
                 style="display: none;">

                <div @click.away="showSuccessModal = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="max-w-md w-full bg-[#161616] border border-[#2E2E2E] rounded-3xl p-8 relative text-center space-y-5 shadow-2xl">

                    <!-- Close Button -->
                    <button type="button"
                            @click="window.location.href = '{{ route('pt-sessions.index') }}'"
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white text-zinc-900 flex items-center justify-center font-bold text-sm hover:bg-zinc-200 transition-colors">
                        ✕
                    </button>

                    <!-- Green Check Icon with Glow -->
                    <div class="w-16 h-16 rounded-full bg-[#10B981] text-white flex items-center justify-center mx-auto shadow-xl shadow-[#10B981]/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <!-- Status Pill Badge -->
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider text-[#ED1B45]">
                            STATUS: TERVERIFIKASI
                        </span>
                    </div>

                    <!-- Modal Title -->
                    <h3 class="text-2xl font-black text-white font-heading">
                        Booking Berhasil <span class="text-[#ED1B45]">Dikonfirmasi!</span>
                    </h3>

                    <!-- Subtext -->
                    <p class="text-xs text-[#A1A1AA] leading-relaxed max-w-xs mx-auto">
                        Sesi latihan privat atletik Anda bersama pelatih telah resmi terjadwal di sistem Maxfit Athletic Arena.
                    </p>

                    <!-- Action Button -->
                    <div class="pt-2">
                        <a href="{{ route('pt-sessions.index') }}"
                           class="inline-block w-full py-3 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white font-bold text-xs uppercase tracking-wider transition-colors">
                            Lihat di Riwayat Sesi
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Alpine Script -->
    <script>
        function ptBooking(config) {
            return {
                trainerId: config.trainerId,
                selectedDate: config.initialDate,
                selectedSlot: '15:00',
                isLoadingSlots: false,
                dateStrip: [],
                slots: { morning: [], afternoon: [], evening: [] },
                formattedSelectedDate: '',
                endSlotTime: '16:00',
                currentMonthYear: 'Oktober 2026',
                showSuccessModal: false,

                async init() {
                    await this.fetchAvailability();
                    await this.fetchSlots(this.selectedDate);
                },

                async fetchAvailability() {
                    try {
                        const url = `${config.availabilityUrl}?trainer_id=${this.trainerId}&start_date=${this.selectedDate}&days=7`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.dateStrip = data.days || [];
                            if (this.dateStrip.length > 0) {
                                const d = new Date(this.selectedDate);
                                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                this.currentMonthYear = `${months[d.getMonth()]} ${d.getFullYear()}`;
                            }
                        }
                    } catch (e) {
                        console.error('Failed fetching availability', e);
                    }
                },

                async fetchSlots(date) {
                    this.isLoadingSlots = true;
                    try {
                        const url = `${config.slotsUrl}?trainer_id=${this.trainerId}&date=${date}`;
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
                    const d = new Date(date);
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    this.currentMonthYear = `${months[d.getMonth()]} ${d.getFullYear()}`;
                    this.fetchSlots(date);
                },

                selectSlot(time) {
                    this.selectedSlot = time;
                    const [h, m] = time.split(':').map(Number);
                    const endH = String((h + 1) % 24).padStart(2, '0');
                    this.endSlotTime = `${endH}:${String(m).padStart(2, '0')}`;
                },

                changeWeek(offsetDays) {
                    const current = new Date(this.selectedDate);
                    current.setDate(current.getDate() + offsetDays);
                    this.selectedDate = current.toISOString().split('T')[0];
                    this.fetchAvailability();
                    this.fetchSlots(this.selectedDate);
                }
            };
        }
    </script>
</x-app-layout>
