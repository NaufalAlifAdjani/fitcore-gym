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

        <!-- 7. Link Balik ke Halaman Login -->
        <div class="text-center text-xs text-gray-600 pt-2">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="text-[#be0a32] font-bold hover:underline">
                Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>