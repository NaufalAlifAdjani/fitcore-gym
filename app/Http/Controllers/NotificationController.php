<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Menandai satu notifikasi sebagai sudah dibaca dan mengarahkan ke URL terkait.
     */
    public function markAsRead(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai sudah dibaca.',
            ]);
        }

        $url = $notification->data['url'] ?? null;
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            return redirect($url);
        }

        return back();
    }

    /**
     * Menandai semua notifikasi pengguna sebagai sudah dibaca.
     */
    public function markAllAsRead(Request $request): JsonResponse|RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi berhasil ditandai sudah dibaca.',
            ]);
        }

        return back()->with('success', 'Semua notifikasi berhasil ditandai sudah dibaca.');
    }
}
