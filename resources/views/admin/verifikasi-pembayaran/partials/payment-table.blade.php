<x-card :padding="false" aria-label="Daftar pembayaran">
    <x-slot:header>
        <h2 class="font-heading text-base font-bold text-[#16151A]">Riwayat Pembayaran</h2>
        <p class="text-xs text-[#858894]">
            Menampilkan {{ $payments->firstItem() ?? 0 }}–{{ $payments->lastItem() ?? 0 }} dari {{ $payments->total() }} pembayaran
        </p>
    </x-slot:header>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1120px] border-collapse text-left">
            <thead class="border-b border-[#F0F0F1] bg-[#FCFCFC] text-[10px] font-semibold uppercase tracking-[0.12em] text-[#858894]">
                <tr>
                    <th scope="col" class="px-6 py-4">Invoice ID</th>
                    <th scope="col" class="px-5 py-4">Pemesan</th>
                    <th scope="col" class="px-5 py-4">Paket</th>
                    <th scope="col" class="px-5 py-4">Nominal</th>
                    <th scope="col" class="px-5 py-4">Bank</th>
                    <th scope="col" class="px-5 py-4">Tanggal Upload</th>
                    <th scope="col" class="px-5 py-4">Status</th>
                    <th scope="col" class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0F0F1]">
                @forelse ($payments as $payment)
                    @php
                        $packageName = $payment->package_type === 'membership'
                            ? $payment->membershipPackage?->name
                            : $payment->ptPackage?->name;
                    @endphp
                    <tr class="transition hover:bg-[#FCFCFC]">
                        <td class="whitespace-nowrap px-6 py-5">
                            <span class="font-mono text-xs font-bold text-[#34343B]">{{ $payment->invoice_id }}</span>
                        </td>
                        <td class="px-5 py-5">
                            <p class="text-sm font-semibold text-[#16151A]">{{ $payment->member?->name ?? 'Member tidak tersedia' }}</p>
                            <p class="mt-1 text-xs text-[#858894]">{{ $payment->member?->email ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-5">
                            <p class="text-sm font-semibold text-[#34343B]">{{ $packageName ?? 'Paket tidak tersedia' }}</p>
                            <p class="mt-1 text-[10px] font-semibold uppercase tracking-wide text-[#858894]">
                                {{ $payment->package_type === 'membership' ? 'Membership' : 'Sesi PT' }}
                            </p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-5 text-sm font-bold text-[#16151A]">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-5">
                            <p class="text-xs font-semibold text-[#34343B]">{{ $payment->bank_sender }}</p>
                            <p class="mt-1 text-[10px] text-[#858894]">ke {{ $payment->bank_destination }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-5 text-xs text-[#565A66]">
                            {{ $payment->transfer_date?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-5">
                            @include('admin.verifikasi-pembayaran.partials.payment-status-badge', ['payment' => $payment])
                        </td>
                        <td class="whitespace-nowrap px-5 py-5 text-right">
                            <x-button
                                type="button"
                                variant="outline"
                                size="xs"
                                x-on:click="openDetails($el.dataset.detailUrl)"
                                data-detail-url="{{ route('admin.payments.show', $payment->id) }}"
                                x-bind:disabled="isLoadingDetail"
                                class="!rounded-full !border-[#E5E5E8] !text-[#BA0030] hover:!border-[#BA0030] hover:!bg-rose-50 disabled:cursor-wait disabled:opacity-60"
                            >
                                Detail / Verifikasi
                            </x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <p class="font-heading text-lg font-bold text-[#34343B]">Belum ada pembayaran</p>
                            <p class="mt-2 text-sm text-[#858894]">Pembayaran yang sesuai filter akan tampil di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($payments->hasPages())
        <x-slot:footer class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-[#858894]">Halaman {{ $payments->currentPage() }} dari {{ $payments->lastPage() }}</p>
            <nav aria-label="Navigasi halaman pembayaran" class="flex items-center gap-1 rounded-full bg-[#EEEEEF] p-1">
                @if ($payments->onFirstPage())
                    <span aria-disabled="true" class="rounded-full px-3 py-2 text-xs font-semibold text-[#A5A6AD]">Sebelumnya</span>
                @else
                    <a href="{{ $payments->previousPageUrl() }}" rel="prev" class="rounded-full px-3 py-2 text-xs font-semibold text-[#565A66] hover:bg-white">Sebelumnya</a>
                @endif
                <span class="rounded-full bg-white px-3 py-2 text-xs font-bold text-[#BA0030] shadow-sm">{{ $payments->currentPage() }}</span>
                @if ($payments->hasMorePages())
                    <a href="{{ $payments->nextPageUrl() }}" rel="next" class="rounded-full px-3 py-2 text-xs font-semibold text-[#565A66] hover:bg-white">Berikutnya</a>
                @else
                    <span aria-disabled="true" class="rounded-full px-3 py-2 text-xs font-semibold text-[#A5A6AD]">Berikutnya</span>
                @endif
            </nav>
        </x-slot:footer>
    @endif
</x-card>
