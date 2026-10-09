<x-badge
    x-data="{ status: @js($payment->status) }"
    x-on:payment-status-updated.window="if (Number($event.detail.id) === {{ $payment->id }}) status = $event.detail.status"
    x-bind:class="status === 'pending'
        ? 'bg-amber-50 text-amber-700 ring-amber-200'
        : (status === 'verified'
            ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
            : 'bg-rose-50 text-rose-700 ring-rose-200')"
    :status="$payment->status"
>
    <span
        x-text="{pending: 'Menunggu', verified: 'Terverifikasi', rejected: 'Ditolak'}[status]"
    >{{ ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'][$payment->status] }}</span>
</x-badge>
