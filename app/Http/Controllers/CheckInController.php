<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckInRequest;
use App\Models\CheckIn;
use App\Models\QrToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckInController extends Controller
{
    /** Minimum minutes between successive check-ins for the same member. */
    private const COOLDOWN_MINUTES = 10;

    /**
     * Display the QR scanner page (admin/staff only — enforced by route middleware).
     */
    public function index(): View
    {
        $today = now('Asia/Jakarta')->toDateString();

        $todayCheckIns = CheckIn::with([
            'member:id,name,email',
            'member.memberProfile:id,user_id,member_code',
            'membership.package:id,name',
            'checkedBy:id,name',
        ])
            ->whereDate('check_in_date', $today)
            ->latest('checked_in_at')
            ->get();

        return view('admin.checkin', compact('todayCheckIns'));
    }

    /**
     * Process a QR code check-in request.
     * All validation logic is performed on the backend.
     * Returns a structured JSON response for the frontend scanner.
     */
    public function store(CheckInRequest $request): JsonResponse
    {
        $qrTokenStr = $request->validated()['qr_token'];

        // ── A. Validate Temporary QR Token (atomic) ──────────────────────────
        // Use a DB transaction with a row-level lock to prevent race conditions
        // where two simultaneous requests both see the token as unused.
        $result = DB::transaction(function () use ($qrTokenStr, $request) {
            /** @var QrToken|null $qrToken */
            $qrToken = QrToken::where('token', $qrTokenStr)->lockForUpdate()->first();

            if (! $qrToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau tidak terdaftar.',
                ], 404);
            }

            if ($qrToken->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code sudah kedaluwarsa. Minta member untuk menampilkan QR baru.',
                ], 422);
            }

            if ($qrToken->isUsed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code sudah digunakan. Minta member untuk menampilkan QR baru.',
                ], 422);
            }

            // ── B. Load member & validate active membership ──────────────────
            $member = $qrToken->member()->with([
                'memberProfile',
                'memberships.package',
                'memberships.payment',
                'memberships.payments',
            ])->first();

            $today = now('Asia/Jakarta');

            $activeMembership = $member->memberships()
                ->where('status', 'active')
                ->where('start_date', '<=', $today->toDateString())
                ->where('end_date', '>=', $today->toDateString())
                ->where(function ($query) {
                    $query->whereHas('payment', fn ($q) => $q->where('status', 'verified'))
                        ->orWhereHas('payments', fn ($q) => $q->where('status', 'verified'));
                })
                ->first();

            if (! $activeMembership) {
                $anyMembership = $member->memberships()->latest()->first();

                if (! $anyMembership) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Member belum memiliki membership aktif.',
                    ], 422);
                }

                if ($anyMembership->status === 'expired' || $today->toDateString() > $anyMembership->end_date->toDateString()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Membership member sudah berakhir.',
                    ], 422);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Membership member belum aktif atau pembayaran belum diverifikasi.',
                ], 422);
            }

            // ── C. Cooldown check (10-minute minimum between check-ins) ───────
            $lastCheckIn = CheckIn::where('member_id', $member->id)
                ->latest('checked_in_at')
                ->first();

            if ($lastCheckIn) {
                $minutesSinceLast = (int) $lastCheckIn->checked_in_at->diffInMinutes($today);

                if ($minutesSinceLast < self::COOLDOWN_MINUTES) {
                    $waitMinutes = self::COOLDOWN_MINUTES - $minutesSinceLast;

                    return response()->json([
                        'success' => false,
                        'message' => "Member baru saja check-in. Mohon tunggu {$waitMinutes} menit lagi sebelum check-in berikutnya.",
                    ], 429);
                }
            }

            // ── D. Mark token as used (inside the same transaction + lock) ───
            $qrToken->update(['used_at' => $today]);

            // ── E. Save check-in record ───────────────────────────────────────
            $memberProfile = $member->memberProfile;
            $todayDate = $today->toDateString();

            $checkIn = CheckIn::create([
                'member_id' => $member->id,
                'membership_id' => $activeMembership->id,
                'check_in_date' => $todayDate,
                'checked_in_at' => $today,
                'checked_out_at' => null,
                'method' => 'qr_code',
                'checked_by' => $request->user()->id,
            ]);

            // ── F. Build success response ─────────────────────────────────────
            $memberCode = $memberProfile->member_code ?? ('MBR-'.str_pad((string) $member->id, 5, '0', STR_PAD_LEFT));
            $operatorName = $request->user()?->name ?? 'Admin';
            $packageName = $activeMembership->package->name ?? 'Membership FitCore';
            $validUntil = $activeMembership->end_date->locale('id')->translatedFormat('d F Y');
            $checkedInTime = $today->format('H:i');

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil!',
                'member_name' => $member->name,
                'member_code' => $memberCode,
                'membership' => $packageName,
                'valid_until' => $validUntil,
                'checked_in_at' => $checkedInTime,
                'checked_by_name' => $operatorName,
            ]);
        });

        return $result;
    }
}
