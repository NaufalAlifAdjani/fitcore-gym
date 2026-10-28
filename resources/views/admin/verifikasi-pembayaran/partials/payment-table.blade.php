<div class="bg-white rounded-[16px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] overflow-hidden" aria-label="Daftar pembayaran">
    <div class="p-6 border-b border-[#E5E7EB]">
        <h2 class="font-poppins font-bold text-xl text-[#16151A]">Riwayat Pembayaran</h2>
        <p class="font-inter text-sm text-[#565A66] mt-1">
            Menampilkan {{ $payments->firstItem() ?? 0 }}–{{ $payments->lastItem() ?? 0 }} dari {{ $payments->total() }} pembayaran
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1120px] border-collapse text-left">
            <thead class="bg-[#F6F6F7]/50 border-b border-[#E5E7EB] text-[11px] font-bold text-[#565A66] uppercase tracking-wider">
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
            <tbody class="divide-y divide-[#E5E7EB]">
                @forelse ($payments as $payment)
                    @php
                        $packageName = $payment->package_type === 'membership'
                            ? $payment->membershipPackage?->name
                            : $payment->ptPackage?->name;
                    @endphp
                    <tr class="transition hover:bg-[#F6F6F7]/60">
                        {{-- INVOICE ID --}}
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-poppins font-bold text-sm text-[#16151A]">{{ $payment->invoice_id }}</span>
                        </td>

                        {{-- PEMESAN --}}
                        <td class="px-5 py-4">
                            <p class="font-inter font-bold text-sm text-[#16151A]">{{ $payment->member?->name ?? 'Member tidak tersedia' }}</p>
                            <p class="mt-0.5 font-inter text-xs text-[#565A66]">{{ $payment->member?->email ?? '-' }}</p>
                        </td>

                        {{-- PAKET --}}
                        <td class="px-5 py-4">
                            <p class="font-inter font-bold text-sm text-[#16151A]">{{ $packageName ?? 'Paket tidak tersedia' }}</p>
                            <p class="mt-0.5 font-inter text-xs uppercase tracking-wide text-[#565A66]">
                                {{ $payment->package_type === 'membership' ? 'Membership' : 'Sesi PT' }}
                            </p>
                        </td>

                        {{-- NOMINAL --}}
                        <td class="whitespace-nowrap px-5 py-4 font-poppins font-bold text-sm text-[#16151A]">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>

                        {{-- BANK --}}
                        <td class="whitespace-nowrap px-5 py-4">
                            <p class="font-inter font-bold text-sm text-[#16151A]">{{ $payment->bank_sender }}</p>
                            <p class="mt-0.5 font-inter text-xs text-[#565A66]">ke {{ $payment->bank_destination }}</p>
                        </td>

                        {{-- TANGGAL UPLOAD --}}
                        <td class="whitespace-nowrap px-5 py-4 font-inter text-xs text-[#16151A]">
                            <div>{{ $payment->transfer_date?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-' }}</div>
                            @if ($payment->proof_image_path || $payment->proof_image || $payment->proof_image_url)
                                <!-- Modal Pratinjau Bukti Transfer (Lightbox) Terisolasi per Baris -->
                                <div x-data="{ open: false }" class="mt-1.5 inline-block">
                                    <button
                                        type="button"
                                        x-on:click="open = true"
                                        class="text-[#ED1B45] text-xs font-semibold flex items-center gap-1 hover:underline cursor-pointer"
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
                                        <div class="relative max-w-2xl w-full rounded-[16px] bg-white p-6 shadow-2xl text-left" x-on:click.stop>
                                            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3 mb-4">
                                                <div>
                                                    <p class="font-inter text-[10px] font-bold uppercase tracking-wider text-[#ED1B45]">Bukti Transfer Member</p>
                                                    <h3 class="font-poppins text-base font-bold text-[#16151A]">{{ $payment->invoice_id }} - {{ $payment->member?->name }}</h3>
                                                </div>
                                                <button type="button" x-on:click="open = false" class="rounded-full p-1.5 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]" aria-label="Tutup">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                                </button>
                                            </div>
                                            <div class="max-h-[65vh] overflow-auto rounded-xl bg-[#F6F6F7] p-2 flex items-center justify-center">
                                                <img src="{{ Storage::url($payment->proof_image_path ?? $payment->proof_image) }}" 
                                                     alt="Bukti transfer {{ $payment->invoice_id }}"
                                                     class="w-full h-auto rounded-lg object-contain bg-white border border-[#E5E7EB]">
                                            </div>
                                            <div class="mt-4 flex items-center justify-between text-xs font-inter text-[#565A66]">
                                                <span>Nominal: <strong class="text-[#ED1B45]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong> ({{ $payment->bank_sender }})</span>
                                                <button type="button" x-on:click="open = false" class="rounded-full bg-[#F6F6F7] px-4 py-1.5 text-xs font-semibold text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] transition">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="whitespace-nowrap px-5 py-4">
                            @include('admin.verifikasi-pembayaran.partials.payment-status-badge', ['payment' => $payment])
                        </td>

                        {{-- AKSI --}}
                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($payment->status === 'pending')
                                    <!-- Modal Penolakan Terisolasi per Baris -->
                                    <div x-data="{ open: false }" class="inline-block text-left">
                                        <button
                                            type="button"
                                            x-on:click="open = true"
                                            class="border border-rose-300 text-rose-600 hover:bg-rose-50 px-4 py-1.5 text-xs font-semibold font-inter rounded-full transition inline-flex items-center justify-center cursor-pointer"
                                        >
                                            Tolak
                                        </button>

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
                                            <div class="w-full max-w-lg rounded-[16px] bg-white p-6 shadow-2xl sm:p-8 text-left" x-on:click.stop>
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600" aria-hidden="true">
                                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M12 8v5m0 3h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                                </div>
                                                <h2 id="reject-row-title-{{ $payment->id }}" class="mt-4 font-poppins text-lg font-bold text-[#16151A]">
                                                    Tolak Pembayaran ({{ $payment->invoice_id }})
                                                </h2>
                                                <p class="mt-1 font-inter text-xs leading-5 text-[#565A66]">
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
                                                                class="rounded-full border border-[#E5E7EB] px-3 py-1 text-[11px] font-inter text-[#565A66] hover:border-rose-300 hover:bg-rose-50 hover:text-rose-700 transition cursor-pointer"
                                                            >
                                                                {{ $quickReason }}
                                                            </button>
                                                        @endforeach
                                                    </div>

                                                    <div>
                                                        <label for="reject-reason-{{ $payment->id }}" class="block mb-1 font-inter text-xs font-semibold text-[#16151A]">Alasan Penolakan *</label>
                                                        <textarea
                                                            id="reject-reason-{{ $payment->id }}"
                                                            name="reason"
                                                            x-model="reason"
                                                            rows="3"
                                                            required
                                                            placeholder="Tuliskan alasan penolakan..."
                                                            class="w-full rounded-[12px] border border-[#E5E7EB] bg-[#F6F6F7] p-3 text-xs font-inter focus:border-rose-500 focus:bg-white focus:ring-1 focus:ring-rose-500 outline-none transition"
                                                        ></textarea>
                                                    </div>

                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <button type="button" x-on:click="open = false" class="rounded-full bg-[#F6F6F7] px-4 py-2 text-xs font-semibold font-inter text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] transition cursor-pointer">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="rounded-full bg-[#ED1B45] px-5 py-2 text-xs font-semibold font-inter text-white hover:bg-[#D1123D] transition cursor-pointer">
                                                            Konfirmasi Tolak
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <button
                                    type="button"
                                    x-on:click="openDetails($el.dataset.detailUrl)"
                                    data-detail-url="{{ route('admin.payments.show', $payment->id) }}"
                                    x-bind:disabled="isLoadingDetail"
                                    class="border border-[#ED1B45] text-[#ED1B45] hover:bg-[#ED1B45] hover:text-white px-4 py-1.5 text-xs font-semibold font-inter rounded-full transition inline-flex items-center justify-center disabled:cursor-wait disabled:opacity-60 cursor-pointer"
                                >
                                    Detail / Verifikasi
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <p class="font-poppins text-lg font-bold text-[#16151A]">Belum ada pembayaran</p>
                            <p class="mt-1 font-inter text-sm text-[#565A66]">Pembayaran yang sesuai filter akan tampil di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($payments->hasPages())
        <div class="flex flex-col gap-4 border-t border-[#E5E7EB] bg-white px-6 py-4 rounded-b-[16px] sm:flex-row sm:items-center sm:justify-between">
            <p class="font-inter text-sm text-[#565A66]">Halaman {{ $payments->currentPage() }} dari {{ $payments->lastPage() }}</p>
            <nav aria-label="Navigasi halaman pembayaran" class="flex items-center gap-1.5">
                @if ($payments->onFirstPage())
                    <span aria-disabled="true" class="rounded-full border border-[#E5E7EB] px-3.5 py-1.5 font-inter text-xs font-semibold text-[#565A66]/40 cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $payments->previousPageUrl() }}" rel="prev" class="rounded-full border border-[#E5E7EB] px-3.5 py-1.5 font-inter text-xs font-semibold text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition">Sebelumnya</a>
                @endif
                <span class="rounded-full bg-[#ED1B45] px-3 py-1.5 font-inter text-xs font-bold text-white shadow-sm">{{ $payments->currentPage() }}</span>
                @if ($payments->hasMorePages())
                    <a href="{{ $payments->nextPageUrl() }}" rel="next" class="rounded-full border border-[#E5E7EB] px-3.5 py-1.5 font-inter text-xs font-semibold text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition">Berikutnya</a>
                @else
                    <span aria-disabled="true" class="rounded-full border border-[#E5E7EB] px-3.5 py-1.5 font-inter text-xs font-semibold text-[#565A66]/40 cursor-not-allowed">Berikutnya</span>
                @endif
            </nav>
        </div>
    @endif
</div>
