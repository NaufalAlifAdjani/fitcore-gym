{{--
ROLE: MEMBER
MODUL: Detail Sesi PT Mobile
VIEW: mobile.member.pt-sessions.show
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #ED1B45, Text #16151A / #565A66)
--}}

@php
    $isPending = $ptSession->status === \App\Enums\PtSessionStatus::Pending;
    $isConfirmed = $ptSession->status === \App\Enums\PtSessionStatus::Confirmed;
    $isCancelled = $ptSession->status === \App\Enums\PtSessionStatus::Cancelled;
    $canCancel = $ptSession->can_be_changed;
    $trainer = $ptSession->trainer;
    $profile = $trainer?->trainerProfile;
@endphp

<x-layouts.mobile-app title="Detail Sesi PT - FitCore Athletic" active-tab="pt-sessions">
    <div x-data="{ showCancelModal: false, cancelReason: '' }" class="space-y-4">

        <!-- 1. Header Navigation -->
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
            <a href="{{ route('mobile.member.pt-sessions.index') }}"
               class="p-1.5 text-[#565A66] hover:text-[#16151A] transition flex items-center gap-1 cursor-pointer"
               aria-label="Kembali ke Daftar Sesi">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-xs font-semibold">Jadwal Sesi</span>
            </a>
            <h1 class="text-sm font-black font-poppins text-[#16151A] tracking-wide">
                Detail Sesi PT
            </h1>
            <div class="w-8"></div>
        </div>

        <!-- 2. Alert Notifikasi -->
        @if (session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-800 flex items-start gap-2.5 shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 shadow-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- 3. Status Sesi Card -->
        <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] text-center space-y-3">
            @if ($isPending)
                <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-7 h-7 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Menunggu Respon Pelatih
                    </span>
                    <h2 class="font-poppins font-black text-base text-[#16151A]">
                        Permintaan Sesi Terkirim
                    </h2>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Coach {{ $trainer->name ?? 'Pelatih' }} akan mengonfirmasi jadwal ini segera. Jika tidak ada konfirmasi dalam 12 jam sebelum sesi, sistem akan otomatis membatalkan dan mengembalikan kuota Anda.
                    </p>
                </div>
            @elseif ($isConfirmed)
                <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Sesi Terkonfirmasi
                    </span>
                    <h2 class="font-poppins font-black text-base text-[#16151A]">
                        Jadwal Latihan Siap!
                    </h2>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Pelatih siap mendampingi latihan Anda. Mohon datang 10 menit lebih awal untuk pemanasan mandiri di area cardio.
                    </p>
                </div>
            @else
                <div class="w-14 h-14 rounded-full bg-gray-100 text-gray-500 border border-gray-200 flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="inline-flex px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                        Dibatalkan
                    </span>
                    <h2 class="font-poppins font-black text-base text-[#16151A]">
                        Sesi Telah Dibatalkan
                    </h2>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Sesi ini dibatalkan{{ $ptSession->cancellation_reason ? ': ' . $ptSession->cancellation_reason : '.' }} Kuota sesi telah dikembalikan secara utuh ke saldo paket Anda.
                    </p>
                </div>
            @endif
        </div>

        <!-- 4. Detail Pelatih Card -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] font-poppins block">
                Pelatih Pendamping
            </span>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 text-[#16151A] flex items-center justify-center font-bold text-sm border border-[#E5E7EB]">
                        {{ strtoupper(substr($trainer->name ?? 'PT', 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#16151A] font-poppins">
                            Coach {{ $trainer->name ?? 'Pelatih FitCore' }}
                        </h3>
                        <p class="text-xs text-[#565A66]">
                            {{ $profile->specialization ?? 'CSCS Certified Trainer' }}
                        </p>
                        <span class="inline-flex items-center gap-1 text-[11px] text-amber-500 font-bold mt-0.5">
                            ★ {{ $profile->rating ?? '4.9' }} Rating Member
                        </span>
                    </div>
                </div>

                @if ($profile && $profile->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->phone) }}?text=Halo%20Coach%20{{ urlencode($trainer->name) }}%2C%20saya%20member%20FitCore%20untuk%20sesi%20tanggal%20{{ urlencode(\Carbon\Carbon::parse($ptSession->session_date)->translatedFormat('d M Y')) }}"
                       target="_blank"
                       class="px-3 py-1.5 rounded-full text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-xs flex items-center gap-1">
                        <span>Chat WA</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- 5. Rincian Jadwal & Waktu -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3 text-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] font-poppins block">
                Informasi Jadwal Latihan
            </span>

            <div class="space-y-2.5 font-sans">
                <div class="flex items-center justify-between py-1 border-b border-[#E5E7EB]">
                    <span class="text-[#565A66]">Hari &amp; Tanggal</span>
                    <strong class="text-[#16151A] font-semibold">
                        {{ \Carbon\Carbon::parse($ptSession->session_date)->translatedFormat('l, d F Y') }}
                    </strong>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#E5E7EB]">
                    <span class="text-[#565A66]">Jam Sesi</span>
                    <strong class="text-[#ED1B45] font-bold font-poppins">
                        {{ substr($ptSession->start_time, 0, 5) }} - {{ substr($ptSession->end_time, 0, 5) }} WIB
                    </strong>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#E5E7EB]">
                    <span class="text-[#565A66]">Durasi Latihan</span>
                    <span class="text-[#16151A] font-medium">60 Menit</span>
                </div>

                <div class="flex items-center justify-between py-1 border-b border-[#E5E7EB]">
                    <span class="text-[#565A66]">Lokasi Latihan</span>
                    <span class="text-[#16151A] font-medium text-right">FitCore Gym • Lantai 2 (Area PT)</span>
                </div>

                <div class="flex items-start justify-between py-1 border-b border-[#E5E7EB]">
                    <span class="text-[#565A66]">Target &amp; Fokus</span>
                    <span class="text-[#16151A] font-bold text-right max-w-[200px]">
                        {{ $ptSession->workout_focus ?? 'Full Body Conditioning' }}
                    </span>
                </div>

                <div class="py-1">
                    <span class="text-[#565A66] block mb-1">Catatan untuk Pelatih:</span>
                    <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] text-[#16151A] italic text-[11px]">
                        {{ $ptSession->notes ?: 'Tidak ada catatan khusus.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Aksi & Kebijakan Pembatalan -->
        @if ($canCancel)
            <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] font-poppins block">
                    Kelola Jadwal Sesi
                </span>
                <p class="text-[11px] text-[#565A66] leading-relaxed">
                    Sesi dapat dibatalkan maksimal <strong class="text-[#16151A]">24 jam sebelum jadwal dimulai</strong>. Kuota sesi Anda akan otomatis dikembalikan 100%.
                </p>

                <button type="button"
                        @click="showCancelModal = true"
                        class="w-full py-3 rounded-full border-2 border-rose-300 text-rose-600 hover:bg-rose-50 font-bold text-xs uppercase tracking-wider transition active:scale-[0.98] cursor-pointer">
                    Batalkan Sesi Latihan
                </button>
            </div>
        @elseif (!$isCancelled)
            <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-2xl text-[11px] text-amber-800 flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Batas waktu pembatalan/reschedule (24 jam sebelum sesi) telah terlewati. Sesi ini tidak dapat dibatalkan lagi.</span>
            </div>
        @endif

        <!-- 7. Tombol Kembali -->
        <div class="pt-2">
            <a href="{{ route('mobile.member.pt-sessions.index') }}"
               class="w-full block text-center py-3 rounded-full bg-white border border-[#E5E7EB] text-[#16151A] hover:bg-gray-50 text-xs font-bold uppercase tracking-wider transition shadow-xs">
                Kembali ke Daftar Sesi
            </a>
        </div>

        <!-- 8. Modal Konfirmasi Pembatalan -->
        <div x-show="showCancelModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
             x-transition>
            <div class="bg-white rounded-2xl max-w-sm w-full p-5 space-y-4 shadow-xl border border-[#E5E7EB]"
                 @click.away="showCancelModal = false">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <div class="text-center space-y-1">
                    <h3 class="font-poppins font-bold text-sm text-[#16151A]">
                        Batalkan Sesi Latihan Ini?
                    </h3>
                    <p class="text-xs text-[#565A66]">
                        Sesi pada <strong class="text-[#16151A]">{{ \Carbon\Carbon::parse($ptSession->session_date)->translatedFormat('d M Y') }}</strong> pukul <strong class="text-[#16151A]">{{ substr($ptSession->start_time, 0, 5) }} WIB</strong> akan dibatalkan dan 1 kuota akan dikembalikan.
                    </p>
                </div>

                <form method="POST" action="{{ route('pt-sessions.cancel', $ptSession->id) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-1 text-left">
                        <label for="modal_reason" class="block text-[11px] font-bold text-[#16151A]">
                            Alasan Pembatalan <span class="text-[#565A66] font-normal">(Opsional)</span>
                        </label>
                        <textarea id="modal_reason"
                                  name="reason"
                                  rows="2"
                                  placeholder="Contoh: Ada keperluan mendadak..."
                                  class="w-full p-2.5 rounded-xl border border-[#E5E7EB] bg-white text-xs text-[#16151A] focus:outline-none focus:ring-2 focus:ring-[#ED1B45] shadow-xs"></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <button type="button"
                                @click="showCancelModal = false"
                                class="w-1/2 py-2.5 rounded-full bg-white border border-[#E5E7EB] text-[#16151A] text-xs font-bold hover:bg-gray-50 transition">
                            Tutup
                        </button>
                        <button type="submit"
                                class="w-1/2 py-2.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-600/25 transition">
                            Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.mobile-app>

