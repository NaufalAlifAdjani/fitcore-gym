{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Web Desktop UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Tentang Kami & Fasilitas Gym Publik
| FILE         : resources/views/landing/about.blade.php
| LAYOUT       : layouts/public.blade.php
| AKSES ROUTE  : /about
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.public title="Tentang Kami - FitCore Athletic" active-tab="about">

    <div class="py-16 bg-[#F6F6F7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

            <!-- 1. HEADER SECTION -->
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-block px-3.5 py-1 rounded-full bg-orange-50 text-[#FF5722] text-xs font-bold uppercase tracking-widest mb-3">
                    FILOSOFI &amp; VISI KAMI
                </span>
                <h1 class="font-poppins font-black text-3xl sm:text-5xl text-[#16151A] tracking-tight">
                    Membangun Kultur Disiplin &amp; Performa Tanpa Kompromi
                </h1>
                <p class="text-[#565A66] text-sm sm:text-base mt-4 leading-relaxed font-normal">
                    FitCore Athletic didirikan bukan sekadar sebagai tempat berolahraga biasa, melainkan pusat pembinaan fisik dan mental berstandar atletik tertinggi.
                </p>
            </div>

            <!-- 2. VISI & MISI CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl p-8 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-poppins font-extrabold text-2xl text-[#16151A]">Visi Kami</h3>
                    <p class="text-xs sm:text-sm text-[#565A66] leading-relaxed">
                        Menjadi ekosistem kebugaran premier yang menetapkan tolok ukur baru dalam pelatihan fisik, pemulihan atletik, dan kultur disiplin berkelanjutan di Asia Tenggara.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-200 flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-poppins font-extrabold text-2xl text-[#16151A]">Misi Kami</h3>
                    <p class="text-xs sm:text-sm text-[#565A66] leading-relaxed">
                        Menyediakan peralatan berstandar kejuaraan internasional, membina pelatih berlisensi terakreditasi dunia, serta menghadirkan fasilitas pemulihan kelas atas yang dapat diakses oleh semua pencari keunggulan fisik.
                    </p>
                </div>
            </div>

            <!-- 3. FASILITAS & ZONA LATIHAN -->
            <div class="space-y-8">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="text-xs font-bold text-[#FF5722] uppercase tracking-wider">ZONA LATIHAN &amp; WELLNESS</span>
                    <h2 class="font-poppins font-extrabold text-3xl text-[#16151A] mt-1">Galeri Fasilitas FitCore</h2>
                    <p class="text-xs sm:text-sm text-[#565A66] mt-2">Setiap jengkal fasilitas dirancang untuk kenyamanan, higienitas, dan efektivitas latihan Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Fasilitas 1 -->
                    <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#FF5722] text-white flex items-center justify-center font-bold text-sm">
                            01
                        </div>
                        <h4 class="font-poppins font-bold text-lg text-[#16151A]">Free Weights &amp; Barbell Area</h4>
                        <p class="text-xs text-[#565A66] leading-relaxed">
                            Eleiko Olympic barbells, calibrated bumper plates, dumbbell hingga 60kg, dan heavy-duty power rack komersial.
                        </p>
                    </div>

                    <!-- Fasilitas 2 -->
                    <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#FF5722] text-white flex items-center justify-center font-bold text-sm">
                            02
                        </div>
                        <h4 class="font-poppins font-bold text-lg text-[#16151A]">High-Performance Cardio Deck</h4>
                        <p class="text-xs text-[#565A66] leading-relaxed">
                            Curved treadmills Woodway, Concept2 erg rowers &amp; ski-erg, serta Technogym touchscreen smart treadmills.
                        </p>
                    </div>

                    <!-- Fasilitas 3 -->
                    <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#FF5722] text-white flex items-center justify-center font-bold text-sm">
                            03
                        </div>
                        <h4 class="font-poppins font-bold text-lg text-[#16151A]">Cold Plunge &amp; Infrared Sauna</h4>
                        <p class="text-xs text-[#565A66] leading-relaxed">
                            Bak terapi es suhu 4°C untuk pemulihan inflamasi otot dan ruang sauna inframerah untuk relaksasi kardiovaskular.
                        </p>
                    </div>

                    <!-- Fasilitas 4 -->
                    <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#FF5722] text-white flex items-center justify-center font-bold text-sm">
                            04
                        </div>
                        <h4 class="font-poppins font-bold text-lg text-[#16151A]">Locker &amp; Rain Shower</h4>
                        <p class="text-xs text-[#565A66] leading-relaxed">
                            Loker kartu elektronik personal, shower air panas bertekanan tinggi, amenities higienis, dan handuk bersih setiap sesi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 4. LOKASI & JAM OPERASIONAL BANNER -->
            <div class="bg-white rounded-2xl p-8 sm:p-10 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="space-y-4">
                        <span class="text-xs font-bold text-[#FF5722] uppercase tracking-wider">LOKASI STRATEGIS</span>
                        <h3 class="font-poppins font-extrabold text-2xl sm:text-3xl text-[#16151A]">
                            Kunjungi Studio FitCore SCBD
                        </h3>
                        <p class="text-xs sm:text-sm text-[#565A66] leading-relaxed">
                            Terletak di jantung pusat bisnis Jakarta dengan akses parkir luas, keamanan 24 jam, dan lingkungan latihan premium tanpa keramaian berlebih.
                        </p>
                        <div class="space-y-2 pt-2 text-xs text-[#16151A]">
                            <p><strong>Alamat:</strong> Sudirman Central Business District (SCBD) Lot 8, Jakarta Selatan 12190</p>
                            <p><strong>Kontak Front-Desk:</strong> +62 812-0000-0001 (WhatsApp / Call)</p>
                        </div>
                    </div>

                    <div class="bg-[#F6F6F7] rounded-2xl p-6 border border-[#E5E7EB] space-y-4">
                        <h4 class="font-poppins font-bold text-sm text-[#16151A] uppercase tracking-wider">Jam Operasional Studio</h4>
                        <div class="space-y-3 text-xs text-[#565A66]">
                            <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                                <span class="font-medium">Senin – Jumat</span>
                                <span class="font-extrabold text-[#16151A]">05:30 – 22:00 WIB</span>
                            </div>
                            <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                                <span class="font-medium">Sabtu – Minggu</span>
                                <span class="font-extrabold text-[#16151A]">06:00 – 21:00 WIB</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium">Hari Libur Nasional</span>
                                <span class="font-extrabold text-[#FF5722]">07:00 – 20:00 WIB</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('landing.packages') }}" class="block w-full py-3 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold text-center shadow-md shadow-orange-500/25 transition">
                                Daftar Keanggotaan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-layouts.public>

