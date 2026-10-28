<x-guest-layout>
    <!-- Header Title & Subtitle -->
    <div class="mb-6">
        <h2 class="text-3xl sm:text-4xl font-black text-gray-950 leading-tight">
            Atur Ulang Kata Sandi
        </h2>
        <p class="text-gray-500 text-xs sm:text-sm mt-1.5 leading-relaxed">
            Silakan masukkan kata sandi baru yang aman untuk akun Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        @php
            $displayEmail = old('email', $request->email);
            if ($displayEmail && preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $displayEmail, $matches)) {
                $displayEmail = $matches[0];
            }
        @endphp

        <!-- Email Address -->
        <x-auth-input 
            label="Email Akun" 
            icon="at"
            type="email" 
            name="email" 
            :value="$displayEmail" 
            placeholder="nama@email.com"
            required 
            autofocus
        />

        <!-- Password Baru -->
        <x-auth-input 
            label="Kata Sandi Baru" 
            badge="Min. 8 karakter"
            icon="lock"
            type="password" 
            name="password" 
            placeholder="Masukkan kata sandi baru" 
            required 
            autofocus 
            autocomplete="new-password" 
        />

        <!-- Konfirmasi Password Baru -->
        <x-auth-input 
            label="Konfirmasi Kata Sandi Baru" 
            icon="lock"
            type="password" 
            name="password_confirmation" 
            placeholder="Ulangi kata sandi baru" 
            required 
            autocomplete="new-password" 
        />

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button>
                Perbarui Kata Sandi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
