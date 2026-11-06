<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class AutoRedirectMobile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Hanya evaluasi untuk request GET non-JSON/non-Livewire
        if (! $request->isMethod('GET') || $request->expectsJson() || $request->is('livewire/*')) {
            return $next($request);
        }

        // 2. Deteksi apakah perangkat adalah smartphone atau developer memaksa via ?device=mobile
        // Tidak menyimpan ke session agar tidak membajak tampilan web desktop ketika dibuka kembali normal
        $deviceQuery = $request->query('device');
        $agent = new Agent($request->server->all(), $request->userAgent());

        $isMobile = $deviceQuery === 'mobile'
            || ($deviceQuery !== 'desktop' && $agent->isMobile() && ! $agent->isTablet());

        // 3. Jika mobile dan URL belum berada di bawah prefix /m/*
        if ($isMobile && ! $request->is('m') && ! $request->is('m/*')) {
            if ($request->is('login')) {
                return redirect()->route('mobile.login');
            }

            if ($request->is('register')) {
                return redirect()->route('mobile.register');
            }

            if ($request->is('member/dashboard')) {
                return redirect()->route('mobile.member.dashboard');
            }

            if ($request->is('trainer/dashboard')) {
                return redirect()->route('mobile.trainer.dashboard');
            }

            if ($request->is('dashboard') && $request->user()?->role === 'trainer') {
                return redirect()->route('mobile.trainer.dashboard');
            }

            if ($request->is('member/card')) {
                return redirect()->route('mobile.member.card');
            }

            if ($request->is('pt-sessions')) {
                return redirect()->route('mobile.member.pt-sessions.index');
            }

            if ($request->is('pt-sessions/create')) {
                return redirect()->route('mobile.member.pt-sessions.create');
            }

            if ($request->is('member/riwayat-transaksi') || $request->is('member/riwayat-transaksi/*')) {
                return redirect()->route('mobile.member.riwayat-transaksi.index');
            }
        }

        return $next($request);
    }
}
