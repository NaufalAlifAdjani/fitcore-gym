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
        $displayTitle = $user?->trainerProfile?->specialization ?? 'Personal Trainer';
    }
@endphp

<header class="bg-white border-b border-[#E5E7EB] sticky top-0 z-40 h-[72px] flex items-center px-4 sm:px-6 lg:px-8">
    <div class="w-full flex items-center justify-between">

        <!-- Left: Mobile Menu Toggle & Brand Lockup -->
        <div class="flex items-center gap-3 sm:gap-6">
            <!-- Mobile Menu Toggle Button -->
            <button type="button" @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-xl text-[#565A66] hover:text-[#16151A] hover:bg-gray-100 focus:outline-none transition-colors cursor-pointer"
                aria-label="Toggle sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Brand Logo Lockup -->
            <a href="{{ route('trainer.sessions.index') }}" class="flex items-center gap-3 group select-none">
                <img src="{{ asset('images/logo/logo.png') }}" alt="FitCore" class="h-8 w-auto object-contain">
                <div class="hidden sm:flex flex-col pl-3 border-l border-[#E5E7EB] leading-none">
                    <span class="text-xs font-black tracking-wider text-[#FF5722] uppercase">
                        PERSONAL TRAINER
                    </span>
                </div>
            </a>
        </div>

        <!-- Right: Notifications & Trainer Profile -->
        <div class="flex items-center gap-3 sm:gap-4">

            <!-- Notification Bell Icon with Dropdown -->
            <x-notification-dropdown theme="light" />

            <!-- Vertical Separator Divider -->
            <div class="h-8 w-px bg-[#E5E7EB]"></div>

            <!-- Trainer Profile & Avatar Dropdown -->
            <div class="relative" x-data="{ profileOpen: false }">
                <button type="button" @click="profileOpen = !profileOpen"
                    class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-50 transition-colors focus:outline-none cursor-pointer group">
                    <!-- Trainer Name & Title -->
                    <div class="text-right hidden sm:block">
                        <div
                            class="text-sm font-bold text-[#16151A] font-heading leading-tight group-hover:text-[#FF5722] transition-colors">
                            {{ $displayName }}
                        </div>
                        <div class="text-xs text-[#565A66] font-medium leading-tight mt-0.5">
                            {{ $displayTitle }}
                        </div>
                    </div>

                    <!-- Avatar with subtle ring -->
                    <div class="relative shrink-0">
                        @if($user?->trainerProfile?->photo_path)
                            <img src="{{ asset('storage/' . $user->trainerProfile->photo_path) }}" alt="{{ $displayName }}"
                                class="w-10 h-10 rounded-full object-cover ring-2 ring-[#FF5722]/20 shadow-xs">
                        @else
                            <img src="https://images.unsplash.com/photo-1567013127542-490d757e51fc?w=120&auto=format&fit=crop&q=80"
                                alt="{{ $displayName }}"
                                class="w-10 h-10 rounded-full object-cover ring-2 ring-[#FF5722]/20 shadow-xs"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div
                                class="w-10 h-10 rounded-full bg-[#16151A] text-white font-bold text-xs items-center justify-center ring-2 ring-[#FF5722]/20 shadow-xs hidden">
                                {{ strtoupper(substr($displayName, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                </button>

                <!-- Profile Dropdown Menu -->
                <div x-show="profileOpen" @click.away="profileOpen = false"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-[#E5E7EB] shadow-xl p-2 z-50 text-sm text-[#16151A]"
                    style="display: none;">
                    <div class="border-b border-[#E5E7EB] px-3 py-2 mb-1">
                        <p class="text-xs font-bold text-[#16151A]">{{ $displayName }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-orange-50/70 hover:text-[#16151A] transition-colors">
                        Pengaturan Profil
                    </a>

                    <a href="{{ route('profile.edit') }}#password"
                        class="block rounded-xl px-3 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-orange-50/70 hover:text-[#16151A] transition-colors">
                        Ganti Kata Sandi
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl px-3 py-2.5 text-left text-xs font-bold text-[#FF5722] hover:bg-orange-50 cursor-pointer transition-colors">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</header>