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

        return view('member.pt-packages', compact(
            'packages',
            'allPackages',
            'hasHiddenPackages',
            'banks',
            'activeMembership',
            'remainingDays',
            'myQuotas'
        ));
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
