<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman/form login
     */
    public function create(Request $request): View
    {
        if ($request->has('redirect')) {
            session()->put('url.intended', $request->query('redirect'));
        }

        return view('auth.login');
    }

    /**
     * Memproses otentikasi login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Jika ada sesi pengguna aktif sebelumnya, bersihkan sesi lama terlebih dahulu
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Validasi input
        $credentials = $request->validated();

        // 1. Cari user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();

        // 2. Validasi kredensial (user harus ada dan password cocok)
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 3. Validasi status verifikasi email (is_verified / email_verified_at)
        if (! $user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Harap verifikasi email Anda terlebih dahulu sebelum login.',
            ])->onlyInput('email');
        }

        $requestedRole = $request->input('role');

        // 4. Pengecekan Hak Akses Berdasarkan Portal Login
        if ($requestedRole === 'admin') {
            // Portal Login Khusus Admin (/admin/login)
            if (! $user->isAdmin()) {
                abort(403, 'Akses Ditolak. Akun Anda (Role: '.$user->role.') tidak memiliki hak akses Administrator untuk masuk ke Portal Admin.');
            }
        } else {
            // Portal Login Umum (/login) - HANYA untuk Member dan Trainer
            // Jika akun adalah Admin, tolak dengan status 403 Forbidden
            if ($user->isAdmin()) {
                abort(403, 'Akses Ditolak. Administrator tidak dapat login melalui halaman ini. Halaman ini hanya diperuntukkan bagi Member dan Personal Trainer.');
            }

            // Pastikan akun memiliki role yang diizinkan (Member atau Trainer)
            if (! $user->isMember() && ! $user->isTrainer()) {
                abort(403, 'Akses Ditolak. Halaman login ini hanya diperuntukkan bagi Member dan Personal Trainer.');
            }
        }

        // 5. Lakukan login dan buat sesi baru
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // 6. Redirect otomatis berdasarkan Role User
        if ($user->isTrainer()) {
            return redirect()->intended(route('trainer.dashboard', absolute: false));
        } elseif ($user->isMember()) {
            return redirect()->intended(route('member.dashboard', absolute: false));
        } elseif ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('member.dashboard', absolute: false));
    }

    /**
     * Memproses Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
