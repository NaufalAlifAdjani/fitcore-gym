<x-guest-layout>
    <!-- Header Title & Subtitle -->
    <div class="mb-6">
        <h2 class="text-3xl sm:text-4xl font-black text-gray-950 leading-tight">
            Lupa Kata Sandi
        </h2>
        <p class="text-gray-500 text-xs sm:text-sm mt-1.5 leading-relaxed">
            Tidak masalah! Masukkan alamat email akun Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi baru.
        </p>
    </div>

    <!-- Status Session Flash Message (misal: link berhasil dikirim) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address Field -->
        <x-auth-input 
            label="Email Akun" 
            badge="Akun Terdaftar"
            icon="at"
            type="email" 
            name="email" 
            :value="old('email')" 
            placeholder="nama@email.com" 
            required 
            autofocus 
        />

        <!-- Submit Button -->
        <x-primary-button>
            Kirim Tautan Reset Password
        </x-primary-button>

        <!-- Footer Link Balik ke Halaman Login -->
        <div class="text-center text-xs text-gray-600 pt-3">
            Ingat kata sandi akun Anda? 
            <a href="{{ route('login') }}" class="text-[#be0a32] font-bold hover:underline">
                Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>
