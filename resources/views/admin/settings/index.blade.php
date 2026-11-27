<x-app-layout>
    <div
        x-data="{
            activeTab: 'operational',
            showBankModal: false,
            editBankMode: false,
            bankForm: {
                id: null,
                name: '',
                account_number: '',
                account_holder: '',
                status: 'active',
                actionUrl: '{{ route('admin.settings.banks.store') }}'
            },
            openAddBank() {
                this.editBankMode = false;
                this.bankForm = {
                    id: null,
                    name: '',
                    account_number: '',
                    account_holder: '',
                    status: 'active',
                    actionUrl: '{{ route('admin.settings.banks.store') }}'
                };
                this.showBankModal = true;
            },
            openEditBank(bank) {
                this.editBankMode = true;
                this.bankForm = {
                    id: bank.id,
                    name: bank.name,
                    account_number: bank.account_number,
                    account_holder: bank.account_holder,
                    status: bank.status,
                    actionUrl: '{{ url('admin/settings/banks') }}/' + bank.id
                };
                this.showBankModal = true;
            }
        }"
        class="min-h-screen bg-[#F6F6F7] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-6xl space-y-6">

            <!-- 1. Header Pengaturan -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Pengaturan Sistem dan Operasional
                    </h1>
                    <p class="font-inter text-sm text-[#565A66] mt-1">
                        Pusat kendali aturan operasional gym, sesi personal trainer, keamanan check-in, dan rekening pembayaran.
                    </p>
                </div>

                <!-- Tab Navigation Buttons -->
                <div class="inline-flex rounded-2xl bg-gray-200/70 p-1 text-xs font-bold text-[#565A66]">
                    <button
                        type="button"
                        @click="activeTab = 'operational'"
                        :class="activeTab === 'operational' ? 'bg-white text-[#16151A] shadow-xs' : 'hover:text-[#16151A]'"
                        class="px-4 py-2 rounded-xl transition duration-150 cursor-pointer"
                    >
                        Aturan Sesi & QR
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'profile'"
                        :class="activeTab === 'profile' ? 'bg-white text-[#16151A] shadow-xs' : 'hover:text-[#16151A]'"
                        class="px-4 py-2 rounded-xl transition duration-150 cursor-pointer"
                    >
                        Profil & Kontak
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'banks'"
                        :class="activeTab === 'banks' ? 'bg-white text-[#16151A] shadow-xs' : 'hover:text-[#16151A]'"
                        class="px-4 py-2 rounded-xl transition duration-150 cursor-pointer"
                    >
                        Rekening Bank
                    </button>
                </div>
            </header>

            <!-- 2. Flash Notifications -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none">&times;</button>
                </div>
            @endif

            @if (session('info'))
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-sm font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-900 text-lg leading-none">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold flex items-start gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-bold">Terjadi kesalahan pada input:</p>
                        <ul class="list-disc list-inside mt-1 font-normal text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- 3. TAB 1: Aturan Operasional Sesi PT & Dynamic QR -->
            <div x-show="activeTab === 'operational'" x-cloak class="space-y-6">
                <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] p-6 sm:p-8">
                    <div class="border-b border-[#E5E7EB] pb-5 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h2 class="text-lg font-bold font-poppins text-[#16151A]">
                                Aturan Booking Sesi PT & Keamanan QR
                            </h2>
                            <p class="text-xs text-[#565A66] mt-1">
                                Kebijakan tenggat waktu booking, pembatalan jadwal, durasi sesi, dan token dynamic QR check-in.
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 bg-orange-50 border border-orange-200 text-[#FF5722] text-xs font-bold rounded-full w-fit">
                            Aturan Aktif
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.operational.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Batas Waktu Reschedule/Batal -->
                            <div class="space-y-1.5">
                                <label for="change_deadline_hours" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Batas Reschedule / Batal Sesi (Jam) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-xs">
                                    <input
                                        type="number"
                                        name="change_deadline_hours"
                                        id="change_deadline_hours"
                                        min="1"
                                        max="168"
                                        value="{{ old('change_deadline_hours', $operationalSettings['change_deadline_hours']) }}"
                                        required
                                        class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-[#565A66]">
                                        Jam Sebelum Sesi
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#565A66]">
                                    Batas waktu minimum sebelum sesi dimulai bagi member untuk membatalkan atau mengubah jadwal (default: 24 jam).
                                </p>
                            </div>

                            <!-- Minimal Waktu Booking Dimuka -->
                            <div class="space-y-1.5">
                                <label for="min_booking_notice_hours" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Minimal Waktu Booking di Muka (Jam) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-xs">
                                    <input
                                        type="number"
                                        name="min_booking_notice_hours"
                                        id="min_booking_notice_hours"
                                        min="1"
                                        max="168"
                                        value="{{ old('min_booking_notice_hours', $operationalSettings['min_booking_notice_hours']) }}"
                                        required
                                        class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-[#565A66]">
                                        Jam Sebelumnya
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#565A66]">
                                    Berapa jam sebelum jadwal sesi member diwajibkan melakukan pemesanan (default: 24 jam).
                                </p>
                            </div>

                            <!-- Durasi Sesi Latihan -->
                            <div class="space-y-1.5">
                                <label for="session_duration_minutes" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Durasi Standar Sesi (Menit) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-xs">
                                    <input
                                        type="number"
                                        name="session_duration_minutes"
                                        id="session_duration_minutes"
                                        min="15"
                                        max="240"
                                        step="15"
                                        value="{{ old('session_duration_minutes', $operationalSettings['session_duration_minutes']) }}"
                                        required
                                        class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-[#565A66]">
                                        Menit / Sesi
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#565A66]">
                                    Durasi standar 1 sesi latihan bersama personal trainer (default: 60 menit).
                                </p>
                            </div>


                            <!-- Batas Auto-cancel Sesi Pending -->
                            <div class="space-y-1.5">
                                <label for="auto_cancel_pending_hours_before" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Batas Auto-cancel Konfirmasi Trainer (Jam) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-xs">
                                    <input
                                        type="number"
                                        name="auto_cancel_pending_hours_before"
                                        id="auto_cancel_pending_hours_before"
                                        min="1"
                                        max="72"
                                        value="{{ old('auto_cancel_pending_hours_before', $operationalSettings['auto_cancel_pending_hours_before']) }}"
                                        required
                                        class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-[#565A66]">
                                        Jam Sebelum Sesi
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#565A66]">
                                    Jika trainer belum konfirmasi sesi hingga batas ini, kuota otomatis dikembalikan ke member (default: 12 jam).
                                </p>
                            </div>

                            <!-- Durasi Refresh Dynamic QR Code -->
                            <div class="space-y-1.5">
                                <label for="qr_token_ttl" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Durasi Refresh Dynamic QR (Detik) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-xs">
                                    <input
                                        type="number"
                                        name="qr_token_ttl"
                                        id="qr_token_ttl"
                                        min="10"
                                        max="300"
                                        step="5"
                                        value="{{ old('qr_token_ttl', $operationalSettings['qr_token_ttl']) }}"
                                        required
                                        class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-[#565A66]">
                                        Detik
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#565A66]">
                                    Masa berlaku satu kode QR pada kartu member sebelum otomatis berganti untuk keamanan (default: 30 detik).
                                </p>
                            </div>

                        </div>

                        <!-- Tombol Simpan -->
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <button
                                type="submit"
                                class="px-6 py-3 rounded-xl bg-[#FF5722] hover:bg-[#F4511E] text-white text-xs font-bold uppercase tracking-wider transition duration-150 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:ring-offset-2 flex items-center gap-2 cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Simpan Aturan Operasional</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 4. TAB 2: Profil & Kontak Gym -->
            <div x-show="activeTab === 'profile'" x-cloak class="space-y-6">
                <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] p-6 sm:p-8">
                    <div class="border-b border-[#E5E7EB] pb-5 mb-6">
                        <h2 class="text-lg font-bold font-poppins text-[#16151A]">
                            Profil & Kontak Resmi FitCore
                        </h2>
                        <p class="text-xs text-[#565A66] mt-1">
                            Informasi identitas gym yang dicantumkan pada invoice PDF, kartu keanggotaan, dan kontak bantuan WhatsApp member.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Nama Gym -->
                            <div class="space-y-1.5">
                                <label for="gym_name" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Nama Pusat Kebugaran <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="gym_name"
                                    id="gym_name"
                                    value="{{ old('gym_name', $gymProfile['gym_name']) }}"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                >
                            </div>

                            <!-- WhatsApp CS -->
                            <div class="space-y-1.5">
                                <label for="gym_phone" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Nomor WhatsApp CS / Helpdesk <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="gym_phone"
                                    id="gym_phone"
                                    value="{{ old('gym_phone', $gymProfile['gym_phone']) }}"
                                    required
                                    placeholder="Contoh: 081234567890"
                                    class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                >
                            </div>

                            <!-- Email Support -->
                            <div class="space-y-1.5">
                                <label for="gym_email" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Email Resmi Bantuan
                                </label>
                                <input
                                    type="email"
                                    name="gym_email"
                                    id="gym_email"
                                    value="{{ old('gym_email', $gymProfile['gym_email']) }}"
                                    readonly
                                    class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] bg-gray-100/90 text-[#565A66] cursor-not-allowed select-none text-sm font-semibold focus:outline-none"
                                >
                                <p class="text-[11px] text-[#565A66]">Email resmi sistem bersifat permanen dan tidak dapat diubah.</p>
                            </div>

                            <!-- Alamat Gym -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label for="gym_address" class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                                    Alamat Lengkap Pusat Gym <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    name="gym_address"
                                    id="gym_address"
                                    rows="2"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                                >{{ old('gym_address', $gymProfile['gym_address']) }}</textarea>
                            </div>

                        </div>

                        <!-- Tombol Simpan Profil -->
                        <div class="pt-4 border-t border-[#E5E7EB]">
                            <button
                                type="submit"
                                class="px-6 py-3 rounded-xl bg-[#FF5722] hover:bg-[#F4511E] text-white text-xs font-bold uppercase tracking-wider transition duration-150 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:ring-offset-2 flex items-center gap-2 cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Simpan Profil & Kontak</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 5. TAB 3: Rekening Bank Pembayaran Resmi -->
            <div x-show="activeTab === 'banks'" x-cloak class="space-y-6">
                <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] overflow-hidden">
                    <div class="p-6 border-b border-[#E5E7EB] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold font-poppins text-[#16151A]">
                                Rekening Bank Resmi Gym (Tujuan Transfer)
                            </h2>
                            <p class="text-xs text-[#565A66] mt-0.5">
                                Rekening bank aktif yang ditampilkan kepada member pada halaman checkout pembayaran paket membership dan PT.
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="openAddBank()"
                            class="px-4 py-2.5 rounded-xl bg-[#FF5722] hover:bg-[#F4511E] text-white text-xs font-bold uppercase tracking-wider transition duration-150 shadow-xs flex items-center gap-1.5 shrink-0 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Tambah Rekening</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/70 border-b border-[#E5E7EB] text-[11px] font-bold text-[#565A66] uppercase tracking-wider">
                                    <th class="py-3.5 px-6">Bank</th>
                                    <th class="py-3.5 px-6">Nomor Rekening</th>
                                    <th class="py-3.5 px-6">Atas Nama Rekening</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E5E7EB] text-xs">
                                @forelse ($banks as $bank)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-3.5 px-6 font-bold text-[#16151A]">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg bg-orange-50 border border-orange-200 text-[#FF5722] font-black flex items-center justify-center text-xs">
                                                    {{ strtoupper(substr($bank->name, 0, 3)) }}
                                                </div>
                                                <span>{{ $bank->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-6 font-mono font-bold text-[#16151A]">
                                            {{ $bank->account_number }}
                                        </td>
                                        <td class="py-3.5 px-6 text-[#565A66]">
                                            {{ $bank->account_holder }}
                                        </td>
                                        <td class="py-3.5 px-6">
                                            @if ($bank->status === 'active')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    Aktif Digunakan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-6 text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    @click="openEditBank(@js($bank))"
                                                    class="px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition cursor-pointer"
                                                >
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('admin.settings.banks.destroy', $bank) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/menonaktifkan rekening ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="px-2.5 py-1 rounded-lg text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition cursor-pointer"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-[#565A66] text-xs">
                                            Belum ada data rekening bank yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- 6. Modal Tambah / Edit Rekening Bank -->
        <div
            x-show="showBankModal"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-50 bg-[#16151A]/60 flex items-center justify-center p-4 backdrop-blur-xs"
        >
            <div
                @click.away="showBankModal = false"
                class="bg-white rounded-2xl border border-[#E5E7EB] shadow-2xl w-full max-w-md overflow-hidden"
            >
                <div class="p-6 border-b border-[#E5E7EB] flex items-center justify-between">
                    <h3 class="font-poppins font-bold text-base text-[#16151A]" x-text="editBankMode ? 'Edit Rekening Bank' : 'Tambah Rekening Bank'">
                    </h3>
                    <button type="button" @click="showBankModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
                </div>

                <form :action="bankForm.actionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editBankMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                            Nama Bank <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            x-model="bankForm.name"
                            placeholder="Contoh: BCA, Mandiri, BNI"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                            Nomor Rekening <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="account_number"
                            x-model="bankForm.account_number"
                            placeholder="Contoh: 8271 0812 3456"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                            Nama Pemilik Rekening (Atas Nama) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="account_holder"
                            x-model="bankForm.account_holder"
                            placeholder="Contoh: PT FITCORE INDONESIA"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#16151A]">
                            Status Rekening <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="status"
                            x-model="bankForm.status"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-sm font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                        >
                            <option value="active">Aktif (Tampil di Checkout)</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2">
                        <button
                            type="button"
                            @click="showBankModal = false"
                            class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold uppercase tracking-wider transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl bg-[#FF5722] hover:bg-[#F4511E] text-white text-xs font-bold uppercase tracking-wider transition shadow-xs cursor-pointer"
                        >
                            Simpan Rekening
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
