<div class="relative overflow-hidden p-8 sm:p-12 lg:p-16 flex flex-col justify-between min-h-full"
     style="background: 
        radial-gradient(circle at 10% 25%, rgba(180, 10, 50, 0.28) 0%, rgba(100, 10, 40, 0.15) 35%, transparent 70%),
        linear-gradient(135deg, #1c0822 0%, #120718 45%, #0b040e 100%);">

    <!-- Efek Cahaya Merah Halus & Elegan (Subtle Red Ambient Glow) -->
    <div class="absolute -top-16 -left-16 w-[450px] h-[450px] rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(190, 15, 50, 0.35) 0%, rgba(130, 10, 40, 0.18) 50%, transparent 70%); filter: blur(90px); -webkit-filter: blur(90px);"></div>
         
    <div class="absolute top-[15%] -left-10 w-[550px] h-[320px] rounded-full pointer-events-none -rotate-[22deg]"
         style="background: linear-gradient(135deg, rgba(200, 20, 60, 0.25) 0%, rgba(140, 10, 45, 0.12) 50%, transparent 75%); filter: blur(100px); -webkit-filter: blur(100px);"></div>

    <!-- Konten Teks Headline -->
    <div class="relative z-10 max-w-xl">
        <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight tracking-tight">
            All You Can Fit<br>
            with One<br>
            Membership
        </h1>
        <p class="mt-6 text-gray-400 text-sm sm:text-base leading-relaxed max-w-md font-normal">
            Akses gym tanpa batas dengan fasilitas modern, instruktur tersertifikasi, dan puluhan kelas eksklusif.
        </p>
    </div>

    <!-- Stats Cards Row (Efek Timbul saat Kursor Diarahkan) -->
    <div class="relative z-10 grid grid-cols-3 gap-3 sm:gap-4 max-w-lg mt-12 pt-8">
        <!-- Card 1: 40+ VARIASI KELAS -->
        <x-stat-card number="40" label="VARIASI" sublabel="KELAS" />

        <!-- Card 2: 120+ LOKASI MAXFIT (Highlight Terluas) -->
        <x-stat-card number="120" badge="TERLUAS" label="LOKASI" highlight="MAXFIT" :is-featured="true" />

        <!-- Card 3: 30+ KOTA INDONESIA -->
        <x-stat-card number="30" label="KOTA" sublabel="INDONESIA" />
    </div>
</div>