<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleAuthController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman OAuth Google menggunakan Laravel Socialite.
     */
    public function redirectToGoogle(): SymfonyRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback autentikasi dari Google.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()->route('login')->withErrors([
                'google' => 'Login Google dibatalkan atau terjadi kesalahan: '.$request->get('error'),
            ]);
        }

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $googleId = $googleUser->getId();
            $email = $googleUser->getEmail();
            $name = $googleUser->getName() ?: ($email ? explode('@', $email)[0] : 'Member');
            $avatar = $googleUser->getAvatar();

            if (! $email) {
                return redirect()->route('login')->withErrors([
                    'google' => 'Akun Google Anda tidak menyediakan alamat email publik.',
                ]);
            }

            // Cari user lama berdasarkan google_id atau email
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                // Update atribut dan pastikan email_verified_at terisi agar bypass verifikasi email
                $user->forceFill([
                    'google_id' => $user->google_id ?: $googleId,
                    'avatar' => $avatar ?: $user->avatar,
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ])->save();

                // Pastikan memberProfile ada jika rolenya member
                if ($user->role === 'member' && ! $user->memberProfile()->exists()) {
                    $user->memberProfile()->create([
                        'member_code' => 'MBR-'.strtoupper(Str::random(6)),
                    ]);
                }
            } else {
                // Buat user baru dengan role member, status active, password hash acak, dan email terverifikasi
                $user = User::forceCreate([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'avatar' => $avatar,
                    'role' => 'member',
                    'status' => 'active',
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                ]);

                // Buatkan juga relasi profil membership
                $user->memberProfile()->create([
                    'member_code' => 'MBR-'.strtoupper(Str::random(6)),
                ]);
            }

            // Login user ke sesi aplikasi
            Auth::login($user, remember: true);
            $request->session()->regenerate();

            // Redirect user sesuai role spesifik (Member ke dashboard member)
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isTrainer()) {
                return redirect()->route('trainer.dashboard');
            }

            return redirect()->route('member.dashboard');
        } catch (\Throwable $e) {
            Log::error('Google OAuth Callback Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->withErrors([
                'google' => 'Terjadi kendala saat memproses login Google: '.$e->getMessage(),
            ]);
        }
    }
}
