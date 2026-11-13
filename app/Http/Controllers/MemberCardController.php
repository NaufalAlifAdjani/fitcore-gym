<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberCardController extends Controller
{
    /**
     * Display the Digital Member Card for the authenticated member.
     * QR Code is now generated dynamically via JavaScript — no longer pre-generated here.
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
        $activeMembership = $user->memberships->first();

        // URLs passed to the view so the Blade template can wire up the JS.
        $qrGenerateUrl = route('member.card.qr-token');
        $qrSvgUrl = route('member.card.qr-svg');

        return view('member.card', compact('user', 'profile', 'activeMembership', 'qrGenerateUrl', 'qrSvgUrl'));
    }
}
