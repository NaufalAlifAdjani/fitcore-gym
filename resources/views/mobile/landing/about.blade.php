{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : GUEST / PUBLIK (Non-Authenticated)
| MODUL        : Mobile Tentang Kami & Fasilitas Gym Publik
| FILE         : resources/views/mobile/landing/about.blade.php
| LAYOUT       : layouts/mobile-guest.blade.php
| AKSES ROUTE  : /m/about
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-guest title="Tentang Kami - FitCore Mobile" active-tab="about">

    <div class="px-4 py-5 space-y-5">

        <!-- Header Ringkas -->
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-[#FF5722] uppercase tracking-wider">FILOSOFI KAMI</span>
            <h1 class="font-poppins font-black text-2xl text-[#16151A]">Tentang FitCore</h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Pusat kebugaran premier yang didedikasikan untuk performa atletik, disiplin latihan, dan hasil nyata.
            </p>
        </div>

        <!-- Visi & Misi Card -->
        <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-[#FF5722] flex items-center justify-center font-bold shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h2 class="font-poppins font-bold text-base text-[#16151A]">Visi &amp; Komitmen</h2>
            </div>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Menghadirkan fasilitas berstandar kejuaraan dunia dengan atmosfer latihan profesional yang bebas distraksi untuk membantu Anda mencapai potensi fisik terbaik.
            </p>
        </div>

        <!-- Zona Fasilitas Gym -->
        <div class="space-y-3">
            <h2 class="font-poppins font-bold text-base text-[#16151A]">Fasilitas Utama Studio</h2>

            <div class="space-y-3">
                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#FF5722] text-white text-[10px] font-bold inline-flex items-center justify-center shadow-xs shadow-orange-500/25">1</span>
                        <h3 class="font-poppins font-bold text-sm text-[#16151A]">Free Weights &amp; Barbell Arena</h3>
                    </div>
                    <p class="text-xs text-[#565A66] pl-7 leading-relaxed">
                        Olympic Eleiko barbells, calibrated bumper plates, dan dumbbell hingga 60kg.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#FF5722] text-white text-[10px] font-bold inline-flex items-center justify-center shadow-xs shadow-orange-500/25">2</span>
                        <h3 class="font-poppins font-bold text-sm text-[#16151A]">Cardio Deck &amp; Ergometer</h3>
                    </div>
                    <p class="text-xs text-[#565A66] pl-7 leading-relaxed">
                        Curved treadmills Woodway, Concept2 rower, ski-erg, dan Technogym.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#FF5722] text-white text-[10px] font-bold inline-flex items-center justify-center shadow-xs shadow-orange-500/25">3</span>
                        <h3 class="font-poppins font-bold text-sm text-[#16151A]">Cold Plunge &amp; Infrared Sauna</h3>
                    </div>
                    <p class="text-xs text-[#565A66] pl-7 leading-relaxed">
                        Bak es terapi 4°C untuk meredakan inflamasi otot dan ruang sauna relaksasi.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-[#FF5722] text-white text-[10px] font-bold inline-flex items-center justify-center shadow-xs shadow-orange-500/25">4</span>
                        <h3 class="font-poppins font-bold text-sm text-[#16151A]">Loker &amp; Rain Shower Air Panas</h3>
                    </div>
                    <p class="text-xs text-[#565A66] pl-7 leading-relaxed">
                        Loker elektronik aman, shower air panas, handuk higienis, dan sabun mandi premium.
                    </p>
                </div>
            </div>
        </div>

        <!-- Lokasi & Jam Operasional -->
        <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.06)] space-y-3">
            <h3 class="font-poppins font-bold text-sm text-[#16151A] uppercase tracking-wider">Jam Buka Studio SCBD</h3>
            <div class="space-y-2 text-xs text-[#565A66]">
                <div class="flex items-center justify-between pb-1.5 border-b border-[#E5E7EB]">
                    <span>Senin – Jumat</span>
                    <span class="font-bold text-[#16151A]">05:30 – 22:00 WIB</span>
                </div>
                <div class="flex items-center justify-between pb-1.5 border-b border-[#E5E7EB]">
                    <span>Sabtu – Minggu</span>
                    <span class="font-bold text-[#16151A]">06:00 – 21:00 WIB</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Hari Libur Nasional</span>
                    <span class="font-bold text-[#FF5722]">07:00 – 20:00 WIB</span>
                </div>
            </div>

            <div class="pt-2 text-[11px] text-[#565A66]">
                <p><strong>Alamat:</strong> SCBD Lot 8, Jakarta Selatan 12190</p>
                <p class="mt-0.5"><strong>WhatsApp CS:</strong> +62 812-0000-0001</p>
            </div>
        </div>

        <!-- Tombol CTA -->
        <div class="pt-2">
            <a href="{{ route('mobile.landing.packages') }}" class="block w-full py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold text-center shadow-md shadow-orange-500/25 active:scale-98 transition">
                Lihat Paket Membership
            </a>
        </div>

    </div>

</x-layouts.mobile-guest>

