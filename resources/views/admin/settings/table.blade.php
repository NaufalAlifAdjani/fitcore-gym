<div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] overflow-hidden">
    <!-- Desktop & Tablet Table View -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse font-sans">
            <thead>
                <tr class="bg-gray-50/70 border-b border-[#E5E7EB] text-[11px] font-bold text-[#565A66] uppercase tracking-wider">
                    <th scope="col" class="py-4 px-6">Nama Trainer</th>
                    <th scope="col" class="py-4 px-6">Nama Klien / Sesi</th>
                    <th scope="col" class="py-4 px-6 text-right">Total Tarif</th>
                    <th scope="col" class="py-4 px-6 text-right">Komisi PT (70%)</th>
                    <th scope="col" class="py-4 px-6 text-right">Pendapatan Gym (30%)</th>
                    <th scope="col" class="py-4 px-6 text-center">Status Pencairan</th>
                    <th scope="col" class="py-4 px-6 text-center">Aksi / Pencairan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E7EB] text-xs">
                @forelse ($records as $record)
                    @php
                        $trainer = $record->trainer ?? \App\Models\User::where('role', 'trainer')->first();
                        $trainerName = $trainer?->name ?? 'Coach Budi';
                        $clientName = $record->member?->name ?? 'Member FitCore';
                        $sessionOrPackage = $record->ptPackage?->name ?? 'Sesi Personal Trainer';
                        $amount = (float) $record->amount;
                        $trainerComm = (float) $record->trainer_revenue > 0 ? (float) $record->trainer_revenue : round($amount * 0.70, 2);
                        $gymRev = (float) $record->gym_revenue > 0 ? (float) $record->gym_revenue : round($amount * 0.30, 2);
                        $payoutStatus = $record->payout_status ?: 'pending';
                    @endphp
                    <tr class="hover:bg-[#F6F6F7]/50 transition duration-150">
                        <!-- 1. Nama Trainer -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF5722] to-[#FFB703] text-white flex items-center justify-center font-poppins font-black text-xs shadow-xs shrink-0">
                                    {{ strtoupper(substr($trainerName, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-poppins font-bold text-sm text-[#16151A] truncate">{{ $trainerName }}</p>
                                    <p class="text-[11px] text-[#565A66]">{{ $trainer?->email ?? 'trainer@fitcore.test' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- 2. Nama Klien / Sesi -->
                        <td class="py-4 px-6">
                            <div>
                                <p class="font-bold text-[#16151A]">{{ $clientName }}</p>
                                <p class="text-[11px] text-[#565A66] flex items-center gap-1 mt-0.5">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#FF5722]"></span>
                                    <span>{{ $sessionOrPackage }}</span>
                                </p>
                                <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $record->invoice_id }}</p>
                            </div>
                        </td>

                        <!-- 3. Total Tarif -->
                        <td class="py-4 px-6 text-right font-mono font-bold text-[#16151A] text-sm">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </td>

                        <!-- 4. Komisi PT (70%) -->
                        <td class="py-4 px-6 text-right">
                            <span class="inline-block font-mono font-black text-sm text-[#FF5722]">
                                Rp {{ number_format($trainerComm, 0, ',', '.') }}
                            </span>
                            <span class="block text-[10px] font-semibold text-[#565A66]">70% Porsi Trainer</span>
                        </td>

                        <!-- 5. Pendapatan Gym (30%) -->
                        <td class="py-4 px-6 text-right">
                            <span class="inline-block font-mono font-bold text-sm text-emerald-700">
                                Rp {{ number_format($gymRev, 0, ',', '.') }}
                            </span>
                            <span class="block text-[10px] font-semibold text-[#565A66]">30% Hak Gym</span>
                        </td>

                        <!-- 6. Status Pencairan -->
                        <td class="py-4 px-6 text-center">
                            @if ($payoutStatus === 'paid')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Paid
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pending
                                </span>
                            @endif
                        </td>

                        <!-- 7. Aksi / Tanggal Pencairan -->
                        <td class="py-4 px-6 text-center">
                            @if ($payoutStatus === 'pending')
                                <button
                                    type="button"
                                    wire:click="cairkanKomisi({{ $record->id }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold shadow-xs transition active:scale-95 cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Cairkan Komisi</span>
                                </button>
                            @else
                                <div class="text-center">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Lunas Dicairkan</span>
                                    </span>
                                    @if ($record->payout_date)
                                        <p class="text-[10px] text-[#565A66] mt-0.5">
                                            {{ \Carbon\Carbon::parse($record->payout_date)->translatedFormat('d M Y, H:i') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 px-6 text-center text-[#565A66]">
                            <div class="max-w-sm mx-auto space-y-2">
                                <svg class="w-10 h-10 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-bold text-sm text-[#16151A]">Tidak Ada Data Transaksi Komisi PT</p>
                                <p class="text-xs">Belum ada transaksi paket sesi PT yang memenuhi kriteria pencarian atau filter saat ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if ($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
        <div class="p-4 border-t border-[#E5E7EB] bg-gray-50/50">
            {{ $records->links() }}
        </div>
    @endif
</div>

