<x-guest-layout>
<<<<<<< HEAD
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Masuk ke FitCore Gym</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Silakan masuk dengan akun Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Field Email -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 p-2.5 border" />
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password</label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 p-2.5 border" />
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Checkbox Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat Saya</span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150">
                        Masuk
                    </button>
                </div>
            </form>

        </div>
    </div>
=======
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
>>>>>>> origin/feature/auth-login
</x-guest-layout>