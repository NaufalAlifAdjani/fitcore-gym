@if ($payment->status === 'pending')
    <x-badge type="warning">PENDING</x-badge>
@elseif ($payment->status === 'verified')
    <x-badge type="success">TERVERIFIKASI</x-badge>
@elseif ($payment->status === 'rejected')
    <x-badge type="danger">DITOLAK</x-badge>
@else
    <x-badge type="default">{{ strtoupper($payment->status) }}</x-badge>
@endif

