<header class="w-full bg-[#222226] border-b border-gray-800/80 sticky top-0 z-50">
    <div class="w-full px-8 sm:px-12 lg:px-16 h-20 flex items-center justify-between">
        <!-- Brand Logo (Aligns with left content) -->
        <a href="/" class="flex items-center gap-3">
            <x-application-logo />
            <div class="leading-none">
                <span class="block font-black tracking-wider text-white text-lg">FITCORE</span>
                <span class="block font-bold tracking-widest text-[#e88b9c] text-[10px] mt-0.5">GYM</span>
            </div>
        </a>

        <!-- Center Navigation Links -->
        <nav class="hidden md:flex items-center gap-8 lg:gap-10 text-sm font-semibold text-gray-200">
            <a href="#" class="hover:text-white transition">Paket Membership</a>
            <a href="#" class="hover:text-white transition">Personal Trainer</a>
            <a href="#" class="hover:text-white transition">Tentang Kami</a>
        </nav>

        <!-- Right CTA Navigation -->
        <div class="flex items-center gap-6 text-sm font-semibold">
            <a href="{{ route('login') }}" class="text-white hover:text-gray-300 transition">Masuk</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="bg-[#be0a32] hover:bg-[#a1082a] text-white px-7 py-2.5 rounded-full font-bold transition shadow-md hover:shadow-lg">
                    Daftar
                </a>
            @endif
        </div>
    </div>
</header>
