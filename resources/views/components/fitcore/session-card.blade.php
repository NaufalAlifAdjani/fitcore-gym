@props(['session'])

@php
    $trainer = $session->trainer;
    $trainerProfile = $trainer?->trainerProfile;
    $status = $session->status;
    $isScheduled = $status === \App\Enums\PtSessionStatus::Scheduled;
    $isDone = $status === \App\Enums\PtSessionStatus::Done;
    $isCancelled = $status === \App\Enums\PtSessionStatus::Cancelled;
    $canChange = $session->can_be_changed;
    $dateFormatted = \Carbon\Carbon::parse($session->session_date)->locale('id')->translatedFormat('l, d F Y');
    $initials = $trainer ? strtoupper(substr($trainer->name, 0, 2)) : 'RP';
@endphp

<div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
        <!-- Left: Badges, Date, Time, Coach -->
        <div class="space-y-3">
            <!-- Badges Row -->
            <div class="flex flex-wrap items-center gap-2">
                @if ($isScheduled)
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Terkonfirmasi
                    </span>

                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <span>{{ $session->relative_time_badge }}</span>
                    </span>
                @elseif ($isDone)
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                        <span>Selesai</span>
                    </span>

                    @if ($session->id % 2 === 0)
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <span>5.0 Diberikan</span>
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <span>Beri Ulasan Sesi</span>
                        </span>
                    @endif
                @else
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                        <span>Dibatalkan</span>
                    </span>
                @endif
            </div>

            <!-- Date & Time -->
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-[#16151A] font-heading">
                    {{ $dateFormatted }}
                </h3>
                <div class="flex items-center gap-1.5 text-xs font-bold text-[#ED1B45] mt-1">
                    <span>{{ $session->time_range }}</span>
                </div>
            </div>

            <!-- Coach Info Row -->
            <div class="flex items-center gap-3 pt-1">
                <div
                    class="w-9 h-9 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center font-bold text-xs text-zinc-700">
                    {{ $initials }}
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-[#565A66] tracking-wider block">PELATIH
                        UTAMA</span>
                    <span
                        class="text-xs font-bold text-[#16151A]">{{ $trainer->name ?? 'Coach Rama Prasetya, CSCS' }}</span>
                </div>
            </div>
        </div>

        <!-- Right: Actions Buttons -->
        <div class="flex flex-col sm:items-end justify-center gap-2 pt-2 sm:pt-0 shrink-0">
            @if ($isScheduled)
                @if ($canChange)
                    <a href="{{ route('pt-sessions.edit', $session) }}"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-full bg-white hover:bg-zinc-50 text-[#16151A] border border-[#D1D5DB] text-xs font-bold shadow-xs transition-colors">
                        Ubah Jadwal
                    </a>
                    <button type="button"
                        @click="$dispatch('open-cancel-modal', { id: {{ $session->id }}, date: '{{ $dateFormatted }}', time: '{{ $session->time_range }}' })"
                        class="text-[#ED1B45] hover:underline text-xs font-bold text-center px-2 py-1">
                        Batal Sesi
                    </button>
                @else
                    <button disabled title="Batas waktu perubahan jadwal maksimal 4 jam sebelum sesi."
                        class="inline-flex items-center justify-center px-5 py-2 rounded-full bg-zinc-100 text-zinc-400 border border-zinc-200 text-xs font-semibold cursor-not-allowed">
                        Ubah Jadwal
                    </button>
                    <span class="text-[11px] text-zinc-400 italic">Batas waktu lewat (&lt; 4 jam)</span>
                @endif
            @elseif ($isDone)
                @if ($session->id % 2 === 0)
                    <button type="button"
                        onclick="alert('Log Latihan Sesi:\n- Barbell Back Squat 4x8 (100kg)\n- Romanian Deadlift 3x10 (80kg)\n- Walking Lunges 3x12 (20kg DB)\n- Core Plank 3x60s')"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-[#F3F4F6] hover:bg-[#E5E7EB] text-[#16151A] text-xs font-bold transition-colors">
                        <span>Lihat Log Latihan</span>
                    </button>
                @else
                    <button type="button" onclick="alert('Fitur Ulasan Sesi akan segera dibuka!')"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-bold shadow-sm transition-colors">
                        Beri Ulasan Sesi
                    </button>
                    <button type="button"
                        onclick="alert('Rangkuman Latihan: Sesi Upper Body Hypertrophy selesai dengan intensitas optimal.')"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-xl bg-[#F3F4F6] hover:bg-[#E5E7EB] text-[#374151] text-xs font-semibold transition-colors">
                        Rangkuman Latihan
                    </button>
                @endif
            @else
                <div class="text-xs text-zinc-400 italic">
                    Sesi dibatalkan & kuota telah dikembalikan
                </div>
            @endif
        </div>
    </div>
</div>