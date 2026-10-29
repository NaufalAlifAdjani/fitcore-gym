<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Coba lakukan autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            $requestedRole = $request->input('role');

            // 1. Jika login via form Admin Login tetapi akun bukan Admin
            if ($requestedRole === 'admin' && ! $user->isAdmin()) {
                $request->session()->regenerate();
                abort(403, 'Akses Ditolak. Akun Anda (Role: '.$user->role.') tidak memiliki hak akses Administrator untuk masuk ke Portal Admin.');
            }

            // 2. Jika login via tab Trainer tetapi akun bukan Trainer
            if ($requestedRole === 'trainer' && ! $user->isTrainer()) {
                $request->session()->regenerate();
                abort(403, 'Akses Ditolak. Akun Anda (Role: '.$user->role.') bukan akun Personal Trainer dan tidak memiliki izin masuk melalui Portal Pelatih.');
            }

            // 3. Jika login via tab Member tetapi akun bukan Member
            if ($requestedRole === 'member' && ! $user->isMember()) {
                $request->session()->regenerate();
                abort(403, 'Akses Ditolak. Akun Anda (Role: '.$user->role.') bukan akun Member dan tidak memiliki izin masuk melalui Portal Member.');
            }

            $request->session()->regenerate();

            // Redirect berdasarkan Role User
            $role = $user->role;
            if ($role === 'admin') {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } elseif ($role === 'trainer') {
                return redirect()->intended(route('trainer.dashboard', absolute: false));
            } elseif ($role === 'member') {
                return redirect()->intended(route('member.dashboard', absolute: false));
            }

            return redirect()->intended(route('member.dashboard', absolute: false));
        }

        // Jika gagal, kembalikan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
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
