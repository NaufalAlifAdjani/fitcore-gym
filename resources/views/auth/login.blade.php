<x-guest-layout>
    <!-- Role Selector Toggle (Member / Personal Trainer) -->
    <div class="flex p-1 bg-gray-100 rounded-xl mb-8 border border-gray-200/60">
        <button type="button" @click="role = 'member'"
            :class="role === 'member' ? 'bg-white shadow text-[#be0a32] font-bold' : 'text-gray-500 font-medium hover:text-gray-800'"
            class="flex-1 py-2 text-xs rounded-lg transition duration-150 text-center">
            Member
        </button>
        <button type="button" @click="role = 'trainer'"
            :class="role === 'trainer' ? 'bg-white shadow text-[#be0a32] font-bold' : 'text-gray-500 font-medium hover:text-gray-800'"
            class="flex-1 py-2 text-xs rounded-lg transition duration-150 text-center">
            Personal Trainer
        </button>
    </div>

    <!-- Title Heading -->
    <h2 class="text-3xl sm:text-4xl font-black text-gray-950 leading-tight mb-8">
        Masuk Akun<br>
        <span x-text="role === 'trainer' ? 'Trainer' : 'Member'">Member</span><br>
        FITCORE GYM
    </h2>

    <!-- Flash Message Session Status (Card Hijau) -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <!-- Card Peringatan Verifikasi Email (Card Merah) -->
    @if ($errors->has('email') && in_array('Harap verifikasi email Anda terlebih dahulu sebelum login.', (array) $errors->get('email'), true))
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50/95 p-4 text-rose-900 shadow-xs">
            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#be0a32] text-white font-bold text-xs mt-0.5">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="text-xs font-semibold leading-relaxed">
                Harap verifikasi email Anda terlebih dahulu sebelum login.
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <!-- Role login yang dipilih (member atau trainer) -->
        <input type="hidden" name="role" :value="role">

        <!-- Email atau Nomor WhatsApp -->
        <x-auth-input
            label="Email atau Nomor WhatsApp"
            badge="Akun Terdaftar"
            icon="at"
            type="text"
            name="email"
            :value="old('email')"
            placeholder="nama@email.com atau 081234567890"
            required
            autofocus />

        <!-- Kata Sandi -->
        <x-auth-input
            label="Kata Sandi"
            badge="Min. 8 karakter"
            icon="lock"
            type="password"
            name="password"
            placeholder="Masukkan kata sandi akun Anda"
            required />

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs pt-1 font-semibold">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer text-gray-800">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#be0a32] focus:ring-[#be0a32]">
                <span>Ingat Saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[#be0a32] hover:underline font-semibold">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>

        <!-- Submit Button (Masuk Sekarang) -->
        <x-primary-button>
            Masuk Sekarang
        </x-primary-button>

        <!-- Google Login (Hanya untuk Member) -->
        <template x-if="role === 'member'">
            <div class="space-y-3 pt-1">
                <!-- Divider Atau -->
                <div class="relative flex items-center justify-center">
                    <div class="border-t border-gray-200 w-full"></div>
                    <span class="bg-white px-3 text-xs text-gray-400 font-medium uppercase tracking-wider">atau</span>
                    <div class="border-t border-gray-200 w-full"></div>
                </div>

                <!-- Tombol Google Login -->
                <a href="{{ route('auth.google') }}" 
                   class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-full text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:ring-offset-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </a>

                @error('google')
                    <p class="text-xs text-[#be0a32] text-center font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
        </template>

        <!-- Footer Link -->
        <div class="text-center text-xs text-gray-600 pt-3">
            <template x-if="role === 'member'">
                <span>Belum memiliki akun member? <a href="{{ route('register') }}" class="text-[#be0a32] font-bold hover:underline">Daftar di sini</a></span>
            </template>
            <template x-if="role === 'trainer'">
                <span>Belum terdaftar sebagai trainer? <a href="#" class="text-[#be0a32] font-bold hover:underline">Hubungi Admin</a></span>
            </template>
        </div>
    </form>
</x-guest-layout>
