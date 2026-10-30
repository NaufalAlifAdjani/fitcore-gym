<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated or linked user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if (! $user) {
            abort(404, 'Pengguna tidak ditemukan.');
        }

        if (! hash_equals(sha1((string) $user->getEmailForVerification()), (string) $request->route('hash'))) {
            throw new AuthorizationException('Tautan verifikasi tidak valid atau tidak cocok.');
        }

        // Pastikan pengguna terautentikasi ke akun yang sedang diverifikasi
        if (! Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1')
                ->with('verified', true)
                ->with('success', 'Email Anda sudah terverifikasi.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1')
            ->with('verified', true)
            ->with('success', 'Email Anda berhasil diverifikasi! Selamat datang di FitCore Gym.');
    }
}
