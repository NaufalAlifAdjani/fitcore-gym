<aside class="w-64 bg-white border-r border-[#E5E7EB] flex flex-col shrink-0 min-h-[calc(100vh-72px)]">
    <div class="p-4 sm:p-5 flex-1">
        <!-- Main Menu Header -->
        <div class="px-3 mb-2.5">
            <span class="text-[11px] font-bold text-[#565A66] tracking-wider uppercase font-['Inter']">
                MAIN MENU
            </span>
        </div>

        <!-- Navigation Items List -->
        <nav class="space-y-1 font-['Inter']">
            <!-- 1. Dashboard -->
            <a href="{{ route('trainer.dashboard') }}" 
               class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('trainer.dashboard') || (request()->routeIs('dashboard') && !request()->routeIs('trainer.*')) ? 'bg-[#ED1B45]/10 text-[#ED1B45]' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('trainer.dashboard') || (request()->routeIs('dashboard') && !request()->routeIs('trainer.*')) ? 'text-[#ED1B45]' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- 2. Jadwal Sesi PT -->
            <a href="{{ route('trainer.sessions.index') }}" 
               class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('trainer.sessions.*') ? 'bg-[#ED1B45]/10 text-[#ED1B45]' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('trainer.sessions.*') ? 'text-[#ED1B45]' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Jadwal Sesi PT</span>
            </a>

            <!-- 3. Klien Saya -->
            <a href="{{ route('trainer.clients.index') }}" 
               class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('trainer.clients.*') ? 'bg-[#ED1B45]/10 text-[#ED1B45]' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('trainer.clients.*') ? 'text-[#ED1B45]' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Klien Saya</span>
            </a>

            <!-- 4. Ketersediaan Waktu -->
            <a href="{{ route('trainer.availability.index') }}" 
               class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('trainer.availability.*') ? 'bg-[#ED1B45]/10 text-[#ED1B45]' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('trainer.availability.*') ? 'text-[#ED1B45]' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Ketersediaan Waktu</span>
            </a>

            <!-- 5. Pendapatan / Komisi -->
            <a href="{{ route('trainer.commissions.index') }}" 
               class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('trainer.commissions.*') ? 'bg-[#ED1B45]/10 text-[#ED1B45]' : 'text-[#16151A] hover:bg-gray-50 hover:text-[#ED1B45]' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('trainer.commissions.*') ? 'text-[#ED1B45]' : 'text-[#565A66]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Pendapatan / Komisi</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Trainer Quick Info / Status -->
    <div class="p-4 border-t border-[#E5E7EB] bg-gray-50/50">
        <div class="flex items-center justify-between text-xs text-[#565A66]">
            <span class="flex items-center gap-1.5 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Status: Online
            </span>
            <span class="font-bold text-[#16151A]">Fitcore SCBD</span>
        </div>
    </div>
</aside>
