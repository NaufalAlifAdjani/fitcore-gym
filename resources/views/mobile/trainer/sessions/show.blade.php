{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Detail Sesi Latihan Klien Mobile
VIEW: mobile.trainer.sessions.show
LAYOUT: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@php
    $isPending = $ptSession->status === \App\Enums\PtSessionStatus::Pending;
    $isConfirmed = $ptSession->status === \App\Enums\PtSessionStatus::Confirmed;
    $isDone = in_array($ptSession->status, [\App\Enums\PtSessionStatus::Completed, \App\Enums\PtSessionStatus::Done]);
    $isCancelled = $ptSession->status === \App\Enums\PtSessionStatus::Cancelled;
    $member = $ptSession->member;
    $memberProfile = $member?->memberProfile;
    $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($member?->phone ?? ''));
    if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
        $cleanPhone = '62' . substr($cleanPhone, 1);
    }
@endphp

<x-layouts.mobile-trainer title="Detail Sesi Klien - FitCore Coach" active="sessions">
    <div class="space-y-4" x-data="{ showRejectModal: false }">

        <!-- 1. Header Navigation -->
        <div class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-3">
                <a href="{{ route('mobile.trainer.sessions.index') }}"
                   class="w-9 h-9 rounded-full bg-white border border-[#E5E7EB] flex items-center justify-center text-[#16151A] hover:bg-gray-50 transition shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">Detail Sesi Latihan</h1>
                    <p class="text-[11px] font-semibold text-[#565A66]">ID Sesi #{{ str_pad($ptSession->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <!-- Status Pill Header -->
            @if ($isPending)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu
                </span>
            @elseif ($isConfirmed)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Terkonfirmasi
                </span>
            @elseif ($isDone)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    Selesai
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                    Dibatalkan
                </span>
            @endif
        </div>

        <!-- 2. Kartu Identitas Klien / Member -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <span class="text-[10px] uppercase font-bold tracking-wider text-[#565A66] block">Profil Klien Latihan</span>

            <div class="flex items-center gap-3.5">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 border-2 border-orange-200 text-[#FF5722] flex items-center justify-center font-poppins font-black text-xl shrink-0 shadow-inner">
                    {{ strtoupper(substr($member->name ?? 'M', 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-extrabold font-poppins text-[#16151A] truncate tracking-tight">
                        {{ $member->name ?? 'Member FitCore' }}
                    </h3>

                    <div class="flex items-center gap-1.5 mt-0.5 text-xs text-[#565A66] truncate">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $member->email ?? '-' }}</span>
                    </div>

                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-[#565A66] border border-[#E5E7EB]">
                            ID: {{ $memberProfile->member_code ?? ('MEM-' . str_pad($member->id ?? 0, 5, '0', STR_PAD_LEFT)) }}
                        </span>
                        @if ($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($member->name ?? 'Member') }},%20saya%20Coach%20{{ urlencode($ptSession->trainer->name ?? '') }}%20dari%20FitCore%20Gym"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition">
                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.974.554 1.769.82 2.796.82 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.768-5.766zm9.969 5.766c0 5.508-4.462 9.969-9.969 9.969-1.758 0-3.417-.463-4.871-1.272l-5.16 1.353 1.378-5.034c-.9-1.517-1.416-3.292-1.416-5.016 0-5.508 4.462-9.969 9.969-9.969 5.507 0 9.969 4.461 9.969 9.969z"/>
                                </svg>
                                <span>WhatsApp Klien</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Rincian Jadwal & Kuota Latihan -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3.5">
            <span class="text-[10px] uppercase font-bold tracking-wider text-[#565A66] block">Informasi Sesi</span>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-[10px] uppercase font-bold text-[#565A66] block">Hari &amp; Tanggal</span>
                    <span class="font-bold text-[#16151A] mt-0.5 block font-poppins">
                        {{ \Carbon\Carbon::parse($ptSession->session_date)->translatedFormat('l, d M Y') }}
                    </span>
                </div>

                <div class="p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-[10px] uppercase font-bold text-[#565A66] block">Jam Sesi</span>
                    <span class="font-bold text-[#FF5722] mt-0.5 block font-poppins">
                        {{ substr($ptSession->start_time, 0, 5) }} - {{ substr($ptSession->end_time, 0, 5) }} WIB
                    </span>
                </div>

                <div class="p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-[10px] uppercase font-bold text-[#565A66] block">Durasi</span>
                    <span class="font-bold text-[#16151A] mt-0.5 block">60 Menit</span>
                </div>

                <div class="p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-[10px] uppercase font-bold text-[#565A66] block">Paket PT</span>
                    <span class="font-bold text-[#16151A] mt-0.5 block truncate">
                        {{ $ptSession->quota->ptPackage->name ?? 'Paket Sesi PT' }}
                    </span>
                </div>
            </div>

            @if ($ptSession->quota)
                <div class="p-3 rounded-xl bg-blue-50/60 border border-blue-200/80 text-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-blue-800 uppercase block">Saldo Kuota Member</span>
                        <span class="text-[#16151A] font-semibold">Tersisa {{ $ptSession->quota->remaining_sessions ?? 0 }} sesi dari total {{ $ptSession->quota->total_sessions ?? 0 }} sesi</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold text-[10px]">
                        Aktif
                    </span>
                </div>
            @endif
        </div>

        <!-- 4. Tombol Aksi Konfirmasi / Tolak Sesi -->
        @if ($isPending)
            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-2">
                <span class="text-[10px] uppercase font-bold tracking-wider text-amber-700 block">Tindakan Diperlukan</span>
                <p class="text-xs text-[#565A66]">Permintaan sesi ini menunggu keputusan Anda. Konfirmasi jika waktu cocok atau tolak untuk mengembalikan kuota ke member.</p>

                <div class="flex items-center gap-2 pt-2">
                    <button type="button"
                            @click="showRejectModal = true"
                            class="flex-1 py-3 rounded-full border border-gray-300 hover:border-rose-300 hover:bg-rose-50 text-[#565A66] hover:text-rose-600 font-poppins font-bold text-xs uppercase tracking-wider transition cursor-pointer text-center">
                        Tolak Sesi
                    </button>

                    <form method="POST" action="{{ route('mobile.trainer.sessions.confirm', $ptSession) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 rounded-full bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-poppins font-bold text-xs uppercase tracking-wider transition shadow-md shadow-orange-500/20 cursor-pointer">
                            Konfirmasi Sesi
                        </button>
                    </form>
                </div>
            </div>
        @elseif ($isConfirmed)
            <div class="bg-white border border-emerald-200 rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-2">
                <div class="flex items-center gap-2 text-emerald-800 font-bold text-xs">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Sesi Telah Terkonfirmasi</span>
                </div>
                <p class="text-xs text-[#565A66]">Jadwal ini sudah terdaftar dalam agenda resmi. Pastikan Anda hadir tepat waktu di area gym.</p>
            </div>
        @endif

        <!-- 5. Modal Konfirmasi Penolakan Sesi -->
        <div x-show="showRejectModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl max-w-sm w-full p-5 shadow-2xl border border-[#E5E7EB] space-y-4"
                 @click.away="showRejectModal = false">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto border border-rose-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <div class="text-center">
                    <h3 class="font-poppins font-extrabold text-base text-[#16151A]">Tolak Permintaan Sesi?</h3>
                    <p class="text-xs text-[#565A66] mt-1 leading-relaxed">
                        Permintaan sesi dari <strong class="text-[#16151A]">{{ $member->name ?? 'Member' }}</strong> akan dibatalkan dan kuota sesi akan dikembalikan ke member.
                    </p>
                </div>

                <form method="POST" action="{{ route('mobile.trainer.sessions.reject', $ptSession) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label for="show_reason" class="block text-xs font-bold text-[#16151A] mb-1">
                            Alasan Penolakan <span class="text-[#565A66] font-normal">(Opsional)</span>
                        </label>
                        <textarea id="show_reason" name="reason" rows="2" placeholder="Misal: Sedang berhalangan karena jadwal kompetisi..."
                                  class="w-full p-2.5 rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-xs text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722]"></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <button type="button"
                                @click="showRejectModal = false"
                                class="flex-1 py-2.5 rounded-full border border-[#E5E7EB] text-xs font-bold text-[#565A66] hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold font-poppins transition shadow-sm">
                            Ya, Tolak Sesi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.mobile-trainer>

