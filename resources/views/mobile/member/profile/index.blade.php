{{--
ROLE: MEMBER
MODUL: Profil Pengguna Mobile
VIEW: mobile.member.profile.index
LAYOUT: layouts.mobile-app
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #ED1B45, Text #16151A / #565A66)
--}}

<x-layouts.mobile-app title="Profil Saya - FitCore Athletic" active-tab="beranda">
    <div class="space-y-4" x-data="{ activeTab: 'contact', showLogoutModal: false }">

        <!-- 1. Header Navigasi Atas -->
        <div class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-3">
                <a href="{{ route('mobile.member.dashboard') }}"
                   class="w-9 h-9 rounded-full bg-white border border-[#E5E7EB] flex items-center justify-center text-[#16151A] hover:bg-gray-50 transition shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">Profil Saya</h1>
                    <p class="text-[11px] font-semibold text-[#565A66]">Kelola akun & preferensi latihanmu</p>
                </div>
            </div>

            <a href="{{ route('mobile.member.card') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-[#ED1B45]/10 text-[#ED1B45] border border-[#ED1B45]/20 hover:bg-[#ED1B45]/20 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <span>Kartu QR</span>
            </a>
        </div>

        <!-- 2. Flash Status Notifications -->
        @if (session('status') === 'profile-updated')
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-2xs">
                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span>Informasi profil berhasil diperbarui!</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-2xs">
                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span>Kata sandi akun berhasil diperbarui!</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold space-y-1 shadow-2xs">
                <div class="flex items-center gap-2 font-bold text-red-800">
                    <svg class="w-4 h-4 text-[#ED1B45] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Terjadi kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside pl-1 text-[11px] text-red-600 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 3. Kartu Identitas Pengguna Utama -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] relative overflow-hidden">
            <div class="flex items-center gap-4">
                <!-- Avatar Inisial Besar -->
                <div class="w-16 h-16 rounded-2xl bg-[#ED1B45]/10 border-2 border-[#ED1B45]/20 text-[#ED1B45] flex items-center justify-center font-poppins font-black text-2xl shadow-inner shrink-0">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-extrabold font-poppins text-[#16151A] truncate tracking-tight">
                            {{ $user->name }}
                        </h2>
                    </div>

                    <div class="flex items-center gap-1.5 mt-0.5 text-xs text-[#565A66] truncate">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $user->email }}</span>
                    </div>

                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-[#565A66] border border-[#E5E7EB]">
                            ID: {{ $memberProfile->member_code ?? ('MEM-' . str_pad($user->id, 5, '0', STR_PAD_LEFT)) }}
                        </span>

                        @if ($activeMembership)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Member Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Paket Langganan Aktif -->
            <div class="mt-4 pt-3.5 border-t border-[#E5E7EB]">
                @if ($activeMembership)
                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-[#565A66] block">Paket Keanggotaan</span>
                            <span class="font-bold text-[#16151A] font-poppins">{{ $activeMembership->package->name ?? 'Standard Membership' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-[#565A66] block">Berlaku Hingga</span>
                            <span class="font-bold text-[#ED1B45]">{{ \Carbon\Carbon::parse($activeMembership->end_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-amber-700 block">Belum ada paket aktif</span>
                            <span class="text-xs text-[#565A66]">Akses gym tidak aktif</span>
                        </div>
                        <a href="{{ route('mobile.packages.index') }}"
                           class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#ED1B45] text-white hover:bg-[#D1123D] transition shadow-xs">
                            Beli Paket
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- 4. Tab Switcher Sub-Menu -->
        <div class="flex rounded-xl bg-gray-200/80 p-1 text-xs font-bold font-inter">
            <button type="button"
                    @click="activeTab = 'contact'"
                    :class="activeTab === 'contact' ? 'bg-white text-[#16151A] shadow-xs' : 'text-[#565A66] hover:text-[#16151A]'"
                    class="flex-1 py-2 text-center rounded-lg transition">
                Kontak & Profil
            </button>
            <button type="button"
                    @click="activeTab = 'password'"
                    :class="activeTab === 'password' ? 'bg-white text-[#16151A] shadow-xs' : 'text-[#565A66] hover:text-[#16151A]'"
                    class="flex-1 py-2 text-center rounded-lg transition">
                Ubah Sandi
            </button>
            <button type="button"
                    @click="activeTab = 'services'"
                    :class="activeTab === 'services' ? 'bg-white text-[#16151A] shadow-xs' : 'text-[#565A66] hover:text-[#16151A]'"
                    class="flex-1 py-2 text-center rounded-lg transition">
                Pintasan & Layanan
            </button>
        </div>

        <!-- 5. Tab Panels -->
        
        <!-- Panel 1: Update Profil & Informasi Kontak -->
        <div x-show="activeTab === 'contact'" x-cloak class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            <div class="border-b border-[#E5E7EB] pb-3">
                <h3 class="font-poppins font-extrabold text-sm text-[#16151A]">Edit Informasi Akun</h3>
                <p class="text-[11px] text-[#565A66]">Pastikan nama dan email Anda selalu valid.</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <!-- Nama Lengkap -->
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-bold text-[#16151A]">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name"
                               name="name"
                               type="text"
                               required
                               value="{{ old('name', $user->name) }}"
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ED1B45]/20 focus:border-[#ED1B45] transition">
                    </div>
                    @error('name')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-bold text-[#16151A]">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email"
                               name="email"
                               type="email"
                               required
                               value="{{ old('email', $user->email) }}"
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ED1B45]/20 focus:border-[#ED1B45] transition">
                    </div>
                    @error('email')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor WhatsApp / Kontak (Informasi) -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#16151A]">Nomor WhatsApp Terdaftar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input type="text"
                               disabled
                               value="{{ $user->phone ?? 'Belum ada nomor WhatsApp' }}"
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                    <p class="text-[10px] text-[#565A66]">Hubungi front-desk gym untuk perubahan nomor telepon terdaftar.</p>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] active:scale-[0.99] text-white font-poppins font-bold text-xs uppercase tracking-wider transition shadow-md shadow-[#ED1B45]/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel 2: Ubah Kata Sandi -->
        <div x-show="activeTab === 'password'" x-cloak class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            <div class="border-b border-[#E5E7EB] pb-3">
                <h3 class="font-poppins font-extrabold text-sm text-[#16151A]">Perbarui Kata Sandi</h3>
                <p class="text-[11px] text-[#565A66]">Gunakan kata sandi yang kuat dengan minimal 8 karakter.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <!-- Password Lama -->
                <div class="space-y-1">
                    <label for="current_password" class="block text-xs font-bold text-[#16151A]">Kata Sandi Saat Ini</label>
                    <input id="current_password"
                           name="current_password"
                           type="password"
                           required
                           autocomplete="current-password"
                           class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ED1B45]/20 focus:border-[#ED1B45] transition">
                    @error('current_password', 'updatePassword')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div class="space-y-1">
                    <label for="password" class="block text-xs font-bold text-[#16151A]">Kata Sandi Baru</label>
                    <input id="password"
                           name="password"
                           type="password"
                           required
                           autocomplete="new-password"
                           class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ED1B45]/20 focus:border-[#ED1B45] transition">
                    @error('password', 'updatePassword')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-xs font-bold text-[#16151A]">Ulangi Kata Sandi Baru</label>
                    <input id="password_confirmation"
                           name="password_confirmation"
                           type="password"
                           required
                           autocomplete="new-password"
                           class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ED1B45]/20 focus:border-[#ED1B45] transition">
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] active:scale-[0.99] text-white font-poppins font-bold text-xs uppercase tracking-wider transition shadow-md shadow-[#ED1B45]/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel 3: Pintasan & Layanan Keanggotaan -->
        <div x-show="activeTab === 'services'" x-cloak class="space-y-3">
            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] divide-y divide-[#E5E7EB]">
                <!-- Link 1: Riwayat Transaksi -->
                <a href="{{ route('mobile.member.riwayat-transaksi.index') }}" class="py-3 flex items-center justify-between group first:pt-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#ED1B45] transition">Riwayat Transaksi</span>
                            <span class="text-[11px] text-[#565A66]">Cek status invoice dan bukti transfer</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#ED1B45] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Link 2: Sesi Personal Trainer -->
                <a href="{{ route('mobile.member.pt-sessions.index') }}" class="py-3 flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#ED1B45] transition">Jadwal Sesi PT</span>
                            <span class="text-[11px] text-[#565A66]">Daftar sesi latihan & riwayat booking</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#ED1B45] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Link 3: Katalog Paket Gym -->
                <a href="{{ route('mobile.packages.index') }}" class="py-3 flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#ED1B45] transition">Katalog Paket Gym</span>
                            <span class="text-[11px] text-[#565A66]">Langganan paket keanggotaan baru</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#ED1B45] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <!-- Link 4: Bantuan WhatsApp CS -->
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20FitCore%20Gym,%20saya%20member%20memerlukan%20bantuan"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="py-3 flex items-center justify-between group last:pb-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-[#16151A] group-hover:text-[#ED1B45] transition">Pusat Bantuan & CS</span>
                            <span class="text-[11px] text-[#565A66]">Hubungi WhatsApp customer support</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 group-hover:text-[#ED1B45] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- 6. Card Keluar dari Akun (Logout) -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
            <button type="button"
                    @click="showLogoutModal = true"
                    class="w-full py-2.5 px-4 rounded-full border border-red-200 bg-red-50/50 hover:bg-red-50 active:scale-[0.99] text-[#ED1B45] font-poppins font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar dari Akun</span>
            </button>
        </div>

        <!-- 7. Modal Konfirmasi Logout -->
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
                    <p class="text-xs text-[#565A66] mt-1">Apakah Anda yakin ingin keluar dari aplikasi FitCore Gym?</p>
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
</x-layouts.mobile-app>

