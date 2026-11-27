{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Profil Pelatih Mobile
VIEW: mobile.trainer.profile.index
LAYOUT: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-trainer title="Profil Pelatih - FitCore Coach" active="profile">
    <div class="space-y-4">

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
                <div class="w-16 h-16 rounded-2xl bg-orange-50 border-2 border-orange-200 text-[#FF5722] flex items-center justify-center font-poppins font-black text-2xl shadow-inner shrink-0">
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

                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            Shift {{ $trainer->shift_label }}
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

            <!-- Grid Pengalaman -->
            <div class="pt-2 border-t border-[#E5E7EB] text-xs">
                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                    <span class="text-[10px] font-bold text-[#565A66] uppercase block">Pengalaman Melatih</span>
                    <span class="font-bold text-[#16151A] mt-0.5 block">{{ $profile->experience ?? 1 }} Tahun</span>
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
                    <span class="text-sm font-black font-poppins text-[#FF5722] block truncate">Rp {{ number_format($estimatedCommission, 0, ',', '.') }}</span>
                    <span class="text-[9px] uppercase font-bold text-[#565A66] block mt-0.5">Estimasi Komisi</span>
                </div>
            </div>
        </div>

        <!-- 4. Opsi Pengaturan Akun -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] divide-y divide-[#E5E7EB]">
            <!-- Link 1: Pengaturan Profil -->
            <a href="{{ route('profile.edit') }}" class="py-3 flex items-center justify-between group first:pt-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#FF5722] transition">Pengaturan Profil</span>
                        <span class="text-[11px] text-[#565A66]">Perbarui informasi kontak dan data akun</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#FF5722] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <!-- Link 2: Ganti Kata Sandi -->
            <a href="{{ route('profile.edit') }}#password" class="py-3 flex items-center justify-between group last:pb-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#FF5722] transition">Ganti Kata Sandi</span>
                        <span class="text-[11px] text-[#565A66]">Amankan akun Anda dengan kata sandi baru</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#FF5722] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- 4. Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" class="pt-2 pb-6">
            @csrf
            <button type="submit" class="w-full py-3 px-4 rounded-xl border border-rose-200 bg-rose-50/50 hover:bg-rose-50 text-rose-600 font-bold text-xs font-inter flex items-center justify-center gap-2 transition cursor-pointer">
                <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar dari Akun
            </button>
        </form>

    </div>
</x-layouts.mobile-trainer>

