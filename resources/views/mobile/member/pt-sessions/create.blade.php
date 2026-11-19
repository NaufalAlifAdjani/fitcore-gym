{{--
ROLE: MEMBER
MODUL: Booking Sesi PT Mobile (Tersinkronisasi Penuh dengan Alur Website Desktop & Pelatih Terikat Paket)
VIEW: mobile.member.pt-sessions.create
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@php
    $cleanCoachName = function (?string $name): string {
        if (! $name) return 'Coach';
        $cleaned = preg_replace('/^(Coach\s+)+/i', '', trim($name));
        return 'Coach ' . $cleaned;
    };

    $boundTrainer = $trainer ?? $trainers->first();
    $initialDate = now()->toDateString();
    $initialDateLabel = now()->locale('id')->translatedFormat('l, d F Y');
    $remainingQuota = $quota->remaining_sessions ?? 0;

    $firstName = strtolower(explode(' ', trim(str_replace('Coach ', '', $boundTrainer?->name ?? '')))[0]);
    $trainerPhoto = file_exists(public_path("images/trainers/coach-{$firstName}.jpg"))
        ? asset("images/trainers/coach-{$firstName}.jpg")
        : ($boundTrainer?->trainerProfile?->photo_path ? asset('storage/' . $boundTrainer->trainerProfile->photo_path) : null);
@endphp

<x-layouts.mobile-app title="Booking Sesi PT - FitCore Athletic" active-tab="pt-sessions">
    <div x-data="ptBookingMobile({
        trainerId: {{ $boundTrainer->id ?? 1 }},
        trainerName: '{{ $cleanCoachName($boundTrainer?->name) }}',
        trainerSpec: '{{ $boundTrainer?->trainerProfile?->specialization ?? 'Personal Trainer' }}',
        trainerPhoto: '{{ $trainerPhoto ?? '' }}',
        trainerShift: '{{ $boundTrainer?->shift_label ?? 'Shift Pagi' }}',
        initialDate: '{{ $initialDate }}',
        initialDateLabel: '{{ $initialDateLabel }}',
        slotsUrl: '{{ route('pt-sessions.slots') }}',
        availabilityUrl: '{{ route('pt-sessions.availability') }}'
    })" class="space-y-4 pb-16">

        <!-- Top Header with Back Button -->
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
            <button type="button"
                    @click="window.history.back()"
                    class="p-1.5 text-[#565A66] hover:text-[#16151A] transition flex items-center gap-1 cursor-pointer"
                    aria-label="Kembali">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-xs font-semibold">Kembali</span>
            </button>
            <h1 class="text-sm font-black font-poppins text-[#16151A] tracking-wide">
                Booking Sesi PT
            </h1>
            <div class="w-8"></div>
        </div>

        <!-- Alert Error Server Validation -->
        @if ($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Pemesanan Tidak Dapat Diproses:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- CARD INFO KUOTA AKTIF & PELATIH TERIKAT PAKET (LOCKED - TIDAK BISA DIUBAH)-->
        <!-- ========================================================================= -->
        <div class="p-4 rounded-3xl bg-white border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            
            <!-- Profil Pelatih Terikat Paket (Terkunci Sesuai Pembelian Paket PT) -->
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <template x-if="trainerPhoto">
                        <img :src="trainerPhoto" :alt="trainerName" class="w-12 h-12 rounded-2xl object-cover border border-[#E5E7EB] shrink-0">
                    </template>
                    <template x-if="!trainerPhoto">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#FF5722] flex items-center justify-center font-bold text-sm border border-orange-200 shrink-0"
                             x-text="trainerName ? trainerName.substring(0, 2).toUpperCase() : 'PT'">
                        </div>
                    </template>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1">
                            <h2 class="text-sm font-bold text-[#16151A] font-poppins truncate" x-text="trainerName">
                                {{ $cleanCoachName($boundTrainer?->name) }}
                            </h2>
                            <span class="text-[#FF5722] text-xs font-bold shrink-0">✓</span>
                        </div>
                        <p class="text-[11px] text-[#565A66] truncate font-sans" x-text="trainerSpec">
                            {{ $boundTrainer?->trainerProfile?->specialization ?? 'Personal Trainer' }}
                        </p>
                        <div class="flex items-center gap-2 pt-0.5 text-[10px]">
                            <span class="text-amber-500 font-bold">★ 4.9</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-[#565A66]" x-text="trainerShift"></span>
                        </div>
                    </div>
                </div>

                <!-- Lencana Pelatih Terikat Paket PT (Tidak Dapat Diganti) -->
                <div class="shrink-0 text-right">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 border border-orange-200 text-[#FF5722]">
                        <svg class="w-3 h-3 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Pelatih Paket Anda</span>
                    </span>
                </div>
            </div>

            <!-- Catatan Edukasi Bahwa Pelatih Terikat Paket Pembelian -->
            <div class="p-2.5 rounded-xl bg-gray-50 border border-gray-100 flex items-start gap-2 text-[10px] text-[#565A66] leading-relaxed">
                <svg class="w-3.5 h-3.5 text-[#FF5722] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Sesi latihan ini otomatis terikat dengan Personal Trainer yang Anda pilih pada paket membership PT aktif Anda.</span>
            </div>

            <!-- Sisa Kuota Bar & Masa Berlaku -->
            <div class="pt-2 border-t border-[#E5E7EB] space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-[#565A66] font-medium">Sisa Kuota Sesi:</span>
                    <div class="font-bold">
                        <span class="text-[#FF5722] text-sm">{{ $remainingQuota }}</span>
                        <span class="text-[#16151A]"> / {{ $quota->total_sessions ?? 0 }} Sesi</span>
                    </div>
                </div>

                @php
                    $total = $quota->total_sessions ?? 0;
                    $rem = $quota->remaining_sessions ?? 0;
                    $pct = $total > 0 ? round(($rem / $total) * 100) : 0;
                @endphp
                <div class="w-full bg-zinc-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-[#FF5722] h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                </div>

                <div class="flex items-center justify-between text-[11px] text-[#565A66]">
                    <span>Berlaku s/d {{ $quota && $quota->end_date ? $quota->end_date->locale('id')->translatedFormat('d M Y') : 'Aktif' }}</span>
                    @if ($remainingQuota > 0)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-[10px]">
                            Paket Aktif
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 font-bold text-[10px]">
                            Kuota Habis
                        </span>
                    @endif
                </div>

                @if (isset($activeMembership) && $activeMembership)
                    <div class="pt-1 flex items-center justify-between text-[11px] text-[#565A66]">
                        <span>Membership Gym:</span>
                        <span class="inline-flex items-center gap-1 font-semibold text-emerald-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif s/d {{ $activeMembership->end_date->locale('id')->translatedFormat('d M Y') }}
                        </span>
                    </div>
                @endif
            </div>

            @if ($remainingQuota <= 0)
                <div class="p-3 rounded-2xl bg-orange-50 border border-orange-200 text-xs space-y-2">
                    <div class="flex items-center gap-1.5 text-orange-950 font-bold">
                        <svg class="w-4 h-4 text-[#FF5722] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Saldo Kuota 0 Sesi</span>
                    </div>
                    <p class="text-[11px] text-[#565A66] leading-relaxed">
                        Anda tidak memiliki saldo sesi latihan privat aktif. Harap lakukan pembelian paket PT terlebih dahulu.
                    </p>
                    <a href="{{ route('mobile.member.pt-packages.index') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-xs transition">
                        <span>Beli Paket Sesi PT &rarr;</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- ========================================================================= -->
        <!-- CARD 1: PILIH TANGGAL SESI (GOOGLE CALENDAR UI IDENTIK DENGAN WEBSITE)   -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-3xl border border-[#E5E7EB] p-4 sm:p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                        Pilih Tanggal Sesi
                    </h2>
                    <p class="text-[10px] text-[#565A66]">Reservasi minimal 24 jam sebelum sesi dimulai</p>
                </div>
                <button type="button"
                        @click="goToToday()"
                        class="px-3 py-1 text-[11px] font-semibold text-[#16151A] bg-white border border-[#E5E7EB] rounded-full hover:border-[#FF5722] hover:text-[#FF5722] active:bg-gray-100 transition shadow-2xs cursor-pointer">
                    Today
                </button>
            </div>

            <!-- Navigasi Bulan -->
            <div class="flex items-center justify-between px-1">
                <button type="button"
                        @click="prevMonth()"
                        class="w-7 h-7 flex items-center justify-center rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 active:bg-gray-200 transition cursor-pointer"
                        aria-label="Bulan sebelumnya">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <span class="text-xs font-bold font-poppins text-[#16151A] min-w-[120px] text-center" x-text="currentMonthYear">
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

            <!-- Grid Kalender 7 Kolom -->
            <div class="rounded-2xl border border-[#E5E7EB] overflow-hidden bg-white">
                <!-- Day Names Header Row (SUN, MON, TUE, WED, THU, FRI, SAT) -->
                <div class="grid grid-cols-7 border-b border-[#E5E7EB] bg-gray-50/70 text-center">
                    <template x-for="header in ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT']" :key="header">
                        <div class="text-[9px] font-bold text-[#565A66] py-2" x-text="header"></div>
                    </template>
                </div>

                <!-- 7 Columns Calendar Body Grid (Identik dengan Desktop) -->
                <div class="grid grid-cols-7 divide-x divide-[#E5E7EB]">
                    <template x-for="day in monthDays" :key="day.date">
                        <div @click="!day.isPast && selectDate(day.date)"
                             :class="{
                                 'bg-[#FF5722]/10 ring-2 ring-inset ring-[#FF5722] font-bold': selectedDate === day.date,
                                 'bg-white hover:bg-gray-50/80 cursor-pointer': selectedDate !== day.date && !day.isPast && day.isCurrentMonth,
                                 'bg-gray-50/50 hover:bg-gray-100/70 cursor-pointer': selectedDate !== day.date && !day.isPast && !day.isCurrentMonth,
                                 'bg-gray-50/70 opacity-40 cursor-not-allowed': day.isPast
                             }"
                             class="min-h-[58px] p-1 border-b border-[#E5E7EB] flex flex-col items-center justify-between transition select-none">
                            
                            <!-- Day Number -->
                            <div class="flex items-center justify-center mt-0.5">
                                <template x-if="selectedDate === day.date">
                                    <div class="w-6 h-6 rounded-full bg-[#FF5722] text-white font-bold text-[11px] flex items-center justify-center shadow-xs" x-text="day.dayNum"></div>
                                </template>
                                <template x-if="selectedDate !== day.date && day.isToday">
                                    <div class="w-6 h-6 rounded-full bg-white border border-[#FF5722] text-[#FF5722] font-bold text-[11px] flex items-center justify-center shadow-2xs" x-text="day.dayNum"></div>
                                </template>
                                <template x-if="selectedDate !== day.date && !day.isToday">
                                    <span class="text-[11px] font-medium" :class="day.isCurrentMonth ? 'text-[#16151A]' : 'text-gray-400'" x-text="day.dayNum"></span>
                                </template>
                            </div>

                            <!-- Indikator Status Slot Kalender: Ada Slot / Penuh (Sesuai Website Desktop) -->
                            <div class="mt-0.5 flex items-center justify-center">
                                <template x-if="!day.isPast && availabilityMap[day.date] === true">
                                    <span class="inline-flex items-center gap-1 px-1 py-0.2 rounded-full text-[8px] font-bold"
                                          :class="selectedDate === day.date ? 'bg-[#FF5722] text-white' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="selectedDate === day.date ? 'bg-white' : 'bg-emerald-500'"></span>
                                        <span class="hidden sm:inline">Ada slot</span>
                                    </span>
                                </template>
                                <template x-if="!day.isPast && availabilityMap[day.date] === false">
                                    <span class="text-[8px] text-zinc-400 font-medium">Penuh</span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Feedback Tanggal Dipilih -->
            <div class="p-3 rounded-2xl bg-orange-50/50 border border-orange-200/60 flex items-center justify-between text-xs">
                <span class="text-[#565A66]">Tanggal Sesi:</span>
                <span class="font-bold text-[#FF5722] font-poppins" x-text="formattedSelectedDate || selectedDate"></span>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- CARD 2: PILIH JAM SESI (REAL-TIME SLOTS PERSIS FLOW WEBSITE)              -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-3xl border border-[#E5E7EB] p-4 sm:p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                        Pilih Jam Sesi
                    </h2>
                    <p class="text-[10px] text-[#565A66] mt-0.5">
                        Jadwal untuk: <span class="font-bold text-[#FF5722]" x-text="formattedSelectedDate || selectedDate"></span> (Durasi 60 Menit)
                    </p>
                </div>
                <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 shrink-0">
                    Slot Real-Time
                </span>
            </div>

            <!-- Loader Slots -->
            <div x-show="isLoadingSlots" class="py-8 text-center space-y-2">
                <div class="inline-block w-6 h-6 border-3 border-[#FF5722] border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs text-[#565A66]">Memeriksa ketersediaan jam latihan...</p>
            </div>

            <!-- Daftar Slot Jam (Pagi, Siang & Sore, Malam) -->
            <div x-show="!isLoadingSlots" class="space-y-4">

                <!-- Periode 1: Pagi (07:00 - 11:00) -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Sesi Pagi (07:00 – 11:00)</span>
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
                                            'bg-[#F9FAFB] border border-[#E5E7EB] text-zinc-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                        }"
                                        class="w-full p-2.5 rounded-2xl text-center transition">
                                    <span x-text="slot.time"></span>
                                    <template x-if="slot.status === 'booked'">
                                        <span class="block text-[9px] font-normal text-rose-500 mt-0.5">Penuh</span>
                                    </template>
                                    <template x-if="slot.status === 'passed'">
                                        <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Lewat</span>
                                    </template>
                                    <template x-if="slot.status === 'off'">
                                        <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Di Luar Shift</span>
                                    </template>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Periode 2: Siang & Sore (13:00 - 17:00) -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Sesi Siang &amp; Sore (13:00 – 17:00)</span>
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
                                            'bg-[#F9FAFB] border border-[#E5E7EB] text-zinc-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                        }"
                                        class="w-full p-2.5 rounded-2xl text-center transition">
                                    <span x-text="slot.time"></span>
                                    <template x-if="slot.status === 'booked'">
                                        <span class="block text-[9px] font-normal text-rose-500 mt-0.5">Penuh</span>
                                    </template>
                                    <template x-if="slot.status === 'passed'">
                                        <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Lewat</span>
                                    </template>
                                    <template x-if="slot.status === 'off'">
                                        <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Di Luar Shift</span>
                                    </template>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Periode 3: Malam (18:00 - 22:00) -->
                <div class="space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Sesi Malam (18:00 – 22:00)</span>
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
                                            'bg-[#F9FAFB] border border-[#E5E7EB] text-zinc-400 cursor-not-allowed opacity-60': slot.status !== 'available'
                                        }"
                                        class="w-full p-2.5 rounded-2xl text-center transition">
                                    <span x-text="slot.time"></span>
                                    <template x-if="slot.status === 'booked'">
                                        <span class="block text-[9px] font-normal text-rose-500 mt-0.5">Penuh</span>
                                    </template>
                                    <template x-if="slot.status === 'passed'">
                                        <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Lewat</span>
                                    </template>
                                    <template x-if="slot.status === 'off'">
                                        <span class="block text-[9px] font-normal text-gray-400 mt-0.5">Di Luar Shift</span>
                                    </template>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- CARD 3: RINGKASAN BOOKING & KONFIRMASI (SUBMIT PERSIS WEBSITE)            -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-3xl border border-[#E5E7EB] p-4 sm:p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] text-[#16151A] space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins pb-2 border-b border-[#E5E7EB]">
                Ringkasan Booking
            </h2>

            <div class="space-y-3 text-xs font-sans">
                <!-- Tanggal Latihan -->
                <div class="flex items-start justify-between">
                    <span class="text-[#565A66]">Tanggal Latihan:</span>
                    <span class="font-bold text-[#16151A]" x-text="formattedSelectedDate || selectedDate || 'Pilih tanggal'"></span>
                </div>

                <!-- Jam Sesi -->
                <div class="flex items-start justify-between">
                    <span class="text-[#565A66]">Jam Sesi:</span>
                    <span class="font-bold text-[#FF5722]" x-text="selectedSlot ? (selectedSlot + ' - ' + endSlotTime + ' WIB (60 Menit)') : 'Pilih slot jam'"></span>
                </div>

                <!-- Pelatih -->
                <div class="flex items-start justify-between">
                    <span class="text-[#565A66]">Pelatih Terikat:</span>
                    <span class="font-bold text-[#16151A] font-poppins" x-text="trainerName">
                        {{ $cleanCoachName($boundTrainer?->name) }}
                    </span>
                </div>

                <!-- Pemotongan Kuota -->
                <div class="flex items-center justify-between pt-2 border-t border-[#E5E7EB]">
                    <span class="text-[#565A66]">Pemotongan Kuota:</span>
                    @if ($remainingQuota > 0)
                        <div class="text-right">
                            <span class="font-bold text-emerald-700 block">1 Sesi dari Saldo Paket</span>
                            <span class="text-[10px] text-[#565A66]">Sisa setelah booking: {{ max(0, $remainingQuota - 1) }} Sesi</span>
                        </div>
                    @else
                        <span class="font-bold text-rose-600">Saldo 0 Sesi (Wajib Beli Paket)</span>
                    @endif
                </div>
            </div>

            <!-- Form Submit POST ke PtSessionController@store -->
            <form method="POST" action="{{ route('pt-sessions.store') }}" class="space-y-4 pt-2">
                @csrf
                <input type="hidden" name="trainer_id" :value="trainerId">
                <input type="hidden" name="session_date" :value="selectedDate">
                <input type="hidden" name="start_time" :value="selectedSlot">
                <input type="hidden" name="redirect_to" value="mobile">
                <input type="hidden" name="from_mobile" value="1">

                <!-- Catatan untuk Coach (Opsional) -->
                <div class="space-y-1">
                    <label for="notes" class="block text-xs font-bold text-[#16151A]">
                        Catatan untuk Pelatih <span class="text-[#565A66] font-normal">(Opsional)</span>
                    </label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Contoh: Ingin fokus penguatan bahu dan perbanyak pemanasan dinamis..."
                              class="w-full p-3 rounded-2xl bg-white border border-[#E5E7EB] text-xs text-[#16151A] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] shadow-xs"></textarea>
                </div>

                <!-- Checkbox Kebijakan Reschedule/Batal 24 Jam -->
                <label class="flex items-start gap-2.5 cursor-pointer text-xs text-[#565A66] pt-1">
                    <input type="checkbox" required name="agreed_policy" x-model="agreedPolicy"
                           class="w-4 h-4 mt-0.5 text-[#FF5722] border-[#E5E7EB] bg-white rounded focus:ring-[#FF5722] shrink-0 cursor-pointer" />
                    <span class="text-[11px] leading-tight select-none">
                        Saya menyetujui kebijakan pembatalan &amp; reschedule sesi minimal <strong class="text-[#16151A]">24 jam sebelum jadwal latihan</strong> dimulai.
                    </span>
                </label>

                <!-- Tombol Submit Utama (Perbedaan Jelas Aktif vs Nonaktif) -->
                <div class="pt-2">
                    @if ($remainingQuota > 0)
                        <button type="submit"
                                :disabled="!selectedDate || !selectedSlot || !agreedPolicy"
                                :class="(selectedDate && selectedSlot && agreedPolicy)
                                    ? 'bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-lg shadow-orange-500/25 active:scale-[0.98] cursor-pointer'
                                    : 'bg-gray-100 text-[#565A66]/50 border border-[#E5E7EB] cursor-not-allowed opacity-60'"
                                class="w-full py-3.5 rounded-full font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition-all duration-200">
                            <span>Konfirmasi Booking Sesi</span>
                            <span x-show="selectedDate && selectedSlot && agreedPolicy">&rarr;</span>
                        </button>
                    @else
                        <a href="{{ route('mobile.member.pt-packages.index') }}"
                           class="w-full py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/25 active:scale-[0.98] transition">
                            <span>Beli Paket Sesi PT Terlebih Dahulu &rarr;</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

    </div>

    <!-- Script Alpine Mobile Booking (Identik Logika dengan Website) -->
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

        function ptBookingMobile(config) {
            return {
                trainerId: config.trainerId,
                trainerName: config.trainerName,
                trainerSpec: config.trainerSpec,
                trainerPhoto: config.trainerPhoto,
                trainerShift: config.trainerShift,

                selectedDate: config.initialDate,
                formattedSelectedDate: config.initialDateLabel || config.initialDate,
                selectedSlot: '',
                endSlotTime: '',
                isLoadingSlots: false,
                currentViewDate: parseLocalDate(config.initialDate),
                monthDays: [],
                availabilityMap: {},
                slots: { morning: [], afternoon: [], evening: [] },
                currentMonthYear: '',
                agreedPolicy: false,

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
                    if (!this.monthDays.length || !this.trainerId) return;
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
                        console.error('Gagal mengambil ketersediaan bulan', e);
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

                async fetchSlots(date) {
                    if (!date || !this.trainerId) return;
                    this.isLoadingSlots = true;
                    try {
                        const url = `${config.slotsUrl}?trainer_id=${this.trainerId}&date=${date}`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.formattedSelectedDate = data.formatted_date || date;
                            this.slots = data.groups || (data.data?.groups) || { morning: [], afternoon: [], evening: [] };

                            // Validasi apakah slot terpilih masih tersedia pada tanggal baru
                            const allSlots = [...(this.slots.morning || []), ...(this.slots.afternoon || []), ...(this.slots.evening || [])];
                            const activeStillValid = allSlots.some(s => s.time === this.selectedSlot && s.status === 'available');
                            if (!activeStillValid) {
                                this.selectedSlot = '';
                                this.endSlotTime = '';
                            }
                        }
                    } catch (e) {
                        console.error('Gagal mengambil data slot jam', e);
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
                    const [h, m] = time.split(':').map(Number);
                    const endH = String((h + 1) % 24).padStart(2, '0');
                    this.endSlotTime = `${endH}:${String(m).padStart(2, '0')}`;
                }
            };
        }
    </script>
</x-layouts.mobile-app>
