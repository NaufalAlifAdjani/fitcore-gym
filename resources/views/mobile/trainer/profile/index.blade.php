{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Profil Pelatih Mobile
VIEW: mobile.trainer.profile.index
LAYOUT: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #ED1B45, Text #16151A / #565A66)
--}}

<x-layouts.mobile-trainer title="Profil Pelatih - FitCore Coach" active="profile">
    <div class="space-y-4" x-data="{ showLogoutModal: false }">

        <!-- 1. Header Navigasi Atas -->
        <div class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-3">
                <a href="{{ route('mobile.trainer.dashboard') }}"
                   class="w-9 h-9 rounded-full bg-white border border-[#E5E7EB] flex items-center justify-center text-[#16151A] hover:bg-gray-50 transition shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">Profil Pelatih</h1>
                    <p class="text-[11px] font-semibold text-[#565A66]">Informasi akun &amp; performa kepelatihan</p>
                </div>
            </div>

            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Aktif
            </span>
        </div>

        <!-- 2. Kartu Identitas Trainer Utama -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] relative overflow-hidden space-y-4">
            <div class="flex items-center gap-4">
                <!-- Avatar Inisial Besar -->
                <div class="w-16 h-16 rounded-2xl bg-[#ED1B45]/10 border-2 border-[#ED1B45]/20 text-[#ED1B45] flex items-center justify-center font-poppins font-black text-2xl shadow-inner shrink-0">
                    {{ strtoupper(substr($trainer->name, 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-extrabold font-poppins text-[#16151A] truncate tracking-tight">
                        Coach {{ $trainer->name }}
                    </h2>

                    <div class="flex items-center gap-1.5 mt-0.5 text-xs text-[#565A66] truncate">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $trainer->email }}</span>
                    </div>

                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#ED1B45]/10 text-[#ED1B45] border border-[#ED1B45]/20">
                            {{ $profile->tier ?? 'Personal Trainer' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            ★ {{ $profile->rating ?? '4.9' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Detail Spesialisasi & Bio -->
            <div class="pt-3 border-t border-[#E5E7EB] space-y-2">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#565A66] block">Spesialisasi</span>
                    <span class="text-xs font-bold text-[#16151A] font-poppins block mt-0.5">
                        {{ $profile->specialization ?? 'Strength Training & Hypertrophy' }}
                    </span>
                </div>

                @if ($profile && $profile->bio)
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-[#565A66] block">Bio Singkat</span>
                        <p class="text-xs text-[#565A66] mt-0.5 leading-relaxed">
                            {{ $profile->bio }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Grid Pengalaman & Studio -->
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#E5E7EB] text-xs">
                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-[10px] font-bold text-[#565A66] uppercase block">Pengalaman</span>
                    <span class="font-bold text-[#16151A] mt-0.5 block">{{ $profile->experience ?? 1 }} Tahun</span>
                </div>
                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-[10px] font-bold text-[#565A66] uppercase block">Studio Latihan</span>
                    <span class="font-bold text-[#16151A] mt-0.5 block truncate">{{ $profile->studio ?? 'FitCore Main Studio' }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Rangkuman Performa & Estimasi Komisi -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <span class="text-[10px] uppercase font-bold tracking-wider text-[#565A66] block">Statistik Kepelatihan</span>

            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-lg font-black font-poppins text-[#16151A] block">{{ $completedSessions }}</span>
                    <span class="text-[9px] uppercase font-bold text-[#565A66] block mt-0.5">Sesi Selesai</span>
                </div>

                <div class="p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-lg font-black font-poppins text-[#16151A] block">{{ $activeMembersCount }}</span>
                    <span class="text-[9px] uppercase font-bold text-[#565A66] block mt-0.5">Total Klien</span>
                </div>

                <div class="p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-sm font-black font-poppins text-[#ED1B45] block truncate">Rp {{ number_format($estimatedCommission, 0, ',', '.') }}</span>
                    <span class="text-[9px] uppercase font-bold text-[#565A66] block mt-0.5">Estimasi Komisi</span>
                </div>
            </div>
        </div>

        <!-- 4. Pintasan Menu Operasional -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] divide-y divide-[#E5E7EB]">
            <!-- Link 1: Jadwal Sesi -->
            <a href="{{ route('mobile.trainer.sessions.index') }}" class="py-3 flex items-center justify-between group first:pt-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-[#ED1B45] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#ED1B45] transition">Jadwal Sesi Klien</span>
                        <span class="text-[11px] text-[#565A66]">Cek jadwal latihan aktif dan riwayat</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#ED1B45] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <!-- Link 2: Klien Saya -->
            <a href="{{ route('mobile.trainer.clients.index') }}" class="py-3 flex items-center justify-between group last:pb-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#ED1B45] transition">Member &amp; Klien Saya</span>
                        <span class="text-[11px] text-[#565A66]">Daftar member dalam bimbingan latihan Anda</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#ED1B45] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- 5. Card Keluar dari Akun (Logout) -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
            <button type="button"
                    @click="showLogoutModal = true"
                    class="w-full py-2.5 px-4 rounded-full border border-red-200 bg-red-50/50 hover:bg-red-50 active:scale-[0.99] text-[#ED1B45] font-poppins font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar dari Akun</span>
            </button>
        </div>

        <!-- 6. Modal Konfirmasi Logout -->
        <div x-show="showLogoutModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl max-w-xs w-full p-5 shadow-2xl border border-[#E5E7EB] text-center space-y-4"
                 @click.away="showLogoutModal = false">
                <div class="w-12 h-12 rounded-full bg-red-100 text-[#ED1B45] flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>

                <div>
                    <h3 class="font-poppins font-extrabold text-base text-[#16151A]">Konfirmasi Keluar</h3>
                    <p class="text-xs text-[#565A66] mt-1">Apakah Anda yakin ingin keluar dari akun Coach FitCore?</p>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button type="button"
                            @click="showLogoutModal = false"
                            class="flex-1 py-2.5 rounded-full border border-[#E5E7EB] text-xs font-bold text-[#565A66] hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-bold font-poppins transition shadow-sm">
                            Ya, Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layouts.mobile-trainer>

