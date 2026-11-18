{{--
ROLE: MEMBER
MODUL: Booking Sesi PT 3-Step Wizard Mobile
VIEW: mobile.member.pt-sessions.create
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@php
    $defaultTrainer = $trainer ?? $trainers->first();
    $initialDate = now()->addDay()->toDateString();
    $initialDateLabel = now()->addDay()->translatedFormat('l, d M Y');
    $remainingQuota = $quota->remaining_sessions ?? 0;
@endphp

<x-layouts.mobile-app title="Booking Sesi PT - FitCore Athletic" active-tab="pt-sessions">
    <div x-data="{
        step: 1,
        selectedTrainerId: '{{ $defaultTrainer->id ?? '' }}',
        selectedTrainerName: '{{ $defaultTrainer->name ?? '' }}',
        selectedTrainerSpec: '{{ $defaultTrainer->trainerProfile->specialization ?? 'CSCS Certified' }}',
        selectedFocus: 'Chest & Triceps Hypertrophy',
        selectedDate: '{{ $initialDate }}',
        selectedDateLabel: '{{ $initialDateLabel }}',
        selectedSlot: '08:00',
        selectedTimeLabel: '08:00 - 09:00 WIB',
        agreedPolicy: false,
        activeFilter: 'semua',
        notes: '',
        isLoadingSlots: false,
        slotStatusMap: {},

        init() {
            if (this.selectedTrainerId && this.selectedDate) {
                this.fetchSlots();
            }
        },

        async fetchSlots() {
            if (!this.selectedTrainerId || !this.selectedDate) return;
            this.isLoadingSlots = true;
            try {
                const url = '{{ route('pt-sessions.slots') }}?trainer_id=' + this.selectedTrainerId + '&date=' + this.selectedDate;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    const data = await res.json();
                    const groups = data.data?.groups || data.groups || {};
                    const map = {};
                    for (const key in groups) {
                        if (Array.isArray(groups[key])) {
                            groups[key].forEach(slot => {
                                map[slot.time] = slot.status;
                            });
                        }
                    }
                    this.slotStatusMap = map;
                }
            } catch (e) {
                console.error('Error fetching slots:', e);
            } finally {
                this.isLoadingSlots = false;
            }
        },

        getSlotStatus(time) {
            return this.slotStatusMap[time] || 'available';
        },

        filterTrainer(spec) {
            if (this.activeFilter === 'semua') return true;
            const s = (spec || '').toLowerCase();
            if (this.activeFilter === 'hypertrophy') return s.includes('hypertrophy') || s.includes('muscle') || s.includes('cscs') || s.includes('conditioning') || s.includes('strength');
            if (this.activeFilter === 'fatloss') return s.includes('fat') || s.includes('loss') || s.includes('hiit') || s.includes('cardio') || s.includes('weight');
            if (this.activeFilter === 'rehab') return s.includes('rehab') || s.includes('mobility') || s.includes('recovery') || s.includes('posture');
            return true;
        },

        selectTrainer(id, name, spec) {
            this.selectedTrainerId = id;
            this.selectedTrainerName = name;
            this.selectedTrainerSpec = spec;
            this.fetchSlots();
        },

        selectDate(date, label) {
            this.selectedDate = date;
            this.selectedDateLabel = label;
            this.fetchSlots();
        },

        selectSlot(time, label) {
            if (this.getSlotStatus(time) !== 'available') return;
            this.selectedSlot = time;
            this.selectedTimeLabel = label;
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

        <!-- 4. Banner Kuota Paket Sesi PT -->
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
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-xs shadow-xs space-y-2">
                <div class="flex items-center gap-2 text-amber-800 font-bold">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Sisa Kuota Sesi PT Anda Habis</span>
                </div>
                <p class="text-[11px] text-amber-700">
                    Anda belum memiliki saldo kuota sesi PT aktif. Silakan pilih paket membership yang menyertakan sesi latihan atau beli paket sesi tambahan.
                </p>
                <a href="{{ route('mobile.member.packages') }}"
                   class="inline-block px-3 py-1.5 rounded-full text-[10px] font-bold bg-[#FF5722] text-white shadow-xs">
                    Lihat Paket Membership →
                </a>
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
                    <div x-show="filterTrainer('{{ addslashes($t->trainerProfile->specialization ?? '') }}')"
                         @click="selectTrainer('{{ $t->id }}', '{{ $t->name }}', '{{ $t->trainerProfile->specialization ?? 'CSCS Certified' }}')"
                         class="min-w-[170px] max-w-[185px] p-3.5 rounded-2xl border transition-all cursor-pointer shrink-0 relative flex flex-col justify-between"
                         :class="selectedTrainerId == '{{ $t->id }}' ? 'bg-white border-[#FF5722] shadow-md shadow-orange-500/15 ring-2 ring-[#FF5722]' : 'bg-white border-[#E5E7EB] hover:border-gray-300 shadow-[0_4px_12px_rgba(0,0,0,0.04)]'">
                        
                        <!-- Checkmark Terpilih Pill -->
                        <div x-show="selectedTrainerId == '{{ $t->id }}'" class="absolute top-2 right-2 px-1.5 py-0.5 rounded-full bg-[#FF5722] text-white text-[9px] font-bold">
                            Terpilih
                        </div>

                        <div>
                            <div class="w-12 h-12 rounded-xl bg-gray-100 text-[#16151A] flex items-center justify-center font-bold text-sm mb-2 border border-[#E5E7EB]">
                                {{ strtoupper(substr($t->name, 0, 2)) }}
                            </div>
                            <h4 class="text-xs font-bold text-[#16151A] truncate font-poppins">
                                Coach {{ $t->name }}
                            </h4>
                            <p class="text-[10px] text-[#565A66] mt-0.5 line-clamp-1 font-sans">
                                {{ $t->trainerProfile->specialization ?? 'CSCS Certified' }}
                            </p>
                        </div>

                        <div class="mt-3 pt-2 border-t border-[#E5E7EB] flex items-center justify-between text-[10px]">
                            <span class="text-amber-500 font-bold">★ {{ $t->trainerProfile->rating ?? '4.9' }}</span>
                            <span class="text-emerald-600 font-semibold">Tersedia</span>
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

            <!-- Tombol Lanjut ke Step 2 (Pill Button) -->
            <div class="pt-3">
                <button type="button"
                        @click="if (selectedTrainerId && selectedFocus) { step = 2; fetchSlots(); }"
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
        <!-- STEP 2: PILIH HARI, TANGGAL & JAM SESI    -->
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

            <!-- Carousel Tanggal 7 Hari -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                        Pilih Hari &amp; Tanggal
                    </label>
                    <span class="text-[10px] text-[#565A66] font-semibold uppercase">{{ now()->translatedFormat('F Y') }}</span>
                </div>

                <div class="grid grid-cols-5 gap-1.5 text-center">
                    @for ($i = 0; $i < 5; $i++)
                        @php
                            $targetDate = now()->addDays($i);
                            $dateStr = $targetDate->toDateString();
                            $dayName = substr($targetDate->translatedFormat('D'), 0, 3);
                            $dayNum = $targetDate->format('d');
                        @endphp
                        <button type="button"
                                @click="selectDate('{{ $dateStr }}', '{{ $targetDate->translatedFormat('l, d M Y') }}')"
                                class="p-2.5 rounded-2xl border transition-all flex flex-col items-center justify-center cursor-pointer"
                                :class="selectedDate === '{{ $dateStr }}' ? 'bg-[#FF5722] text-white border-[#FF5722] shadow-md shadow-orange-500/25' : 'bg-white text-[#565A66] border-[#E5E7EB] hover:border-gray-300 shadow-xs'">
                            <span class="text-[10px] font-medium uppercase">{{ $dayName }}</span>
                            <span class="text-base font-black font-poppins mt-0.5">{{ $dayNum }}</span>
                            <span class="text-[8px] mt-0.5 opacity-80">{{ $i === 0 ? 'Hari Ini' : ($i === 1 ? 'Besok' : 'Tersedia') }}</span>
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Notice Booking 24 Jam -->
            <div class="p-2.5 bg-blue-50/70 border border-blue-200 rounded-xl text-[10px] text-blue-800 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Sesi dapat dipesan minimal 24 jam sebelum jadwal latihan dimulai.</span>
            </div>

            <!-- Jam Sesi Grouped By Pagi, Siang & Sore, Malam -->
            <div class="space-y-3 pt-1">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                        Pilih Jam Sesi
                    </label>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                        Durasi 60 Menit
                    </span>
                </div>

                <!-- Bagian 1: Pagi (07:00 - 11:00) -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Pagi (07:00 - 11:00)</span>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                        @foreach (['07:00' => '07:00 - 08:00', '08:00' => '08:00 - 09:00', '09:00' => '09:00 - 10:00', '10:00' => '10:00 - 11:00'] as $time => $label)
                            <button type="button"
                                    @click="selectSlot('{{ $time }}', '{{ $label }} WIB')"
                                    :disabled="getSlotStatus('{{ $time }}') !== 'available'"
                                    :class="{
                                        'bg-[#FF5722] text-white border-[#FF5722] shadow-xs': selectedSlot === '{{ $time }}',
                                        'bg-white text-[#16151A] border-[#E5E7EB] hover:border-gray-300 shadow-xs': selectedSlot !== '{{ $time }}' && getSlotStatus('{{ $time }}') === 'available',
                                        'bg-gray-100 text-gray-400 border-gray-200 line-through cursor-not-allowed opacity-60': getSlotStatus('{{ $time }}') !== 'available'
                                    }"
                                    class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                                <span>{{ $label }}</span>
                                <template x-if="getSlotStatus('{{ $time }}') === 'booked'">
                                    <span class="block text-[9px] font-normal text-rose-500">Penuh</span>
                                </template>
                                <template x-if="getSlotStatus('{{ $time }}') === 'passed'">
                                    <span class="block text-[9px] font-normal text-gray-400">Lewat</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Bagian 2: Siang & Sore (13:00 - 17:00) -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Siang &amp; Sore (13:00 - 17:00)</span>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                        @foreach (['13:00' => '13:00 - 14:00', '14:00' => '14:00 - 15:00', '15:00' => '15:00 - 16:00', '16:00' => '16:00 - 17:00'] as $time => $label)
                            <button type="button"
                                    @click="selectSlot('{{ $time }}', '{{ $label }} WIB')"
                                    :disabled="getSlotStatus('{{ $time }}') !== 'available'"
                                    :class="{
                                        'bg-[#FF5722] text-white border-[#FF5722] shadow-xs': selectedSlot === '{{ $time }}',
                                        'bg-white text-[#16151A] border-[#E5E7EB] hover:border-gray-300 shadow-xs': selectedSlot !== '{{ $time }}' && getSlotStatus('{{ $time }}') === 'available',
                                        'bg-gray-100 text-gray-400 border-gray-200 line-through cursor-not-allowed opacity-60': getSlotStatus('{{ $time }}') !== 'available'
                                    }"
                                    class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                                <span>{{ $label }}</span>
                                <template x-if="getSlotStatus('{{ $time }}') === 'booked'">
                                    <span class="block text-[9px] font-normal text-rose-500">Penuh</span>
                                </template>
                                <template x-if="getSlotStatus('{{ $time }}') === 'passed'">
                                    <span class="block text-[9px] font-normal text-gray-400">Lewat</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Bagian 3: Malam (18:00 - 22:00) -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Malam (18:00 - 22:00)</span>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                        @foreach (['18:00' => '18:00 - 19:00', '19:00' => '19:00 - 20:00', '20:00' => '20:00 - 21:00', '21:00' => '21:00 - 22:00'] as $time => $label)
                            <button type="button"
                                    @click="selectSlot('{{ $time }}', '{{ $label }} WIB')"
                                    :disabled="getSlotStatus('{{ $time }}') !== 'available'"
                                    :class="{
                                        'bg-[#FF5722] text-white border-[#FF5722] shadow-xs': selectedSlot === '{{ $time }}',
                                        'bg-white text-[#16151A] border-[#E5E7EB] hover:border-gray-300 shadow-xs': selectedSlot !== '{{ $time }}' && getSlotStatus('{{ $time }}') === 'available',
                                        'bg-gray-100 text-gray-400 border-gray-200 line-through cursor-not-allowed opacity-60': getSlotStatus('{{ $time }}') !== 'available'
                                    }"
                                    class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                                <span>{{ $label }}</span>
                                <template x-if="getSlotStatus('{{ $time }}') === 'booked'">
                                    <span class="block text-[9px] font-normal text-rose-500">Penuh</span>
                                </template>
                                <template x-if="getSlotStatus('{{ $time }}') === 'passed'">
                                    <span class="block text-[9px] font-normal text-gray-400">Lewat</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tombol Navigasi Step 2 (Pill Button) -->
            <div class="flex items-center gap-3 pt-3">
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
                        <span class="font-bold text-[#16151A]" x-text="selectedDateLabel">{{ $initialDateLabel }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#565A66]">Jam Latihan</span>
                        <span class="font-bold text-[#FF5722]" x-text="selectedTimeLabel">08:00 - 09:00 WIB</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#565A66]">Target &amp; Fokus</span>
                        <span class="font-bold text-[#16151A] truncate max-w-[200px]" x-text="selectedFocus">Chest &amp; Triceps</span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-[#E5E7EB]">
                        <span class="text-[#565A66]">Potong Kuota</span>
                        <span class="font-bold text-emerald-700">1 Sesi dari Saldo Paket</span>
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

                <!-- Tombol Submit Akhir (Pill Full Radius) -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="step = 2" class="w-1/3 py-3.5 rounded-full bg-white border border-[#E5E7EB] text-[#16151A] text-xs font-bold text-center cursor-pointer hover:bg-gray-50 transition shadow-xs">
                        Kembali
                    </button>
                    <button type="submit"
                            :disabled="!agreedPolicy || {{ $remainingQuota <= 0 ? 'true' : 'false' }}"
                            :class="(!agreedPolicy || {{ $remainingQuota <= 0 ? 'true' : 'false' }}) ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#FF5722] hover:bg-[#E64A19] cursor-pointer shadow-md shadow-orange-500/25 active:scale-[0.98]'"
                            class="w-2/3 py-3.5 rounded-full text-white text-xs font-bold flex items-center justify-center gap-1.5 transition">
                        <span>Konfirmasi Booking</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </form>

        </div>

    </div>
</x-layouts.mobile-app>
