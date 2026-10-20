<x-guest-layout>
    <!-- Title Heading -->
    <h2 class="text-3xl sm:text-4xl font-black text-gray-950 leading-tight mb-8">
        Masuk Akun<br>
        Admin<br>
        FITCORE GYM
    </h2>

    <!-- Flash Message Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

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