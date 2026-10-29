<div
    x-data="{
        show: {{ (request()->has('verified') || session('verified')) ? 'true' : 'false' }},
        closeModal() {
            this.show = false;
            try {
                const url = new URL(window.location.href);
                if (url.searchParams.has('verified')) {
                    url.searchParams.delete('verified');
                    const cleanUrl = url.pathname + (url.search ? url.search : '') + url.hash;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            } catch (e) {}
        }
    }"
    x-cloak
    x-show="show"
    x-on:keydown.escape.window="closeModal()"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="email-verified-title"
>
    <!-- Backdrop Overlay -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="closeModal()"
        class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"
    ></div>

    <!-- Modal Content Window -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-md transform overflow-hidden rounded-3xl border border-zinc-800 bg-[#111622] p-6 sm:p-8 text-center shadow-2xl transition-all"
    >
        <!-- Top Ambient Glow -->
        <div class="pointer-events-none absolute -top-24 left-1/2 h-48 w-48 -translate-x-1/2 rounded-full bg-emerald-500/20 blur-3xl"></div>

        <!-- Close Button (X) -->
        <button
            type="button"
            x-on:click="closeModal()"
            class="absolute top-4 right-4 text-zinc-400 hover:text-white transition-colors p-2 rounded-xl hover:bg-zinc-800/60 focus:outline-none"
            aria-label="Tutup modal"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Success Icon -->
        <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 shadow-xl shadow-emerald-500/15">
            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <!-- Status Pill -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 mb-3">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Email Berhasil Diverifikasi</span>
        </div>

        <!-- Title -->
        <h3 id="email-verified-title" class="text-xl sm:text-2xl font-black text-white font-montserrat tracking-tight">
            Selamat Datang di FitCore!
        </h3>

        <!-- Body Message -->
        <p class="mt-3 text-sm text-zinc-300 leading-relaxed font-inter">
            Alamat email Anda telah berhasil dikonfirmasi. Akun Anda kini aktif penuh dan siap digunakan untuk menikmati seluruh fitur, jadwal latihan, dan fasilitas eksklusif FitCore Gym.
        </p>

        <!-- Action Button -->
        <button
            type="button"
            x-on:click="closeModal()"
            class="mt-6 w-full inline-flex justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-[#BA0030] to-[#E6003D] hover:from-[#A00029] hover:to-[#C90035] text-white px-5 py-3.5 text-sm font-bold shadow-lg shadow-[#BA0030]/25 transition-all duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#BA0030] focus:ring-offset-2 focus:ring-offset-[#111622]"
        >
            <span>Mulai Eksplorasi Dashboard</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>
    </div>
</div>

