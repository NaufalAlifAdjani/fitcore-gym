<x-guest-layout>
    <!-- Judul & Subjudul Form -->
    <div class="mb-6">
        <h2 class="text-3xl sm:text-4xl font-black text-gray-950 leading-tight">
            Daftar Membership FITCORE GYM
        </h2>
        <p class="text-gray-500 text-xs sm:text-sm mt-1.5">
            Mulai gaya hidup sehatmu sekarang dengan penawaran membership eksklusif.
        </p>
    </div>

    <!-- Form Registrasi -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ password: '' }">
        @csrf

        <!-- 1. Nama Lengkap -->
        <div class="space-y-1">
            <label for="name" class="block text-xs font-bold text-gray-900">
                <span class="text-[#be0a32]">*</span> Nama
            </label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                placeholder="Isi nama sesuai KTP" 
                required 
                autofocus 
                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:border-transparent transition" 
            />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- 2. Nomor Handphone (+62 Prefix) -->
        <div class="space-y-1">
            <label for="phone" class="block text-xs font-bold text-gray-900">
                <span class="text-[#be0a32]">*</span> Nomor Handphone
            </label>
            <div class="flex rounded-xl overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#be0a32] focus-within:border-transparent transition">
                <span class="inline-flex items-center px-4 bg-gray-50 text-gray-600 text-sm font-semibold border-r border-gray-300 select-none">
                    +62
                </span>
                <input 
                    id="phone" 
                    type="tel" 
                    name="phone" 
                    value="{{ old('phone') }}" 
                    placeholder="82134567890" 
                    required 
                    class="w-full px-4 py-3 bg-white text-sm text-gray-900 placeholder-gray-400 focus:outline-none border-none" 
                />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <!-- 3. Email -->
        <div class="space-y-1">
            <label for="email" class="block text-xs font-bold text-gray-900">
                <span class="text-[#be0a32]">*</span> Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="user@mail.com" 
                required 
                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:border-transparent transition" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- 4. Password -->
        <div class="space-y-1">
            <label for="password" class="block text-xs font-bold text-gray-900">
                <span class="text-[#be0a32]">*</span> Password
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                x-model="password"
                placeholder="password" 
                required 
                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:border-transparent transition" 
            />
            <!-- Sinkronisasi tersembunyi agar validasi bawaan Laravel otomatis terpenuhi -->
            <input type="hidden" name="password_confirmation" :value="password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- 5. Checkbox Kebijakan Privasi -->
        <div class="pt-2">
            <label class="flex items-start gap-2.5 cursor-pointer text-xs text-gray-700">
                <input 
                    type="checkbox" 
                    name="terms" 
                    required 
                    class="w-4 h-4 mt-0.5 rounded border-gray-300 text-[#be0a32] focus:ring-[#be0a32]" 
                />
                <span>
                    <span class="text-[#be0a32] font-bold">*</span> Saya sudah baca dan setuju dengan 
                    <a href="#" class="text-[#be0a32] font-semibold hover:underline">kebijakan data privasi</a>.
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-1" />
        </div>

        <!-- 6. Tombol Submit (Daftar sekarang) -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full bg-[#be0a32] hover:bg-[#a1082a] text-white font-bold py-3.5 px-6 rounded-full flex items-center justify-center text-sm shadow-md hover:shadow-lg transition duration-150 focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:ring-offset-2">
                Daftar sekarang
            </button>
        </div>

        <!-- Divider Atau -->
        <div class="relative flex items-center justify-center pt-1">
            <div class="border-t border-gray-200 w-full"></div>
            <span class="bg-white px-3 text-xs text-gray-400 font-medium uppercase tracking-wider">atau</span>
            <div class="border-t border-gray-200 w-full"></div>
        </div>

        <!-- Tombol Daftar dengan Google -->
        <div>
            <a href="{{ route('auth.google') }}" 
               class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-full text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:ring-offset-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Daftar dengan Google</span>
            </a>

            @error('google')
                <p class="text-xs text-[#be0a32] text-center font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 7. Link Balik ke Halaman Login -->
        <div class="text-center text-xs text-gray-600 pt-2">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="text-[#be0a32] font-bold hover:underline">
                Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>