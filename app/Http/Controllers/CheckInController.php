<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckInRequest;
use App\Models\CheckIn;
use App\Models\MemberProfile;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CheckInController extends Controller
{
    /**
     * Display the QR scanner page (admin/staff only — enforced by route middleware).
     */
    public function index(): View
    {
        return view('admin.checkin');
    }

    /**
     * Process a QR code check-in request.
     * All validation logic is performed on the backend.
     * Returns a structured JSON response for the frontend scanner.
     */
    public function store(CheckInRequest $request): JsonResponse
    {
        $qrToken = $request->validated()['qr_token'];

        // ── A. Validate QR Token ─────────────────────────────────────────────
        $memberProfile = MemberProfile::with([
            'user',
            'user.memberships.package',
            'user.memberships.payments',
        ])->where('qr_token', $qrToken)->first();

        if (! $memberProfile) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau tidak terdaftar.',
            ], 404);
        }

        $member = $memberProfile->user;

        // ── B. Validate Active Membership ────────────────────────────────────
        $today = now('Asia/Jakarta');

        $activeMembership = $member->memberships()
            ->where('status', 'active')
            ->where('start_date', '<=', $today->toDateString())
            ->where('end_date', '>=', $today->toDateString())
            ->whereHas('payments', fn ($q) => $q->where('status', 'verified'))
            ->first();

        if (! $activeMembership) {
            // Differentiate: expired vs pending payment vs no membership at all
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

            // Pending payment or pending status
            return response()->json([
                'success' => false,
                'message' => 'Membership member belum aktif atau pembayaran belum diverifikasi.',
            ], 422);
        }

        // ── C. Validate Duplicate Check-In ───────────────────────────────────
        $todayDate = $today->toDateString(); // e.g. "2026-10-11"

        // Use whereDate() for cross-driver compatibility (SQLite stores dates as text).
        $alreadyCheckedIn = CheckIn::where('member_id', $member->id)
            ->whereDate('check_in_date', $todayDate)
            ->exists();

        if ($alreadyCheckedIn) {
            return response()->json([
                'success' => false,
                'message' => 'Member sudah melakukan check-in hari ini.',
            ], 409);
        }

        // ── D. Save Check-In ─────────────────────────────────────────────────
        // The unique(member_id, check_in_date) DB constraint is the ultimate safeguard
        // against race conditions. We catch it explicitly and return a 409.
        try {
            $checkIn = CheckIn::create([
                'member_id' => $member->id,
                'membership_id' => $activeMembership->id,
                'check_in_date' => $todayDate,
                'checked_in_at' => $today,
                'checked_out_at' => null,
                'method' => 'qr_code',
                'checked_by' => $request->user()->id,
            ]);
        } catch (UniqueConstraintViolationException) {
            // Race condition: two requests passed the exists() check simultaneously.
            return response()->json([
                'success' => false,
                'message' => 'Member sudah melakukan check-in hari ini.',
            ], 409);
        }

        // ── E. Return Success Response ────────────────────────────────────────
        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'member_name' => $member->name,
            'membership' => $activeMembership->package->name ?? 'N/A',
            'valid_until' => $activeMembership->end_date->locale('id')->translatedFormat('d F Y'),
            'checked_in_at' => $today->format('H:i'),
        ]);
    }
}
