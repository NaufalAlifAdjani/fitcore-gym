@props(['session'])

@php
    $trainer = $session->trainer;
    $trainerProfile = $trainer?->trainerProfile;
    $status = $session->status;
    $isPending = $status === \App\Enums\PtSessionStatus::Pending;
    $isConfirmed = $status === \App\Enums\PtSessionStatus::Confirmed;
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
                @if ($isPending)
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        Menunggu Konfirmasi
                    </span>

                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <span>{{ $session->relative_time_badge }}</span>
                    </span>
                @elseif ($isConfirmed)
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Terkonfirmasi
                    </span>

                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span>{{ $session->relative_time_badge }}</span>
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-600 border border-zinc-200">
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
            @if ($isConfirmed || $isPending)
                @if ($canChange)
                    <a href="{{ route('pt-sessions.edit', $session) }}"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-full bg-white hover:bg-zinc-50 text-[#16151A] border border-[#D1D5DB] text-xs font-bold shadow-xs transition-colors">
                        Ubah Jadwal
                    </a>
                    <button type="button"
                        @click="$dispatch('open-cancel-modal', { id: {{ $session->id }}, date: '{{ $dateFormatted }}', time: '{{ $session->time_range }}' })"
                        class="text-[#ED1B45] hover:underline text-xs font-bold text-center px-2 py-1 cursor-pointer">
                        Batal Sesi
                    </button>
                @else
                    <button disabled title="Batas waktu perubahan jadwal maksimal 1 hari (24 jam) sebelum sesi."
                        class="inline-flex items-center justify-center px-5 py-2 rounded-full bg-zinc-100 text-zinc-400 border border-zinc-200 text-xs font-semibold cursor-not-allowed">
                        Ubah Jadwal
                    </button>
                    <span class="text-[11px] text-zinc-400 italic">Batas waktu lewat (&lt; 24 jam)</span>
                @endif
            @else
                <div class="text-xs text-zinc-400 italic">
                    Sesi dibatalkan & kuota telah dikembalikan
                </div>
            @endif
        </div>
    </div>
</div>