<x-guest-layout>
    <!-- Title Heading -->
    <h2 class="text-3xl sm:text-4xl font-black text-gray-950 leading-tight mb-8">
        Masuk Akun<br>
        Admin<br>
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

        <!-- Menyampaikan role admin ke controller jika diperlukan -->
        <input type="hidden" name="role" value="admin">

        <!-- Input Username / Email (Ubah name menjadi 'email' agar dibaca oleh Laravel AuthenticatedSessionController) -->
        <x-auth-input 
            label="Username / Email Admin" 
            badge="Akun Admin"
            icon="user"
            type="text" 
            name="email" 
            :value="old('email')" 
            placeholder="Masukkan username atau email admin" 
            required autofocus />

        <!-- Input Password -->
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

        <!-- Footer Link -->
        <div class="text-center text-xs text-gray-600 pt-3">
            <span>Bukan Admin? <a href="{{ route('login') }}" class="text-[#be0a32] font-bold hover:underline">Masuk sebagai Member</a></span>
        </div>
    </form>
</x-guest-layout>