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
        // Validasi input
        $credentials = $request->validated();

        // Coba lakukan autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan Role User
            $role = Auth::user()->role;
            if ($role === 'admin') {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } elseif ($role === 'trainer') {
                return redirect()->intended('/trainer/dashboard');
            } elseif ($role === 'member') {
                return redirect()->route('member.dashboard');
            }

            return redirect()->route('member.dashboard');
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

        return redirect('/');
    }
}
