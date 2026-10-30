<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = $request->user() ?: User::find($request->route('id'));

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Pengguna tidak ditemukan.',
            ]);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors([
                'email' => 'Tautan verifikasi tidak valid atau telah kedaluwarsa.',
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            if ($request->user()) {
                return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
            }

            return redirect()->route('login')->with('status', 'Email Anda sudah diverifikasi sebelumnya. Silakan login.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if ($request->user()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi! Silakan login untuk melanjutkan.');
    }
}
