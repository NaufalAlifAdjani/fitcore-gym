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
                            <div>{{ $payment->transfer_date?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-' }}</div>
                            @if ($payment->proof_image_path || $payment->proof_image || $payment->proof_image_url)
                                <!-- Modal Pratinjau Bukti Transfer (Lightbox) Terisolasi per Baris -->
                                <div x-data="{ open: false }" class="mt-1.5 inline-block">
                                    <button
                                        type="button"
                                        x-on:click="open = true"
                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#BA0030] hover:underline"
                                        title="Pratinjau Struk Transfer"
                                    >
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        <span>Bukti</span>
                                    </button>

                                    <!-- Lightbox Modal -->
                                    <div
                                        x-cloak
                                        x-show="open"
                                        x-transition.opacity
                                        x-on:click.self="open = false"
                                        x-on:keydown.escape.window="open = false"
                                        class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/85 p-4 backdrop-blur-sm whitespace-normal"
                                        role="dialog"
                                        aria-modal="true"
                                        aria-label="Bukti Transfer {{ $payment->invoice_id }}"
                                    >
                                        <div class="relative max-w-2xl w-full rounded-2xl bg-white p-5 shadow-2xl text-left" x-on:click.stop>
                                            <div class="flex items-center justify-between border-b border-[#F0F0F1] pb-3 mb-4">
                                                <div>
                                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#BA0030]">Bukti Transfer Member</p>
                                                    <h3 class="font-heading text-sm font-bold text-[#16151A]">{{ $payment->invoice_id }} - {{ $payment->member?->name }}</h3>
                                                </div>
                                                <button type="button" x-on:click="open = false" class="rounded-full p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700" aria-label="Tutup">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                                </button>
                                            </div>
                                            <div class="max-h-[65vh] overflow-auto rounded-xl bg-slate-100 p-2 flex items-center justify-center">
                                                <img src="{{ Storage::url($payment->proof_image_path ?? $payment->proof_image) }}" 
                                                     alt="Bukti transfer {{ $payment->invoice_id }}"
                                                     class="w-full h-auto rounded-lg object-contain bg-gray-50 border border-gray-200">
                                            </div>
                                            <div class="mt-4 flex items-center justify-between text-xs text-[#565A66]">
                                                <span>Nominal: <strong class="text-[#BA0030]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong> ({{ $payment->bank_sender }})</span>
                                                <x-button type="button" variant="secondary" size="xs" x-on:click="open = false">Tutup</x-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-5">
                            @include('admin.verifikasi-pembayaran.partials.payment-status-badge', ['payment' => $payment])
                        </td>
                        <td class="whitespace-nowrap px-5 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($payment->status === 'pending')
                                    <!-- Modal Penolakan Terisolasi per Baris -->
                                    <div x-data="{ open: false }" class="inline-block text-left">
                                        <x-button
                                            type="button"
                                            variant="danger-outline"
                                            size="xs"
                                            x-on:click="open = true"
                                            class="!rounded-full !border-rose-200 !text-rose-700 hover:!bg-rose-50"
                                        >
                                            Tolak
                                        </x-button>

                                        <!-- Modal Penolakan Box -->
                                        <div
                                            x-cloak
                                            x-show="open"
                                            x-transition.opacity
                                            x-on:click.self="open = false"
                                            x-on:keydown.escape.window="open = false"
                                            class="fixed inset-0 z-[75] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm whitespace-normal"
                                            role="dialog"
                                            aria-modal="true"
                                            aria-labelledby="reject-row-title-{{ $payment->id }}"
                                        >
                                            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl sm:p-8 text-left" x-on:click.stop>
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600" aria-hidden="true">
                                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M12 8v5m0 3h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                                </div>
                                                <h2 id="reject-row-title-{{ $payment->id }}" class="mt-4 font-heading text-lg font-bold text-[#16151A]">
                                                    Tolak Pembayaran ({{ $payment->invoice_id }})
                                                </h2>
                                                <p class="mt-1 text-xs leading-5 text-[#565A66]">
                                                    Member: <strong>{{ $payment->member?->name }}</strong> &bull; Nominal: <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                                </p>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.payments.reject', $payment->id) }}"
                                                    x-data="{ reason: '' }"
                                                    class="mt-4 space-y-4"
                                                >
                                                    @csrf
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach (['Bukti Buram / Tidak Jelas', 'Nominal Transfer Kurang', 'Nama Rekening Beda'] as $quickReason)
                                                            <button
                                                                type="button"
                                                                x-on:click="reason = @js($quickReason)"
                                                                class="rounded-full border border-gray-200 px-2.5 py-1 text-[11px] text-[#565A66] hover:border-rose-300 hover:bg-rose-50 hover:text-rose-700 transition"
                                                            >
                                                                {{ $quickReason }}
                                                            </button>
                                                        @endforeach
                                                    </div>

                                                    <div>
                                                        <x-input-label for="reject-reason-{{ $payment->id }}" value="Alasan Penolakan *" class="!mb-1 text-xs" />
                                                        <textarea
                                                            id="reject-reason-{{ $payment->id }}"
                                                            name="reason"
                                                            x-model="reason"
                                                            rows="3"
                                                            required
                                                            placeholder="Tuliskan alasan penolakan..."
                                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-xs focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-200 outline-none transition"
                                                        ></textarea>
                                                    </div>

                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <x-button type="button" variant="secondary" size="xs" x-on:click="open = false" class="!rounded-xl">
                                                            Batal
                                                        </x-button>
                                                        <x-button type="submit" variant="danger" size="xs" class="!rounded-xl">
                                                            Konfirmasi Tolak
                                                        </x-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
                            </div>
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
