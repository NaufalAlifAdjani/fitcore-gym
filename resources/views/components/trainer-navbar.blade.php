@php
    $user = Auth::user();
    $rawName = $user?->name ?? 'Coach Rama Prasetya, CSCS';
    
    if (str_contains($rawName, ', CSCS')) {
        $displayName = str_replace(', CSCS', '', $rawName);
        $displayTitle = 'CSCS - Head Trainer';
    } elseif ($rawName === 'Coach Rama Prasetya') {
        $displayName = 'Coach Rama Prasetya';
        $displayTitle = 'CSCS - Head Trainer';
    } else {
        $displayName = $rawName;
        $displayTitle = $user?->trainerProfile?->specialization ?? ($user?->trainerProfile?->tier ?? 'CSCS - Head Trainer');
    }
@endphp

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-40 h-[72px] flex items-center px-4 sm:px-6 lg:px-8">
    <div class="w-full flex items-center justify-between">
        
        <!-- Left: Mobile Menu Toggle & Brand Lockup -->
        <div class="flex items-center gap-3 sm:gap-6">
            <!-- Mobile Menu Toggle Button -->
            <button 
                type="button" 
                @click="sidebarOpen = !sidebarOpen" 
                class="lg:hidden p-2 rounded-xl text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 focus:outline-none transition-colors cursor-pointer"
                aria-label="Toggle sidebar"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Brand Logo Lockup -->
            <a href="{{ route('trainer.sessions.index') }}" class="flex items-center gap-4 group select-none">
                <!-- Barbell Icon + MAXFIT. -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center text-[#ED1B45] group-hover:scale-105 transition-transform duration-200">
                        <svg class="h-6 w-auto" viewBox="0 0 32 16" fill="currentColor">
                            <!-- Left Plate -->
                            <rect x="0" y="0" width="4" height="16" rx="2" />
                            <rect x="5" y="2" width="2" height="12" rx="1" />
                            <!-- Barbell Center Rod -->
                            <rect x="7" y="6.5" width="18" height="3" rx="1" />
                            <!-- Right Plate -->
                            <rect x="25" y="2" width="2" height="12" rx="1" />
                            <rect x="28" y="0" width="4" height="16" rx="2" />
                        </svg>
                    </div>
                    <span class="text-lg sm:text-xl font-extrabold tracking-tight text-[#16151A] font-heading uppercase">
                        MAXFIT<span class="text-[#ED1B45]">.</span>
                    </span>
                </div>

                <!-- Text MAXFIT / TRAINER PORTAL -->
                <div class="hidden sm:flex flex-col pl-4 border-l border-[#E5E7EB] leading-none">
                    <span class="text-base sm:text-lg font-black tracking-tight text-[#16151A] font-heading uppercase">
                        MAXFIT
                    </span>
                    <span class="text-[10px] sm:text-[11px] tracking-wider text-[#ED1B45] font-extrabold uppercase mt-0.5">
                        TRAINER PORTAL
                    </span>
                </div>
            </a>
        </div>

        <!-- Right: Notifications & Trainer Profile -->
        <div class="flex items-center gap-3 sm:gap-4">
            
            <!-- Notification Bell Icon with Red Dot -->
            <div class="relative" x-data="{ notifOpen: false }">
                <button 
                    type="button" 
                    @click="notifOpen = !notifOpen"
                    class="relative p-2.5 rounded-full text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer"
                    aria-label="Notifikasi"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <!-- Red Notification Dot -->
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-[#ED1B45] rounded-full ring-2 ring-white"></span>
                </button>

                <!-- Notifications Dropdown -->
                <div 
                    x-show="notifOpen" 
                    @click.away="notifOpen = false" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-[#E5E7EB] py-3 px-4 z-50 text-sm"
                    style="display: none;"
                >
                    <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                        <span class="font-bold text-[#16151A] font-heading">Notifikasi Sesi</span>
                        <span class="text-[11px] bg-red-50 text-[#ED1B45] font-extrabold px-2 py-0.5 rounded-full">Baru</span>
                    </div>
                    <div class="py-3 space-y-2.5">
                        <div class="flex items-start gap-2.5 text-xs text-[#565A66]">
                            <span class="w-2 h-2 rounded-full bg-[#ED1B45] mt-1.5 shrink-0"></span>
                            <div>
                                <p class="font-semibold text-[#16151A]">Permintaan booking baru</p>
                                <p class="text-[11px] text-[#565A66]">Member telah mengajukan sesi latihan personal.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vertical Separator Divider -->
            <div class="h-8 w-px bg-[#E5E7EB]"></div>

            <!-- Trainer Profile & Avatar Dropdown -->
            <div class="relative" x-data="{ profileOpen: false }">
                <button 
                    type="button" 
                    @click="profileOpen = !profileOpen"
                    class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-50 transition-colors focus:outline-none cursor-pointer group"
                >
                    <!-- Trainer Name & Title -->
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-[#16151A] font-heading leading-tight group-hover:text-[#ED1B45] transition-colors">
                            {{ $displayName }}
                        </div>
                        <div class="text-xs text-[#565A66] font-medium leading-tight mt-0.5">
                            {{ $displayTitle }}
                        </div>
                    </div>

                    <!-- Avatar with subtle ring -->
                    <div class="relative shrink-0">
                        @if($user?->trainerProfile?->photo_path)
                            <img 
                                src="{{ asset('storage/' . $user->trainerProfile->photo_path) }}" 
                                alt="{{ $displayName }}" 
                                class="w-10 h-10 rounded-full object-cover ring-2 ring-[#ED1B45]/20 shadow-xs"
                            >
                        @else
                            <img 
                                src="https://images.unsplash.com/photo-1567013127542-490d757e51fc?w=120&auto=format&fit=crop&q=80" 
                                alt="{{ $displayName }}" 
                                class="w-10 h-10 rounded-full object-cover ring-2 ring-[#ED1B45]/20 shadow-xs"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="w-10 h-10 rounded-full bg-[#16151A] text-white font-bold text-xs items-center justify-center ring-2 ring-[#ED1B45]/20 shadow-xs hidden">
                                {{ strtoupper(substr($displayName, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                </button>

                <!-- Profile Dropdown Menu -->
                <div 
                    x-show="profileOpen" 
                    @click.away="profileOpen = false" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-[#E5E7EB] shadow-xl p-2 z-50 text-sm text-[#16151A]"
                    style="display: none;"
                >
                    <div class="px-3 py-2 border-b border-[#E5E7EB] mb-1">
                        <p class="text-xs font-bold text-[#16151A]">{{ $displayName }}</p>
                        <p class="text-[11px] text-[#565A66] truncate">{{ $user?->email ?? 'trainer@maxfit.test' }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-gray-50 text-[#16151A] transition-colors">
                        <svg class="w-4 h-4 text-[#565A66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profil Saya</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-[#ED1B45] hover:bg-red-50 transition-colors text-left font-semibold cursor-pointer">
                            <svg class="w-4 h-4 text-[#ED1B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</header>
