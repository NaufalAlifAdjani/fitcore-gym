<x-app-layout>
    <div
        id="checkin-page"
        data-store-url="{{ route('admin.checkin.store') }}"
        class="min-h-screen bg-[#F6F6F7] px-4 py-6 text-[#16151A] sm:px-6 lg:px-8 w-full max-w-full"
    >
        <div class="mx-auto max-w-4xl space-y-6">
            <!-- Header & Badge -->
            <header class="flex flex-col gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 font-inter text-xs font-bold text-[#ED1B45] border border-rose-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#ED1B45]"></span>
                            Portal Admin
                        </span>
                        <span class="text-xs font-medium text-[#565A66] font-inter">Presensi &amp; Check-In</span>
                    </div>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Scanner Check-in QR Code Member
                    </h1>
                    <p class="font-inter text-sm text-[#565A66] mt-1 max-w-2xl">
                        Arahkan kamera ke QR Code pada kartu digital member untuk memvalidasi paket membership aktif dan mencatat kehadiran check-in harian.
                    </p>
                </div>
            </header>

            <!-- Scanner Card -->
            <div class="bg-white rounded-[20px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB] p-6 sm:p-8 space-y-6">
                <!-- Camera Controls -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 border-b border-[#E5E7EB]">
                    <div class="space-y-1.5 flex-1">
                        <label for="camera-select" class="block font-inter text-xs font-bold uppercase tracking-wider text-[#565A66]">
                            Pilih Perangkat Kamera
                        </label>
                        <select
                            id="camera-select"
                            class="w-full sm:max-w-md rounded-xl border border-[#E5E7EB] bg-white py-2.5 pl-4 pr-10 text-sm font-inter text-[#16151A] shadow-sm focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition cursor-pointer"
                        >
                            <option value="">Memuat daftar kamera…</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <button
                            id="btn-start-scanner"
                            type="button"
                            class="bg-[#ED1B45] text-white px-6 py-2.5 rounded-full font-inter font-semibold shadow-sm hover:bg-[#D1123D] transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Mulai Scanner</span>
                        </button>

                        <button
                            id="btn-stop-scanner"
                            type="button"
                            disabled
                            class="bg-[#F6F6F7] text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] border border-[#E5E7EB] px-5 py-2.5 rounded-full font-inter font-semibold transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
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
                    <div class="relative max-w-lg mx-auto overflow-hidden rounded-2xl border-2 border-[#E5E7EB] bg-black shadow-inner">
                        <div id="qr-reader" class="w-full"></div>
                    </div>
                    <p class="text-xs font-inter text-[#565A66] text-center">
                        Posisikan QR Code di dalam kotak yang terlihat pada layar kamera.
                    </p>
                </div>

                <!-- Scan Result Container -->
                <div id="scan-result" class="mt-6 rounded-2xl p-5 hidden"></div>
            </div>

            <!-- Panduan Penggunaan Scanner Card -->
            <div class="bg-white rounded-[20px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB] p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#ED1B45]/10 text-[#ED1B45]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <h2 class="font-poppins text-base font-bold text-[#16151A]">
                        Panduan SOP Scanner Check-in
                    </h2>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 text-xs font-inter text-[#565A66]">
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#ED1B45] text-[10px] shadow-xs">1</span>
                        <span>Berikan izin kamera browser dan pilih kamera belakang jika menggunakan tablet/hp.</span>
                    </div>
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#ED1B45] text-[10px] shadow-xs">2</span>
                        <span>Klik <strong>Mulai Scanner</strong>, lalu arahkan kamera ke layar QR Code member.</span>
                    </div>
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#ED1B45] text-[10px] shadow-xs">3</span>
                        <span>Sistem otomatis memverifikasi masa aktif membership dan status verifikasi bayar.</span>
                    </div>
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#F6F6F7]">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[#ED1B45] text-[10px] shadow-xs">4</span>
                        <span>Check-in berlaku maksimal 1x per hari per member untuk mencegah penyalahgunaan pass.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/checkin-scanner.js')
    @endpush
</x-app-layout>
