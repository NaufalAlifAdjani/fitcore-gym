@props(['quota' => null])

@php
    $total = $quota ? $quota->total_sessions : 0;
    $remaining = $quota ? $quota->remaining_sessions : 0;
    $used = $quota ? $quota->used_sessions : 0;
    $percentage = $total > 0 ? min(100, round(($remaining / $total) * 100)) : 0;
    $endDateFormatted = $quota && $quota->end_date ? \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d M Y') : '-';
@endphp

<div class="bg-white border border-orange-100 rounded-2xl p-5 shadow-sm relative overflow-hidden group">
    <!-- Background Glow Effect -->
    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#FF5722]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-orange-50 border border-orange-200/60 text-[#FF5722] flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs uppercase font-extrabold tracking-wider text-zinc-500">Sisa Kuota Sesi PT</span>
                <div class="text-sm font-semibold text-zinc-800">
                    Paket Private Coaching
                </div>
            </div>
        </div>

        <div class="text-right">
            <div class="text-xl font-black text-[#16151A] tracking-tight">
                <span class="text-[#FF5722]">{{ $remaining }}</span> <span class="text-xs font-normal text-zinc-500">/ {{ $total }} Sesi</span>
            </div>
            <div class="text-[11px] text-zinc-500">
                Berlaku s/d {{ $endDateFormatted }}
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-orange-50 rounded-full h-2.5 overflow-hidden p-0.5 border border-orange-200/60">
        <div class="bg-gradient-to-r from-[#FF5722] to-[#FFB703] h-full rounded-full transition-all duration-500 shadow-sm"
             style="width: {{ $percentage }}%"></div>
    </div>

    <div class="flex justify-between items-center mt-2.5 text-[11px] text-zinc-500">
        <span>{{ $used }} Sesi Telah Digunakan</span>
        <span class="font-medium text-emerald-600">{{ $remaining > 0 ? 'Status: Aktif' : 'Status: Habis' }}</span>
    </div>
</div>
