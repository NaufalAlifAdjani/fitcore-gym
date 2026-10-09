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

    <!-- Flash Message Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email atau Nomor WhatsApp -->
        <x-auth-input 
            label="Email atau Nomor WhatsApp" 
            badge="Akun Terdaftar"
            icon="at"
            type="text" 
            name="email" 
            :value="old('email')" 
            placeholder="nama@email.com atau 081234567890" 
            required autofocus />

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