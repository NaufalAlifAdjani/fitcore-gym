<x-guest-layout>
    <!-- Header Icon & Title -->
    <div class="mb-6">
        <div class="w-12 h-12 bg-red-50 text-[#be0a32] rounded-2xl flex items-center justify-center mb-4 border border-red-100 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-3xl sm:text-4xl font-black text-gray-950 leading-tight">
            Verifikasi Email Anda
        </h2>
        <p class="text-gray-500 text-xs sm:text-sm mt-2 leading-relaxed">
            Terima kasih telah bergabung dengan <strong class="text-gray-900">{{ config('app.name', 'FitCore Gym') }}</strong>! Kami telah mengirimkan tautan verifikasi ke:
            <span class="block mt-1 font-bold text-[#ED1B45]">{{ auth()->user()?->email }}</span>
            Silakan buka kotak masuk email Anda (atau folder spam) dan klik tautan tersebut untuk mengaktifkan akun Anda.
        </p>
    </div>

    <!-- Alert Sukses Kirim Ulang -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 p-3.5 bg-green-50 border border-green-200 text-green-700 text-xs font-semibold rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Tautan verifikasi baru telah berhasil dikirimkan ke alamat email Anda.</span>
        </div>
    @endif

    <!-- Alert Peringatan / Warning -->
    @if (session('warning'))
        <div class="mb-5 p-3.5 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold rounded-xl flex items-start gap-2">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    <!-- Alert Gagal / Error -->
    @if (session('error'))
        <div class="mb-5 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl flex items-start gap-2">
            <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="space-y-4">
        <!-- Form Kirim Ulang Email Verifikasi -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full">
                Kirim Ulang Email Verifikasi
            </x-primary-button>
        </form>

        <!-- Form Logout / Keluar -->
        <form method="POST" action="{{ route('logout') }}" class="text-center pt-2">
            @csrf
            <button type="submit" class="text-xs text-gray-500 hover:text-gray-900 underline font-semibold transition">
                Keluar (Log Out)
            </button>
        </form>
    </div>
</x-guest-layout>
