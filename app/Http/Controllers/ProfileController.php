<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        $today = now('Asia/Jakarta')->toDateString();
        $user = $request->user()->load([
            'memberProfile',
            'memberships' => fn ($q) => $q->where('status', 'active')
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->with('package')
                ->latest('end_date'),
        ]);

        $activeMembership = $user->memberships->first();
        $maxEndDate = $user->memberships()
            ->where('status', 'active')
            ->where('end_date', '>=', $today)
            ->max('end_date');
        $memberProfile = $user->memberProfile;

        return view('mobile.member.profile.index', [
            'user' => $user,
            'activeMembership' => $activeMembership,
            'maxEndDate' => $maxEndDate,
            'memberProfile' => $memberProfile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        if ($request->hasFile('avatar')) {
            if ($user->avatar && ! str_starts_with($user->avatar, 'http') && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->fill($request->safe()->only(['name', 'email', 'phone']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'member') {
            $profileAttributes = [];
            if ($request->has('address')) {
                $profileAttributes['address'] = $request->input('address');
            }
            if ($request->has('gender')) {
                $profileAttributes['gender'] = $request->input('gender');
            }

            if (! empty($profileAttributes)) {
                if ($user->memberProfile) {
                    $user->memberProfile->update($profileAttributes);
                } else {
                    $user->memberProfile()->create(array_merge([
                        'member_code' => 'MEM'.rand(100000, 999999),
                    ], $profileAttributes));
                }
            }
        }

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
