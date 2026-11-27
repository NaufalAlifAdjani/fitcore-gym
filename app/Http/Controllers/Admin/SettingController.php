<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan sistem operasional FitCore.
     */
    public function index(): View
    {
        $operationalSettings = [
            'change_deadline_hours' => (int) Setting::get('change_deadline_hours', config('pt_booking.change_deadline_hours', 24)),
            'min_booking_notice_hours' => (int) Setting::get('min_booking_notice_hours', config('pt_booking.min_booking_notice_hours', 24)),
            'session_duration_minutes' => (int) Setting::get('session_duration_minutes', config('pt_booking.session_duration_minutes', 60)),
            'auto_cancel_pending_hours_before' => (int) Setting::get('auto_cancel_pending_hours_before', config('pt_booking.auto_cancel_pending_hours_before', 12)),
            'qr_token_ttl' => (int) Setting::get('qr_token_ttl', 30),
        ];

        $gymProfile = [
            'gym_name' => (string) Setting::get('gym_name', 'FitCore Athletic Gym'),
            'gym_phone' => (string) Setting::get('gym_phone', '081234567890'),
            'gym_email' => (string) Setting::get('gym_email', 'support@fitcore.test'),
            'gym_address' => (string) Setting::get('gym_address', 'Jl. Sudirman No. 10, Jakarta Pusat'),
        ];

        $banks = Bank::orderBy('name')->get();

        return view('admin.settings.index', compact('operationalSettings', 'gymProfile', 'banks'));
    }

    /**
     * Perbarui aturan operasional sesi PT & dynamic QR.
     */
    public function updateOperational(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'change_deadline_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'min_booking_notice_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'session_duration_minutes' => ['required', 'integer', 'min:15', 'max:240'],
            'auto_cancel_pending_hours_before' => ['required', 'integer', 'min:1', 'max:72'],
            'qr_token_ttl' => ['required', 'integer', 'min:10', 'max:300'],
        ], [
            'change_deadline_hours.required' => 'Batas waktu reschedule/pembatalan wajib diisi.',
            'change_deadline_hours.min' => 'Batas waktu reschedule minimal 1 jam.',
            'min_booking_notice_hours.required' => 'Minimal booking di muka wajib diisi.',
            'session_duration_minutes.required' => 'Durasi sesi wajib diisi.',
            'auto_cancel_pending_hours_before.required' => 'Batas auto-cancel wajib diisi.',
            'qr_token_ttl.required' => 'Durasi refresh dynamic QR wajib diisi.',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Aturan operasional sesi PT dan keamanan QR berhasil diperbarui.');
    }

    /**
     * Perbarui informasi kontak & profil gym.
     */
    public function updateGymProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'gym_name' => ['required', 'string', 'max:100'],
            'gym_phone' => ['required', 'string', 'max:25'],
            'gym_email' => ['required', 'email', 'max:100'],
            'gym_address' => ['required', 'string', 'max:255'],
        ], [
            'gym_name.required' => 'Nama gym wajib diisi.',
            'gym_phone.required' => 'Nomor WhatsApp CS wajib diisi.',
            'gym_email.required' => 'Email support wajib diisi.',
            'gym_address.required' => 'Alamat gym wajib diisi.',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Informasi profil dan kontak resmi gym berhasil diperbarui.');
    }

    /**
     * Tambah rekening bank baru untuk pembayaran transfer.
     */
    public function storeBank(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'name.required' => 'Nama bank wajib diisi (contoh: BCA, Mandiri).',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        Bank::create($validated);

        return back()->with('success', 'Rekening bank baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data rekening bank yang ada.
     */
    public function updateBank(Request $request, Bank $bank): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $bank->update($validated);

        return back()->with('success', "Rekening bank {$bank->name} berhasil diperbarui.");
    }

    /**
     * Hapus rekening bank.
     */
    public function destroyBank(Bank $bank): RedirectResponse
    {
        if ($bank->payments()->exists()) {
            $bank->update(['status' => 'inactive']);

            return back()->with('info', "Rekening {$bank->name} dinonaktifkan karena telah memiliki riwayat pembayaran.");
        }

        $bank->delete();

        return back()->with('success', "Rekening {$bank->name} berhasil dihapus.");
    }
}
