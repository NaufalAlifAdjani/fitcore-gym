<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TrainerCredentialsMail;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PtManagementController extends Controller
{
    /**
     * Menampilkan daftar seluruh akun Personal Trainer dengan filter dan pencarian.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'all');
        $specialization = $request->input('specialization', 'all');

        $query = User::where('role', 'trainer')
            ->with(['trainerProfile', 'trainerSessions'])
            ->withCount('trainerSessions');

        // Pencarian berdasarkan nama, email, no telepon, atau spesialisasi
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('trainerProfile', function ($profileQ) use ($search) {
                        $profileQ->where('specialization', 'like', "%{$search}%")
                            ->orWhere('bio', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status (active/inactive)
        if (in_array($status, ['active', 'inactive'], true)) {
            $query->where('status', $status);
        }

        // Filter spesialisasi
        if ($specialization !== 'all' && ! empty($specialization)) {
            $query->whereHas('trainerProfile', function ($profileQ) use ($specialization) {
                $profileQ->where('specialization', $specialization);
            });
        }

        // Metrik Ringkasan (Stat Cards)
        $totalTrainers = User::where('role', 'trainer')->count();
        $activeTrainers = User::where('role', 'trainer')->where('status', 'active')->count();
        $inactiveTrainers = User::where('role', 'trainer')->where('status', 'inactive')->count();
        $totalSessionsHandled = DB::table('pt_sessions')->count();

        // Daftar spesialisasi unik untuk filter dropdown
        $availableSpecializations = TrainerProfile::whereNotNull('specialization')
            ->where('specialization', '!=', '')
            ->distinct()
            ->pluck('specialization');

        $trainers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pt-management.index', [
            'trainers' => $trainers,
            'totalTrainers' => $totalTrainers,
            'activeTrainers' => $activeTrainers,
            'inactiveTrainers' => $inactiveTrainers,
            'totalSessionsHandled' => $totalSessionsHandled,
            'availableSpecializations' => $availableSpecializations,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'currentSpecialization' => $specialization,
        ]);
    }

    /**
     * Menyimpan data akun Personal Trainer baru dan mengirimkan kredensial melalui email.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'specialization' => ['required', 'string', 'max:100'],
            'experience' => ['required', 'integer', 'min:0', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:8'],
            'send_email' => ['nullable'],
        ], [
            'name.required' => 'Nama lengkap pelatih wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email yang dimasukkan tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain. Silakan gunakan email yang berbeda.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'specialization.required' => 'Spesialisasi keahlian wajib dipilih atau diisi.',
            'experience.required' => 'Pengalaman kerja kepelatihan wajib diisi.',
            'experience.integer' => 'Pengalaman harus berupa angka tahun.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
        ]);

        $plainPassword = ! empty($validated['password'])
            ? $validated['password']
            : Str::random(10);

        $shouldSendEmail = $request->boolean('send_email', true);

        /** @var User $trainer */
        $trainer = null;

        DB::transaction(function () use ($validated, $plainPassword, &$trainer) {
            $trainer = User::create([
                'name' => $validated['name'],
                'email' => strtolower(trim($validated['email'])),
                'phone' => trim($validated['phone']),
                'password' => Hash::make($plainPassword),
                'role' => 'trainer',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            TrainerProfile::create([
                'user_id' => $trainer->id,
                'specialization' => trim($validated['specialization']),
                'experience' => (int) $validated['experience'],
                'bio' => $validated['bio'] ?? null,
                'status' => 'active',
            ]);
        });

        // Kirim email kredensial ke pelatih baru
        $emailStatusMessage = '';
        if ($shouldSendEmail) {
            try {
                Mail::to($trainer->email)->send(new TrainerCredentialsMail($trainer, $plainPassword, route('login')));
                $emailStatusMessage = " Informasi akses dan kredensial telah dikirimkan ke email {$trainer->email}.";
            } catch (\Throwable $e) {
                Log::error('Gagal mengirimkan email kredensial trainer: '.$e->getMessage(), [
                    'trainer_id' => $trainer->id,
                    'email' => $trainer->email,
                ]);
                $emailStatusMessage = ' Namun, pengiriman email kredensial mengalami kendala (periksa konfigurasi mail server).';
            }
        }

        return redirect()->route('admin.trainers.index')
            ->with('success', "Akun pelatih {$trainer->name} berhasil disimpan ke database.{$emailStatusMessage}");
    }

    /**
     * Mengambil data detail akun pelatih dalam format JSON untuk kebutuhan form modal/drawer.
     */
    public function show(User $trainer)
    {
        if ($trainer->role !== 'trainer') {
            abort(404);
        }

        $trainer->load(['trainerProfile', 'trainerSessions' => function ($q) {
            $q->latest()->take(5);
        }]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $trainer->id,
                    'name' => $trainer->name,
                    'email' => $trainer->email,
                    'phone' => $trainer->phone,
                    'status' => $trainer->status,
                    'avatar' => $trainer->avatar,
                    'specialization' => $trainer->trainerProfile?->specialization ?? '-',
                    'experience' => $trainer->trainerProfile?->experience ?? 0,
                    'bio' => $trainer->trainerProfile?->bio ?? '',
                    'total_sessions' => $trainer->trainerSessions()->count(),
                    'created_at' => $trainer->created_at?->format('d M Y'),
                ],
            ]);
        }

        return redirect()->route('admin.trainers.index', ['detail' => $trainer->id]);
    }

    /**
     * Memperbarui data akun Personal Trainer.
     */
    public function update(Request $request, User $trainer): RedirectResponse
    {
        if ($trainer->role !== 'trainer') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$trainer->id],
            'phone' => ['required', 'string', 'max:20'],
            'specialization' => ['required', 'string', 'max:100'],
            'experience' => ['required', 'integer', 'min:0', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'name.required' => 'Nama lengkap pelatih wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email yang dimasukkan tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain. Silakan gunakan email yang berbeda.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'specialization.required' => 'Spesialisasi keahlian wajib dipilih atau diisi.',
            'experience.required' => 'Pengalaman kerja kepelatihan wajib diisi.',
            'status.in' => 'Status akun harus aktif atau non-aktif.',
            'password.min' => 'Kata sandi minimal harus 8 karakter jika ingin diubah.',
        ]);

        DB::transaction(function () use ($trainer, $validated) {
            $userPayload = [
                'name' => $validated['name'],
                'email' => strtolower(trim($validated['email'])),
                'phone' => trim($validated['phone']),
                'status' => $validated['status'],
            ];

            if (! empty($validated['password'])) {
                $userPayload['password'] = Hash::make($validated['password']);
            }

            $trainer->update($userPayload);

            $trainer->trainerProfile()->updateOrCreate(
                ['user_id' => $trainer->id],
                [
                    'specialization' => trim($validated['specialization']),
                    'experience' => (int) $validated['experience'],
                    'bio' => $validated['bio'] ?? null,
                    'status' => $validated['status'],
                ]
            );
        });

        return redirect()->route('admin.trainers.index')
            ->with('success', "Perubahan data akun pelatih {$trainer->name} berhasil disimpan.");
    }

    /**
     * Mengubah status aktif/non-aktif akun pelatih.
     */
    public function toggleStatus(User $trainer): RedirectResponse
    {
        if ($trainer->role !== 'trainer') {
            abort(404);
        }

        $newStatus = ($trainer->status === 'active') ? 'inactive' : 'active';

        DB::transaction(function () use ($trainer, $newStatus) {
            $trainer->update(['status' => $newStatus]);
            $trainer->trainerProfile()->update(['status' => $newStatus]);
        });

        $statusLabel = $newStatus === 'active' ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()->route('admin.trainers.index')
            ->with('success', "Status akun pelatih {$trainer->name} berhasil {$statusLabel}.");
    }

    /**
     * Menghapus akun pelatih secara permanen dari database.
     */
    public function destroy(User $trainer): RedirectResponse
    {
        if ($trainer->role !== 'trainer') {
            abort(404);
        }

        // Cek apakah pelatih masih memiliki sesi PT yang sedang berjalan atau aktif
        $hasActiveSessions = $trainer->trainerSessions()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasActiveSessions) {
            return redirect()->route('admin.trainers.index')
                ->with('error', "Pelatih {$trainer->name} tidak dapat dihapus permanen karena masih memiliki jadwal sesi PT aktif/mendatang. Silakan nonaktifkan akun sebagai gantinya.");
        }

        $trainerName = $trainer->name;

        DB::transaction(function () use ($trainer) {
            $trainer->trainerProfile()->delete();
            $trainer->delete();
        });

        return redirect()->route('admin.trainers.index')
            ->with('success', "Akun pelatih {$trainerName} berhasil dihapus secara permanen dari database.");
    }
}
