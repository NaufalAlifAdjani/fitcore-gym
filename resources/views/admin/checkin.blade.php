<x-app-layout>
    <div id="checkin-page" data-store-url="{{ route('admin.checkin.store') }}"
        class="min-h-screen bg-[#F6F6F7] px-4 py-6 text-[#16151A] sm:px-6 lg:px-8 w-full max-w-full">
        <div class="mx-auto max-w-4xl space-y-6">
            <!-- Header & Badge -->
            <header class="flex flex-col gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1 font-inter text-xs font-bold text-[#FF5722] border border-orange-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#FF5722]"></span>
                            Portal Admin
                        </span>
                        <span class="text-xs font-medium text-[#565A66] font-inter">Presensi &amp; Check-In</span>
                    </div>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Scanner Check-in QR Code Member
                    </h1>
                    <p class="font-inter text-sm text-[#565A66] mt-1 max-w-2xl">
                        Arahkan kamera ke QR Code pada kartu digital member untuk memvalidasi paket membership aktif dan
                        mencatat kehadiran check-in harian.
                    </p>
                </div>
            </header>

            <!-- Scanner Card -->
            <div
                class="bg-white rounded-[20px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB] p-6 sm:p-8 space-y-6">
                <!-- Hardware Scanner (USB/Wireless Gun) & Manual Input Section -->
                <div class="rounded-2xl bg-[#F6F6F7]/80 border border-[#E5E7EB] p-4 sm:p-5 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#ED1B45]/10 text-[#ED1B45] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </span>
                            <label for="hardware-scanner-input"
                                class="font-poppins text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                Alat Scanner Fisik (USB / Wireless) &amp; Input Token
                            </label>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold font-inter bg-emerald-50 text-emerald-700 border border-emerald-200 w-fit">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Siap Terima Tembakan Scanner
                        </span>
                    </div>

                    <form id="hardware-scanner-form" class="flex flex-col sm:flex-row gap-2.5" onsubmit="return false;">
                        <div class="relative flex-1">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </div>
                            <input id="hardware-scanner-input" type="text" autocomplete="off" autofocus
                                placeholder="Tembak QR dengan alat scanner atau masukkan token manual..."
                                class="w-full rounded-xl border border-[#E5E7EB] bg-white py-2.5 pl-10 pr-4 text-sm font-inter text-[#16151A] shadow-xs focus:border-[#FF5722] focus:outline-none focus:ring-1 focus:ring-[#FF5722] transition placeholder:text-slate-400" />
                        </div>
                        <button id="btn-submit-hardware" type="submit"
                            class="bg-[#16151A] hover:bg-black text-white px-5 py-2.5 rounded-xl font-inter font-semibold transition text-sm flex items-center justify-center gap-2 cursor-pointer shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                            <span>Verifikasi</span>
                        </button>
                    </form>
                    <p class="text-[11px] font-inter text-[#565A66]">
                        Cukup tembakkan alat scanner ke layar HP member. Hasil scan akan otomatis terkirim tanpa perlu
                        menekan tombol apa pun.
                    </p>
                </div>

                <!-- Divider: Opsi Alternatif Menggunakan Kamera -->
                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-[#E5E7EB]"></div>
                    <span
                        class="flex-shrink mx-4 text-[11px] font-bold uppercase tracking-widest text-[#565A66] font-inter bg-white px-2">
                        Atau Gunakan Kamera Webcam
                    </span>
                    <div class="flex-grow border-t border-[#E5E7EB]"></div>
                </div>

                <!-- Camera Controls -->
                <div
                    class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 border-b border-[#E5E7EB]">
                    <div class="space-y-1.5 flex-1">
                        <label for="camera-select"
                            class="block font-inter text-xs font-bold uppercase tracking-wider text-[#565A66]">
                            Pilih Perangkat Kamera
                        </label>
                        <select id="camera-select"
                            class="w-full sm:max-w-md rounded-xl border border-[#E5E7EB] bg-white py-2.5 pl-4 pr-10 text-sm font-inter text-[#16151A] shadow-sm focus:border-[#FF5722] focus:outline-none focus:ring-1 focus:ring-[#FF5722] transition cursor-pointer">
                            <option value="">Memuat daftar kamera…</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <button id="btn-start-scanner" type="button"
                            class="bg-[#FF5722] text-white px-6 py-2.5 rounded-full font-inter font-semibold shadow-sm hover:bg-[#E64A19] transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Mulai Scanner</span>
                        </button>

                        <button id="btn-stop-scanner" type="button" disabled
                            class="bg-[#F6F6F7] text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] border border-[#E5E7EB] px-5 py-2.5 rounded-full font-inter font-semibold transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                            </svg>
                            <span>Hentikan</span>
                        </button>
                    </div>
                </div>

                <!-- Status Text -->
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-[#565A66]"></span>
                    <p id="scanner-status" class="text-sm font-inter font-medium text-[#565A66]">
                        Scanner belum aktif. Klik "Mulai Scanner" untuk memulai.
                    </p>
                </div>

                <!-- Camera Feed Wrapper -->
                <div id="camera-wrapper" class="hidden space-y-2">
                    <div
                        class="relative max-w-lg mx-auto overflow-hidden rounded-2xl border-2 border-[#E5E7EB] bg-black shadow-inner">
                        <div id="qr-reader" class="w-full"></div>
                    </div>
                    <p class="text-xs font-inter text-[#565A66] text-center">
                        Posisikan QR Code di dalam kotak yang terlihat pada layar kamera.
                    </p>
                </div>

                <!-- Scan Result Container -->
                <div id="scan-result" class="mt-6 rounded-2xl p-5 hidden"></div>
            </div>

            <!-- Table: Daftar Kehadiran Member Hari Ini -->
            <div
                class="bg-white rounded-[20px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB] p-6 sm:p-8 space-y-4">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#E5E7EB]">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-[#FF5722] border border-orange-200 shrink-0">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="font-poppins text-lg font-bold text-[#16151A]">
                                Daftar Kehadiran Member Hari Ini
                            </h2>
                            <p class="font-inter text-xs text-[#565A66]">
                                Data presensi masuk gym per tanggal
                                {{ now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-inter bg-slate-100 text-slate-700 border border-slate-200">
                            Total Hadir: <strong id="today-checkin-count"
                                class="text-[#FF5722] ml-1">{{ $todayCheckIns->count() }}</strong> Member
                        </span>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-[#E5E7EB] text-[11px] font-bold uppercase tracking-wider text-[#565A66] font-inter bg-[#F6F6F7]/60">
                                <th scope="col" class="py-3 px-4 rounded-l-xl">Waktu</th>
                                <th scope="col" class="py-3 px-4">Member</th>
                                <th scope="col" class="py-3 px-4">Paket Membership</th>
                                <th scope="col" class="py-3 px-4">Masa Berlaku</th>
                                <th scope="col" class="py-3 px-4">Petugas</th>
                                <th scope="col" class="py-3 px-4 rounded-r-xl text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="today-checkin-tbody"
                            class="divide-y divide-[#E5E7EB] text-xs font-inter text-[#16151A]">
                            @forelse($todayCheckIns as $checkIn)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 font-mono font-semibold text-slate-700 whitespace-nowrap">
                                        {{ $checkIn->checked_in_at ? $checkIn->checked_in_at->locale('id')->format('H:i') : '-' }}
                                        WIB
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-[#16151A]">
                                            {{ $checkIn->member->name ?? 'Member FitCore' }}
                                        </div>
                                        <div class="font-mono text-[11px] text-[#565A66]">
                                            ID:
                                            {{ $checkIn->member?->memberProfile?->member_code ?? ('MBR-' . str_pad($checkIn->member_id, 5, '0', STR_PAD_LEFT)) }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-medium text-slate-700">
                                        {{ $checkIn->membership?->package?->name ?? 'Membership FitCore' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-[#565A66] whitespace-nowrap">
                                        {{ $checkIn->membership?->end_date ? $checkIn->membership->end_date->locale('id')->translatedFormat('d M Y') : '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-[#565A66]">
                                        {{ $checkIn->checkedBy->name ?? 'Admin' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Berhasil
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-checkin-row">
                                    <td colspan="6" class="py-8 text-center text-[#565A66] font-medium">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span>Belum ada member yang melakukan check-in hari ini.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Panduan Penggunaan Scanner Card -->
            <div
                class="bg-white rounded-[20px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB] p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#FF5722]/10 text-[#FF5722]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <h2 class="font-poppins text-base font-bold text-[#16151A]">
                        Panduan SOP Scanner Check-in
                    </h2>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 text-xs font-inter text-[#565A66]">
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#FF5722] text-[10px] shadow-xs">1</span>
                        <span><strong>Alat Scanner Fisik (Rekomendasi):</strong> Tancapkan scanner USB/Wireless, arahkan
                            laser scanner ke layar HP member.</span>
                    </div>
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#FF5722] text-[10px] shadow-xs">2</span>
                        <span><strong>Kamera Webcam:</strong> Berikan izin kamera browser, klik <strong>Mulai
                                Scanner</strong>, lalu posisikan QR Code di layar.</span>
                    </div>
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#FF5722] text-[10px] shadow-xs">3</span>
                        <span>Sistem otomatis memverifikasi masa aktif membership dan status verifikasi bayar tanpa
                            intervensi manual.</span>
                    </div>
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#FF5722] text-[10px] shadow-xs">4</span>
                        <span>Check-in berlaku maksimal 1x per hari per member. Data langsung masuk ke tabel kehadiran
                            hari ini secara real-time.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/checkin-scanner.js')
    @endpush
</x-app-layout>