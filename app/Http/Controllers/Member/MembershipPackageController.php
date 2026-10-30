<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipPaymentRequest;
use App\Models\Bank;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\AdminNewPaymentNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Throwable;

class MembershipPackageController extends Controller
{
    /**
     * Menampilkan katalog paket membership untuk member.
     */
    public function index(): View
    {
        $packages = MembershipPackage::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->get();

        return view('member.packages.index', compact('packages'));
    }

    /**
     * Menampilkan form checkout & instruksi pembayaran.
     */
    public function checkout(MembershipPackage $package): View|RedirectResponse
    {
        if (! $package->is_active || $package->status !== 'active') {
            return redirect()
                ->route('member.membership.packages')
                ->with('error', 'Paket membership yang dipilih sedang tidak aktif.');
        }

        $banks = Bank::query()
            ->where('status', 'active')
            ->get();

        if ($banks->isEmpty()) {
            $banks = Bank::all();
        }

        $uniqueCode = random_int(100, 999);

        return view('member.packages.checkout', compact('package', 'banks', 'uniqueCode'));
    }

    /**
     * Menampilkan formulir upload bukti transfer pembayaran.
     */
    public function uploadProof(Request $request, MembershipPackage $package): View|RedirectResponse
    {
        if (! $package->is_active || $package->status !== 'active') {
            return redirect()
                ->route('member.membership.packages')
                ->with('error', 'Paket membership yang dipilih sedang tidak aktif.');
        }

        $banks = Bank::query()
            ->where('status', 'active')
            ->get();

        if ($banks->isEmpty()) {
            $banks = Bank::all();
        }

        $selectedBank = null;
        if ($request->filled('bank_id')) {
            $selectedBank = $banks->firstWhere('id', (int) $request->query('bank_id'));
        }

        $selectedBank ??= $banks->first();

        $uniqueCode = (int) $request->query('unique_code', 0);
        if ($uniqueCode <= 0) {
            $uniqueCode = random_int(100, 999);
        }

        $effectivePrice = ($package->promo_price !== null && $package->promo_price < $package->price)
            ? $package->promo_price
            : $package->price;

        $totalAmount = $effectivePrice + $uniqueCode;

        return view('member.packages.upload-proof', compact(
            'package',
            'banks',
            'selectedBank',
            'uniqueCode',
            'totalAmount'
        ));
    }

    /**
     * Memproses upload bukti bayar dan membuat entri payment.
     */
    public function processPayment(StoreMembershipPaymentRequest $request, MembershipPackage $package): RedirectResponse
    {
        if (! $package->is_active || $package->status !== 'active') {
            return redirect()
                ->route('member.membership.packages')
                ->with('error', 'Paket membership yang dipilih sedang tidak aktif.');
        }

        $validated = $request->validated();
        $bank = Bank::findOrFail($validated['bank_id']);

        $file = $request->file('proof_image');

        try {
            $manager = new ImageManager(new Driver);
            $image = method_exists($manager, 'read')
                ? $manager->read($file)
                : $manager->decode($file);

            $image->scaleDown(width: 800);

            if (class_exists(WebpEncoder::class)) {
                $encoded = $image->encode(new WebpEncoder(quality: 80));
                $extension = 'webp';
            } elseif (method_exists($image, 'toWebp')) {
                $encoded = $image->toWebp(80);
                $extension = 'webp';
            } elseif (class_exists(JpegEncoder::class)) {
                $encoded = $image->encode(new JpegEncoder(quality: 80));
                $extension = 'jpg';
            } else {
                $encoded = method_exists($image, 'toJpeg')
                    ? $image->toJpeg(80)
                    : $image->encode();
                $extension = 'jpg';
            }

            $path = 'payments/'.Str::uuid().'.'.$extension;
            Storage::disk('public')->put($path, (string) $encoded);
            $url = Storage::url($path);
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors(['proof_image' => 'Gagal memproses dan mengompres berkas bukti pembayaran. Pastikan berkas adalah gambar yang valid.'])
                ->with('error', 'Terjadi kesalahan saat memproses bukti pembayaran.');
        }

        $uniqueCode = (int) $request->input('unique_code', 0);
        $basePrice = ($package->promo_price !== null && $package->promo_price < $package->price)
            ? $package->promo_price
            : $package->price;
        $totalAmount = (int) $basePrice + $uniqueCode;

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
            'amount' => $totalAmount,
            'bank_id' => $bank->id,
            'bank_sender' => $validated['sender_name'],
            'bank_destination' => $bankDestination,
            'proof_image_path' => $path,
            'proof_image_url' => $url,
            'transfer_date' => now(),
            'status' => 'pending',
        ]);

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new AdminNewPaymentNotification($payment));

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
