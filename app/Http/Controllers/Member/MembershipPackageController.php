<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipPaymentRequest;
use App\Models\Bank;
use App\Models\MembershipPackage;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MembershipPackageController extends Controller
{
    /**
     * Menampilkan katalog paket membership untuk member.
     */
    public function index(): View
    {
        $packages = MembershipPackage::query()
            ->where(function ($query): void {
                $query->where('is_active', true)
                    ->orWhere('status', 'active');
            })
            ->get();

        return view('member.packages.index', compact('packages'));
    }

    /**
     * Menampilkan form checkout & instruksi pembayaran.
     */
    public function checkout(MembershipPackage $package): View
    {
        if (! $package->is_active && $package->status !== 'active') {
            abort(404, 'Paket membership tidak aktif atau tidak ditemukan.');
        }

        $banks = Bank::query()
            ->where('status', 'active')
            ->get();

        if ($banks->isEmpty()) {
            $banks = Bank::all();
        }

        return view('member.packages.checkout', compact('package', 'banks'));
    }

    /**
     * Memproses upload bukti bayar dan membuat entri payment.
     */
    public function processPayment(StoreMembershipPaymentRequest $request, MembershipPackage $package): RedirectResponse
    {
        if (! $package->is_active && $package->status !== 'active') {
            abort(404, 'Paket membership tidak aktif.');
        }

        $validated = $request->validated();
        $bank = Bank::findOrFail($validated['bank_id']);

        $file = $request->file('proof_image');
        $path = $file->store('payment-proofs', 'public');
        $url = Storage::url($path);

        $amount = ($package->promo_price !== null && $package->promo_price < $package->price)
            ? $package->promo_price
            : $package->price;

        do {
            $invoiceId = 'INV-'.strtoupper(Str::random(8));
        } while (Payment::where('invoice_id', $invoiceId)->exists());

        $bankDestination = "{$bank->name} ({$bank->account_number} a.n. {$bank->account_holder})";

        $payment = Payment::create([
            'invoice_id' => $invoiceId,
            'member_id' => $request->user()->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => $amount,
            'bank_id' => $bank->id,
            'bank_sender' => $validated['sender_name'],
            'bank_destination' => $bankDestination,
            'proof_image_path' => $path,
            'proof_image_url' => $url,
            'transfer_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('member.membership.payment-status', $payment->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Tim kami akan segera melakukan verifikasi.');
    }

    /**
     * Menampilkan status transaksi verifikasi pembayaran.
     */
    public function paymentStatus(Payment $payment): View
    {
        if ($payment->member_id !== auth()->id() && auth()->user()?->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $payment->load(['membershipPackage', 'bank']);

        return view('member.packages.payment-status', compact('payment'));
    }
}
