<div class="relative overflow-hidden p-8 sm:p-12 lg:p-16 flex flex-col justify-between min-h-full"
     style="background: 
        radial-gradient(circle at 10% 25%, rgba(255, 87, 34, 0.16) 0%, rgba(255, 183, 3, 0.1) 40%, transparent 70%),
        linear-gradient(135deg, #FFF9F5 0%, #FFF2EB 45%, #FFEADB 100%);">

    <!-- Efek Cahaya Blaze Orange & Amber Halus (Light Athletic Ambient Glow) -->
    <div class="absolute -top-16 -left-16 w-[450px] h-[450px] rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(255, 87, 34, 0.18) 0%, rgba(255, 183, 3, 0.12) 50%, transparent 70%); filter: blur(80px); -webkit-filter: blur(80px);"></div>
         
    <div class="absolute top-[15%] -left-10 w-[550px] h-[320px] rounded-full pointer-events-none -rotate-[22deg]"
         style="background: linear-gradient(135deg, rgba(255, 183, 3, 0.15) 0%, rgba(255, 87, 34, 0.1) 50%, transparent 75%); filter: blur(90px); -webkit-filter: blur(90px);"></div>

    <!-- Konten Teks Headline Dinamis -->
    <div class="relative z-10 max-w-xl">
        @if(request()->routeIs('admin.login'))
            <!-- Headline Khusus Admin -->
            <h1 class="text-4xl sm:text-5xl font-black text-zinc-900 leading-tight tracking-tight">
                Central Control<br>
                &amp; Management<br>
                System
            </h1>
            <p class="mt-6 text-zinc-600 text-sm sm:text-base leading-relaxed max-w-md font-normal">
                Panel resmi administrator FITCORE GYM untuk pengelolaan keanggotaan, inventaris fasilitas, serta laporan operasional gym.
            </p>
        @else
            <!-- Headline Default Member/Trainer -->
            <h1 class="text-4xl sm:text-5xl font-black text-zinc-900 leading-tight tracking-tight">
                All You Can Fit<br>
                with One<br>
                Membership
            </h1>
            <p class="mt-6 text-zinc-600 text-sm sm:text-base leading-relaxed max-w-md font-normal">
                Akses gym tanpa batas dengan fasilitas modern, instruktur tersertifikasi, dan puluhan kelas eksklusif.
            </p>
        @endif
    </div>

    <!-- Stats Cards Row Dinamis -->
    <div class="relative z-10 grid grid-cols-3 gap-3 sm:gap-4 max-w-lg mt-12 pt-8">
        @if(request()->routeIs('admin.login'))
            <!-- Card Khusus Admin -->
            <x-stat-card number="2.4" label="VERSION" sublabel="SYSTEM" />
            <x-stat-card number="24/7" badge="ONLINE" label="SERVER" highlight="STATUS" :is-featured="true" />
            <x-stat-card number="SECURE" label="ACCESS" sublabel="ADMIN" />
        @else
            <!-- Card Default Member/Trainer -->
            <x-stat-card number="40" label="VARIASI" sublabel="KELAS" />
            <x-stat-card number="120" badge="TERLUAS" label="LOKASI" highlight="MAXFIT" :is-featured="true" />
            <x-stat-card number="30" label="KOTA" sublabel="INDONESIA" />
        @endif
    </div>
</div>