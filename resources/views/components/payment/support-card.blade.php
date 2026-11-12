@props([
    'whatsappNumber' => '6281234567890',
])

<x-card :padding="false" {{ $attributes->merge(['class' => '!rounded-2xl border border-[#E5E7EB] p-5 flex items-center justify-between gap-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] bg-white']) }}>
    <div class="flex items-center gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
        </span>
        <div>
            <p class="font-bold text-xs text-[#16151A]">Butuh bantuan transaksi?</p>
            <p class="text-[11px] text-[#565A66]">Hubungi Customer Service FitCore / WhatsApp CS</p>
        </div>
    </div>
    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="px-3.5 py-1.5 rounded-full text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-300 hover:bg-emerald-100 transition whitespace-nowrap">
        WhatsApp CS
    </a>
</x-card>

