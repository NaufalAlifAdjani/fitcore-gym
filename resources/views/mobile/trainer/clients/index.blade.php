{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Daftar Klien & Member Bimbingan Mobile
VIEW: mobile.trainer.clients.index
LAYOUT: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-trainer title="Klien Saya - FitCore Coach" active="clients">
    <div class="space-y-4">

        <!-- 1. Header Judul Halaman -->
        <div class="pb-1">
            <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                Member &amp; Klien Bimbingan
            </h1>
            <p class="text-[11px] text-[#565A66] font-medium font-sans">
                Daftar member aktif yang telah atau sedang melakukan sesi latihan bersama Anda.
            </p>
        </div>

        <!-- 2. Daftar Klien Card -->
        <div class="space-y-3">
            @forelse ($clients as $client)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($client->phone ?? ''));
                    if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    $activeQuota = $client->ptQuotas->first();
                @endphp

                <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-200 text-[#FF5722] flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr($client->name ?? 'M', 0, 2)) }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins">
                                {{ $client->name }}
                            </h4>
                            <p class="text-[11px] text-[#565A66] truncate font-sans">
                                {{ $client->email }}
                            </p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-[#565A66] mt-1 border border-[#E5E7EB]">
                                ID: {{ $client->memberProfile->member_code ?? ('MEM-' . str_pad($client->id, 5, '0', STR_PAD_LEFT)) }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-2.5 border-t border-[#E5E7EB] flex items-center justify-between text-xs">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#565A66] block">Sisa Kuota PT</span>
                            @if ($activeQuota)
                                <span class="font-bold text-[#FF5722] font-poppins">{{ $activeQuota->remaining_sessions }} Sesi Aktif</span>
                            @else
                                <span class="font-semibold text-gray-400">Tidak ada paket</span>
                            @endif
                        </div>

                        @if ($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($client->name) }},%20saya%20Coach%20{{ urlencode($trainer->name) }}%20dari%20FitCore%20Gym"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 text-[11px] font-bold transition">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.974.554 1.769.82 2.796.82 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.768-5.766zm9.969 5.766c0 5.508-4.462 9.969-9.969 9.969-1.758 0-3.417-.463-4.871-1.272l-5.16 1.353 1.378-5.034c-.9-1.517-1.416-3.292-1.416-5.016 0-5.508 4.462-9.969 9.969-9.969 5.507 0 9.969 4.461 9.969 9.969z"/>
                                </svg>
                                <span>Hubungi</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-dashed border-[#E5E7EB] text-center space-y-2 shadow-[0_4px_12px_rgba(0,0,0,0.02)]">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 text-[#565A66] flex items-center justify-center mx-auto mb-1 border border-[#E5E7EB]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#16151A]">Belum Ada Klien</p>
                    <p class="text-[11px] text-[#565A66] max-w-xs mx-auto">
                        Anda belum memiliki member yang pernah memesan sesi latihan bimbingan.
                    </p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($clients->hasPages())
                <div class="pt-2">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.mobile-trainer>

