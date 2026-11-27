{{--
ROLE: MEMBER
MODUL: Profil Pengguna Mobile
VIEW: mobile.member.profile.index
LAYOUT: layouts.mobile-app
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@php
    $isPasswordTab = request('tab') === 'password';
@endphp

<x-layouts.mobile-app :title="($isPasswordTab ? 'Ganti Kata Sandi' : 'Profil Saya') . ' - FitCore Athletic'" active-tab="profile">
    <div class="space-y-4">

        <!-- 1. Header Navigasi Atas -->
        <div class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-3">
                <a href="{{ $isPasswordTab ? route('mobile.member.profile.index') : route('mobile.member.dashboard') }}"
                   class="w-9 h-9 rounded-full bg-white border border-[#E5E7EB] flex items-center justify-center text-[#16151A] hover:bg-gray-50 transition shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">{{ $isPasswordTab ? 'Ganti Kata Sandi' : 'Profil Saya' }}</h1>
                    <p class="text-[11px] font-semibold text-[#565A66]">{{ $isPasswordTab ? 'Amankan akun Anda dengan kata sandi baru' : 'Kelola akun & preferensi latihanmu' }}</p>
                </div>
            </div>

            @if (!$isPasswordTab)
                <a href="{{ route('mobile.member.card') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-orange-50 text-[#FF5722] border border-orange-200 hover:bg-orange-100 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span>Kartu QR</span>
                </a>
            @endif
        </div>

        <!-- 2. Flash Status Notifications -->
        @if (session('warning'))
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 text-xs font-semibold flex items-start gap-2.5 shadow-2xs">
                <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center shrink-0 text-amber-600 mt-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <span class="leading-relaxed">{{ session('warning') }}</span>
            </div>
        @endif

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
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

        @if (!$isPasswordTab)
        <!-- 3. Kartu Identitas Pengguna Utama -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] relative overflow-hidden">
            <div class="flex items-center gap-4">
                <!-- Avatar Inisial Besar / Foto Profil -->
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-orange-200 shadow-sm shrink-0">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-orange-50 border-2 border-orange-200 text-[#FF5722] flex items-center justify-center font-poppins font-black text-2xl shadow-inner shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif

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
                            <span class="font-bold text-[#FF5722]">{{ \Carbon\Carbon::parse($maxEndDate ?? $activeMembership->end_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-amber-700 block">Belum ada paket aktif</span>
                            <span class="text-xs text-[#565A66]">Akses gym tidak aktif</span>
                        </div>
                        <a href="{{ route('mobile.packages.index') }}"
                           class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#FF5722] text-white hover:bg-[#E64A19] transition shadow-xs">
                            Beli Paket
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Update Profil & Informasi Kontak -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            <div class="border-b border-[#E5E7EB] pb-3">
                <h3 class="font-poppins font-extrabold text-sm text-[#16151A]">Edit Informasi Akun</h3>
                <p class="text-[11px] text-[#565A66]">Pastikan nama dan email Anda selalu valid.</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4" x-data="{ photoName: null, photoPreview: null }">
                @csrf
                @method('patch')

                <!-- Foto Profil (Wajib untuk Check-in) -->
                <div class="space-y-1.5 pb-2 border-b border-[#E5E7EB]">
                    <label class="block text-xs font-bold text-[#16151A]">
                        Foto Profil
                        <span class="text-[10px] font-semibold text-[#FF5722] bg-orange-50 px-2 py-0.5 rounded-full border border-orange-200 ml-1">Wajib Check-In</span>
                    </label>

                    <div class="flex items-center gap-3.5 pt-1">
                        <!-- Current / Preview -->
                        <div x-show="!photoPreview" class="shrink-0">
                            @if ($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-orange-200 shadow-xs">
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-orange-50 border-2 border-orange-200 text-[#FF5722] flex items-center justify-center font-poppins font-bold text-sm shadow-inner">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        <div x-show="photoPreview" style="display: none;" class="shrink-0">
                            <span class="block w-14 h-14 rounded-2xl bg-cover bg-no-repeat bg-center border-2 border-[#FF5722] shadow-sm"
                                  :style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <input id="mobile_avatar" name="avatar" type="file" accept="image/*" class="hidden"
                                   x-ref="mobilePhoto"
                                   x-on:change="
                                        photoName = $refs.mobilePhoto.files[0]?.name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.mobilePhoto.files[0]);
                                   " />

                            <button type="button" x-on:click="$refs.mobilePhoto.click()"
                                    class="px-3.5 py-1.5 rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] hover:bg-white text-xs font-semibold text-[#16151A] transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-[#565A66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $user->avatar_url ? 'Ganti Foto' : 'Unggah Foto' }}</span>
                            </button>
                            <p class="text-[10px] text-[#565A66] mt-1">Format JPG, PNG (Maks 2MB). Foto akan dicocokkan staf saat check-in.</p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">
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
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">
                    </div>
                    @error('email')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor WhatsApp / Kontak -->
                <div class="space-y-1">
                    <label for="phone" class="block text-xs font-bold text-[#16151A]">
                        Nomor WhatsApp / Telepon <span class="text-[#FF5722]">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input id="phone"
                               name="phone"
                               type="text"
                               required
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">
                    </div>
                    @error('phone')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat Domisili -->
                <div class="space-y-1">
                    <label for="address" class="block text-xs font-bold text-[#16151A]">
                        Alamat Domisili <span class="text-[#FF5722]">*</span>
                    </label>
                    <textarea id="address"
                              name="address"
                              rows="3"
                              required
                              placeholder="Masukkan alamat domisili lengkap Anda"
                              class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">{{ old('address', $memberProfile->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div class="space-y-1">
                    <label for="gender" class="block text-xs font-bold text-[#16151A]">Jenis Kelamin</label>
                    <select id="gender"
                            name="gender"
                            class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="male" {{ old('gender', $memberProfile->gender ?? '') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $memberProfile->gender ?? '') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 rounded-full bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-poppins font-bold text-xs uppercase tracking-wider transition shadow-md shadow-orange-500/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

        @else
        <!-- 2. Ubah Kata Sandi -->
        <div class="bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
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
                           class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">
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
                           class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">
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
                           class="w-full px-3.5 py-2.5 text-xs font-medium rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition">
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-[11px] font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 rounded-full bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-poppins font-bold text-xs uppercase tracking-wider transition shadow-md shadow-orange-500/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
</x-layouts.mobile-app>

