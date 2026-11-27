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
    public const TIER_RANKS = [
        'Basic' => 1,
        'Student Tier' => 1,
        'Standard' => 2,
        'Premium & VIP' => 3,
    ];

    /**
     * Menampilkan katalog paket membership untuk member.
     */
    public function index(): View
    {
        $today = now('Asia/Jakarta')->startOfDay();
        $user = auth()->user();

        $currentActive = $user?->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->with('package')
            ->latest('end_date')
            ->first();

        $currentTierRank = $currentActive ? (self::TIER_RANKS[$currentActive->package?->tier] ?? 1) : 0;

        $allPackages = MembershipPackage::query()
            ->where('is_active', true)
            ->get();

        $packages = $allPackages->map(function ($pkg) use ($currentActive, $currentTierRank) {
            $newTierRank = self::TIER_RANKS[$pkg->tier] ?? 1;

            $pkg->is_downgrade = $currentActive && ($newTierRank < $currentTierRank);
            $pkg->is_eligible = ! $pkg->is_downgrade;
            $pkg->is_current_tier = $currentActive && ($newTierRank === $currentTierRank);
            $pkg->is_upgrade = $currentActive && ($newTierRank > $currentTierRank);

            return $pkg;
        });

        return view('member.packages.index', compact('packages', 'currentActive'));
    }

    /**
     * Menampilkan form checkout & instruksi pembayaran.
     */
    public function checkout(MembershipPackage $package): View|RedirectResponse
    {
        if (auth()->check() && ! auth()->user()->isProfileComplete()) {
            $message = 'Mohon lengkapi biodata (Nomor Telepon, Alamat, dll) Anda terlebih dahulu sebelum melakukan pembelian paket.';
            $redirectRoute = request()->is('m/*') || request()->routeIs('mobile.*')
                ? 'mobile.member.profile.index'
                : 'profile.edit';

            return redirect()->route($redirectRoute)
                ->with('warning', $message)
                ->with('profile_incomplete', true);
        }

        if (! $package->is_active) {
            return redirect()
                ->route('member.membership.packages')
                ->with('error', 'Paket membership yang dipilih sedang tidak aktif.');
        }

        // Proteksi Tier (Pilihan 2): Dilarang downgrade saat masih memiliki membership tier lebih tinggi yang aktif
        $today = now('Asia/Jakarta')->startOfDay();
        $user = auth()->user();
        $currentActive = $user?->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->with('package')
            ->latest('end_date')
            ->first();

        $currentTierRank = $currentActive ? (self::TIER_RANKS[$currentActive->package?->tier] ?? 1) : 0;
        $newTierRank = self::TIER_RANKS[$package->tier] ?? 1;

        if ($currentActive && $newTierRank < $currentTierRank) {
            $currentTierName = $currentActive->package?->name ?? 'tingkat lebih tinggi';
            $targetRoute = (request()->is('m/*') || request()->routeIs('mobile.*'))
                ? 'mobile.packages.index'
                : 'member.membership.packages';

            return redirect()->route($targetRoute)
                ->with('error', "Anda saat ini memiliki keanggotaan aktif {$currentTierName}. Pembelian tier yang lebih rendah hanya dapat dilakukan setelah masa aktif membership Anda berakhir.");
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
        if (auth()->check() && ! auth()->user()->isProfileComplete()) {
            $message = 'Mohon lengkapi biodata (Nomor Telepon, Alamat, dll) Anda terlebih dahulu sebelum melakukan pembelian paket.';
            $redirectRoute = $request->is('m/*') || $request->routeIs('mobile.*')
                ? 'mobile.member.profile.index'
                : 'profile.edit';

            return redirect()->route($redirectRoute)
                ->with('warning', $message)
                ->with('profile_incomplete', true);
        }

        if (! $package->is_active) {
            return redirect()
                ->route('member.membership.packages')
                ->with('error', 'Paket membership yang dipilih sedang tidak aktif.');
        }

        // Proteksi Tier (Pilihan 2): Dilarang downgrade saat masih memiliki membership tier lebih tinggi yang aktif
        $today = now('Asia/Jakarta')->startOfDay();
        $user = $request->user();
        $currentActive = $user?->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->with('package')
            ->latest('end_date')
            ->first();

        $currentTierRank = $currentActive ? (self::TIER_RANKS[$currentActive->package?->tier] ?? 1) : 0;
        $newTierRank = self::TIER_RANKS[$package->tier] ?? 1;

        if ($currentActive && $newTierRank < $currentTierRank) {
            $currentTierName = $currentActive->package?->name ?? 'tingkat lebih tinggi';
            $targetRoute = ($request->is('m/*') || $request->routeIs('mobile.*'))
                ? 'mobile.packages.index'
                : 'member.membership.packages';

            return redirect()->route($targetRoute)
                ->with('error', "Anda saat ini memiliki keanggotaan aktif {$currentTierName}. Pembelian tier yang lebih rendah hanya dapat dilakukan setelah masa aktif membership Anda berakhir.");
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

        $user = $request->user();
        $payment = null;
        if ($user) {
            $bankDestination = $selectedBank ? "{$selectedBank->name} ({$selectedBank->account_number} a.n. {$selectedBank->account_holder})" : null;

            if ($request->filled('payment_id')) {
                $payment = Payment::where('id', $request->query('payment_id'))
                    ->where('member_id', $user->id)
                    ->where('status', 'pending')
                    ->first();
            }

            if (! $payment) {
                $payment = Payment::where('member_id', $user->id)
                    ->where('package_id', $package->id)
                    ->where('package_type', 'membership')
                    ->where('status', 'pending')
                    ->whereNull('proof_image_path')
                    ->latest('id')
                    ->first();
            }

            if (! $payment) {
                do {
                    $invoiceId = 'INV-'.strtoupper(Str::random(8));
                } while (Payment::where('invoice_id', $invoiceId)->exists());

                $payment = Payment::create([
                    'invoice_id' => $invoiceId,
                    'member_id' => $user->id,
                    'package_id' => $package->id,
                    'package_type' => 'membership',
                    'payment_type' => 'membership',
                    'amount' => $totalAmount,
                    'bank_id' => $selectedBank?->id,
                    'bank_destination' => $bankDestination,
                    'status' => 'pending',
                    'proof_image_path' => null,
                    'proof_image_url' => null,
                ]);
            } else {
                $payment->update([
                    'amount' => $totalAmount,
                    'bank_id' => $selectedBank?->id,
                    'bank_destination' => $bankDestination,
                ]);
            }
        }

        return view('member.packages.upload-proof', compact(
            'package',
            'banks',
            'selectedBank',
            'uniqueCode',
            'totalAmount',
            'payment'
        ));
    }

    /**
     * Memproses upload bukti bayar dan membuat entri payment.
     */
    public function processPayment(StoreMembershipPaymentRequest $request, MembershipPackage $package): RedirectResponse
    {
        if (auth()->check() && ! auth()->user()->isProfileComplete()) {
            $message = 'Mohon lengkapi biodata (Nomor Telepon, Alamat, dll) Anda terlebih dahulu sebelum melakukan pembelian paket.';
            $redirectRoute = $request->is('m/*') || $request->routeIs('mobile.*')
                ? 'mobile.member.profile.index'
                : 'profile.edit';

            return redirect()->route($redirectRoute)
                ->with('warning', $message)
                ->with('profile_incomplete', true);
        }

        if (! $package->is_active) {
            return redirect()
                ->route('member.membership.packages')
                ->with('error', 'Paket membership yang dipilih sedang tidak aktif.');
        }

        // Proteksi Tier (Pilihan 2): Dilarang downgrade saat masih memiliki membership tier lebih tinggi yang aktif
        $today = now('Asia/Jakarta')->startOfDay();
        $user = $request->user();
        $currentActive = $user?->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->with('package')
            ->latest('end_date')
            ->first();

        $currentTierRank = $currentActive ? (self::TIER_RANKS[$currentActive->package?->tier] ?? 1) : 0;
        $newTierRank = self::TIER_RANKS[$package->tier] ?? 1;

        if ($currentActive && $newTierRank < $currentTierRank) {
            $currentTierName = $currentActive->package?->name ?? 'tingkat lebih tinggi';
            $targetRoute = ($request->is('m/*') || $request->routeIs('mobile.*'))
                ? 'mobile.packages.index'
                : 'member.membership.packages';

            return redirect()->route($targetRoute)
                ->with('error', "Anda saat ini memiliki keanggotaan aktif {$currentTierName}. Pembelian tier yang lebih rendah hanya dapat dilakukan setelah masa aktif membership Anda berakhir.");
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

        $payment = null;
        if ($request->filled('payment_id')) {
            $payment = Payment::where('id', $request->input('payment_id'))
                ->where('member_id', $request->user()->id)
                ->where('status', 'pending')
                ->first();
        }

        if (! $payment) {
            $payment = Payment::where('member_id', $request->user()->id)
                ->where('package_id', $package->id)
                ->where('package_type', 'membership')
                ->where('status', 'pending')
                ->whereNull('proof_image_path')
                ->latest('id')
                ->first();
        }

        if ($payment) {
            $payment->update([
                'amount' => $totalAmount,
                'bank_id' => $bank->id,
                'bank_sender' => $validated['sender_name'],
                'bank_destination' => $bankDestination,
                'proof_image_path' => $path,
                'proof_image_url' => $url,
                'transfer_date' => now(),
                'status' => 'pending',
            ]);
        } else {
            do {
                $invoiceId = 'INV-'.strtoupper(Str::random(8));
            } while (Payment::where('invoice_id', $invoiceId)->exists());

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
        }

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

        $payment->load(['membershipPackage', 'ptPackage', 'bank']);

        return view('member.packages.payment-status', compact('payment'));
    }
}
