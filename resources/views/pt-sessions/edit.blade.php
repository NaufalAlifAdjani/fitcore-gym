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

            <!-- Breadcrumbs -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-[#565A66]">
                <nav class="flex items-center gap-2 font-medium">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#16151A] transition-colors">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('pt-sessions.index') }}" class="hover:text-[#16151A] transition-colors">Riwayat
                        Sesi</a>
                    <span>/</span>
                    <span class="text-[#ED1B45] font-bold">Ubah Jadwal</span>
                </nav>

                <a href="{{ route('pt-sessions.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white hover:bg-zinc-100 text-[#16151A] border border-[#E5E7EB] text-xs font-semibold shadow-xs transition-colors w-fit">
                    &larr; Batalkan & Kembali
                </a>
            </div>

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

                    <!-- Card 1: Pilih Tanggal Sesi -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-5">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-bold text-[#16151A] font-heading">
                                Pilih Tanggal Baru
                            </h2>

                            <!-- Month Navigation -->
                            <div class="flex items-center gap-3 text-xs font-bold text-[#16151A]">
                                <button type="button" @click="changeWeek(-7)"
                                    class="p-1 hover:text-[#ED1B45] transition-colors">
                                    &lsaquo;
                                </button>
                                <span x-text="currentMonthYear">Oktober 2026</span>
                                <button type="button" @click="changeWeek(7)"
                                    class="p-1 hover:text-[#ED1B45] transition-colors">
                                    &rsaquo;
                                </button>
                            </div>
                        </div>

                        <!-- 7 Days Strip -->
                        <div class="grid grid-cols-7 gap-2.5 sm:gap-3">
                            <template x-for="day in dateStrip" :key="day.date">
                                <button type="button" @click="selectDate(day.date)" :class="{
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

                    <!-- Card 2: Pilih Jam Sesi Pengganti -->
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-6">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E5E7EB]">
                            <div>
                                <h2 class="text-base font-bold text-[#16151A] font-heading">
                                    Pilih Jam Sesi Pengganti
                                </h2>
                                <p class="text-xs text-[#565A66] mt-0.5">
                                    Durasi 60 Menit — Jadwal Real-Time Coach Rama Prasetya
                                </p>
                            </div>
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
                                    {{ $trainer->trainerProfile->tier ?? 'Senior PT Tier III' }} —
                                    {{ $trainer->trainerProfile->studio ?? 'SCBD Studio' }}
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
                                <span class="font-bold text-white text-xs mt-0.5" x-text="formattedSelectedDate"></span>
                            </div>

                            <!-- Jam Baru -->
                            <div>
                                <span class="text-[#A1A1AA] text-[11px] block">Jam Sesi Baru:</span>
                                <span class="font-bold text-[#ED1B45] text-xs mt-0.5"
                                    x-text="selectedSlot ? (selectedSlot + ' - ' + endSlotTime + ' WIB') : 'Pilih slot jam'"></span>
                            </div>

                            <!-- Kuota Status -->
                            <div>
                                <span class="text-[#A1A1AA] text-[11px] block">Mutasi Kuota:</span>
                                <span class="font-semibold text-emerald-400 text-xs mt-0.5">0 Sesi (Tetap Utuh)</span>
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
        function ptReschedule(config) {
            return {
                sessionId: config.sessionId,
                trainerId: config.trainerId,
                selectedDate: config.initialDate,
                selectedSlot: config.currentSlot,
                isLoadingSlots: false,
                dateStrip: [],
                slots: { morning: [], afternoon: [], evening: [] },
                formattedSelectedDate: '',
                endSlotTime: '',
                currentMonthYear: 'Oktober 2026',

                async init() {
                    this.computeEndTime(this.selectedSlot);
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
                    const d = new Date(date);
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    this.currentMonthYear = `${months[d.getMonth()]} ${d.getFullYear()}`;
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