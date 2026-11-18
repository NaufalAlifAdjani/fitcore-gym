<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Dapatkan VAPID Public Key untuk frontend subscription.
     */
    public function vapidPublicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key'),
        ]);
    }

    /**
     * Simpan atau perbarui subscription push browser pengguna.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string', 'url', 'max:1024'],
            'keys.p256dh' => ['nullable', 'string', 'max:255'],
            'keys.auth' => ['nullable', 'string', 'max:255'],
            'content_encoding' => ['nullable', 'string', 'max:50'],
        ]);

        $endpoint = $validated['endpoint'];
        $key = $validated['keys']['p256dh'] ?? null;
        $token = $validated['keys']['auth'] ?? null;
        $contentEncoding = $validated['content_encoding'] ?? 'aes128gcm';

        $request->user()->updatePushSubscription($endpoint, $key, $token, $contentEncoding);

        return response()->json([
            'success' => true,
            'message' => 'Langganan notifikasi push berhasil disimpan.',
        ], 201);
    }

    /**
     * Hapus subscription push browser pengguna.
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string'],
        ]);

        $request->user()->deletePushSubscription($validated['endpoint']);

        return response()->json([
            'success' => true,
            'message' => 'Langganan notifikasi push berhasil dihapus.',
        ]);
    }
}
