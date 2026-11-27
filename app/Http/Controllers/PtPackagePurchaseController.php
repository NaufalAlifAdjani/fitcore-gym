<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyPtPackageRequest;
use App\Models\Bank;
use App\Models\MemberPtQuota;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use App\Notifications\AdminNewPaymentNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
                $q->whereDoesntHave('payments', function ($sub) {
                    $sub->where(function ($typeQ) {
                        $typeQ->where('payment_type', 'membership')
                            ->orWhere('package_type', 'membership');
                    });
                })
                    ->orWhereHas('payments', function ($sub) {
                        $sub->where(function ($typeQ) {
                            $typeQ->where('payment_type', 'membership')
                                ->orWhere('package_type', 'membership');
                        })->where('status', 'verified');
                    });
            })
            ->with('package')
            ->latest('end_date')
            ->first();

        $remainingDays = 0;
        $maxEndDate = null;
        if ($activeMembership) {
            $maxEndDate = $user->memberships()
                ->where('status', 'active')
                ->where('end_date', '>=', $today->toDateString())
                ->max('end_date') ?? $activeMembership->end_date;
            $endDate = Carbon::parse($maxEndDate)->startOfDay();
            $remainingDays = max(0, (int) $today->diffInDays($endDate, false) + 1);
        }

        // ── 2. Fetch Active PT Quota with remaining sessions (for dynamic stacking projection) ───
        $latestActivePtQuota = $user->ptQuotas()
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', $today->toDateString())
            ->latest('end_date')
            ->first();

        $membershipEndDateCarbon = $maxEndDate ? Carbon::parse($maxEndDate)->startOfDay() : null;

        // ── 3. Fetch Active PT Quota Remaining Sessions (Batas Solusi A: Maksimal <= 2 Sesi) ───
        $activeRemainingSessions = (int) $user->ptQuotas()
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', $today->toDateString())
            ->sum('remaining_sessions');

        $isQuotaThresholdBlocked = $activeRemainingSessions > 2;

        // ── 4. Fetch Active PT Packages & Evaluate Eligibility by Min Membership Days & Quota Threshold ───
        $allPackages = PtPackage::where('status', 'active')
            ->orderBy('pt_session_count')
            ->get();

        // Tampilkan semua paket dan tandai status kelayakannya secara dinamis
        $packages = $allPackages->map(function ($pkg) use ($activeMembership, $remainingDays, $isQuotaThresholdBlocked, $activeRemainingSessions) {
            $minDays = (int) ($pkg->validity_days ?? 30);
            $validityDays = $minDays;

            $isMinDaysSatisfied = ($minDays === 0 && $activeMembership) || ($activeMembership && $remainingDays >= $minDays);
            $isEligible = $isMinDaysSatisfied && ! $isQuotaThresholdBlocked;

            $disabledLabel = ! $activeMembership
                ? 'Wajib Membership Aktif'
                : ($isQuotaThresholdBlocked
                    ? "Sisa Kuota Masih {$activeRemainingSessions} Sesi"
                    : ($minDays > 0 ? "Min. Membership {$minDays} Hari" : ''));

            $disabledTitle = ! $activeMembership
                ? 'Anda wajib memiliki membership aktif untuk membeli paket personal trainer.'
                : ($isQuotaThresholdBlocked
                    ? "Pembelian paket PT baru hanya dapat dilakukan saat sisa kuota Anda maksimal 2 sesi (Sisa kuota aktif saat ini: {$activeRemainingSessions} sesi)."
                    : ($minDays > 0 ? "Masa aktif membership Anda ({$remainingDays} hari) kurang dari syarat minimum ({$minDays} hari)." : ''));

            $pkg->min_days = $minDays;
            $pkg->validity_days = $validityDays;
            $pkg->disabled_label = $disabledLabel;
            $pkg->disabled_title = $disabledTitle;
            $pkg->is_membership_extension_needed = false;
            $pkg->is_eligible = (bool) $isEligible;

            return $pkg;
        });

        $eligiblePackagesCount = $packages->where('is_eligible', true)->count();
        $hasHiddenPackages = false;
        $noEligiblePackages = $packages->isNotEmpty() && $eligiblePackagesCount === 0;

        $banks = Bank::where('status', 'active')
            ->orderBy('name')
            ->get();

        // ── 5. Fetch Member's Existing Quotas ────────────────────────────────
        $myQuotas = $user->ptQuotas()
            ->with(['ptPackage', 'trainer.trainerProfile'])
            ->latest()
            ->get();

        $currentTrainer = $user->getCurrentPtTrainer();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'active_membership' => $activeMembership,
                'remaining_days' => $remainingDays,
                'packages' => $packages,
                'has_hidden_packages' => $hasHiddenPackages,
                'no_eligible_packages' => $noEligiblePackages,
                'banks' => $banks,
                'my_quotas' => $myQuotas,
                'active_remaining_sessions' => $activeRemainingSessions,
                'is_quota_threshold_blocked' => $isQuotaThresholdBlocked,
                'current_trainer' => $currentTrainer ? [
                    'id' => $currentTrainer->id,
                    'name' => $currentTrainer->name,
                    'specialization' => $currentTrainer->trainerProfile?->specialization ?? 'Personal Trainer',
                    'photo' => $currentTrainer->avatar_url,
                ] : null,
            ]);
        }

        // Data default 8 paket sesuai Figma
        $defaultPackages = [
            ['id' => 'pkg-4', 'name' => 'PT 4 Sesi', 'sessions' => 4, 'price' => 1155000, 'original_price' => 1443750, 'per_session' => 288750, 'is_popular' => false, 'min_days' => 0, 'is_eligible' => true],
            ['id' => 'pkg-8', 'name' => 'PT 8 Sesi', 'sessions' => 8, 'price' => 2072700, 'original_price' => 2590875, 'per_session' => 259088, 'is_popular' => false, 'min_days' => 0, 'is_eligible' => true],
            ['id' => 'pkg-16', 'name' => 'PT 16 Sesi', 'sessions' => 16, 'price' => 3582000, 'original_price' => 4515000, 'per_session' => 223875, 'is_popular' => true, 'min_days' => 0, 'is_eligible' => true],
            ['id' => 'pkg-24', 'name' => 'PT 24 Sesi', 'sessions' => 24, 'price' => 5040000, 'original_price' => 6300000, 'per_session' => 210000, 'is_popular' => false, 'min_days' => 0, 'is_eligible' => true],
            ['id' => 'pkg-36', 'name' => 'PT 36 Sesi', 'sessions' => 36, 'price' => 6804000, 'original_price' => 8505000, 'per_session' => 189000, 'is_popular' => false, 'min_days' => 0, 'is_eligible' => true],
            ['id' => 'pkg-48', 'name' => 'PT 48 Sesi', 'sessions' => 48, 'price' => 8316000, 'original_price' => 10395000, 'per_session' => 173250, 'is_popular' => false, 'min_days' => 0, 'is_eligible' => true],
            ['id' => 'pkg-72', 'name' => 'PT 72 Sesi', 'sessions' => 72, 'price' => 12168000, 'original_price' => 15210000, 'per_session' => 169000, 'is_popular' => false, 'min_days' => 0, 'is_eligible' => true],
            ['id' => 'pkg-96', 'name' => 'PT 96 Sesi', 'sessions' => 96, 'price' => 15840000, 'original_price' => 19800000, 'per_session' => 165000, 'is_popular' => false, 'min_days' => 0, 'is_eligible' => true],
        ];

        return view('member.pt-packages', compact(
            'packages',
            'allPackages',
            'hasHiddenPackages',
            'noEligiblePackages',
            'banks',
            'activeMembership',
            'remainingDays',
            'myQuotas',
            'defaultPackages',
            'currentTrainer',
            'activeRemainingSessions',
            'isQuotaThresholdBlocked'
        ));
    }

    /**
     * Langkah 2: Pilih Personal Trainer
     */
    public function selectTrainer(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $packageId = $request->query('package_id');
        $sessionCount = (int) $request->query('sessions', 12);

        // Batas Solusi A: Kuota aktif harus <= 2 sesi
        $activeRemainingSessions = (int) $user->ptQuotas()
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', now('Asia/Jakarta')->startOfDay()->toDateString())
            ->sum('remaining_sessions');

        if ($activeRemainingSessions > 2) {
            $targetRoute = ($request->is('m/*') || $request->routeIs('mobile.*'))
                ? 'mobile.member.pt-packages.index'
                : 'member.pt-packages.index';

            return redirect()->route($targetRoute)
                ->with('error', "Pembelian paket PT baru hanya dapat dilakukan saat sisa kuota Anda maksimal 2 sesi. Anda saat ini masih memiliki {$activeRemainingSessions} sesi aktif.");
        }

        $selectedPackage = null;
        if ($packageId && is_numeric($packageId)) {
            $selectedPackage = PtPackage::find($packageId);
            if ($selectedPackage) {
                if ($selectedPackage->status !== 'active') {
                    return redirect()->route('member.pt-packages.index')
                        ->with('error', 'Paket PT yang dipilih sedang tidak aktif dan tidak dapat dipilih.');
                }

                $eligibilityError = $this->validatePtPackageEligibility($request->user(), $selectedPackage);
                if ($eligibilityError) {
                    $targetRoute = ($request->is('m/*') || $request->routeIs('mobile.*'))
                        ? 'mobile.member.pt-packages.index'
                        : 'member.pt-packages.index';

                    return redirect()->route($targetRoute)->with('error', $eligibilityError);
                }
            }
        }

        // Ambil daftar pelatih aktif langsung dari database
        $dbTrainers = User::where('role', 'trainer')
            ->where('status', 'active')
            ->whereHas('trainerProfile')
            ->with(['trainerProfile'])
            ->get();

        if ($dbTrainers->isEmpty()) {
            $trainers = [
                ['id' => 1, 'name' => 'Coach Rama Prasetya', 'title' => 'Coach Rama Prasetya, CSCS', 'specialization' => 'Hypertrophy & Strength Conditioning', 'photo' => asset('images/trainers/coach-rama.jpg'), 'is_active_highlight' => true, 'days_label' => 'Senin – Sabtu', 'periods' => ['morning', 'afternoon', 'evening'], 'periods_label' => 'Pagi – Malam', 'experience' => 8, 'bio' => 'Head Coach dengan spesialisasi hipertrofi dan kekuatan atletik tingkat lanjut.'],
                ['id' => 2, 'name' => 'Coach Sarah Jenkins', 'title' => 'Coach Sarah Jenkins, CPT', 'specialization' => 'Fat Loss & Functional Mobility', 'photo' => asset('images/trainers/coach-sarah.jpg'), 'is_active_highlight' => false, 'days_label' => 'Senin – Sabtu', 'periods' => ['morning', 'afternoon'], 'periods_label' => 'Pagi – Siang', 'experience' => 5, 'bio' => 'Spesialis penurunan lemak tubuh, mobilitas fungsional, dan program pasca cedera.'],
                ['id' => 3, 'name' => 'Coach Dimas Pratama', 'title' => 'Coach Dimas Pratama, CSCS', 'specialization' => 'Powerlifting & Athletic Performance', 'photo' => asset('images/trainers/coach-dimas.jpg'), 'is_active_highlight' => false, 'days_label' => 'Senin – Sabtu', 'periods' => ['afternoon', 'evening'], 'periods_label' => 'Siang – Malam', 'experience' => 6, 'bio' => 'Fokus pada teknik powerlifting presisi, performa daya ledak, dan kekuatan maksimal.'],
                ['id' => 4, 'name' => 'Coach Maya Anindita', 'title' => 'Coach Maya Anindita, RYT', 'specialization' => 'Calisthenics, Yoga & Flexibility', 'photo' => asset('images/trainers/coach-maya.jpg'), 'is_active_highlight' => false, 'days_label' => 'Senin – Sabtu', 'periods' => ['morning', 'afternoon', 'evening'], 'periods_label' => 'Pagi – Malam', 'experience' => 4, 'bio' => 'Pendampingan fleksibilitas, calisthenics, keseimbangan tubuh, dan teknik yoga dinamis.'],
            ];
        } else {
            $trainers = $dbTrainers->map(function ($trainer, $index) {
                $cFirstName = strtolower(explode(' ', trim(str_replace('Coach ', '', $trainer->name)))[0]);
                $cPhoto = file_exists(public_path("images/trainers/coach-{$cFirstName}.jpg"))
                    ? asset("images/trainers/coach-{$cFirstName}.jpg")
                    : ($trainer->trainerProfile?->photo_path ? asset('storage/'.$trainer->trainerProfile->photo_path) : ($trainer->avatar_url ?? null));

                $summary = $trainer->getWorkingScheduleSummary();

                return [
                    'id' => $trainer->id,
                    'name' => $trainer->name,
                    'title' => $trainer->name,
                    'specialization' => $trainer->trainerProfile?->specialization ?? 'Personal Trainer',
                    'photo' => $cPhoto,
                    'is_active_highlight' => $index === 0,
                    'days_label' => $summary['days_label'],
                    'periods' => $summary['periods'],
                    'periods_label' => $summary['periods_label'],
                    'experience' => $trainer->trainerProfile?->experience ?? 1,
                    'bio' => $trainer->trainerProfile?->bio ?? 'Berpengalaman mendampingi member mencapai target kebugaran dan kekuatan atletik secara optimal.',
                ];
            })->values()->all();
        }

        $currentTrainer = $request->user()?->getCurrentPtTrainer();

        return view('member.pt-select-trainer', compact('trainers', 'packageId', 'sessionCount', 'selectedPackage', 'currentTrainer'));
    }

    /**
     * Langkah 3: Rincian Pesanan & Pembayaran (Checkout)
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $packageId = $request->query('package_id');
        $trainerId = (int) $request->query('trainer_id');
        $currentTrainer = $user?->getCurrentPtTrainer();

        // Batas Solusi A: Kuota aktif harus <= 2 sesi
        $activeRemainingSessions = (int) $user->ptQuotas()
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', now('Asia/Jakarta')->startOfDay()->toDateString())
            ->sum('remaining_sessions');

        if ($activeRemainingSessions > 2) {
            $targetRoute = ($request->is('m/*') || $request->routeIs('mobile.*'))
                ? 'mobile.member.pt-packages.index'
                : 'member.pt-packages.index';

            return redirect()->route($targetRoute)
                ->with('error', "Pembelian paket PT baru hanya dapat dilakukan saat sisa kuota Anda maksimal 2 sesi. Anda saat ini masih memiliki {$activeRemainingSessions} sesi aktif.");
        }

        // Jika trainer_id tidak disertakan tetapi member sudah punya trainer, gunakan currentTrainer
        if ($trainerId <= 0 && $currentTrainer) {
            $trainerId = $currentTrainer->id;
        }

        $trainer = null;
        if ($trainerId > 0) {
            $trainer = User::where('role', 'trainer')->where('id', $trainerId)->first();
        }

        if (! $trainer) {
            $trainer = $currentTrainer
                ?? User::where('role', 'trainer')->where('email', 'rama@gym.test')->first()
                ?? User::where('role', 'trainer')->first();
        }
        $selectedTrainerName = $trainer?->name ?? 'Coach Rama Prasetya';
        $trainerId = $trainer?->id ?? 1;

        $selectedPackage = null;
        if ($packageId && is_numeric($packageId)) {
            $selectedPackage = PtPackage::find($packageId);
            if ($selectedPackage) {
                if ($selectedPackage->status !== 'active') {
                    return redirect()->route('member.pt-packages.index')
                        ->with('error', 'Paket PT yang dipilih sedang tidak aktif dan tidak dapat dibeli.');
                }

                $eligibilityError = $this->validatePtPackageEligibility($request->user(), $selectedPackage);
                if ($eligibilityError) {
                    $targetRoute = ($request->is('m/*') || $request->routeIs('mobile.*'))
                        ? 'mobile.member.pt-packages.index'
                        : 'member.pt-packages.index';

                    return redirect()->route($targetRoute)->with('error', $eligibilityError);
                }
            }
        }

        // Nilai default sesuai Figma
        $packageName = $selectedPackage?->name ?? 'Paket Performance Pro (12 Sesi)';
        $sessionCount = $selectedPackage?->pt_session_count ?? 12;
        $subtotal = $selectedPackage ? (float) $selectedPackage->price : 3000000;

        $uniqueCode = random_int(100, 999);
        $totalAmount = (int) ($subtotal + $uniqueCode);

        $banks = Bank::where('status', 'active')->orderBy('id')->get();
        if ($banks->isEmpty()) {
            $banks = collect([
                (object) ['id' => 1, 'name' => 'Bank BCA', 'account_number' => '8271 0812 3456 7890', 'account_holder' => 'PT MAXFIT INDONESIA PERFORMA'],
                (object) ['id' => 2, 'name' => 'Bank Mandiri', 'account_number' => '1370 0192 8374 6500', 'account_holder' => 'PT MAXFIT INDONESIA PERFORMA'],
                (object) ['id' => 3, 'name' => 'Bank BNI', 'account_number' => '0912 3847 5610 2938', 'account_holder' => 'PT MAXFIT INDONESIA PERFORMA'],
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
            'banks',
            'currentTrainer'
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

        // Batas Solusi A: Kuota aktif harus <= 2 sesi
        $activeRemainingSessions = (int) $user->ptQuotas()
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', now('Asia/Jakarta')->startOfDay()->toDateString())
            ->sum('remaining_sessions');

        if ($activeRemainingSessions > 2) {
            $targetRoute = ($request->routeIs('mobile.*') || str_contains(url()->previous(), '/m/'))
                ? 'mobile.member.pt-packages.index'
                : 'member.pt-packages.index';

            return redirect()->route($targetRoute)
                ->with('error', "Pembelian paket PT baru hanya dapat dilakukan saat sisa kuota Anda maksimal 2 sesi. Anda saat ini masih memiliki {$activeRemainingSessions} sesi aktif.");
        }

        $packageId = $request->input('package_id');
        $trainerInputId = (int) $request->input('trainer_id', 1);
        $bankId = $request->input('bank_id');

        $selectedTrainer = User::where('role', 'trainer')->where('id', $trainerInputId)->first()
            ?? User::where('role', 'trainer')->where('email', 'rama@gym.test')->first()
            ?? User::where('role', 'trainer')->first();
        $trainerId = $selectedTrainer?->id ?? $trainerInputId;

        $ptPackage = null;
        if ($packageId && is_numeric($packageId)) {
            $ptPackage = PtPackage::find($packageId);
            if ($ptPackage) {
                if ($ptPackage->status !== 'active') {
                    return redirect()->route('member.pt-packages.index')
                        ->with('error', 'Paket PT yang dipilih sedang tidak aktif dan tidak dapat dibeli.');
                }

                $eligibilityError = $this->validatePtPackageEligibility($user, $ptPackage);
                if ($eligibilityError) {
                    $targetRoute = ($request->routeIs('mobile.*') || str_contains(url()->previous(), '/m/'))
                        ? 'mobile.member.pt-packages.index'
                        : 'member.pt-packages.index';

                    return redirect()->route($targetRoute)->with('error', $eligibilityError);
                }
            }
        }

        $uniqueCode = (int) $request->input('unique_code', 0);
        if ($uniqueCode <= 0) {
            $uniqueCode = random_int(100, 999);
        }
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

        do {
            $invoiceId = 'INV-PT-'.strtoupper(Str::random(8));
        } while (Payment::where('invoice_id', $invoiceId)->exists());

        $bankDestination = "{$bank->name} ({$bank->account_number} a.n. {$bank->account_holder})";

        // Catat pembayaran sementara status pending
        $payment = Payment::create([
            'invoice_id' => $invoiceId,
            'member_id' => $user->id,
            'trainer_id' => $trainerId,
            'membership_id' => $user->memberships()->where('status', 'active')->latest()->first()?->id,
            'package_id' => $ptPackage?->id,
            'pt_package_id' => $ptPackage?->id,
            'package_type' => 'pt_session',
            'payment_type' => 'pt_package',
            'bank_id' => $bank->id,
            'bank_destination' => $bankDestination,
            'amount' => $totalAmount,
            'status' => 'pending',
        ]);

        $targetRoute = ($request->routeIs('mobile.*') || str_contains(url()->previous(), '/m/'))
            ? 'mobile.member.pt-packages.upload-payment'
            : 'member.pt-packages.upload-payment';

        return redirect()->route($targetRoute, [
            'payment' => $payment->id,
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

        $orderNumber = $payment?->invoice_id ?? ($payment ? ('#PT-'.date('Y').'-'.str_pad($payment->id, 4, '0', STR_PAD_LEFT)) : '#PT-2024-8849');
        $trainerId = (int) $request->query('trainer_id', $payment?->trainer_id ?? 0);
        $trainer = User::where('role', 'trainer')->where('id', $trainerId)->first()
            ?? ($payment?->trainer_id ? User::find($payment->trainer_id) : null)
            ?? User::where('role', 'trainer')->where('email', 'rama@gym.test')->first()
            ?? User::where('role', 'trainer')->first();
        $trainerName = $trainer?->name ?? 'Coach Rama Prasetya';

        $packageName = $payment?->ptPackage?->name ?? 'Paket Performance Pro';
        $packageSubtext = '12 Sesi Pelatihan Terstruktur + Monitoring InBody Bulanan';
        $uniqueCode = (int) $request->query('unique_code', 0);
        if ($payment) {
            $basePrice = $payment->ptPackage ? (float) $payment->ptPackage->price : 3000000;
            $uniqueCode = max(0, (int) ($payment->amount - $basePrice));
        }
        if ($uniqueCode <= 0) {
            $uniqueCode = random_int(100, 999);
        }
        $totalAmount = $payment ? $payment->amount : (3000000 + $uniqueCode);
        $packagePrice = $payment ? max(0, $payment->amount - $uniqueCode) : 3000000;

        $bank = $payment?->bank ?? (object) [
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
            'proof_image' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'sender_name' => ['required', 'string', 'max:255'],
            'sender_bank' => ['nullable', 'string', 'max:255'],
        ], [
            'proof_image.required' => 'Foto atau berkas bukti transfer wajib diunggah.',
            'proof_image.image' => 'Berkas bukti pembayaran harus berupa gambar.',
            'proof_image.mimes' => 'Format berkas bukti pembayaran harus berupa JPG, JPEG, PNG, atau WEBP.',
            'proof_image.max' => 'Ukuran berkas bukti pembayaran maksimal 2 MB.',
            'sender_name.required' => 'Nama pengirim / pemilik rekening wajib diisi.',
        ]);

        $file = $request->file('proof_image');
        $proofPath = $file ? $file->store('payment-proofs', 'public') : null;
        $proofUrl = $proofPath ? Storage::url($proofPath) : null;
        $bankSender = $request->filled('sender_bank')
            ? $request->input('sender_name').' ('.$request->input('sender_bank').')'
            : $request->input('sender_name');

        $payment = null;
        if ($paymentId && is_numeric($paymentId)) {
            $payment = Payment::find($paymentId);
            if ($payment) {
                $payment->update([
                    'proof_image_path' => $proofPath,
                    'proof_image_url' => $proofUrl,
                    'bank_sender' => $bankSender,
                    'transfer_date' => now(),
                ]);

                $admins = User::where('role', 'admin')->get();
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new AdminNewPaymentNotification($payment));
                }
            }
        }

        $isMobile = ($request->routeIs('mobile.*') || str_contains(url()->previous(), '/m/'));

        if ($payment) {
            $targetRoute = $isMobile
                ? 'mobile.member.packages.payment-status'
                : 'member.membership.payment-status';

            return redirect()->route($targetRoute, $payment->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah! Tim finance akan memverifikasi dalam 1×24 jam.');
        }

        $fallbackRoute = $isMobile
            ? 'mobile.member.pt-packages.index'
            : 'member.pt-packages.index';

        return redirect()->route($fallbackRoute)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Tim finance akan memverifikasi dalam 1×24 jam.');
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

        // ── 2b. Validate PT Eligibility (Maksimal sisa kuota <= 2 sesi) ───────
        $today = now('Asia/Jakarta')->startOfDay();
        $activeRemainingSessions = (int) $user->ptQuotas()
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', $today->toDateString())
            ->sum('remaining_sessions');

        if ($activeRemainingSessions > 2) {
            $msg = "Pembelian paket PT baru hanya dapat dilakukan saat sisa kuota Anda maksimal 2 sesi. Anda saat ini masih memiliki {$activeRemainingSessions} sesi aktif.";
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 422);
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
                $q->whereDoesntHave('payments', function ($sub) {
                    $sub->where(function ($typeQ) {
                        $typeQ->where('payment_type', 'membership')
                            ->orWhere('package_type', 'membership');
                    });
                })
                    ->orWhereHas('payments', function ($sub) {
                        $sub->where(function ($typeQ) {
                            $typeQ->where('payment_type', 'membership')
                                ->orWhere('package_type', 'membership');
                        })->where('status', 'verified');
                    });
            })
            ->latest('end_date')
            ->first();

        $remainingDays = 0;
        if ($activeMembership) {
            $maxEndDate = $user->memberships()
                ->where('status', 'active')
                ->where('end_date', '>=', $today->toDateString())
                ->max('end_date') ?? $activeMembership->end_date;
            $endDate = Carbon::parse($maxEndDate)->startOfDay();
            $remainingDays = max(0, (int) $today->diffInDays($endDate, false) + 1);
        }

        $minDays = (int) ($ptPackage->validity_days ?? 30);

        if ($minDays > 0) {
            if (! $activeMembership) {
                $message = "Pembelian gagal. Anda belum memiliki membership aktif. Paket {$ptPackage->name} memerlukan sisa masa aktif membership minimal {$minDays} hari.";
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'required_days' => $minDays,
                        'remaining_days' => 0,
                    ], 422);
                }

                return back()->with('error', $message);
            }

            if ($remainingDays < $minDays) {
                $message = "Pembelian gagal. Sisa masa aktif membership Anda ({$remainingDays} hari) kurang dari syarat minimum ({$minDays} hari) untuk membeli paket {$ptPackage->name}.";
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'required_days' => $minDays,
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
            do {
                $invoiceId = 'INV-PT-'.strtoupper(Str::random(8));
            } while (Payment::where('invoice_id', $invoiceId)->exists());

            $bankDestination = "{$bank->name} ({$bank->account_number} a.n. {$bank->account_holder})";
            $proofUrl = $proofPath ? Storage::url($proofPath) : null;

            // Catat pembayaran
            $payment = Payment::create([
                'invoice_id' => $invoiceId,
                'member_id' => $user->id,
                'trainer_id' => $validated['trainer_id'] ?? User::where('role', 'trainer')->first()?->id,
                'membership_id' => $activeMembership?->id,
                'package_id' => $ptPackage->id,
                'pt_package_id' => $ptPackage->id,
                'package_type' => 'pt_session',
                'payment_type' => 'pt_package',
                'bank_id' => $bank->id,
                'bank_destination' => $bankDestination,
                'bank_sender' => $user->name,
                'amount' => $ptPackage->price,
                'proof_image_path' => $proofPath,
                'proof_image_url' => $proofUrl,
                'transfer_date' => now(),
                'status' => 'pending',
            ]);

            // Buat kuota sesi PT
            $quota = MemberPtQuota::create([
                'member_id' => $user->id,
                'membership_id' => $activeMembership?->id,
                'pt_package_id' => $ptPackage->id,
                'trainer_id' => $payment->trainer_id,
                'source' => 'purchase',
                'total_sessions' => $ptPackage->pt_session_count,
                'used_sessions' => 0,
                'remaining_sessions' => $ptPackage->pt_session_count,
                'start_date' => $today->toDateString(),
                'end_date' => $today->copy()->addDays($ptPackage->validity_days)->toDateString(),
                'status' => 'active',
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
                'quota' => $quota->load('ptPackage'),
            ], 201);
        }

        return back()->with('success', $successMessage);
    }

    /**
     * Memvalidasi apakah member memenuhi syarat masa aktif membership (validity_days) dan batas kuota sesi PT (Solusi A: Sisa <= 2 Sesi).
     */
    private function validatePtPackageEligibility(User $user, PtPackage $package): ?string
    {
        $today = now('Asia/Jakarta')->startOfDay();

        // 1. Validasi Batas Kuota Sesi Aktif (Solusi A: Pembelian baru hanya diperbolehkan jika sisa kuota <= 2 sesi)
        $activeRemainingSessions = (int) $user->ptQuotas()
            ->where('status', 'active')
            ->where('remaining_sessions', '>', 0)
            ->whereDate('end_date', '>=', $today->toDateString())
            ->sum('remaining_sessions');

        if ($activeRemainingSessions > 2) {
            return "Pembelian paket PT baru hanya dapat dilakukan saat sisa kuota Anda maksimal 2 sesi. Anda saat ini masih memiliki {$activeRemainingSessions} sesi aktif.";
        }

        $activeMembership = $user->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->latest('end_date')
            ->first();

        $maxEndDate = $user->memberships()
            ->where('status', 'active')
            ->where('end_date', '>=', $today->toDateString())
            ->max('end_date') ?? $activeMembership?->end_date;

        $remainingDays = $activeMembership ? max(0, (int) $today->diffInDays(Carbon::parse($maxEndDate)->startOfDay(), false) + 1) : 0;
        $minDays = (int) ($package->validity_days ?? 30);

        if (! $activeMembership) {
            return 'Anda wajib memiliki membership aktif untuk mengambil paket personal trainer.';
        }

        if ($minDays > 0 && $remainingDays < $minDays) {
            return "Masa aktif membership Anda ({$remainingDays} hari) tidak mencukupi untuk paket ini (Min. {$minDays} hari).";
        }

        return null;
    }
}
