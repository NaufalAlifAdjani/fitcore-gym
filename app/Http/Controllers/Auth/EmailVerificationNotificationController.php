<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang email verifikasi: '.$e->getMessage());

            return back()->with('error', 'Gagal mengirim email verifikasi ke '.$request->user()->email.'. Pastikan konfigurasi SMTP di berkas .env sudah aktif dan valid.');
        }
    }
}
