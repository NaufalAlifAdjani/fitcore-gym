<x-trainer-layout>
    <div class="space-y-6">
        <div>
            <h1 class="font-poppins text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                {{ $title }}
            </h1>
            <p class="mt-1 font-inter text-xs sm:text-sm text-gray-500">
                Fitur {{ $title }} untuk Personal Trainer di FitCore Gym.
            </p>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-xs border border-gray-100">
            <div class="flex items-center gap-3 text-gray-600 mb-2">
                <svg class="h-6 w-6 text-[#ED1B45]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <h3 class="font-poppins font-bold text-base text-gray-900">Segera Hadir</h3>
            </div>
            <p class="font-inter text-sm text-gray-500 leading-relaxed">
                Modul {{ $title }} sedang dalam tahap finalisasi dan akan segera aktif untuk mendukung operasional pelatihan Anda.
            </p>
        </div>
    </div>
</x-trainer-layout>
