<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyPtPackageRequest;
use App\Models\Bank;
use App\Models\MemberPtQuota;
use App\Models\Payment;
use App\Models\PtPackage;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PtPackagePurchaseController extends Controller
{
    /**
     * Display the list of available PT packages for members.
     * Evaluates member's active membership status and remaining days.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();
        $today = now('Asia/Jakarta')->startOfDay();

        // ── 1. Fetch Active Membership ────────────────────────────────────────
        $activeMembership = $user->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->where(function ($q) {
                $q->whereDoesntHave('payments')
                    ->orWhereHas('payments', fn ($p) => $p->where('status', 'verified'));
            })
            ->with('package')
            ->latest('end_date')
            ->first();

        $remainingDays = 0;
        if ($activeMembership) {
            $endDate = Carbon::parse($activeMembership->end_date)->startOfDay();
            $remainingDays = max(0, (int) $today->diffInDays($endDate, false));
        }

        // ── 2. Fetch Active PT Packages & Filter by Min Membership Days ───────
        $allPackages = PtPackage::where('status', 'active')
            ->orderBy('pt_session_count')
            ->get();

        // Hanya tampilkan paket yang nilai min_membership_days-nya terpenuhi oleh sisa hari membership Member
        $packages = $allPackages->filter(function ($pkg) use ($activeMembership, $remainingDays) {
            $minDays = (int) $pkg->min_membership_days;
            if ($minDays === 0) {
                return true;
            }
            return $activeMembership && ($remainingDays >= $minDays);
        })->values();

        $hasHiddenPackages = $allPackages->count() > $packages->count();

        $banks = Bank::where('status', 'active')
            ->orderBy('name')
            ->get();

        // ── 3. Fetch Member's Existing Quotas ────────────────────────────────
        $myQuotas = $user->ptQuotas()
            ->with('ptPackage')
            ->latest()
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success'             => true,
                'active_membership'   => $activeMembership,
                'remaining_days'      => $remainingDays,
                'packages'            => $packages,
                'has_hidden_packages' => $hasHiddenPackages,
                'banks'               => $banks,
                'my_quotas'           => $myQuotas,
            ]);
        }

        // Data default 8 paket sesuai Figma
        $defaultPackages = [
            ['id' => 'pkg-4', 'name' => 'PT 4 Sesi', 'sessions' => 4, 'price' => 1155000, 'original_price' => 1443750, 'per_session' => 288750, 'is_popular' => false],
            ['id' => 'pkg-8', 'name' => 'PT 8 Sesi', 'sessions' => 8, 'price' => 2072700, 'original_price' => 2590875, 'per_session' => 259088, 'is_popular' => false],
            ['id' => 'pkg-16', 'name' => 'PT 16 Sesi', 'sessions' => 16, 'price' => 3582000, 'original_price' => 4515000, 'per_session' => 223875, 'is_popular' => true],
            ['id' => 'pkg-24', 'name' => 'PT 24 Sesi', 'sessions' => 24, 'price' => 5040000, 'original_price' => 6300000, 'per_session' => 210000, 'is_popular' => false],
            ['id' => 'pkg-36', 'name' => 'PT 36 Sesi', 'sessions' => 36, 'price' => 6804000, 'original_price' => 8505000, 'per_session' => 189000, 'is_popular' => false],
            ['id' => 'pkg-48', 'name' => 'PT 48 Sesi', 'sessions' => 48, 'price' => 8316000, 'original_price' => 10395000, 'per_session' => 173250, 'is_popular' => false],
            ['id' => 'pkg-72', 'name' => 'PT 72 Sesi', 'sessions' => 72, 'price' => 12168000, 'original_price' => 15210000, 'per_session' => 169000, 'is_popular' => false],
            ['id' => 'pkg-96', 'name' => 'PT 96 Sesi', 'sessions' => 96, 'price' => 15840000, 'original_price' => 19800000, 'per_session' => 165000, 'is_popular' => false],
        ];

        return view('member.pt-packages', compact(
            'packages',
            'allPackages',
            'hasHiddenPackages',
            'banks',
            'activeMembership',
            'remainingDays',
            'myQuotas',
            'defaultPackages'
        ));
    }

    /**
     * Langkah 2: Pilih Personal Trainer
     */
    public function selectTrainer(Request $request): View
    {
        $packageId = $request->query('package_id');
        $sessionCount = (int) $request->query('sessions', 12);

        $selectedPackage = null;
        if ($packageId && is_numeric($packageId)) {
            $selectedPackage = PtPackage::find($packageId);
        }

        // 4 Trainer sesuai Figma
        $trainers = [
            [
                'id' => 1,
                'name' => 'Coach Rama Prasetya',
                'title' => 'Coach Rama Prasetya, CSCS',
                'specialization' => 'Hypertrophy & Strength Conditioning',
                'photo' => asset('images/trainers/coach-rama.jpg'),
                'is_active_highlight' => true,
            ],
            [
                'id' => 2,
                'name' => 'Coach Sarah Jenkins',
                'title' => 'Coach Sarah Jenkins, CPT',
                'specialization' => 'Fat Loss & Functional Mobility',
                'photo' => asset('images/trainers/coach-sarah.jpg'),
                'is_active_highlight' => false,
            ],
            [
                'id' => 3,
                'name' => 'Coach Dimas Pratama',
                'title' => 'Coach Dimas Pratama, CSCS',
                'specialization' => 'Powerlifting & Athletic Performance',
                'photo' => asset('images/trainers/coach-dimas.jpg'),
                'is_active_highlight' => false,
            ],
            [
                'id' => 4,
                'name' => 'Coach Maya Anindita',
                'title' => 'Coach Maya Anindita, RYT',
                'specialization' => 'Calisthenics, Yoga & Flexibility',
                'photo' => asset('images/trainers/coach-maya.jpg'),
                'is_active_highlight' => false,
            ],
        ];

        return view('member.pt-select-trainer', compact('trainers', 'packageId', 'sessionCount', 'selectedPackage'));
    }

    /**
     * Langkah 3: Rincian Pesanan & Pembayaran (Checkout)
     */
    public function checkout(Request $request): View
    {
        $packageId = $request->query('package_id');
        $trainerId = (int) $request->query('trainer_id', 1);

        $trainers = [
            1 => 'Coach Rama Prasetya',
            2 => 'Coach Sarah Jenkins',
            3 => 'Coach Dimas Pratama',
            4 => 'Coach Maya Anindita',
        ];
        $selectedTrainerName = $trainers[$trainerId] ?? 'Coach Rama Prasetya';

        $selectedPackage = null;
        if ($packageId && is_numeric($packageId)) {
            $selectedPackage = PtPackage::find($packageId);
        }

        // Nilai default sesuai Figma
        $packageName = $selectedPackage?->name ?? 'Paket Performance Pro (12 Sesi)';
        $sessionCount = $selectedPackage?->pt_session_count ?? 12;
        $subtotal = $selectedPackage ? (float) $selectedPackage->price : 3000000;
        
        $uniqueCode = 421;
        $totalAmount = (int) ($subtotal + $uniqueCode);

        $banks = Bank::where('status', 'active')->orderBy('id')->get();
        if ($banks->isEmpty()) {
            $banks = collect([
                (object)['id' => 1, 'name' => 'Bank BCA', 'account_number' => '8271 0812 3456 7890', 'account_holder' => 'PT MAXFIT INDONESIA PERFORMA'],
                (object)['id' => 2, 'name' => 'Bank Mandiri', 'account_number' => '1370 0192 8374 6500', 'account_holder' => 'PT MAXFIT INDONESIA PERFORMA'],
                (object)['id' => 3, 'name' => 'Bank BNI', 'account_number' => '0912 3847 5610 2938', 'account_holder' => 'PT MAXFIT INDONESIA PERFORMA'],
            ]);
        }

        return view('member.pt-checkout', compact(
            'packageId',
            'trainerId',
            'selectedTrainerName',
            'packageName',
            'sessionCount',
            'subtotal',
            'uniqueCode',
            'totalAmount',
            'banks'
        ));
    }

    /**
     * Proses submit checkout (Langkah 3 -> Langkah 4)
     */
    public function processCheckout(Request $request): RedirectResponse
    {
        $request->validate([
            'bank_id' => ['required'],
        ]);

        $user = $request->user();
        $packageId = $request->input('package_id');
        $trainerId = $request->input('trainer_id', 1);
        $bankId = $request->input('bank_id');

        $ptPackage = null;
        if ($packageId && is_numeric($packageId)) {
            $ptPackage = PtPackage::find($packageId);
        }

        $uniqueCode = 421;
        $subtotal = $ptPackage ? (float) $ptPackage->price : 3000000;
        $totalAmount = (int) $request->input('total_amount', $subtotal + $uniqueCode);

        $bank = Bank::find($bankId) ?? Bank::first();
        if (! $bank) {
            $bank = Bank::create([
                'name' => 'BCA',
                'account_number' => '8271 0812 3456 7890',
                'account_holder' => 'PT MAXFIT INDONESIA PERFORMA',
                'status' => 'active',
            ]);
        }

        // Catat pembayaran sementara status pending
        $payment = Payment::create([
            'member_id'        => $user->id,
            'membership_id'    => $user->memberships()->where('status', 'active')->latest()->first()?->id,
            'pt_package_id'    => $ptPackage?->id,
            'bank_id'          => $bank->id,
            'payment_type'     => 'pt_package',
            'amount'           => $totalAmount,
            'status'           => 'pending',
        ]);

        return redirect()->route('member.pt-packages.upload-payment', [
            'payment'    => $payment->id,
            'trainer_id' => $trainerId,
            'package_id' => $packageId,
        ])->with('success', 'Pesanan berhasil dibuat. Silakan transfer dan unggah bukti pembayaran.');
    }

    /**
     * Langkah 4: Halaman Upload Bukti Pembayaran
     */
    public function uploadPayment(Request $request, $paymentId = null): View
    {
        $payment = null;
        if ($paymentId && is_numeric($paymentId)) {
            $payment = Payment::with(['bank', 'ptPackage'])->find($paymentId);
        }

        $orderNumber = $payment ? ('#PT-' . date('Y') . '-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT)) : '#PT-2024-8849';
        $trainerId = (int) $request->query('trainer_id', 1);
        $trainers = [
            1 => 'Coach Rama Prasetya',
            2 => 'Coach Sarah Jenkins',
            3 => 'Coach Dimas Pratama',
            4 => 'Coach Maya Anindita',
        ];
        $trainerName = $trainers[$trainerId] ?? 'Coach Rama Prasetya';

        $packageName = $payment?->ptPackage?->name ?? 'Paket Performance Pro';
        $packageSubtext = '12 Sesi Pelatihan Terstruktur + Monitoring InBody Bulanan';
        $uniqueCode = 421;
        $totalAmount = $payment ? $payment->amount : 3000421;
        $packagePrice = $payment ? max(0, $payment->amount - $uniqueCode) : 3000000;

        $bank = $payment?->bank ?? (object)[
            'name' => 'BANK CENTRAL ASIA (BCA)',
            'account_number' => '8271 0812 3456 7890',
            'account_holder' => 'PT MAXFIT INDONESIA PERFORMA',
            'code' => 'BCA 014',
        ];

        return view('member.pt-upload-payment', compact(
            'payment',
            'orderNumber',
            'trainerName',
            'packageName',
            'packageSubtext',
            'packagePrice',
            'uniqueCode',
            'totalAmount',
            'bank'
        ));
    }

    /**
     * Proses kirim bukti pembayaran (Langkah 4)
     */
    public function submitPaymentProof(Request $request, $paymentId = null): RedirectResponse
    {
        $request->validate([
            'proof_image' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_bank' => ['required', 'string', 'max:255'],
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');
        }

        if ($paymentId && is_numeric($paymentId)) {
            $payment = Payment::find($paymentId);
            if ($payment) {
                if ($proofPath) {
                    $payment->update(['proof_image_path' => $proofPath]);
                }
            }
        }

        return redirect()->route('member.pt-packages.index')
            ->with('success', 'Bukti pembayaran berhasil diunggah! Tim finance Maxfit akan memverifikasi dalam 1×24 jam.');
    }

    /**
     * Process the purchase of a PT package.
     * Validates minimum membership days requirement and records payment + quota.
     */
    public function buy(BuyPtPackageRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // ── 1. Validate PT Package Exists & Active ────────────────────────────
        $ptPackage = PtPackage::where('id', $validated['pt_package_id'])
            ->where('status', 'active')
            ->first();

        if (! $ptPackage) {
            $msg = 'Paket PT yang dipilih tidak ditemukan atau sedang tidak aktif.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 404);
            }
            return back()->with('error', $msg);
        }

        // ── 2. Validate Bank Exists & Active ──────────────────────────────────
        $bank = Bank::where('id', $validated['bank_id'])
            ->where('status', 'active')
            ->first();

        if (! $bank) {
            $msg = 'Bank tujuan pembayaran tidak aktif.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        // ── 3. Validate Minimum Membership Days (Aturan Bisnis Project) ────────
        $today = now('Asia/Jakarta')->startOfDay();

        $activeMembership = $user->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->where(function ($q) {
                $q->whereDoesntHave('payments')
                    ->orWhereHas('payments', fn ($p) => $p->where('status', 'verified'));
            })
            ->latest('end_date')
            ->first();

        $remainingDays = 0;
        if ($activeMembership) {
            $endDate = Carbon::parse($activeMembership->end_date)->startOfDay();
            $remainingDays = (int) $today->diffInDays($endDate, false);
        }

        $minDays = (int) $ptPackage->min_membership_days;

        if ($minDays > 0) {
            if (! $activeMembership) {
                $message = "Pembelian gagal. Anda belum memiliki membership aktif. Paket {$ptPackage->name} memerlukan sisa masa aktif membership minimal {$minDays} hari.";
                if ($request->expectsJson()) {
                    return response()->json([
                        'success'        => false,
                        'message'        => $message,
                        'required_days'  => $minDays,
                        'remaining_days' => 0,
                    ], 422);
                }
                return back()->with('error', $message);
            }

            if ($remainingDays < $minDays) {
                $message = "Pembelian gagal. Sisa masa aktif membership Anda ({$remainingDays} hari) kurang dari syarat minimum ({$minDays} hari) untuk membeli paket {$ptPackage->name}.";
                if ($request->expectsJson()) {
                    return response()->json([
                        'success'        => false,
                        'message'        => $message,
                        'required_days'  => $minDays,
                        'remaining_days' => $remainingDays,
                    ], 422);
                }
                return back()->with('error', $message);
            }
        }

        // ── 4. Upload Payment Proof if provided ──────────────────────────────
        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');
        }

        // ── 5. Save Data Pembelian & Hubungkan Entitas Terkait ─────────────────
        [$payment, $quota] = DB::transaction(function () use (
            $user,
            $activeMembership,
            $ptPackage,
            $bank,
            $today,
            $proofPath
        ) {
            // Catat pembayaran
            $payment = Payment::create([
                'member_id'        => $user->id,
                'membership_id'    => $activeMembership?->id,
                'pt_package_id'    => $ptPackage->id,
                'bank_id'          => $bank->id,
                'payment_type'     => 'pt_package',
                'amount'           => $ptPackage->price,
                'proof_image_path' => $proofPath,
                'status'           => 'pending',
            ]);

            // Buat kuota sesi PT
            $quota = MemberPtQuota::create([
                'member_id'          => $user->id,
                'membership_id'      => $activeMembership?->id,
                'pt_package_id'      => $ptPackage->id,
                'source'             => 'purchase',
                'total_sessions'     => $ptPackage->pt_session_count,
                'used_sessions'      => 0,
                'remaining_sessions' => $ptPackage->pt_session_count,
                'start_date'         => $today->toDateString(),
                'end_date'           => $today->copy()->addDays($ptPackage->validity_days)->toDateString(),
                'status'             => 'active',
            ]);

            return [$payment, $quota];
        });

        // ── 6. Return Clear Success Response ──────────────────────────────────
        $successMessage = "Pembelian paket {$ptPackage->name} berhasil! Kuota {$ptPackage->pt_session_count} sesi PT telah ditambahkan.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'payment' => $payment->load(['bank', 'ptPackage']),
                'quota'   => $quota->load('ptPackage'),
            ], 201);
        }

        return back()->with('success', $successMessage);
    }
}
