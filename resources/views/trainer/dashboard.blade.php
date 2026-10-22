<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-[#16151A]">Dashboard Personal Trainer</h1>
                <p class="font-inter text-sm text-[#565A66]">Pantau jadwal latihan dan sesi personal training Anda di FitCore Gym.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 font-inter text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                    {{ $trainerProfile?->specialization ?? 'Personal Trainer' }}
                </span>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 font-inter text-xs font-medium text-slate-700">
                    Pengalaman: {{ $trainerProfile?->experience ?? 0 }} Tahun
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
        <!-- Trainer Welcome Card -->
        <div class="rounded-2xl bg-gradient-to-r from-[#16151A] to-[#2B2933] p-6 text-white shadow-lg sm:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-block rounded-full bg-[#ED1B45]/20 px-3 py-1 font-inter text-xs font-bold uppercase tracking-wider text-[#ED1B45]">
                        Portal Pelatih FitCore
                    </span>
                    <h2 class="mt-2 font-poppins text-2xl font-bold tracking-tight sm:text-3xl">
                        Selamat Bertugas, {{ $trainer->name }}! 💪
                    </h2>
                    <p class="mt-2 font-inter text-sm text-slate-300 max-w-2xl leading-relaxed">
                        {{ $trainerProfile?->bio ?? 'Membantu member FitCore mencapai potensi performa fisik dan target kebugaran terbaik mereka.' }}
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-3 bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <div class="h-12 w-12 rounded-full bg-[#ED1B45] flex items-center justify-center font-bold text-lg text-white">
                        {{ strtoupper(substr($trainer->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-inter">Status Pelatih</p>
                        <p class="text-sm font-semibold text-white capitalize font-inter">{{ $trainerProfile?->status ?? 'Aktif' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium font-inter text-[#565A66]">Total Sesi Melatih</span>
                    <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3.5" y="5" width="17" height="16" rx="2.5" />
                            <path d="M7.5 3v4M16.5 3v4M4 9.5h16" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 font-poppins text-3xl font-extrabold text-[#16151A]">
                    {{ $trainer->trainerSessions()->count() }}
                </p>
                <p class="mt-1 text-xs text-slate-400 font-inter">Total sesi terdaftar</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium font-inter text-[#565A66]">Sesi Hari Ini</span>
                    <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3 3" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 font-poppins text-3xl font-extrabold text-emerald-600">
                    {{ $trainer->trainerSessions()->whereDate('session_date', today())->count() }}
                </p>
                <p class="mt-1 text-xs text-slate-400 font-inter">Jadwal pada tanggal {{ today()->format('d M Y') }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium font-inter text-[#565A66]">Sesi Berlangsung / Aktif</span>
                    <span class="p-2.5 rounded-xl bg-rose-50 text-[#ED1B45]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 font-poppins text-3xl font-extrabold text-[#ED1B45]">
                    {{ $trainer->trainerSessions()->where('status', 'in_progress')->count() }}
                </p>
                <p class="mt-1 text-xs text-slate-400 font-inter">Sesi yang sedang berjalan</p>
            </div>
        </div>

        <!-- Recent Sessions Table -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-poppins text-lg font-bold text-[#16151A]">Daftar Sesi Personal Training</h3>
                    <p class="font-inter text-xs text-[#565A66]">Daftar sesi pelatihan member terkini.</p>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left font-inter text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-semibold uppercase text-slate-400">
                            <th class="py-3 px-4">Nama Member</th>
                            <th class="py-3 px-4">Paket PT</th>
                            <th class="py-3 px-4">Tanggal & Jam</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($sessions as $session)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4 font-semibold text-slate-900">
                                    {{ $session->member?->name ?? 'Member FitCore' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ ($session->quota ?? $session->memberPtQuota)?->ptPackage?->name ?? 'Personal Trainer Session' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}
                                    <span class="text-xs text-slate-400 block">{{ $session->start_time }} - {{ $session->end_time }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $badgeStyles = match ($session->status) {
                                            'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                            default => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                        };
                                        $statusLabels = [
                                            'scheduled' => 'Terjadwal',
                                            'in_progress' => 'Berlangsung',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $badgeStyles }}">
                                        {{ $statusLabels[$session->status] ?? ucfirst($session->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    Belum ada sesi personal training yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
