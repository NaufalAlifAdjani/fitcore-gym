<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberCardController extends Controller
{
    /**
     * Display the Digital Member Card for the authenticated member.
     * Generates and embeds the QR Code as inline SVG using the member's qr_token.
     * Token is stable — it is never regenerated here, only read.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Eager-load profile and active membership with package
        $user->load([
            'memberProfile',
            'memberships' => fn ($q) => $q->where('status', 'active')
                ->where('start_date', '<=', now('Asia/Jakarta')->toDateString())
                ->where('end_date', '>=', now('Asia/Jakarta')->toDateString())
                ->with('package')
                ->latest('start_date'),
        ]);

        $profile = $user->memberProfile;

        // Ensure the profile has a qr_token (backfill guard — model boot handles new profiles).
        // For edge case: existing profiles that somehow have no token yet.
        if ($profile && empty($profile->qr_token)) {
            $profile->qr_token = Str::uuid()->toString();
            $profile->saveQuietly();
        }

        $activeMembership = $user->memberships->first();

        // Generate QR code SVG inline from the token (not from personal data).
        // SVG format avoids GD dependency issues and scales perfectly on all screens.
        $qrSvg = null;
        if ($profile?->qr_token) {
            $qrSvg = QrCode::format('svg')
                ->size(220)
                ->margin(1)
                ->errorCorrection('M')
                ->generate($profile->qr_token);
        }

        return view('member.card', compact('user', 'profile', 'activeMembership', 'qrSvg'));
    }
}
