<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the mobile user profile view.
     */
    public function mobileIndex(Request $request): View
    {
        $user = $request->user()->load([
            'memberProfile',
            'memberships' => fn ($q) => $q->where('status', 'active')->with('package')->latest('start_date'),
        ]);

        $activeMembership = $user->memberships->first();
        $memberProfile = $user->memberProfile;

        return view('mobile.member.profile.index', [
            'user' => $user,
            'activeMembership' => $activeMembership,
            'memberProfile' => $memberProfile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        if (str_contains($request->header('referer', ''), '/m/')) {
            return Redirect::route('mobile.member.profile.index')->with('status', 'profile-updated');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
