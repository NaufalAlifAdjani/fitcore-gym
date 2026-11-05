<x-app-layout>
    <div 
        x-data="{
            showAddModal: false,
            showDetailModal: false,
            showEditModal: false,
            showDeleteModal: false,
            showToggleModal: false,
            selectedTrainer: null,
            editForm: {
                name: '',
                email: '',
                phone: '',
                specialization: '',
                experience: 0,
                status: 'active',
                bio: '',
                password: '',
                update_url: ''
            },
            openDetail(trainer) {
                this.selectedTrainer = trainer;
                this.showDetailModal = true;
            },
            openEdit(trainer) {
                this.selectedTrainer = trainer;
                this.editForm = {
                    name: trainer?.name || '',
                    email: trainer?.email || '',
                    phone: trainer?.phone || '',
                    specialization: trainer?.specialization || '',
                    experience: trainer?.experience || 0,
                    status: trainer?.status || 'active',
                    bio: trainer?.bio || '',
                    password: '',
                    update_url: trainer?.update_url || ''
                };
                this.showDetailModal = false;
                this.showEditModal = true;
            },
            openToggle(trainer) {
                this.selectedTrainer = trainer;
                this.showDetailModal = false;
                this.showToggleModal = true;
            },
            openDelete(trainer) {
                this.selectedTrainer = trainer;
                this.showDetailModal = false;
                this.showDeleteModal = true;
            }
        }"
        class="min-h-screen bg-[#F6F6F7] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1600px] space-y-8">
            
            <!-- Breadcrumb & Top Bar -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-medium text-[#565A66] mb-1">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-[#ED1B45] transition">Admin</a>
                        <span>/</span>
                        <span class="text-[#16151A] font-semibold">Manajemen Trainer</span>
                    </nav>
                    <h1 class="text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Manajemen Data Personal Trainer
                    </h1>
                    <p class="mt-1 font-inter text-sm text-[#565A66]">
                        Kelola data pelatih, spesialisasi keahlian, status akun, dan pengiriman kredensial akses ke pelatih secara terpusat.
                    </p>
                </div>

                <!-- Tombol Tambah Trainer Baru -->
                <div class="flex items-center gap-3">
                    <button 
                        type="button" 
                        @click="showAddModal = true"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-[#ED1B45] px-5 py-2.5 font-inter text-sm font-bold text-white shadow-md shadow-[#ED1B45]/20 hover:bg-[#D6153B] transition duration-200 cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Trainer Baru</span>
                    </button>
                </div>
            </div>

            <!-- Flash Alert Messages -->
            @if (session('success'))
                <div class="flex items-start gap-3 rounded-2xl bg-[#DCFCE7] border border-[#BBF7D0] p-4 text-[#166534] shadow-xs">
                    <svg class="h-5 w-5 shrink-0 text-[#16A34A] mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1 text-sm font-inter">
                        <p class="font-bold">Berhasil!</p>
                        <p class="mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 rounded-2xl bg-[#FEE2E2] border border-[#FECACA] p-4 text-[#991B1B] shadow-xs">
                    <svg class="h-5 w-5 shrink-0 text-[#DC2626] mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1 text-sm font-inter">
                        <p class="font-bold">Perhatian!</p>
                        <p class="mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl bg-[#FEF2F2] border border-[#FCA5A5] p-4 text-[#991B1B] shadow-xs">
                    <div class="flex items-center gap-2 font-bold font-inter text-sm mb-1 text-[#DC2626]">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Menampilkan pesan data tidak valid atau email sudah digunakan:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs font-inter space-y-1 ml-6 text-[#7F1D1D]">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. Stat Cards Row (Sesuai Layout Gambar Dashboard/Member) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card 1: Total Trainer -->
                <div class="rounded-[20px] bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Total Trainer</p>
                        <h3 class="text-3xl font-extrabold font-poppins text-[#16151A] mt-1">{{ number_format($totalTrainers) }}</h3>
                        <p class="text-xs font-medium text-[#10B981] mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            <span>Terdaftar di sistem</span>
                        </p>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-[#ED1B45]/10 flex items-center justify-center text-[#ED1B45]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Card 2: Trainer Aktif -->
                <div class="rounded-[20px] bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Trainer Aktif</p>
                        <h3 class="text-3xl font-extrabold font-poppins text-[#16151A] mt-1">{{ number_format($activeTrainers) }}</h3>
                        <p class="text-xs font-medium text-[#10B981] mt-1 flex items-center gap-1">
                            <span class="inline-block w-2 h-2 rounded-full bg-[#10B981]"></span>
                            <span>{{ $totalTrainers > 0 ? round(($activeTrainers / $totalTrainers) * 100) : 0 }}% rasio aktif</span>
                        </p>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#16A34A]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Card 3: Trainer Nonaktif -->
                <div class="rounded-[20px] bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Trainer Nonaktif</p>
                        <h3 class="text-3xl font-extrabold font-poppins text-[#16151A] mt-1">{{ number_format($inactiveTrainers) }}</h3>
                        <p class="text-xs font-medium text-[#D97706] mt-1 flex items-center gap-1">
                            <span class="inline-block w-2 h-2 rounded-full bg-[#D97706]"></span>
                            <span>Perlu tinjauan status</span>
                        </p>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-[#FEF3C7] flex items-center justify-center text-[#D97706]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>

                <!-- Card 4: Total Sesi Dilatih -->
                <div class="rounded-[20px] bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Total Sesi Dilatih</p>
                        <h3 class="text-3xl font-extrabold font-poppins text-[#16151A] mt-1">{{ number_format($totalSessionsHandled) }}</h3>
                        <p class="text-xs font-medium text-[#3B82F6] mt-1">Sesi PT selesai & berjalan</p>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-[#DBEAFE] flex items-center justify-center text-[#2563EB]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 2. Filter & Pencarian Bar -->
            <div class="rounded-[20px] bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] space-y-4">
                <form method="GET" action="{{ route('admin.trainers.index') }}" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <!-- Tab Kategori Status -->
                    <div class="flex items-center gap-1.5 p-1 bg-[#F6F6F7] rounded-full self-start sm:self-auto overflow-x-auto w-full lg:w-auto">
                        <a 
                            href="{{ route('admin.trainers.index', ['status' => 'all', 'search' => $currentSearch, 'specialization' => $currentSpecialization]) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ $currentStatus === 'all' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        >
                            Semua ({{ $totalTrainers }})
                        </a>
                        <a 
                            href="{{ route('admin.trainers.index', ['status' => 'active', 'search' => $currentSearch, 'specialization' => $currentSpecialization]) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ $currentStatus === 'active' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        >
                            Aktif ({{ $activeTrainers }})
                        </a>
                        <a 
                            href="{{ route('admin.trainers.index', ['status' => 'inactive', 'search' => $currentSearch, 'specialization' => $currentSpecialization]) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ $currentStatus === 'inactive' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}"
                        >
                            Nonaktif ({{ $inactiveTrainers }})
                        </a>
                    </div>

                    <!-- Input Search & Dropdown Filter -->
                    <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                        <input type="hidden" name="status" value="{{ $currentStatus }}">

                        <!-- Input Pencarian -->
                        <div class="relative flex-1 sm:w-64">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9CA3AF]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ $currentSearch }}" 
                                placeholder="Cari nama, email, spesialisasi..."
                                class="w-full pl-9 pr-3 py-2 text-xs font-inter rounded-full border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] placeholder-[#9CA3AF] focus:border-[#ED1B45] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            />
                        </div>

                        <!-- Filter Spesialisasi -->
                        <select 
                            name="specialization" 
                            onchange="this.form.submit()"
                            class="text-xs font-inter rounded-full border border-[#E5E7EB] bg-[#F6F6F7] px-4 py-2 text-[#565A66] focus:border-[#ED1B45] focus:bg-white focus:outline-none transition cursor-pointer"
                        >
                            <option value="all">Semua Spesialisasi</option>
                            @foreach ($availableSpecializations as $spec)
                                <option value="{{ $spec }}" {{ $currentSpecialization === $spec ? 'selected' : '' }}>{{ $spec }}</option>
                            @endforeach
                        </select>

                        <!-- Tombol Cari -->
                        <button type="submit" class="rounded-full bg-[#16151A] text-white px-4 py-2 text-xs font-bold hover:bg-black transition cursor-pointer">
                            Filter
                        </button>

                        @if ($currentSearch || $currentStatus !== 'all' || $currentSpecialization !== 'all')
                            <a href="{{ route('admin.trainers.index') }}" class="text-xs font-semibold text-[#ED1B45] hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 3. Tabel Daftar Personal Trainer -->
            <div class="rounded-[20px] bg-white border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#E5E7EB] bg-[#FAFAFA] text-[11px] font-bold text-[#565A66] uppercase tracking-wider font-inter">
                                <th class="py-4 px-6">Trainer</th>
                                <th class="py-4 px-6">Spesialisasi &amp; Pengalaman</th>
                                <th class="py-4 px-6">Total Sesi Dilatih</th>
                                <th class="py-4 px-6">Status Akun</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F1F2F4] text-xs font-inter text-[#16151A]">
                            @forelse ($trainers as $trainer)
                                <tr class="hover:bg-[#F9FAFB] transition duration-150">
                                    <!-- Kolom Trainer (Avatar, Nama, Email, Phone) -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @if ($trainer->avatar)
                                                <img src="{{ $trainer->avatar }}" alt="{{ $trainer->name }}" class="h-10 w-10 rounded-full object-cover border border-[#E5E7EB] shrink-0">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-[#ED1B45]/10 text-[#ED1B45] font-poppins font-black flex items-center justify-center shrink-0 border border-[#ED1B45]/20">
                                                    {{ strtoupper(substr($trainer->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-sm font-poppins text-[#16151A]">{{ $trainer->name }}</div>
                                                <div class="text-[11px] text-[#565A66]">{{ $trainer->email }}</div>
                                                <div class="text-[11px] text-[#9CA3AF]">{{ $trainer->phone ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kolom Spesialisasi & Pengalaman -->
                                    <td class="py-4 px-6">
                                        <div class="inline-block px-3 py-1 rounded-full bg-[#F3F4F6] text-[#374151] font-semibold text-[11px] mb-1">
                                            {{ $trainer->trainerProfile?->specialization ?? 'Umum / Fitness' }}
                                        </div>
                                        <div class="text-[11px] text-[#6B7280]">
                                            {{ $trainer->trainerProfile?->experience ?? 0 }} Tahun Pengalaman
                                        </div>
                                    </td>

                                    <!-- Kolom Sesi Dilatih -->
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-[#16151A]">{{ $trainer->trainer_sessions_count ?? 0 }}</span>
                                        <span class="text-[#6B7280] text-[11px]"> Sesi</span>
                                    </td>

                                    <!-- Kolom Status -->
                                    <td class="py-4 px-6">
                                        @if ($trainer->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#16A34A] border border-[#BBF7D0] text-[11px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FEE2E2] text-[#DC2626] border border-[#FECACA] text-[11px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#DC2626]"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Kolom Aksi -->
                                    <td class="py-4 px-6 text-right">
                                        <button 
                                            type="button" 
                                            @click="openDetail({{ json_encode([
                                                'id' => $trainer->id,
                                                'name' => $trainer->name,
                                                'email' => $trainer->email,
                                                'phone' => $trainer->phone,
                                                'status' => $trainer->status,
                                                'avatar' => $trainer->avatar,
                                                'specialization' => $trainer->trainerProfile?->specialization ?? 'Umum',
                                                'experience' => $trainer->trainerProfile?->experience ?? 0,
                                                'bio' => $trainer->trainerProfile?->bio ?? '',
                                                'sessions_count' => $trainer->trainer_sessions_count ?? 0,
                                                'created_at' => $trainer->created_at?->format('d M Y'),
                                                'update_url' => route('admin.trainers.update', $trainer),
                                                'toggle_url' => route('admin.trainers.toggle-status', $trainer),
                                                'delete_url' => route('admin.trainers.destroy', $trainer),
                                            ]) }})"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-[#E5E7EB] hover:border-[#16151A] bg-white hover:bg-[#F9FAFB] text-[#16151A] font-bold text-xs transition duration-150 cursor-pointer shadow-2xs"
                                        >
                                            <svg class="w-3.5 h-3.5 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Lihat Detail</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-[#9CA3AF]">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-14 w-14 rounded-full bg-[#F3F4F6] flex items-center justify-center mb-3 text-[#9CA3AF]">
                                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <p class="font-bold text-sm text-[#16151A]">Belum Ada Data Personal Trainer</p>
                                            <p class="text-xs text-[#6B7280] mt-1 max-w-sm">
                                                Tidak ditemukan data pelatih yang cocok dengan kriteria pencarian Anda. Silakan tambah akun pelatih baru.
                                            </p>
                                            <button 
                                                type="button" 
                                                @click="showAddModal = true"
                                                class="mt-4 rounded-full bg-[#ED1B45] text-white px-4 py-2 text-xs font-bold hover:bg-[#D6153B] transition shadow-xs"
                                            >
                                                + Tambah Trainer Sekarang
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if ($trainers->hasPages())
                    <div class="p-4 border-t border-[#E5E7EB]">
                        {{ $trainers->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- ======================================================== -->
        <!-- MODAL 1: LIHAT DETAIL & OPS KELOLA PELATIH (Sesuai Gambar 2) -->
        <!-- ======================================================== -->
        <div 
            x-show="showDetailModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="showDetailModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-show="showDetailModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative w-full max-w-lg rounded-3xl bg-white shadow-2xl border border-[#E5E7EB] overflow-hidden p-6 sm:p-8"
                >
                    <!-- Close Button -->
                    <button 
                        @click="showDetailModal = false"
                        class="absolute top-5 right-5 h-8 w-8 rounded-full bg-[#F3F4F6] text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] flex items-center justify-center transition cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Header Profil Pelatih -->
                    <div class="flex items-center gap-4 border-b border-[#F1F2F4] pb-6">
                        <template x-if="selectedTrainer && selectedTrainer.avatar">
                            <img :src="selectedTrainer.avatar" class="h-16 w-16 rounded-2xl object-cover border border-[#E5E7EB] shadow-xs">
                        </template>
                        <template x-if="selectedTrainer && !selectedTrainer.avatar">
                            <div class="h-16 w-16 rounded-2xl bg-[#ED1B45]/10 text-[#ED1B45] font-poppins font-black text-xl flex items-center justify-center border border-[#ED1B45]/20">
                                <span x-text="selectedTrainer ? selectedTrainer.name.substring(0, 2).toUpperCase() : 'TR'"></span>
                            </div>
                        </template>

                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#ED1B45] text-white">
                                Profil Trainer
                            </span>
                            <h3 class="text-xl font-black font-poppins text-[#16151A] mt-1" x-text="selectedTrainer?.name"></h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-[#565A66]" x-text="'ID Trainer: #TRN-' + selectedTrainer?.id"></span>
                                <span 
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold"
                                    :class="selectedTrainer?.status === 'active' ? 'bg-[#DCFCE7] text-[#16A34A]' : 'bg-[#FEE2E2] text-[#DC2626]'"
                                    x-text="selectedTrainer?.status === 'active' ? 'Aktif' : 'Nonaktif'"
                                ></span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Kontak Cepat -->
                    <div class="grid grid-cols-2 gap-3 my-4">
                        <a 
                            :href="'https://wa.me/' + (selectedTrainer?.phone ? selectedTrainer.phone.replace(/[^0-9]/g, '') : '')" 
                            target="_blank"
                            class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#DCFCE7] text-[#16A34A] hover:bg-[#BBF7D0] transition font-bold text-xs"
                        >
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.187-2.59-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                        <a 
                            :href="'mailto:' + selectedTrainer?.email"
                            class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-[#F3F4F6] text-[#374151] hover:bg-[#E5E7EB] transition font-bold text-xs"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Email</span>
                        </a>
                    </div>

                    <!-- Informasi Detail Data -->
                    <div class="space-y-3 py-2 text-xs font-inter">
                        <div class="flex justify-between py-1.5 border-b border-[#F1F2F4]">
                            <span class="text-[#565A66]">Email Login</span>
                            <span class="font-bold text-[#16151A]" x-text="selectedTrainer?.email"></span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-[#F1F2F4]">
                            <span class="text-[#565A66]">No. Telepon/WhatsApp</span>
                            <span class="font-bold text-[#16151A]" x-text="selectedTrainer?.phone || '-'"></span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-[#F1F2F4]">
                            <span class="text-[#565A66]">Spesialisasi</span>
                            <span class="font-bold text-[#ED1B45]" x-text="selectedTrainer?.specialization"></span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-[#F1F2F4]">
                            <span class="text-[#565A66]">Pengalaman Melatih</span>
                            <span class="font-bold text-[#16151A]" x-text="(selectedTrainer?.experience || 0) + ' Tahun'"></span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-[#F1F2F4]">
                            <span class="text-[#565A66]">Total Sesi Selesai</span>
                            <span class="font-bold text-[#16151A]" x-text="(selectedTrainer?.sessions_count || 0) + ' Sesi PT'"></span>
                        </div>
                        <div class="py-1.5">
                            <span class="text-[#565A66] block mb-1">Bio / Profil Singkat:</span>
                            <p class="text-[#374151] bg-[#F9FAFB] p-3 rounded-xl border border-[#E5E7EB] italic leading-relaxed" x-text="selectedTrainer?.bio || 'Belum ada bio atau deskripsi profil.'"></p>
                        </div>
                    </div>

                    <!-- Action Buttons (Percabangan Aksi Sesuai Ketentuan 2) -->
                    <div class="mt-6 pt-4 border-t border-[#E5E7EB] space-y-2">
                        <!-- Cabang UBAH: Form Edit Data -->
                        <button 
                            type="button" 
                            @click="openEdit(selectedTrainer)"
                            class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-[#ED1B45] text-white font-bold text-xs shadow-md shadow-[#ED1B45]/20 hover:bg-[#D6153B] transition cursor-pointer"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Ubah Data Pelatih</span>
                        </button>

                        <div class="grid grid-cols-2 gap-2">
                            <!-- Cabang KELOLA: Nonaktifkan / Aktifkan -->
                            <button 
                                type="button" 
                                @click="openToggle(selectedTrainer)"
                                class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-white hover:bg-[#F9FAFB] text-[#374151] font-bold text-xs transition cursor-pointer"
                            >
                                <svg class="h-4 w-4 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                <span x-text="selectedTrainer?.status === 'active' ? 'Nonaktifkan' : 'Aktifkan Akun'"></span>
                            </button>

                            <!-- Cabang KELOLA: Hapus Permanen -->
                            <button 
                                type="button" 
                                @click="openDelete(selectedTrainer)"
                                class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl border border-[#FECACA] bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#DC2626] font-bold text-xs transition cursor-pointer"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Hapus Permanen</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 2: TAMBAH AKUN PELATIH BARU (Cabang "TAMBAH") -->
        <!-- ======================================================== -->
        <div 
            x-show="showAddModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="showAddModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-show="showAddModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative w-full max-w-xl rounded-3xl bg-white shadow-2xl border border-[#E5E7EB] overflow-hidden p-6 sm:p-8"
                >
                    <!-- Close Button -->
                    <button 
                        @click="showAddModal = false"
                        class="absolute top-5 right-5 h-8 w-8 rounded-full bg-[#F3F4F6] text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] flex items-center justify-center transition cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="mb-6">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#ED1B45] text-white">
                            Formulir Akun
                        </span>
                        <h2 class="text-2xl font-black font-poppins text-[#16151A] mt-1">Tambah Personal Trainer Baru</h2>
                        <p class="text-xs text-[#565A66] mt-0.5 font-inter">
                            Isi informasi akun pelatih baru. Kredensial login akan dikirimkan otomatis ke email yang didaftarkan.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.trainers.store') }}" class="space-y-4 font-inter">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-[#16151A] mb-1">Nama Lengkap Trainer <span class="text-[#ED1B45]">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                required
                                placeholder="Contoh: Coach Doni Pratama"
                                value="{{ old('name') }}"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            />
                        </div>

                        <!-- Email & Telepon Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">Alamat Email <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    required
                                    placeholder="pelatih@fitcore.my.id"
                                    value="{{ old('email') }}"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">No. WhatsApp / HP <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="text" 
                                    name="phone" 
                                    required
                                    placeholder="08123456789"
                                    value="{{ old('phone') }}"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                            </div>
                        </div>

                        <!-- Spesialisasi & Pengalaman Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">Spesialisasi Keahlian <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="text" 
                                    name="specialization" 
                                    required
                                    list="specialization-options"
                                    placeholder="Pilih atau ketik spesialisasi"
                                    value="{{ old('specialization') }}"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                                <datalist id="specialization-options">
                                    <option value="Muscle Building & Hypertrophy">
                                    <option value="Weight Loss & Fat Burn">
                                    <option value="Strength & Conditioning">
                                    <option value="Functional Training & Mobility">
                                    <option value="Cardio Endurance">
                                    <option value="Calisthenics & Bodyweight">
                                </datalist>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">Pengalaman (Tahun) <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="number" 
                                    name="experience" 
                                    min="0" 
                                    max="50" 
                                    required
                                    placeholder="Contoh: 4"
                                    value="{{ old('experience', 1) }}"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                            </div>
                        </div>

                        <!-- Password Opsional -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#16151A]">Kata Sandi Awal</label>
                                <span class="text-[11px] text-[#6B7280]">Opsional (acak otomatis jika kosong)</span>
                            </div>
                            <input 
                                type="text" 
                                name="password" 
                                placeholder="Biarkan kosong untuk generate acak otomatis"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            />
                        </div>

                        <!-- Bio / Deskripsi -->
                        <div>
                            <label class="block text-xs font-bold text-[#16151A] mb-1">Bio &amp; Latar Belakang Sertifikasi</label>
                            <textarea 
                                name="bio" 
                                rows="3"
                                placeholder="Tuliskan latar belakang sertifikasi (e.g. APKI/NASM), gaya melatih, dll..."
                                class="w-full px-4 py-2 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            >{{ old('bio') }}</textarea>
                        </div>

                        <!-- Checkbox Kirim Email Kredensial -->
                        <div class="rounded-xl bg-[#F0FDF4] border border-[#BBF7D0] p-3 flex items-center gap-3">
                            <input 
                                type="checkbox" 
                                name="send_email" 
                                id="send_email" 
                                value="1" 
                                checked
                                class="h-4 w-4 rounded border-[#BBF7D0] text-[#16A34A] focus:ring-[#16A34A]"
                            />
                            <label for="send_email" class="text-xs text-[#166534] font-medium cursor-pointer">
                                <strong>Kirim kredensial otomatis:</strong> Kirimkan email berisi username dan password langsung ke akun pelatih setelah data tersimpan.
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E5E7EB]">
                            <button 
                                type="button" 
                                @click="showAddModal = false"
                                class="px-5 py-2.5 rounded-full border border-[#E5E7EB] text-[#565A66] hover:bg-[#F3F4F6] text-xs font-bold transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-6 py-2.5 rounded-full bg-[#ED1B45] text-white text-xs font-bold hover:bg-[#D6153B] transition shadow-md shadow-[#ED1B45]/20 cursor-pointer"
                            >
                                Simpan Akun Trainer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 3: UBAH DATA PELATIH (Cabang "UBAH") -->
        <!-- ======================================================== -->
        <div 
            x-show="showEditModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-show="showEditModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative w-full max-w-xl rounded-3xl bg-white shadow-2xl border border-[#E5E7EB] overflow-hidden p-6 sm:p-8"
                >
                    <!-- Close Button -->
                    <button 
                        @click="showEditModal = false"
                        class="absolute top-5 right-5 h-8 w-8 rounded-full bg-[#F3F4F6] text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] flex items-center justify-center transition cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="mb-6">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#3B82F6] text-white">
                            Edit Data
                        </span>
                        <h2 class="text-2xl font-black font-poppins text-[#16151A] mt-1">Ubah Data Personal Trainer</h2>
                        <p class="text-xs text-[#565A66] mt-0.5 font-inter" x-text="'Perbarui profil dan status akun ' + (selectedTrainer?.name || '')"></p>
                    </div>

                    <form 
                        method="POST" 
                        :action="editForm.update_url" 
                        class="space-y-4 font-inter"
                    >
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-[#16151A] mb-1">Nama Lengkap Trainer <span class="text-[#ED1B45]">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                required
                                x-model="editForm.name"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            />
                        </div>

                        <!-- Email & Telepon Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">Alamat Email <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    required
                                    x-model="editForm.email"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">No. WhatsApp / HP <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="text" 
                                    name="phone" 
                                    required
                                    x-model="editForm.phone"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                            </div>
                        </div>

                        <!-- Spesialisasi & Pengalaman Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">Spesialisasi Keahlian <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="text" 
                                    name="specialization" 
                                    required
                                    x-model="editForm.specialization"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#16151A] mb-1">Pengalaman (Tahun) <span class="text-[#ED1B45]">*</span></label>
                                <input 
                                    type="number" 
                                    name="experience" 
                                    min="0" 
                                    max="50" 
                                    required
                                    x-model="editForm.experience"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                                />
                            </div>
                        </div>

                        <!-- Status Akun -->
                        <div>
                            <label class="block text-xs font-bold text-[#16151A] mb-1">Status Keaktifan Akun <span class="text-[#ED1B45]">*</span></label>
                            <select 
                                name="status" 
                                x-model="editForm.status"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            >
                                <option value="active">Aktif (Dapat menerima jadwal sesi PT)</option>
                                <option value="inactive">Nonaktif (Ditangguhkan sementara)</option>
                            </select>
                        </div>

                        <!-- Password Baru (Opsional) -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#16151A]">Ubah Kata Sandi (Opsional)</label>
                                <span class="text-[11px] text-[#6B7280]">Kosongkan jika tidak ingin mengubah</span>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                x-model="editForm.password"
                                placeholder="Masukkan kata sandi baru (min. 8 karakter)"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            />
                        </div>

                        <!-- Bio / Deskripsi -->
                        <div>
                            <label class="block text-xs font-bold text-[#16151A] mb-1">Bio / Latar Belakang</label>
                            <textarea 
                                name="bio" 
                                rows="3"
                                x-model="editForm.bio"
                                class="w-full px-4 py-2 text-xs rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] focus:bg-white focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            ></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E5E7EB]">
                            <button 
                                type="button" 
                                @click="showEditModal = false; showDetailModal = true"
                                class="px-5 py-2.5 rounded-full border border-[#E5E7EB] text-[#565A66] hover:bg-[#F3F4F6] text-xs font-bold transition cursor-pointer"
                            >
                                Kembali
                            </button>
                            <button 
                                type="submit" 
                                class="px-6 py-2.5 rounded-full bg-[#3B82F6] text-white text-xs font-bold hover:bg-[#2563EB] transition shadow-md shadow-[#3B82F6]/20 cursor-pointer"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 4: KONFIRMASI TOGGLE STATUS (NONAKTIFKAN / AKTIFKAN) -->
        <!-- ======================================================== -->
        <div 
            x-show="showToggleModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="showToggleModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-show="showToggleModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative w-full max-w-md rounded-3xl bg-white shadow-2xl border border-[#E5E7EB] overflow-hidden p-6 text-center"
                >
                    <div class="h-14 w-14 rounded-full bg-[#FEF3C7] text-[#D97706] flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold font-poppins text-[#16151A]">
                        Ubah Status Akun Pelatih?
                    </h3>
                    <p class="text-xs text-[#565A66] mt-2 font-inter leading-relaxed">
                        Apakah Anda yakin ingin <strong x-text="selectedTrainer?.status === 'active' ? 'menonaktifkan' : 'mengaktifkan kembali'"></strong> akun pelatih <span class="font-bold text-[#16151A]" x-text="selectedTrainer?.name"></span>?
                    </p>

                    <form method="POST" :action="selectedTrainer ? selectedTrainer.toggle_url : '#'" class="mt-6 flex items-center justify-center gap-3">
                        @csrf
                        @method('PATCH')

                        <button 
                            type="button" 
                            @click="showToggleModal = false; showDetailModal = true"
                            class="px-5 py-2.5 rounded-full border border-[#E5E7EB] text-[#565A66] hover:bg-[#F3F4F6] text-xs font-bold transition cursor-pointer"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-full bg-[#D97706] text-white text-xs font-bold hover:bg-[#B45309] transition shadow-md cursor-pointer"
                        >
                            Ya, Ubah Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL 5: KONFIRMASI HAPUS PERMANEN (Cabang "HAPUS PERMANEN") -->
        <!-- ======================================================== -->
        <div 
            x-show="showDeleteModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="showDeleteModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-show="showDeleteModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative w-full max-w-md rounded-3xl bg-white shadow-2xl border border-[#E5E7EB] overflow-hidden p-6 text-center"
                >
                    <div class="h-14 w-14 rounded-full bg-[#FEE2E2] text-[#DC2626] flex items-center justify-center mx-auto mb-4">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold font-poppins text-[#991B1B]">
                        Hapus Akun Pelatih Permanen?
                    </h3>
                    <p class="text-xs text-[#565A66] mt-2 font-inter leading-relaxed">
                        Tindakan ini akan <strong>menghapus seluruh rekaman akun</strong> pelatih <span class="font-bold text-[#16151A]" x-text="selectedTrainer?.name"></span> dari database secara permanen dan tidak dapat dibatalkan.
                    </p>

                    <form method="POST" :action="selectedTrainer ? selectedTrainer.delete_url : '#'" class="mt-6 flex items-center justify-center gap-3">
                        @csrf
                        @method('DELETE')

                        <button 
                            type="button" 
                            @click="showDeleteModal = false; showDetailModal = true"
                            class="px-5 py-2.5 rounded-full border border-[#E5E7EB] text-[#565A66] hover:bg-[#F3F4F6] text-xs font-bold transition cursor-pointer"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-full bg-[#DC2626] text-white text-xs font-bold hover:bg-[#B91C1C] transition shadow-md shadow-[#DC2626]/20 cursor-pointer"
                        >
                            Ya, Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
