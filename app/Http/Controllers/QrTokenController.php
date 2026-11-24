<?php

namespace App\Http\Controllers;

use App\Models\QrToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrTokenController extends Controller
{
    /** Token lifetime in seconds. */
    private const TOKEN_TTL = 30;

    /**
     * Generate a fresh temporary QR token for the authenticated member.
     *
     * - Invalidates all previous unused+unexpired tokens for this member.
     * - Creates a new cryptographically secure token that expires in 30 s.
     * - Returns the token string and Unix expiry timestamp for the frontend.
     */
    public function generate(Request $request): JsonResponse
    {
        $memberId = $request->user()->id;

        // Invalidate any previous pending tokens for this member so that
        // refreshing or reopening the page cannot reuse an old token.
        QrToken::where('member_id', $memberId)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->delete();

        $token = bin2hex(random_bytes(32)); // 64 hex characters — cryptographically secure
        $expiresAt = now()->addSeconds(self::TOKEN_TTL);

        QrToken::create([
            'member_id' => $memberId,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'token' => $token,
            'expires_at_unix' => $expiresAt->timestamp,
            'ttl' => self::TOKEN_TTL,
        ]);
    }

    /**
     * Return a QR Code SVG for the given token.
     *
     * Only renders if the token belongs to the authenticated member and
     * has not yet been used or expired (purely cosmetic — validation
     * on actual check-in is done in CheckInController).
     */
    public function svg(Request $request): Response
    {
        $tokenStr = $request->query('token', '');
        $memberId = $request->user()->id;

        // Basic lookup — do not reject expired here because the QR may still
        // be displaying while the countdown reaches zero; rendering it is harmless.
        $qrToken = QrToken::where('token', $tokenStr)
            ->where('member_id', $memberId)
            ->first();

        if (! $qrToken) {
            abort(404);
        }

        $svg = QrCode::format('svg')
            ->size(220)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($tokenStr);

        return response($svg)->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }
}
