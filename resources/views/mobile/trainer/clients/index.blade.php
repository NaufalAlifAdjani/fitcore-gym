{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Daftar Klien & Member Bimbingan Mobile
VIEW: mobile.trainer.clients.index
LAYOUT: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-trainer title="Klien Saya - FitCore Coach" active="clients">
    <div class="space-y-4">

        <!-- 1. Header Judul Halaman & Tanggal -->
        <div class="flex items-start justify-between gap-3 pb-1">
            <div>
                <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                    Member &amp; Klien Bimbingan
                </h1>
                <p class="text-[11px] text-[#565A66] font-medium font-sans">
                    Kelola seluruh member yang pernah atau sedang aktif berlatih bersama Anda.
                </p>
            </div>
            <div class="shrink-0">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-[#E5E7EB] text-[10px] font-bold text-[#565A66] shadow-2xs">
                    <svg class="w-3 h-3 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
                </span>
            </div>
        </div>

        <!-- 2. Ringkasan Metrik (3 Kartu Ringkas Sesuai Desktop) -->
        <div class="grid grid-cols-3 gap-2">
            <!-- Total Portofolio -->
            <div class="p-3 rounded-2xl bg-white border border-[#E5E7EB] shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-[#FF5722]">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#565A66]">Total</span>
                </div>
                <div class="mt-2">
                    <span class="text-xl font-extrabold font-poppins text-[#16151A] block leading-tight">
                        {{ $totalClientsCount ?? $clients->total() }}
                    </span>
                    <span class="text-[10px] text-[#565A66] font-medium">Member</span>
                </div>
            </div>

            <!-- Kuota Menipis -->
            <a href="{{ route('mobile.trainer.clients.index', array_merge(request()->query(), ['status' => 'low_quota'])) }}"
               class="p-3 rounded-2xl bg-white border {{ ($statusFilter ?? '') === 'low_quota' ? 'border-amber-400 ring-2 ring-amber-100' : 'border-[#E5E7EB]' }} shadow-2xs flex flex-col justify-between transition">
                <div class="flex items-center gap-1.5 text-amber-600">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#565A66]">Menipis</span>
                </div>
                <div class="mt-2">
                    <span class="text-xl font-extrabold font-poppins {{ ($lowQuotaClientsCount ?? 0) > 0 ? 'text-amber-600' : 'text-[#16151A]' }} block leading-tight">
                        {{ $lowQuotaClientsCount ?? 0 }}
                    </span>
                    <span class="text-[10px] text-[#565A66] font-medium">&le; 2 Sesi</span>
                </div>
            </a>

            <!-- Sesi Selesai Bulan Ini -->
            <div class="p-3 rounded-2xl bg-white border border-[#E5E7EB] shadow-2xs flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-blue-600">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#565A66]">Sesi</span>
                </div>
                <div class="mt-2">
                    <span class="text-xl font-extrabold font-poppins text-[#16151A] block leading-tight">
                        {{ $completedSessionsMonth ?? 0 }}
                    </span>
                    <span class="text-[10px] text-[#565A66] font-medium">Bulan Ini</span>
                </div>
            </div>
        </div>

        <!-- 3. Toolbar Pencarian & Filter Status -->
        <div class="space-y-2">
            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('mobile.trainer.clients.index') }}" class="relative">
                @if(request('status') && request('status') !== 'all')
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#565A66]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama klien, email, atau ID member..."
                    class="w-full pl-9 pr-8 py-2 rounded-xl bg-white border border-[#E5E7EB] text-xs text-[#16151A] placeholder-[#565A66] focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722] shadow-2xs font-inter"
                >
                @if(!empty($search))
                    <a href="{{ route('mobile.trainer.clients.index', array_merge(request()->except('search'))) }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 text-xs">
                        &times;
                    </a>
                @endif
            </form>

            <!-- Filter Status Pill Tab (Horizontal Scrollable) -->
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5 -mx-4 px-4 sm:mx-0 sm:px-0">
                <a href="{{ route('mobile.trainer.clients.index', array_merge(request()->except('status'), ['status' => 'all'])) }}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($statusFilter ?? 'all') === 'all' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                    Semua
                </a>

                <a href="{{ route('mobile.trainer.clients.index', array_merge(request()->except('status'), ['status' => 'active_quota'])) }}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($statusFilter ?? '') === 'active_quota' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                    Kuota Aktif
                </a>

                <a href="{{ route('mobile.trainer.clients.index', array_merge(request()->except('status'), ['status' => 'low_quota'])) }}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($statusFilter ?? '') === 'low_quota' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                    Menipis (&le; 2)
                </a>

                <a href="{{ route('mobile.trainer.clients.index', array_merge(request()->except('status'), ['status' => 'empty_quota'])) }}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($statusFilter ?? '') === 'empty_quota' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                    Habis / Expired
                </a>
            </div>
        </div>

        <!-- 4. Daftar Klien Card -->
        <div class="space-y-3">
            @forelse ($clients as $client)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($client->phone ?? ''));
                    if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    $activeQuota = $client->ptQuotas->first();
                    $remainingSessions = (int) ($activeQuota->remaining_sessions ?? 0);
                    $totalSessions = (int) ($activeQuota->total_sessions ?? 12);
                    $percentage = $totalSessions > 0 ? min(100, max(0, round(($remainingSessions / $totalSessions) * 100))) : 0;
                    $memberCode = $client->memberProfile->member_code ?? ('MEM-' . str_pad((string)$client->id, 5, '0', STR_PAD_LEFT));
                    $lastSession = $client->memberSessions->first();
                @endphp

                <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] space-y-3 shadow-2xs hover:border-gray-300 transition">
                    <!-- Row 1: Info Member -->
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-orange-50 border border-orange-200 text-[#FF5722] flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr($client->name ?? 'M', 0, 2)) }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins">
                                    {{ $client->name }}
                                </h4>
                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-semibold bg-gray-100 text-[#565A66] border border-[#E5E7EB]">
                                    {{ $memberCode }}
                                </span>
                            </div>
                            <p class="text-[11px] text-[#565A66] truncate font-sans">
                                {{ $client->email }}
                            </p>
                        </div>
                    </div>

                    <!-- Row 2: Paket & Kuota Latihan dengan Progress Bar -->
                    <div class="p-3 rounded-xl bg-gray-50/70 border border-[#E5E7EB] space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-[#16151A] text-[11px] truncate max-w-[65%]">
                                {{ $activeQuota->ptPackage->name ?? 'Paket Sesi PT' }}
                            </span>
                            @if ($remainingSessions > 2)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @elseif ($remainingSessions > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                    Habis
                                </span>
                            @endif
                        </div>

                        <!-- Progress Sesi -->
                        <div>
                            <div class="flex justify-between text-[10px] font-semibold text-[#565A66] mb-1">
                                <span>Sisa Kuota: <strong class="text-[#FF5722] font-poppins">{{ $remainingSessions }}</strong> / {{ $totalSessions }} Sesi</span>
                                <span>{{ $percentage }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-[#FF5722] rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>

                        <!-- Sesi Terakhir -->
                        <div class="flex items-center justify-between text-[10px] text-[#565A66] pt-1 border-t border-gray-200/50">
                            <span>Sesi Terakhir:</span>
                            <span class="font-medium text-[#16151A]">
                                @if($lastSession)
                                    {{ \Carbon\Carbon::parse($lastSession->session_date)->translatedFormat('d M Y') }}
                                @else
                                    Belum ada sesi
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Row 3: Tombol Aksi (Lihat Riwayat & Hubungi) -->
                    <div class="pt-2 border-t border-[#E5E7EB] flex items-center justify-between gap-2">
                        <a href="{{ route('trainer.clients.show', $client) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-[#E5E7EB] hover:border-[#FF5722] hover:text-[#FF5722] text-[#16151A] text-xs font-semibold shadow-2xs transition">
                            <svg class="w-3.5 h-3.5 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Lihat Riwayat</span>
                        </a>

                        @if ($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($client->name) }},%20saya%20Coach%20{{ urlencode($trainer->name) }}%20dari%20FitCore%20Gym"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 text-xs font-bold transition">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.974.554 1.769.82 2.796.82 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.768-5.766zm9.969 5.766c0 5.508-4.462 9.969-9.969 9.969-1.758 0-3.417-.463-4.871-1.272l-5.16 1.353 1.378-5.034c-.9-1.517-1.416-3.292-1.416-5.016 0-5.508 4.462-9.969 9.969-9.969 5.507 0 9.969 4.461 9.969 9.969z"/>
                                </svg>
                                <span>Hubungi</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-dashed border-[#E5E7EB] text-center space-y-2 shadow-2xs">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 text-[#565A66] flex items-center justify-center mx-auto mb-1 border border-[#E5E7EB]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#16151A]">Belum Ada Klien</p>
                    <p class="text-[11px] text-[#565A66] max-w-xs mx-auto">
                        @if(!empty($search) || ($statusFilter ?? 'all') !== 'all')
                            Tidak ada klien yang cocok dengan filter atau kata kunci pencarian Anda.
                        @else
                            Anda belum memiliki member yang pernah memesan sesi latihan bimbingan.
                        @endif
                    </p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($clients->hasPages())
                <div class="pt-2">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.mobile-trainer>
