<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Memastikan member telah melengkapi biodata (Nomor Telepon & Alamat)
     * sebelum diizinkan mengakses fitur checkout paket keanggotaan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'member' && ! $user->isProfileComplete()) {
            $message = 'Mohon lengkapi biodata (Nomor Telepon, Alamat, dll) Anda terlebih dahulu sebelum melakukan pembelian paket.';

            if ($request->is('m/*') || $request->routeIs('mobile.*')) {
                return redirect()->route('mobile.member.profile.index')
                    ->with('warning', $message)
                    ->with('profile_incomplete', true);
            }

            return redirect()->route('profile.edit')
                ->with('warning', $message)
                ->with('profile_incomplete', true);
        }

        return $next($request);
    }
}
