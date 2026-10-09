<span
    x-data="{ status: @js($payment->status) }"
    x-on:payment-status-updated.window="if (Number($event.detail.id) === {{ $payment->id }}) status = $event.detail.status"
    x-bind:class="status === 'pending'
        ? 'bg-amber-50 text-amber-700 ring-amber-200'
        : (status === 'verified'
            ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
            : 'bg-rose-50 text-rose-700 ring-rose-200')"
    @class([
        'inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide ring-1 ring-inset',
        'bg-amber-50 text-amber-700 ring-amber-200' => $payment->status === 'pending',
        'bg-emerald-50 text-emerald-700 ring-emerald-200' => $payment->status === 'verified',
        'bg-rose-50 text-rose-700 ring-rose-200' => $payment->status === 'rejected',
    ])
>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    <span
        x-text="{pending: 'Menunggu', verified: 'Terverifikasi', rejected: 'Ditolak'}[status]"
    >{{ ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'][$payment->status] }}</span>
</span>
