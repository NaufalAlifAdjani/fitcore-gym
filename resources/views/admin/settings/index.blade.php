<div class="min-h-screen bg-[#F6F6F7] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10">
    <div class="mx-auto max-w-[1600px] space-y-6">

        <!-- 1. Header Halaman Pengaturan & Komisi PT -->
        <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-inter text-xs font-bold text-[#FF5722] tracking-wider uppercase">PENGATURAN &amp; KEUANGAN</p>
                <h1 class="mt-1 text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                    Manajemen Komisi PT (Skema 70/30)
                </h1>
                <p class="font-inter text-sm text-[#565A66] mt-0.5">
                    Kelola transparansi pembagian pendapatan sesi personal trainer (70% Komisi Pelatih, 30% Pendapatan Gym) dan pencairan dana.
                </p>
            </div>

            <!-- Skema Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 border border-orange-200/80 rounded-2xl shadow-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-[#FF5722]"></span>
                <span class="font-poppins font-black text-xs text-[#FF5722]">SKEMA AKTIF: 70% PT / 30% GYM</span>
            </div>
        </header>

        <!-- 2. Flash Notification Banner -->
        @if (session()->has('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">&times;</button>
            </div>
        @endif

        @if (session()->has('info'))
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-bold flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-800">&times;</button>
            </div>
        @endif

        <!-- 3. Ringkasan Statistik Komisi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Omzet PT -->
            <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">Total Omzet PT</span>
                <p class="font-poppins font-black text-2xl text-[#16151A] mt-1">
                    Rp {{ number_format($totalTarif, 0, ',', '.') }}
                </p>
                <span class="text-[11px] text-[#565A66] mt-0.5 block">Akumulasi seluruh transaksi PT</span>
            </div>

            <!-- Card 2: Total Komisi Trainer (70%) -->
            <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-xs font-bold uppercase tracking-wider text-[#FF5722]">Komisi Trainer (70%)</span>
                <p class="font-poppins font-black text-2xl text-[#FF5722] mt-1">
                    Rp {{ number_format($totalTrainerCommission, 0, ',', '.') }}
                </p>
                <span class="text-[11px] text-[#565A66] mt-0.5 block">Hak pendapatan seluruh pelatih</span>
            </div>

            <!-- Card 3: Pendapatan Gym (30%) -->
            <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Pendapatan Gym (30%)</span>
                <p class="font-poppins font-black text-2xl text-emerald-700 mt-1">
                    Rp {{ number_format($totalGymRevenue, 0, ',', '.') }}
                </p>
                <span class="text-[11px] text-[#565A66] mt-0.5 block">Alokasi operasional FitCore</span>
            </div>

            <!-- Card 4: Menunggu Pencairan -->
            <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Pencairan Dana</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-poppins font-black text-2xl text-amber-600">{{ $pendingPayoutCount }}</span>
                    <span class="text-xs text-[#565A66] font-semibold">Pending</span>
                    <span class="text-gray-300">/</span>
                    <span class="font-poppins font-black text-lg text-emerald-700">{{ $paidPayoutCount }}</span>
                    <span class="text-xs text-[#565A66] font-semibold">Paid</span>
                </div>
                <span class="text-[11px] text-[#565A66] mt-0.5 block">Status verifikasi transfer komisi</span>
            </div>
        </div>

        <!-- 4. Filter & Pencarian Bar -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="w-full md:w-96 relative">
                <svg class="w-4 h-4 text-[#565A66] absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama trainer, klien, invoice..."
                    class="w-full pl-10 pr-4 py-2 rounded-full border border-[#E5E7EB] text-xs text-[#16151A] placeholder-[#565A66] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722]"
                >
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <!-- Filter Status Payout -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#565A66] font-bold">Status:</span>
                    <select
                        wire:model.live="statusFilter"
                        class="px-3 py-2 rounded-full border border-[#E5E7EB] bg-white text-xs font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722]"
                    >
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending (Belum Cair)</option>
                        <option value="paid">Paid (Sudah Cair)</option>
                    </select>
                </div>

                <!-- Filter Trainer -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#565A66] font-bold">Trainer:</span>
                    <select
                        wire:model.live="trainerFilter"
                        class="px-3 py-2 rounded-full border border-[#E5E7EB] bg-white text-xs font-semibold text-[#16151A] focus:outline-none focus:border-[#FF5722]"
                    >
                        <option value="all">Semua Trainer</option>
                        @foreach ($trainers as $trainerOption)
                            <option value="{{ $trainerOption->id }}">{{ $trainerOption->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 5. Render Tabel Filament / Livewire -->
        {{ $this->table }}

    </div>
</div>

