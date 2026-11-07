<x-trainer-layout :title="'Ketersediaan Waktu & Hari Kerja - PT Portal'">
    <div class="space-y-6" x-data="{
        activeTab: 'weekly',
        selectAllSlots(dayNum) {
            const checkboxes = document.querySelectorAll(`.day-slot-${dayNum}`);
            checkboxes.forEach(cb => cb.checked = true);
        },
        clearAllSlots(dayNum) {
            const checkboxes = document.querySelectorAll(`.day-slot-${dayNum}`);
            checkboxes.forEach(cb => cb.checked = false);
        },
        setSlotsByPeriod(dayNum, period) {
            this.clearAllSlots(dayNum);
            const checkboxes = document.querySelectorAll(`.day-slot-${dayNum}[data-period='${period}']`);
            checkboxes.forEach(cb => cb.checked = true);
        },
        applyStandardOfficeHours() {
            // Aktifkan Senin - Jumat, matikan Sabtu - Minggu (atau sesuaikan)
            for (let i = 0; i <= 6; i++) {
                const toggle = document.getElementById(`toggle-day-${i}`);
                if (toggle) {
                    toggle.checked = (i >= 1 && i <= 5);
                    toggle.dispatchEvent(new Event('change'));
                }
                const checkboxes = document.querySelectorAll(`.day-slot-${i}`);
                checkboxes.forEach(cb => cb.checked = (i >= 1 && i <= 5));
            }
        },
        applyAllDaysActive() {
            for (let i = 0; i <= 6; i++) {
                const toggle = document.getElementById(`toggle-day-${i}`);
                if (toggle) {
                    toggle.checked = true;
                    toggle.dispatchEvent(new Event('change'));
                }
                const checkboxes = document.querySelectorAll(`.day-slot-${i}`);
                checkboxes.forEach(cb => cb.checked = true);
            }
        }
    }">

        <!-- 1. Flash Alerts -->
        @if (session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm text-emerald-800 flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-inter font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="rounded-2xl bg-amber-50 border border-amber-200/80 p-4 text-sm text-amber-800 flex items-center gap-3 shadow-2xs">
                <svg class="h-5 w-5 text-amber-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <span class="font-inter font-medium">{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl bg-rose-50 border border-rose-200/80 p-4 text-sm text-rose-800 flex items-center gap-3 shadow-2xs">
                <svg class="h-5 w-5 text-rose-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <span class="font-inter font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200/80 p-4 text-sm text-rose-800 shadow-2xs">
                <ul class="list-disc list-inside space-y-1 font-inter text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 2. Header & Action -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-poppins text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Ketersediaan Waktu & Hari Kerja
                </h1>
                <p class="mt-1 font-inter text-xs sm:text-sm text-gray-500 max-w-3xl leading-relaxed">
                    Atur jadwal rutin mingguan dan catat tanggal cuti/libur Anda. Member akan memesan sesi latihan sesuai slot jam yang Anda aktifkan di sini.
                </p>
            </div>

            <!-- Tab Switcher -->
            <div class="inline-flex rounded-xl bg-gray-100 p-1 border border-gray-200/60 shadow-2xs shrink-0">
                <button
                    type="button"
                    @click="activeTab = 'weekly'"
                    :class="activeTab === 'weekly' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                    class="rounded-lg px-4 py-2 font-inter text-xs font-semibold transition"
                >
                    📅 Jadwal Mingguan
                </button>
                <button
                    type="button"
                    @click="activeTab = 'dayoffs'"
                    :class="activeTab === 'dayoffs' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                    class="rounded-lg px-4 py-2 font-inter text-xs font-semibold transition flex items-center gap-1.5"
                >
                    <span>🏖️ Cuti & Hari Libur</span>
                    @if ($upcomingDayOffs->count() > 0)
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#ED1B45] text-[10px] font-bold text-white">
                            {{ $upcomingDayOffs->count() }}
                        </span>
                    @endif
                </button>
            </div>
        </div>

        <!-- 3. TAB 1: JADWAL MINGGUAN -->
        <div x-show="activeTab === 'weekly'" x-cloak class="space-y-6">
            <!-- Notice Box & Quick Actions -->
            <div class="rounded-2xl bg-white p-5 border border-gray-100 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-[#ED1B45]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-poppins font-bold text-sm text-gray-900">
                            {{ $hasCustomSchedule ? 'Jadwal Personal Anda Aktif' : 'Menggunakan Jam Kerja Standar Gym' }}
                        </h4>
                        <p class="font-inter text-xs text-gray-500 mt-0.5">
                            Pilih hari aktif dan centang slot jam latihan yang sanggup Anda bimbing.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        @click="applyStandardOfficeHours()"
                        class="rounded-xl border border-gray-200 bg-white px-3.5 py-2 font-inter text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-2xs"
                    >
                        💼 Set Senin – Jumat
                    </button>
                    <button
                        type="button"
                        @click="applyAllDaysActive()"
                        class="rounded-xl border border-gray-200 bg-white px-3.5 py-2 font-inter text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-2xs"
                    >
                        ⚡ Buka Semua Hari (Sen–Min)
                    </button>
                </div>
            </div>

            <!-- Form Simpan Jadwal Mingguan -->
            <form action="{{ route('trainer.availability.schedule.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-5">
                    @foreach ($weeklySchedule as $dayNum => $day)
                        <div
                            x-data="{
                                isOpen: {{ $day['is_active'] ? 'true' : 'false' }},
                                activeSlotsCount: {{ count($day['slots']) }}
                            }"
                            class="rounded-2xl bg-white border border-gray-100 shadow-xs overflow-hidden transition"
                            :class="isOpen ? 'ring-1 ring-gray-200' : 'opacity-70 bg-gray-50/50'"
                        >
                            <!-- Day Header -->
                            <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100/80">
                                <div class="flex items-center gap-4">
                                    <!-- Toggle Switch -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="days[{{ $dayNum }}][is_active]"
                                            value="1"
                                            id="toggle-day-{{ $dayNum }}"
                                            class="sr-only peer"
                                            {{ $day['is_active'] ? 'checked' : '' }}
                                            x-model="isOpen"
                                        >
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#ED1B45]"></div>
                                    </label>

                                    <div>
                                        <div class="flex items-center gap-2.5">
                                            <h3 class="font-poppins font-bold text-base text-gray-900">
                                                {{ $day['name'] }}
                                            </h3>
                                            <template x-if="isOpen">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                    Aktif Bertugas
                                                </span>
                                            </template>
                                            <template x-if="!isOpen">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                    Libur Rutin
                                                </span>
                                            </template>
                                        </div>
                                        <p class="font-inter text-xs text-gray-500 mt-0.5">
                                            <span x-text="isOpen ? 'Slot aktif akan tampil untuk member' : 'Hari ini ditutup dari booking member'"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Quick Button Helpers per Day -->
                                <div x-show="isOpen" class="flex flex-wrap items-center gap-1.5 text-xs font-inter">
                                    <span class="text-gray-400 mr-1 text-[11px]">Pilih Cepat:</span>
                                    <button
                                        type="button"
                                        @click="selectAllSlots({{ $dayNum }})"
                                        class="px-2.5 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 transition font-medium"
                                    >
                                        Semua
                                    </button>
                                    <button
                                        type="button"
                                        @click="setSlotsByPeriod({{ $dayNum }}, 'morning')"
                                        class="px-2.5 py-1 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-800 transition font-medium"
                                    >
                                        🌅 Pagi
                                    </button>
                                    <button
                                        type="button"
                                        @click="setSlotsByPeriod({{ $dayNum }}, 'afternoon')"
                                        class="px-2.5 py-1 rounded-md bg-orange-50 hover:bg-orange-100 text-orange-800 transition font-medium"
                                    >
                                        ☀️ Siang
                                    </button>
                                    <button
                                        type="button"
                                        @click="setSlotsByPeriod({{ $dayNum }}, 'evening')"
                                        class="px-2.5 py-1 rounded-md bg-indigo-50 hover:bg-indigo-100 text-indigo-800 transition font-medium"
                                    >
                                        🌙 Malam
                                    </button>
                                    <button
                                        type="button"
                                        @click="clearAllSlots({{ $dayNum }})"
                                        class="px-2 py-1 rounded-md text-gray-400 hover:text-rose-600 transition"
                                    >
                                        Kosongkan
                                    </button>
                                </div>
                            </div>

                            <!-- Day Slots Body -->
                            <div x-show="isOpen" class="p-5 space-y-4">
                                @foreach ($configuredSlots as $periodKey => $times)
                                    <div>
                                        <div class="flex items-center gap-2 mb-2.5">
                                            <span class="font-poppins font-bold text-xs uppercase tracking-wider text-gray-500">
                                                @if ($periodKey === 'morning')
                                                    🌅 Sesi Pagi (07:00 – 11:00)
                                                @elseif ($periodKey === 'afternoon')
                                                    ☀️ Sesi Siang (13:00 – 17:00)
                                                @else
                                                    🌙 Sesi Sore / Malam (18:00 – 22:00)
                                                @endif
                                            </span>
                                            <div class="h-px bg-gray-100 flex-1"></div>
                                        </div>

                                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                                            @foreach ($times as $time)
                                                @php
                                                    $timeShort = substr($time, 0, 5);
                                                    $isChecked = in_array($timeShort, $day['slots']);
                                                @endphp
                                                <label class="relative flex items-center justify-between p-3 rounded-xl border border-gray-200/80 bg-white hover:border-[#ED1B45] cursor-pointer transition select-none group has-checked:border-[#ED1B45] has-checked:bg-rose-50/40">
                                                    <span class="font-inter font-semibold text-xs text-gray-800 group-hover:text-[#ED1B45]">
                                                        {{ $timeShort }}
                                                    </span>
                                                    <input
                                                        type="checkbox"
                                                        name="days[{{ $dayNum }}][slots][]"
                                                        value="{{ $timeShort }}"
                                                        data-period="{{ $periodKey }}"
                                                        class="day-slot-{{ $dayNum }} rounded-sm border-gray-300 text-[#ED1B45] focus:ring-[#ED1B45] h-4 w-4"
                                                        {{ $isChecked ? 'checked' : '' }}
                                                    >
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Sticky / Bottom Submit Button -->
                <div class="sticky bottom-6 z-20 flex items-center justify-between gap-4 p-4 rounded-2xl bg-gray-900/90 backdrop-blur-md text-white shadow-xl">
                    <div class="flex items-center gap-3 pl-2">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-inter text-xs text-gray-200 hidden sm:block">
                            Pastikan jam kerja yang Anda buka siap untuk melayani sesi latihan member.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-xl font-poppins font-bold text-xs bg-[#ED1B45] hover:bg-[#BA0030] active:scale-95 text-white transition shadow-sm shrink-0"
                    >
                        Simpan Pengaturan Jadwal
                    </button>
                </div>
            </form>
        </div>

        <!-- 4. TAB 2: CUTI & HARI LIBUR KHUSUS -->
        <div x-show="activeTab === 'dayoffs'" x-cloak class="space-y-6">
            <!-- Form Tambah Hari Libur -->
            <div class="rounded-2xl bg-white p-6 border border-gray-100 shadow-xs">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-poppins font-bold text-base text-gray-900">
                            Catat Hari Libur / Cuti Khusus
                        </h3>
                        <p class="font-inter text-xs text-gray-500">
                            Pilih tanggal spesifik di mana Anda tidak melayani sesi PT (misal: sakit, cuti, atau keperluan mendesak).
                        </p>
                    </div>
                </div>

                <form action="{{ route('trainer.availability.day-offs.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label class="block font-inter text-xs font-semibold text-gray-700 mb-1.5">
                            Tanggal Libur <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="date"
                            min="{{ now()->toDateString() }}"
                            required
                            class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 font-inter text-xs text-gray-800 focus:outline-hidden focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] transition"
                        >
                    </div>

                    <div>
                        <label class="block font-inter text-xs font-semibold text-gray-700 mb-1.5">
                            Keterangan / Alasan (Opsional)
                        </label>
                        <input
                            type="text"
                            name="reason"
                            placeholder="Contoh: Cuti Tahunan, Sakit, Acara Keluarga"
                            class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 font-inter text-xs text-gray-800 focus:outline-hidden focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] transition"
                        >
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="w-full py-2.5 px-4 rounded-xl font-poppins font-bold text-xs bg-[#ED1B45] hover:bg-[#BA0030] text-white transition active:scale-95 shadow-sm text-center"
                        >
                            + Tambah Hari Libur
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Hari Libur Mendatang -->
            <div class="rounded-2xl bg-white border border-gray-100 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-poppins font-bold text-sm text-gray-900">
                            Daftar Hari Libur Mendatang
                        </h4>
                        <p class="font-inter text-xs text-gray-500 mt-0.5">
                            Total: {{ $upcomingDayOffs->count() }} hari libur terjadwal
                        </p>
                    </div>
                </div>

                @if ($upcomingDayOffs->isEmpty())
                    <div class="p-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h5 class="font-poppins font-bold text-sm text-gray-800">Belum Ada Hari Libur Khusus</h5>
                        <p class="font-inter text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                            Anda belum menjadwalkan cuti atau hari libur mendatang. Jadwal latihan akan mengacu pada jam mingguan rutin Anda.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left font-inter text-xs text-gray-700">
                            <thead class="bg-gray-50/70 text-gray-500 font-semibold uppercase text-[10px] tracking-wider border-b border-gray-100">
                                <tr>
                                    <th class="py-3 px-5">Tanggal</th>
                                    <th class="py-3 px-5">Keterangan</th>
                                    <th class="py-3 px-5">Status Sesi Member</th>
                                    <th class="py-3 px-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($upcomingDayOffs as $dayOff)
                                    @php
                                        $dateKey = $dayOff->date->toDateString();
                                        $conflictingCount = $conflictingSessionsCounts[$dateKey] ?? 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-4 px-5 font-semibold text-gray-900 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($dayOff->date)->translatedFormat('l, d F Y') }}
                                        </td>
                                        <td class="py-4 px-5 text-gray-600">
                                            {{ $dayOff->reason ?? 'Cuti Pribadi' }}
                                        </td>
                                        <td class="py-4 px-5">
                                            @if ($conflictingCount > 0)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                    </svg>
                                                    ⚠️ Ada {{ $conflictingCount }} Sesi Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                    Aman (0 sesi)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-5 text-right whitespace-nowrap">
                                            <form
                                                action="{{ route('trainer.availability.day-offs.destroy', $dayOff->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan hari libur pada tanggal ini?');"
                                                class="inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 rounded-lg font-semibold text-rose-600 hover:text-rose-800 hover:bg-rose-50 transition"
                                                >
                                                    Batalkan Libur
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-trainer-layout>

