<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman persetujuan login Google (OAuth 2.0).
     */
    public function redirectToGoogle(Request $request): RedirectResponse
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect') ?: url('/auth/google/callback');

        // Jika kredensial Google belum diisi di .env:
        if (empty($clientId) || empty($clientSecret)) {
            // Jika dalam lingkungan development (lokal), arahkan ke halaman pemilihan akun Google
            if (app()->isLocal()) {
                return redirect()->route('auth.google.select');
            }

            return redirect()->route('login')->withErrors([
                'google' => 'Konfigurasi Google Login belum lengkap. Silakan isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET di file .env Anda.',
            ]);
        }

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'access_type' => 'offline',
            'prompt' => 'select_account',
        ];

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
    }

    /**
     * Menerima callback dari Google setelah pengguna memilih akun.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        // Jika pengguna membatalkan atau terjadi error dari Google
        if ($request->has('error')) {
            return redirect()->route('login')->withErrors([
                'google' => 'Login Google dibatalkan atau terjadi kesalahan: ' . $request->get('error'),
            ]);
        }

        $code = $request->get('code');
        if (empty($code)) {
            return redirect()->route('login')->withErrors([
                'google' => 'Kode otorisasi Google tidak ditemukan.',
            ]);
        }

        try {
            // 1. Menukar kode otorisasi dengan Access Token dari Google
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect') ?: url('/auth/google/callback'),
                'grant_type' => 'authorization_code',
            ]);

            if (! $tokenResponse->successful()) {
                return redirect()->route('login')->withErrors([
                    'google' => 'Gagal mendapatkan akses token dari Google.',
                ]);
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            // 2. Mengambil profil pengguna dari Google Userinfo API
            $userinfoResponse = Http::withToken($accessToken)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (! $userinfoResponse->successful()) {
                return redirect()->route('login')->withErrors([
                    'google' => 'Gagal mengambil informasi profil dari Google.',
                ]);
            }

            $googleUser = $userinfoResponse->json();
            $googleId   = $googleUser['sub'] ?? null;
            $email      = $googleUser['email'] ?? null;
            $name       = $googleUser['name'] ?? ($email ? explode('@', $email)[0] : 'Member');
            $avatar     = $googleUser['picture'] ?? null;

            if (! $email) {
                return redirect()->route('login')->withErrors([
                    'google' => 'Akun Google Anda tidak menyediakan alamat email publik.',
                ]);
            }

            // 3. Cari pengguna berdasarkan google_id atau alamat email
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                // Perbarui google_id, avatar, dan verifikasi email jika belum ada
                $user->update([
                    'google_id' => $user->google_id ?: $googleId,
                    'avatar' => $avatar ?: $user->avatar,
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ]);

                // Pastikan memberProfile ada jika rolenya member
                if ($user->role === 'member' && ! $user->memberProfile()->exists()) {
                    $user->memberProfile()->create([
                        'member_code' => 'MBR-' . strtoupper(Str::random(6)),
                    ]);
                }
            } else {
                // Buat akun baru jika belum terdaftar
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'avatar' => $avatar,
                    'role' => 'member',
                    'status' => 'active',
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(), // Akun Google sudah terverifikasi otomatis oleh Google
                ]);

                // Buat profil membership otomatis untuk member
                $user->memberProfile()->create([
                    'member_code' => 'MBR-' . strtoupper(Str::random(6)),
                ]);
            }

            // 4. Login pengguna ke sistem
            Auth::login($user, remember: true);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors([
                'google' => 'Terjadi kendala saat memproses login Google: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Tampilan pemilihan akun Google (untuk pengujian lokal sebelum konfigurasi Google Cloud).
     */
    public function selectAccount(Request $request)
    {
        $existingAccounts = User::where('role', 'member')
            ->select('id', 'name', 'email', 'avatar')
            ->latest()
            ->take(4)
            ->get();

        return view('auth.google-select', compact('existingAccounts'));
    }

    /**
     * Memproses login dengan akun Google yang dipilih pada mode pengujian lokal.
     */
    public function mockLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $email = strtolower(trim($request->email));
        $name = $request->name ?: explode('@', $email)[0];
        $googleId = 'google_demo_' . md5($email);
        $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=4285f4&color=fff';

        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $user->google_id ?: $googleId,
                'avatar' => $avatar ?: $user->avatar,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);

            if ($user->role === 'member' && ! $user->memberProfile()->exists()) {
                $user->memberProfile()->create([
                    'member_code' => 'MBR-' . strtoupper(Str::random(6)),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'role' => 'member',
                'status' => 'active',
                'password' => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
            ]);

            $user->memberProfile()->create([
                'member_code' => 'MBR-' . strtoupper(Str::random(6)),
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
