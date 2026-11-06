{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Booking Sesi PT 3-Step Wizard (Fitur #5 & #6 - Gambar 4)
| FILE         : resources/views/mobile/member/pt-sessions/create.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/pt-sessions/booking atau /pt-sessions/create (name: mobile.member.pt-sessions.create)
| DESKRIPSI    : Alur reservasi sesi latihan bersama pelatih pribadi dalam format
|                wizard mobile-first 3 langkah: (1) Pilih Trainer & Fokus Latihan,
|                (2) Pilih Tanggal & Slot Jam, (3) Konfirmasi & Potong Saldo Kuota.
|                Menggunakan token warna DESIGN.md: primary #ED1B45, surface-dark #1E1E1E,
|                tombol pill full rounded, tipografi Poppins & Inter.
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Booking Sesi PT - FitCore Athletic" active-tab="sessions">
    <div x-data="{
        step: 1,
        selectedTrainerId: '{{ $trainer->id ?? ($trainers->first()->id ?? 1) }}',
        selectedTrainerName: '{{ $trainer->name ?? ($trainers->first()->name ?? 'Coach Dimas') }}',
        selectedFocus: 'Chest & Triceps Hypertrophy',
        selectedDate: '{{ now()->toDateString() }}',
        selectedDateLabel: '{{ now()->translatedFormat('l, d M Y') }}',
        selectedTime: '08:00 - 09:00',
        startTimeValue: '08:00',
        agreedPolicy: false,
        activeFilter: 'semua',
        notes: ''
    }" class="space-y-4">

        <!-- 1. Top Header with Back Button (Gambar 4) -->
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
            <button type="button" @click="if (step > 1) { step-- } else { window.history.back(); }"
                    class="p-1.5 text-[#A1A1AA] hover:text-white transition flex items-center gap-1 cursor-pointer" aria-label="Kembali">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-sm font-black font-poppins text-white tracking-wide">
                Booking Sesi PT
            </h1>
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-white/10 text-white flex items-center justify-center text-[10px] font-bold">?</span>
            </div>
        </div>

        <!-- 2. Stepper Pills Row (Gambar 4: Pilih Trainer, Waktu, Konfirmasi) -->
        <div class="flex items-center gap-1.5 p-1 bg-[#1E1E1E] rounded-full border border-white/10 text-[11px] font-bold text-center">
            <div class="flex-1 py-1.5 rounded-full transition-all"
                 :class="step === 1 ? 'bg-[#ED1B45] text-white shadow-md' : (step > 1 ? 'text-white' : 'text-[#A1A1AA]')">
                1. Pilih Trainer
            </div>
            <div class="flex-1 py-1.5 rounded-full transition-all"
                 :class="step === 2 ? 'bg-[#ED1B45] text-white shadow-md' : (step > 2 ? 'text-white' : 'text-[#A1A1AA]')">
                2. Waktu
            </div>
            <div class="flex-1 py-1.5 rounded-full transition-all"
                 :class="step === 3 ? 'bg-[#ED1B45] text-white shadow-md' : 'text-[#A1A1AA]'">
                3. Konfirmasi
            </div>
        </div>

        <!-- 3. Banner Kuota Paket Aktif (Gambar 4) -->
        <div class="p-3.5 rounded-2xl bg-[#1E1E1E] border border-white/10 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-[#ED1B45]/15 text-[#ED1B45] flex items-center justify-center shrink-0">
                    <svg width="18" height="18" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="#ED1B45"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-[#A1A1AA] uppercase">Paket Member Anda</span>
                    <span class="font-bold text-white text-xs">Paket Sesi PT: Sisa {{ $quota->remaining_sessions ?? 0 }} Sesi</span>
                </div>
            </div>
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-bold text-[10px]">
                Aktif
            </span>
        </div>

        <!-- ========================================== -->
        <!-- STEP 1: PILIH TRAINER & TARGET FOKUS       -->
        <!-- ========================================== -->
        <div x-show="step === 1" x-transition class="space-y-4">
            
            <!-- Filter Kategori Pelatih -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                    Pilih Pelatih Pendamping
                </label>
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs no-scrollbar">
                    <button type="button" @click="activeFilter = 'semua'"
                        :class="activeFilter === 'semua' ? 'bg-[#ED1B45] text-white' : 'bg-[#1E1E1E] text-[#A1A1AA] border border-white/10'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer">
                        Semua
                    </button>
                    <button type="button" @click="activeFilter = 'hypertrophy'"
                        :class="activeFilter === 'hypertrophy' ? 'bg-[#ED1B45] text-white' : 'bg-[#1E1E1E] text-[#A1A1AA] border border-white/10'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer">
                        Hypertrophy
                    </button>
                    <button type="button" @click="activeFilter = 'fatloss'"
                        :class="activeFilter === 'fatloss' ? 'bg-[#ED1B45] text-white' : 'bg-[#1E1E1E] text-[#A1A1AA] border border-white/10'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer">
                        Fat Loss
                    </button>
                    <button type="button" @click="activeFilter = 'rehab'"
                        :class="activeFilter === 'rehab' ? 'bg-[#ED1B45] text-white' : 'bg-[#1E1E1E] text-[#A1A1AA] border border-white/10'"
                        class="px-3.5 py-1.5 rounded-full font-semibold shrink-0 transition cursor-pointer">
                        Rehab
                    </button>
                </div>
            </div>

            <!-- List Horizontal Kartu Trainer (Gambar 4) -->
            <div class="flex gap-3 overflow-x-auto pb-2 pt-1 no-scrollbar">
                @forelse ($trainers as $t)
                    <div @click="selectedTrainerId = '{{ $t->id }}'; selectedTrainerName = '{{ $t->name }}'"
                         class="min-w-[170px] max-w-[180px] p-3 rounded-2xl border transition-all cursor-pointer shrink-0 relative flex flex-col justify-between"
                         :class="selectedTrainerId == '{{ $t->id }}' ? 'bg-[#1E1E1E] border-[#ED1B45] shadow-lg shadow-[#ED1B45]/20 ring-2 ring-[#ED1B45]' : 'bg-[#1E1E1E]/80 border-white/10 hover:border-white/20'">
                        
                        <!-- Checkmark Pill -->
                        <div x-show="selectedTrainerId == '{{ $t->id }}'" class="absolute top-2 right-2 px-1.5 py-0.5 rounded-full bg-[#ED1B45] text-white text-[9px] font-bold">
                            Terpilih
                        </div>

                        <div>
                            <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center font-bold text-sm mb-2 border border-white/10">
                                {{ strtoupper(substr($t->name, 0, 2)) }}
                            </div>
                            <h4 class="text-xs font-bold text-white truncate font-poppins">
                                Coach {{ $t->name }}
                            </h4>
                            <p class="text-[10px] text-[#A1A1AA] mt-0.5 line-clamp-1">
                                {{ $t->trainerProfile->specialization ?? 'CSCS Certified • 7+ Thn' }}
                            </p>
                        </div>

                        <div class="mt-3 pt-2 border-t border-white/10 flex items-center justify-between text-[10px]">
                            <span class="text-amber-400 font-bold">★ 4.9</span>
                            <span class="text-emerald-400 font-semibold">Tersedia</span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 bg-[#1E1E1E] rounded-2xl border border-white/10 text-xs text-[#A1A1AA]">
                        Tidak ada pelatih yang tersedia.
                    </div>
                @endforelse
            </div>

            <!-- Fokus Target Latihan Sesi Ini (Radio Card Options Gambar 4) -->
            <div class="space-y-2 pt-1">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                    Fokus Target Latihan Sesi Ini
                </label>

                <div class="space-y-2">
                    <!-- Option 1 -->
                    <div @click="selectedFocus = 'Chest & Triceps Hypertrophy'"
                         class="p-3.5 rounded-2xl border transition cursor-pointer flex items-center justify-between"
                         :class="selectedFocus === 'Chest & Triceps Hypertrophy' ? 'bg-[#1E1E1E] border-[#ED1B45] ring-1 ring-[#ED1B45]' : 'bg-[#1E1E1E]/70 border-white/10'">
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-white block">Chest &amp; Triceps Hypertrophy</span>
                            <span class="text-[11px] text-[#A1A1AA] block">Fokus pembentukan dada, bahu depan, &amp; tricep</span>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center"
                             :class="selectedFocus === 'Chest & Triceps Hypertrophy' ? 'border-[#ED1B45] bg-[#ED1B45]' : 'border-white/30'">
                            <div x-show="selectedFocus === 'Chest & Triceps Hypertrophy'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>

                    <!-- Option 2 -->
                    <div @click="selectedFocus = 'Legs & Core Power'"
                         class="p-3.5 rounded-2xl border transition cursor-pointer flex items-center justify-between"
                         :class="selectedFocus === 'Legs & Core Power' ? 'bg-[#1E1E1E] border-[#ED1B45] ring-1 ring-[#ED1B45]' : 'bg-[#1E1E1E]/70 border-white/10'">
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-white block">Legs &amp; Core Power</span>
                            <span class="text-[11px] text-[#A1A1AA] block">Peningkatan kekuatan paha, glutes, &amp; otot inti</span>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center"
                             :class="selectedFocus === 'Legs & Core Power' ? 'border-[#ED1B45] bg-[#ED1B45]' : 'border-white/30'">
                            <div x-show="selectedFocus === 'Legs & Core Power'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>

                    <!-- Option 3 -->
                    <div @click="selectedFocus = 'Full Body HIIT Conditioning'"
                         class="p-3.5 rounded-2xl border transition cursor-pointer flex items-center justify-between"
                         :class="selectedFocus === 'Full Body HIIT Conditioning' ? 'bg-[#1E1E1E] border-[#ED1B45] ring-1 ring-[#ED1B45]' : 'bg-[#1E1E1E]/70 border-white/10'">
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-white block">Full Body HIIT Conditioning</span>
                            <span class="text-[11px] text-[#A1A1AA] block">Kardio intensitas tinggi &amp; ketahanan metabolik</span>
                        </div>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center"
                             :class="selectedFocus === 'Full Body HIIT Conditioning' ? 'border-[#ED1B45] bg-[#ED1B45]' : 'border-white/30'">
                            <div x-show="selectedFocus === 'Full Body HIIT Conditioning'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Lanjut ke Step 2 (Pill Full Radius) -->
            <div class="pt-3">
                <button type="button" @click="step = 2" class="w-full py-3.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white font-semibold text-sm shadow-lg shadow-[#ED1B45]/25 flex items-center justify-center gap-2 transition active:scale-[0.99] cursor-pointer">
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
            
            <!-- Trainer Terpilih Summary Pill -->
            <div class="p-3 rounded-2xl bg-[#1E1E1E] border border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-[#ED1B45]/15 text-[#ED1B45] flex items-center justify-center font-bold text-xs">
                        PT
                    </div>
                    <div>
                        <span class="text-xs font-bold text-white block font-poppins" x-text="selectedTrainerName">Coach Dimas</span>
                        <span class="text-[10px] text-[#A1A1AA] block" x-text="selectedFocus">Chest &amp; Triceps</span>
                    </div>
                </div>
                <button type="button" @click="step = 1" class="text-xs text-[#ED1B45] font-semibold hover:underline cursor-pointer">
                    Ubah
                </button>
            </div>

            <!-- Carousel Tanggal Horizontal (Gambar 4) -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Pilih Hari &amp; Tanggal
                    </label>
                    <span class="text-[10px] text-[#A1A1AA] font-semibold uppercase">{{ now()->translatedFormat('F Y') }}</span>
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
                                @click="selectedDate = '{{ $dateStr }}'; selectedDateLabel = '{{ $targetDate->translatedFormat('l, d M Y') }}'"
                                class="p-2.5 rounded-2xl border transition-all flex flex-col items-center justify-center cursor-pointer"
                                :class="selectedDate === '{{ $dateStr }}' ? 'bg-[#ED1B45] text-white border-[#ED1B45] shadow-md' : 'bg-[#1E1E1E] text-[#A1A1AA] border-white/10 hover:border-white/20'">
                            <span class="text-[10px] font-medium uppercase">{{ $dayName }}</span>
                            <span class="text-base font-black font-poppins mt-0.5">{{ $dayNum }}</span>
                            <span class="text-[8px] mt-0.5 opacity-80">{{ $i === 0 ? 'Hari Ini' : ($i === 1 ? 'Besok' : 'Tersedia') }}</span>
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Jam Sesi Grouped By Pagi, Siang, Malam (Gambar 4) -->
            <div class="space-y-3 pt-1">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                        Pilih Jam Sesi
                    </label>
                    <span class="text-[10px] text-emerald-400 font-bold bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">Durasi 60 Menit</span>
                </div>

                <!-- Bagian 1: Pagi (07:00 - 11:00) -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA] block">Pagi (07:00 - 11:00)</span>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                        <button type="button" @click="selectedTime = '07:00 - 08:00'; startTimeValue = '07:00'"
                                :class="selectedTime === '07:00 - 08:00' ? 'bg-[#ED1B45] text-white border-[#ED1B45]' : 'bg-[#1E1E1E] text-white border-white/10 hover:border-white/20'"
                                class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                            07:00 - 08:00
                        </button>
                        <button type="button" @click="selectedTime = '08:00 - 09:00'; startTimeValue = '08:00'"
                                :class="selectedTime === '08:00 - 09:00' ? 'bg-[#ED1B45] text-white border-[#ED1B45]' : 'bg-[#1E1E1E] text-white border-white/10 hover:border-white/20'"
                                class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                            08:00 - 09:00
                        </button>
                    </div>
                </div>

                <!-- Bagian 2: Siang & Sore (13:00 - 18:00) -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA] block">Siang &amp; Sore (13:00 - 18:00)</span>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                        <button type="button" @click="selectedTime = '13:00 - 14:00'; startTimeValue = '13:00'"
                                :class="selectedTime === '13:00 - 14:00' ? 'bg-[#ED1B45] text-white border-[#ED1B45]' : 'bg-[#1E1E1E] text-white border-white/10 hover:border-white/20'"
                                class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                            13:00 - 14:00
                        </button>
                        <button type="button" @click="selectedTime = '16:00 - 17:00'; startTimeValue = '16:00'"
                                :class="selectedTime === '16:00 - 17:00' ? 'bg-[#ED1B45] text-white border-[#ED1B45]' : 'bg-[#1E1E1E] text-white border-white/10 hover:border-white/20'"
                                class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                            16:00 - 17:00
                        </button>
                    </div>
                </div>

                <!-- Bagian 3: Malam (18:00 - 21:15) -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA] block">Malam (18:00 - 21:15)</span>
                    <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                        <button type="button" @click="selectedTime = '19:00 - 20:00'; startTimeValue = '19:00'"
                                :class="selectedTime === '19:00 - 20:00' ? 'bg-[#ED1B45] text-white border-[#ED1B45]' : 'bg-[#1E1E1E] text-white border-white/10 hover:border-white/20'"
                                class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                            19:00 - 20:00
                        </button>
                        <button type="button" @click="selectedTime = '20:15 - 21:15'; startTimeValue = '20:15'"
                                :class="selectedTime === '20:15 - 21:15' ? 'bg-[#ED1B45] text-white border-[#ED1B45]' : 'bg-[#1E1E1E] text-white border-white/10 hover:border-white/20'"
                                class="p-2.5 rounded-xl border text-center transition cursor-pointer">
                            20:15 - 21:15
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tombol Navigasi Step 2 (Pill Full Radius) -->
            <div class="flex items-center gap-3 pt-3">
                <button type="button" @click="step = 1" class="w-1/3 py-3 rounded-full bg-white/10 text-white text-xs font-bold text-center cursor-pointer hover:bg-white/15 transition">
                    Kembali
                </button>
                <button type="button" @click="step = 3" class="w-2/3 py-3 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-semibold shadow-lg shadow-[#ED1B45]/25 cursor-pointer transition">
                    Lanjut ke Konfirmasi →
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 3: KONFIRMASI RINGKASAN & SUBMIT     -->
        <!-- ========================================== -->
        <div x-show="step === 3" x-transition class="space-y-4" x-cloak>
            
            <div class="p-4 rounded-2xl bg-[#1E1E1E] border border-white/10 text-white space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#ED1B45]">Tinggal 1 Langkah Lagi</span>
                    <span class="text-xs font-bold text-[#A1A1AA]">Ringkasan Sesi</span>
                </div>

                <div class="space-y-2.5 text-xs font-sans">
                    <div class="flex items-center justify-between">
                        <span class="text-[#A1A1AA]">Personal Trainer</span>
                        <span class="font-bold text-white font-poppins" x-text="selectedTrainerName">Coach Dimas</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#A1A1AA]">Tanggal Latihan</span>
                        <span class="font-bold text-white" x-text="selectedDateLabel">{{ now()->translatedFormat('l, d M Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#A1A1AA]">Jam Latihan</span>
                        <span class="font-bold text-[#ED1B45]" x-text="selectedTime">08:00 - 09:00 WIB</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[#A1A1AA]">Target &amp; Fokus</span>
                        <span class="font-bold text-white truncate max-w-[200px]" x-text="selectedFocus">Chest &amp; Triceps</span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-white/10">
                        <span class="text-[#A1A1AA]">Potong Kuota</span>
                        <span class="font-bold text-emerald-400">1 Sesi dari Saldo Paket</span>
                    </div>
                </div>
            </div>

            <!-- Form Submit POST ke PtSessionController@store -->
            <form method="POST" action="{{ route('pt-sessions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="trainer_id" :value="selectedTrainerId">
                <input type="hidden" name="session_date" :value="selectedDate">
                <input type="hidden" name="start_time" :value="startTimeValue">

                <!-- Catatan Tambahan untuk Coach (Opsional) -->
                <div class="space-y-1">
                    <label for="notes" class="block text-xs font-bold text-slate-300">
                        Catatan untuk Pelatih <span class="text-[#A1A1AA] font-normal">(Opsional)</span>
                    </label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Misal: Otot paha agak tegang, ingin fokus pemanasan lebih lama..."
                              class="w-full p-3 rounded-xl bg-[#1E1E1E] border border-white/10 text-xs text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#ED1B45]"></textarea>
                </div>

                <!-- Checkbox Kebijakan Reschedule/Batal -->
                <label class="flex items-start gap-2.5 cursor-pointer text-xs text-[#A1A1AA] pt-1">
                    <input type="checkbox" required name="agreed_policy"
                           class="w-4 h-4 mt-0.5 text-[#ED1B45] border-white/20 bg-[#1E1E1E] rounded focus:ring-[#ED1B45] shrink-0" />
                    <span class="text-[11px] leading-tight select-none">
                        Saya menyetujui kebijakan pembatalan &amp; reschedule sesi maksimal <strong class="text-white">4 jam sebelum jadwal latihan</strong> dimulai.
                    </span>
                </label>

                <!-- Tombol Submit Akhir (Pill Full Radius) -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="step = 2" class="w-1/3 py-3.5 rounded-full bg-white/10 text-white text-xs font-bold text-center cursor-pointer hover:bg-white/15 transition">
                        Kembali
                    </button>
                    <button type="submit" class="w-2/3 py-3.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-bold shadow-lg shadow-[#ED1B45]/25 flex items-center justify-center gap-1.5 cursor-pointer transition">
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
