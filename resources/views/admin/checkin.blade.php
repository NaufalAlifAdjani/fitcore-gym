<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Check-In') }}
        </h2>
    </x-slot>

    {{--
        Pass the check-in POST URL to JavaScript safely via a data attribute.
        Avoids hardcoding URLs in JS files.
    --}}
    <div class="py-12" id="checkin-page" data-store-url="{{ route('admin.checkin.store') }}">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Scanner Card ─────────────────────────────────────────── --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        Scanner QR Code Member
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Pilih kamera, klik <strong>Mulai Scanner</strong>, lalu arahkan ke QR Code
                        pada kartu digital member.
                    </p>

                    {{-- Camera selector --}}
                    <div class="mb-4">
                        <label for="camera-select" class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Kamera
                        </label>
                        <select
                            id="camera-select"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                            <option value="">Memuat daftar kamera…</option>
                        </select>
                    </div>

                    {{-- Control buttons --}}
                    <div class="flex gap-3 mb-5">
                        <button
                            id="btn-start-scanner"
                            type="button"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Mulai Scanner
                        </button>

                        <button
                            id="btn-stop-scanner"
                            type="button"
                            disabled
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                            Hentikan Scanner
                        </button>
                    </div>

                    {{-- Status text --}}
                    <p id="scanner-status" class="text-sm font-medium text-gray-500">
                        Scanner belum aktif. Klik "Mulai Scanner" untuk memulai.
                    </p>

                    {{-- Camera feed wrapper (hidden until scanner starts) --}}
                    <div id="camera-wrapper" class="hidden mt-5">
                        <div class="relative">
                            {{-- The html5-qrcode library injects its UI into this div --}}
                            <div id="qr-reader" class="w-full rounded-lg overflow-hidden border border-gray-200"></div>
                        </div>

                        <p class="text-xs text-gray-400 mt-2 text-center">
                            Posisikan QR Code di dalam kotak yang terlihat pada layar kamera.
                        </p>
                    </div>

                    {{-- Scan result --}}
                    <div id="scan-result" class="mt-6 rounded-xl p-5 hidden"></div>

                </div>
            </div>

            {{-- ── Usage Guide Card ─────────────────────────────────────── --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Panduan Penggunaan</h3>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                        <li>Pastikan browser mendapatkan izin kamera dari perangkat ini.</li>
                        <li>Pilih kamera yang akan digunakan (kamera belakang direkomendasikan).</li>
                        <li>Klik tombol <strong>Mulai Scanner</strong>.</li>
                        <li>Minta member menampilkan Digital Member Card mereka.</li>
                        <li>Arahkan kamera ke QR Code pada kartu member.</li>
                        <li>Sistem akan otomatis membaca QR Code dan menampilkan hasil check-in.</li>
                        <li>Jika check-in berhasil, persilahkan member masuk ke gym.</li>
                    </ol>

                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-xs text-amber-800">
                            <strong>Catatan:</strong> Halaman ini hanya dapat diakses oleh Admin dan Petugas.
                            Pastikan menggunakan koneksi HTTPS atau localhost agar kamera dapat berfungsi.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Load scanner JS. window.CHECKIN_STORE_URL is set from the data attribute above. --}}
    @push('scripts')
        @vite('resources/js/checkin-scanner.js')
    @endpush
</x-app-layout>
