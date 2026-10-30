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
    public function create(): View
    {
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

        // 4. Jika login via form Admin Login tetapi akun bukan Admin
        if ($requestedRole === 'admin' && ! $user->isAdmin()) {
            abort(403, 'Akses Ditolak. Akun Anda (Role: '.$user->role.') tidak memiliki hak akses Administrator untuk masuk ke Portal Admin.');
        }

        // 5. Jika login via tab Trainer tetapi akun bukan Trainer
        if ($requestedRole === 'trainer' && ! $user->isTrainer()) {
            abort(403, 'Akses Ditolak. Akun Anda (Role: '.$user->role.') bukan akun Personal Trainer dan tidak memiliki izin masuk melalui Portal Pelatih.');
        }

        // 6. Jika login via tab Member tetapi akun bukan Member
        if ($requestedRole === 'member' && ! $user->isMember()) {
            abort(403, 'Akses Ditolak. Akun Anda (Role: '.$user->role.') bukan akun Member dan tidak memiliki izin masuk melalui Portal Member.');
        }

        // 7. Lakukan login dan buat sesi baru
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Redirect berdasarkan Role User
        $role = $user->role;
        if ($role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($role === 'trainer') {
            return redirect()->intended(route('trainer.dashboard', absolute: false));
        } elseif ($role === 'member') {
            return redirect()->route('member.dashboard');
        }

        return redirect()->route('member.dashboard');
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
