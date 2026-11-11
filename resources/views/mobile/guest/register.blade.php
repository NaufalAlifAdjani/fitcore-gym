{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Pendaftaran Akun Member (Register Mobile)
| FILE         : resources/views/mobile/guest/register.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m/register atau /register
| DESKRIPSI    : Tampilan card register mobile bersih sesuai desain Gambar 2,
|                disinkronkan dengan alur registrasi member web desktop,
|                nomor WhatsApp (+62), validasi password, syarat & privasi,
|                serta tombol pendaftaran dengan Google & Apple.
|                Menggunakan token warna DESIGN.md: primary #FF5722, card 16px,
|                tombol pill full rounded, tipografi Poppins & Inter.
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="Daftar Member Baru - FitCore Athletic">
    <div class="w-full flex flex-col justify-center">

        <!-- 1. Header & Logo Brand (Figma Spec: 56x56px, Blaze Orange #FF5722, radius 16px, isolate) -->
        <div class="flex flex-col items-center justify-center text-center mb-5">
            <!-- Dumbbell Orange Squircle Badge -->
            <div class="w-[56px] h-[56px] rounded-[16px] bg-gradient-to-tr from-[#FF5722] to-[#FFB703] flex items-center justify-center isolate text-white shadow-lg shadow-orange-500/25 mb-3.5 shrink-0">
                <svg width="28" height="28" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                    <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="currentColor"/>
                </svg>
            </div>

            <!-- Brand Name & Portal Pill -->
            <div class="flex items-center gap-2 mb-2">
                <span class="font-poppins font-black text-lg tracking-tight text-[#16151A]">FIT <span class="text-[#FF5722]">CORE</span></span>
                <span class="px-2 py-0.5 rounded-md bg-gray-200/80 text-[10px] font-bold tracking-wider text-[#565A66] uppercase">PORTAL</span>
            </div>

            <!-- Page Title & Subtitle -->
            <h1 class="text-2xl sm:text-3xl font-black font-poppins text-[#16151A] tracking-tight leading-tight">
                Bergabung dengan FitCore
            </h1>
            <p class="mt-1.5 text-xs text-[#565A66] font-medium max-w-xs font-sans">
                Mulai perjalanan kebugaran dan transformasi Anda hari ini
            </p>
        </div>

        <!-- 2. Form Card Container (Token: Surface #FFFFFF, Radius 16px rounded-2xl, Border #E5E7EB, Shadow Subtle) -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB]">
            
            <!-- Error Alert Banners -->
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-3 text-xs text-rose-800">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Terdapat kesalahan pendaftaran:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                @csrf

                <!-- Input: Nama Lengkap -->
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-bold text-[#16151A]">
                        Nama Lengkap
                    </label>
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Alex Pratama"
                            required
                            autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#E5E7EB] rounded-[10px] text-xs sm:text-sm text-[#16151A] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-0.5 text-xs" />
                </div>

                <!-- Input: Alamat Email -->
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-bold text-[#16151A]">
                        Alamat Email
                    </label>
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="alex@example.com"
                            required
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#E5E7EB] rounded-[10px] text-xs sm:text-sm text-[#16151A] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-0.5 text-xs" />
                </div>

                <!-- Input: Nomor WhatsApp (+62 Prefix) -->
                <div class="space-y-1">
                    <label for="phone" class="block text-xs font-bold text-[#16151A]">
                        Nomor WhatsApp
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none gap-1.5 text-[#16151A] font-bold text-xs">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+62</span>
                            <span class="text-gray-300">|</span>
                        </div>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="812-3456-7890"
                            required
                            class="w-full pl-20 pr-4 py-2.5 bg-white border border-[#E5E7EB] rounded-[10px] text-xs sm:text-sm text-[#16151A] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-0.5 text-xs" />
                </div>

                <!-- Input: Kata Sandi -->
                <div class="space-y-1" x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold text-[#16151A]">
                            Kata Sandi
                        </label>
                        <span class="text-[10px] text-[#565A66]">Min. 8 karakter</span>
                    </div>
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#E5E7EB] rounded-[10px] text-xs sm:text-sm text-[#16151A] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
                        >
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-0.5 text-xs" />
                </div>

                <!-- Input: Konfirmasi Kata Sandi -->
                <div class="space-y-1" x-data="{ showConfirmPassword: false }">
                    <label for="password_confirmation" class="block text-xs font-bold text-[#16151A]">
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <input
                            :type="showConfirmPassword ? 'text' : 'password'"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="••••••••"
                            required
                            class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#E5E7EB] rounded-[10px] text-xs sm:text-sm text-[#16151A] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] focus:border-transparent transition"
                        />
                        <button
                            type="button"
                            @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
                        >
                            <svg x-show="!showConfirmPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirmPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Checkbox Syarat & Ketentuan (Gambar 2: "Saya menyetujui Syarat & Ketentuan serta Kebijakan Privasi FitCore.") -->
                <div class="pt-1">
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input
                            type="checkbox"
                            required
                            name="terms"
                            class="w-4 h-4 mt-0.5 text-[#FF5722] border-[#E5E7EB] rounded focus:ring-[#FF5722] shrink-0"
                        />
                        <span class="text-[11px] text-[#565A66] leading-tight select-none font-sans">
                            Saya menyetujui <a href="#" class="font-bold text-[#FF5722] hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="font-bold text-[#FF5722] hover:underline">Kebijakan Privasi</a> FitCore.
                        </span>
                    </label>
                    <x-input-error :messages="$errors->get('terms')" class="mt-0.5 text-xs" />
                </div>

                <!-- Tombol Aksi: Daftar Member Sekarang (Pill Full Radius rounded-full, Blaze Orange #FF5722) -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 rounded-full bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-semibold text-sm flex items-center justify-center gap-2 shadow-md shadow-orange-500/25 transition-all cursor-pointer"
                    >
                        <span>Daftar Member Sekarang</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                <!-- Divider: ATAU DAFTAR DENGAN -->
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-[#E5E7EB]"></div>
                    <span class="shrink-0 mx-3 text-[10px] font-bold text-[#565A66] tracking-wider uppercase">Atau Daftar Dengan</span>
                    <div class="flex-grow border-t border-[#E5E7EB]"></div>
                </div>

                <!-- Tombol Social Register (Google & Apple Berdampingan per Gambar 2) -->
                <div class="grid grid-cols-2 gap-3">
                    <a
                        href="{{ route('auth.google') }}"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 bg-white border border-[#E5E7EB] hover:bg-[#F6F6F7] rounded-xl text-xs font-bold text-[#16151A] transition shadow-xs"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.04 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                        </svg>
                        <span>Google</span>
                    </a>

                    <button
                        type="button"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 bg-white border border-[#E5E7EB] hover:bg-[#F6F6F7] rounded-xl text-xs font-bold text-[#16151A] transition shadow-xs opacity-90 cursor-pointer"
                    >
                        <svg class="w-4 h-4 fill-current text-black" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.37c.66-.82 1.11-1.96.99-3.1-.96.04-2.12.64-2.8 1.44-.6.69-1.13 1.84-.99 2.95 1.07.08 2.14-.47 2.8-1.29z"/>
                        </svg>
                        <span>Apple</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- 3. Bottom Link: Login (Gambar 2) -->
        <p class="mt-4 text-center text-xs text-[#565A66] font-medium font-sans">
            Sudah punya akun?
            <a href="{{ url('/m/login') }}" class="font-bold text-[#FF5722] hover:text-[#E64A19] ml-1 transition">
                Masuk di Sini
            </a>
        </p>

        <!-- 4. Security Encryption Badge (Gambar 2) -->
        <div class="mt-4 flex items-center justify-center gap-2 py-2 px-4 rounded-xl bg-gray-200/60 text-[#565A66] max-w-xs mx-auto">
            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span class="text-[10px] sm:text-[11px] font-semibold text-[#16151A] leading-tight font-sans">
                Data Anda terlindungi dengan enkripsi end-to-end 256-bit
            </span>
        </div>

    </div>
</x-layouts.mobile-guest>
