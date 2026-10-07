<nav x-data="{ open: false }" class="bg-[#141414] border-b border-[#262626] sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Brand Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('pt-sessions.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#ED1B45] flex items-center justify-center shadow-lg shadow-[#ED1B45]/30 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 16.5 18 4.5"/>
                            <path d="m14 4 4 4"/>
                            <path d="m6 20 4-4"/>
                            <circle cx="18" cy="4.5" r="2.5"/>
                            <circle cx="5.5" cy="17" r="2.5"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-black tracking-wider text-white uppercase font-heading leading-tight">
                            FITCORE
                        </span>
                        <span class="text-[10px] tracking-widest text-[#ED1B45] font-extrabold uppercase -mt-0.5">
                            ATHLETIC
                        </span>
                    </div>
                </a>

                <!-- Nav Links Desktop -->
                <div class="hidden lg:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('dashboard') }}" class="text-[#A1A1AA] hover:text-white transition-colors">
                        Beranda
                    </a>
                    <a href="#" class="text-[#A1A1AA] hover:text-white transition-colors">
                        Paket Membership
                    </a>
                    <a href="#" class="text-[#A1A1AA] hover:text-white transition-colors">
                        Personal Trainer
                    </a>
                    <a href="{{ route('pt-sessions.create') }}"
                       class="{{ request()->routeIs('pt-sessions.create') ? 'bg-[#ED1B45] text-white shadow-lg shadow-[#ED1B45]/30' : 'text-[#A1A1AA] hover:text-white hover:bg-[#262626]' }} px-5 py-2 rounded-full font-semibold transition-all">
                        Booking Sesi
                    </a>
                    <a href="{{ route('pt-sessions.index') }}"
                       class="{{ request()->routeIs('pt-sessions.index') ? 'bg-[#ED1B45] text-white shadow-lg shadow-[#ED1B45]/30' : 'text-[#A1A1AA] hover:text-white hover:bg-[#262626]' }} px-5 py-2 rounded-full font-semibold transition-all">
                        Riwayat Sesi
                    </a>
                </div>
            </div>

            <!-- Right Profile Avatar & Dropdown -->
            <div class="hidden lg:flex items-center gap-4">
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-3 p-1 rounded-full hover:bg-[#262626] transition-all focus:outline-none">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-bold text-white leading-tight">{{ Auth::user()->name ?? 'Member' }}</div>
                            <div class="text-[10px] text-[#ED1B45] font-extrabold uppercase tracking-wider">ELITE MEMBER</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#ED1B45] text-white flex items-center justify-center font-bold text-sm shadow-md shadow-[#ED1B45]/25">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div x-show="userMenuOpen"
                         @click.away="userMenuOpen = false"
                         x-transition
                         class="absolute right-0 mt-2 w-56 rounded-2xl bg-[#1E1E1E] border border-[#2E2E2E] shadow-2xl p-2 z-50 text-sm text-zinc-200"
                         style="display: none;">
                        <div class="px-3 py-2 border-b border-[#2E2E2E] mb-1">
                            <p class="text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-[#A1A1AA] truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-[#2A2A2A] hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-[#ED1B45] hover:bg-[#ED1B45]/10 transition-colors text-left font-medium">
                                <svg class="w-4 h-4 text-[#ED1B45]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="flex lg:hidden items-center gap-2">
                <a href="{{ route('pt-sessions.create') }}" class="p-2 bg-[#ED1B45] text-white rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
                <button @click="open = !open" class="p-2 rounded-xl bg-[#262626] text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden border-t border-[#262626] bg-[#141414] px-4 pt-3 pb-5 space-y-2">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium text-[#A1A1AA] hover:bg-[#262626]">
            Beranda
        </a>
        <a href="{{ route('pt-sessions.create') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('pt-sessions.create') ? 'bg-[#ED1B45] text-white' : 'text-[#A1A1AA] hover:bg-[#262626]' }}">
            Booking Sesi
        </a>
        <a href="{{ route('pt-sessions.index') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('pt-sessions.index') ? 'bg-[#ED1B45] text-white' : 'text-[#A1A1AA] hover:bg-[#262626]' }}">
            Riwayat Sesi
        </a>
        <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium text-[#A1A1AA] hover:bg-[#262626]">
            Profil Saya
        </a>
        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-[#262626]">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-semibold text-[#ED1B45] hover:bg-[#ED1B45]/10">
                Keluar (Logout)
            </button>
        </form>
    </div>
</nav>
