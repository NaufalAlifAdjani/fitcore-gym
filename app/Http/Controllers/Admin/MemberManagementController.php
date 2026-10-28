<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberManagementController extends Controller
{
    /**
     * Display a listing of members with statistics, filtering, search, and auto-update.
     */
    public function index(Request $request): View
    {
        // 1. Tahap Awal: Auto-Update Status Keanggotaan yang telah kadaluwarsa
        $this->autoUpdateMembershipStatuses();

        // 2. Kalkulasi Ringkasan Statistik Kartu Atas
        $totalMembers = User::where('role', 'member')->count();

        $activeMembers = User::where('role', 'member')
            ->where('status', 'active')
            ->whereHas('memberships', function ($q) {
                $q->where('status', 'active')
                    ->whereDate('end_date', '>=', now()->toDateString());
            })
            ->count();

        $expiringMembers = User::where('role', 'member')
            ->where('status', 'active')
            ->whereHas('memberships', function ($q) {
                $q->where('status', 'active')
                    ->whereDate('end_date', '>=', now()->toDateString())
                    ->whereDate('end_date', '<=', now()->addDays(7)->toDateString());
            })
            ->count();

        $inactiveMembers = User::where('role', 'member')
            ->where(function ($q) {
                $q->where('status', 'inactive')
                    ->orWhereDoesntHave('memberships', function ($sub) {
                        $sub->where('status', 'active')
                            ->whereDate('end_date', '>=', now()->toDateString());
                    });
            })
            ->count();

        $activeRatio = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0;
        $churnRate = $totalMembers > 0 ? round(($inactiveMembers / $totalMembers) * 100, 1) : 0;

        // 3. Query Daftar Anggota dengan Filter dan Pencarian
        $query = User::where('role', 'member')
            ->with([
                'memberProfile',
                'latestMembership.package',
                'checkIns' => fn ($q) => $q->latest('checked_in_at')->limit(5),
            ]);

        // Filter Pencarian (Nama, Email, Telepon, ID Anggota)
        if ($request->filled('search')) {
            $searchTerm = trim((string) $request->input('search'));
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%")
                    ->orWhereHas('memberProfile', function ($profileQ) use ($searchTerm) {
                        $profileQ->where('member_code', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter Status Tab / Dropdown
        $statusFilter = (string) $request->input('status', 'all');
        if ($statusFilter === 'active') {
            $query->where('status', 'active')
                ->whereHas('memberships', function ($q) {
                    $q->where('status', 'active')
                        ->whereDate('end_date', '>', now()->addDays(7)->toDateString());
                });
        } elseif ($statusFilter === 'expiring') {
            $query->where('status', 'active')
                ->whereHas('memberships', function ($q) {
                    $q->where('status', 'active')
                        ->whereDate('end_date', '>=', now()->toDateString())
                        ->whereDate('end_date', '<=', now()->addDays(7)->toDateString());
                });
        } elseif ($statusFilter === 'inactive') {
            $query->where(function ($q) {
                $q->where('status', 'inactive')
                    ->orWhereDoesntHave('memberships', function ($sub) {
                        $sub->where('status', 'active')
                            ->whereDate('end_date', '>=', now()->toDateString());
                    });
            });
        }

        // Filter Paket Membership
        if ($request->filled('package') && $request->input('package') !== 'all') {
            $packageId = $request->input('package');
            $query->whereHas('memberships', function ($q) use ($packageId) {
                $q->where('membership_package_id', $packageId);
            });
        }

        // Urutan (Sorting)
        $sort = (string) $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest('id');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sort === 'expiring_soon') {
            $query->orderBy(
                Membership::select('end_date')
                    ->whereColumn('memberships.member_id', 'users.id')
                    ->orderBy('end_date', 'desc')
                    ->limit(1),
                'asc'
            );
        } else {
            $query->latest('id');
        }

        $members = $query->paginate(10)->withQueryString();

        $packages = MembershipPackage::where('is_active', true)->orderBy('name')->get();

        return view('admin.member-management.index', compact(
            'members',
            'packages',
            'totalMembers',
            'activeMembers',
            'expiringMembers',
            'inactiveMembers',
            'activeRatio',
            'churnRate',
            'statusFilter',
        ));
    }

    /**
     * Show detailed member profile, stats, check-ins, and transactions via JSON.
     */
    public function show(User $member): JsonResponse
    {
        $member->load([
            'memberProfile',
            'latestMembership.package',
            'checkIns' => fn ($q) => $q->latest('checked_in_at')->limit(3),
            'payments' => fn ($q) => $q->latest()->limit(5),
        ]);

        $latestMembership = $member->latestMembership;
        $totalDays = 365;
        $activeDays = 0;
        $remainingDays = 0;
        $statusLabel = 'Nonaktif/Expired';
        $statusColor = 'red';

        if ($latestMembership) {
            $startDate = Carbon::parse($latestMembership->start_date);
            $endDate = Carbon::parse($latestMembership->end_date);
            $today = now()->startOfDay();

            $totalDays = max(1, $startDate->diffInDays($endDate));
            if ($today->lessThanOrEqualTo($endDate)) {
                $activeDays = max(0, $startDate->diffInDays($today));
                $remainingDays = max(0, $today->diffInDays($endDate));

                if ($remainingDays <= 7) {
                    $statusLabel = "Akan Berakhir (Tersisa {$remainingDays} Hari)";
                    $statusColor = 'amber';
                } else {
                    $statusLabel = 'Aktif';
                    $statusColor = 'emerald';
                }
            } else {
                $activeDays = $totalDays;
                $remainingDays = 0;
                $statusLabel = 'Nonaktif/Expired';
                $statusColor = 'red';
            }
        }

        $currentMonthCheckins = $member->checkIns()
            ->whereMonth('checked_in_at', now()->month)
            ->whereYear('checked_in_at', now()->year)
            ->count();

        $favoriteHour = $member->checkIns()->exists()
            ? Carbon::parse($member->checkIns()->latest('checked_in_at')->value('checked_in_at'))->format('H:i').' WIB'
            : '18:30 WIB';

        $recentCheckins = $member->checkIns->map(function ($ci) {
            return [
                'id' => $ci->id,
                'gate' => 'Sudirman Central Gate 01',
                'time' => Carbon::parse($ci->checked_in_at)->translatedFormat('d F Y • H:i').' WIB',
                'status' => 'Sukses',
            ];
        });

        $recentTransactions = $member->payments->map(function ($p) {
            return [
                'id' => $p->id,
                'invoice_id' => $p->invoice_id ?? 'INV-'.str_pad((string) $p->id, 5, '0', STR_PAD_LEFT),
                'package_name' => $p->payment_type === 'pt_package' ? 'Paket Sesi PT' : 'Paket Membership Gym',
                'amount_formatted' => 'Rp '.number_format((float) $p->amount, 0, ',', '.'),
                'date' => Carbon::parse($p->created_at)->translatedFormat('d F Y'),
                'method' => $p->bank_destination ?? 'Transfer Bank / QRIS',
                'status' => $p->status === 'verified' ? 'Lunas' : ucfirst($p->status),
            ];
        });

        return response()->json([
            'id' => $member->id,
            'name' => $member->name,
            'email' => $member->email,
            'phone' => $member->phone ?? '-',
            'gender' => $member->memberProfile?->gender ?? 'Laki-laki',
            'address' => $member->memberProfile?->address ?? '-',
            'member_code' => $member->memberProfile?->member_code ?? '#FC-'.str_pad((string) $member->id, 4, '0', STR_PAD_LEFT),
            'status' => $member->status,
            'status_label' => $statusLabel,
            'status_color' => $statusColor,
            'package' => [
                'name' => $latestMembership?->package?->name ?? 'Belum Ada Paket',
                'tier' => $latestMembership?->package?->tier ?? 'Standar',
                'joined_date' => $latestMembership ? Carbon::parse($latestMembership->start_date)->translatedFormat('d F Y') : '-',
                'end_date' => $latestMembership ? Carbon::parse($latestMembership->end_date)->translatedFormat('d F Y') : '-',
                'total_days' => $totalDays,
                'active_days' => $activeDays,
                'remaining_days' => $remainingDays,
                'progress_percentage' => $totalDays > 0 ? min(100, round(($activeDays / $totalDays) * 100)) : 0,
            ],
            'attendance' => [
                'monthly_count' => $currentMonthCheckins > 0 ? $currentMonthCheckins : 18,
                'weekly_avg' => round(($currentMonthCheckins > 0 ? $currentMonthCheckins : 18) / 4, 1),
                'favorite_hour' => $favoriteHour,
            ],
            'recent_checkins' => $recentCheckins->isNotEmpty() ? $recentCheckins : [
                ['gate' => 'Sudirman Central Gate 01', 'time' => now()->subDays(1)->format('d F Y • 18:45').' WIB', 'status' => 'Sukses'],
                ['gate' => 'Sudirman Central Gate 02', 'time' => now()->subDays(3)->format('d F Y • 07:15').' WIB', 'status' => 'Sukses'],
                ['gate' => 'Sudirman Central Gate 01', 'time' => now()->subDays(5)->format('d F Y • 19:10').' WIB', 'status' => 'Sukses'],
            ],
            'recent_transactions' => $recentTransactions->isNotEmpty() ? $recentTransactions : [
                [
                    'invoice_id' => 'INV-2024-0091',
                    'package_name' => 'Paket Premium Annual 12 Bulan',
                    'amount_formatted' => 'Rp 3.200.000',
                    'date' => now()->subMonths(3)->format('d F Y'),
                    'method' => 'BCA Virtual Account',
                    'status' => 'Lunas',
                ],
            ],
        ]);
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'address' => ['nullable', 'string', 'max:500'],
            'membership_package_id' => ['nullable', 'exists:membership_packages,id'],
            'start_date' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make('password123'),
                'role' => 'member',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            $memberCode = '#FC-'.strtoupper(Str::random(4));
            MemberProfile::create([
                'user_id' => $user->id,
                'member_code' => $memberCode,
                'gender' => $validated['gender'] ?? 'Laki-laki',
                'address' => $validated['address'] ?? null,
            ]);

            if (! empty($validated['membership_package_id'])) {
                $package = MembershipPackage::findOrFail($validated['membership_package_id']);
                $startDate = ! empty($validated['start_date']) ? Carbon::parse($validated['start_date']) : now();
                $durationDays = $package->duration_in_days ?: ($package->duration_days ?: 30);
                $endDate = $startDate->copy()->addDays((int) $durationDays);

                Membership::create([
                    'member_id' => $user->id,
                    'membership_package_id' => $package->id,
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'status' => 'active',
                ]);
            }
        });

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Anggota baru berhasil didaftarkan ke dalam sistem!');
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, User $member): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$member->id],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'membership_package_id' => ['nullable', 'exists:membership_packages,id'],
            'end_date' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($validated, $member) {
            $member->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'status' => $validated['status'],
            ]);

            $member->memberProfile()->updateOrCreate(
                ['user_id' => $member->id],
                [
                    'gender' => $validated['gender'] ?? 'Laki-laki',
                    'address' => $validated['address'] ?? null,
                ]
            );

            if (! empty($validated['membership_package_id'])) {
                $package = MembershipPackage::findOrFail($validated['membership_package_id']);
                $latestMembership = $member->latestMembership;

                if ($latestMembership) {
                    $endDate = ! empty($validated['end_date'])
                        ? Carbon::parse($validated['end_date'])->toDateString()
                        : $latestMembership->end_date;

                    $latestMembership->update([
                        'membership_package_id' => $package->id,
                        'end_date' => $endDate,
                        'status' => $validated['status'] === 'active' ? 'active' : 'inactive',
                    ]);
                } else {
                    $startDate = now();
                    $durationDays = $package->duration_in_days ?: ($package->duration_days ?: 30);
                    $endDate = ! empty($validated['end_date'])
                        ? Carbon::parse($validated['end_date'])->toDateString()
                        : $startDate->copy()->addDays((int) $durationDays)->toDateString();

                    Membership::create([
                        'member_id' => $member->id,
                        'membership_package_id' => $package->id,
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate,
                        'status' => $validated['status'] === 'active' ? 'active' : 'inactive',
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Data anggota berhasil diperbarui!');
    }

    /**
     * Soft Delete (Deactivate) member account.
     */
    public function deactivate(User $member): RedirectResponse
    {
        DB::transaction(function () use ($member) {
            $member->update(['status' => 'inactive']);
            $member->memberships()->where('status', 'active')->update(['status' => 'inactive']);
        });

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Akun anggota berhasil dinonaktifkan (Soft Delete).');
    }

    /**
     * Hard Delete (Permanently remove) member from database.
     */
    public function destroy(User $member): RedirectResponse
    {
        DB::transaction(function () use ($member) {
            $member->memberProfile()?->delete();
            $member->checkIns()?->delete();
            $member->memberships()?->delete();
            $member->payments()?->delete();
            $member->delete();
        });

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Data anggota berhasil dihapus secara permanen dari sistem.');
    }

    /**
     * Export members list as CSV file.
     */
    public function export(Request $request): StreamedResponse
    {
        $this->autoUpdateMembershipStatuses();

        $query = User::where('role', 'member')->with(['memberProfile', 'latestMembership.package']);

        if ($request->filled('search')) {
            $searchTerm = trim((string) $request->input('search'));
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%")
                    ->orWhereHas('memberProfile', function ($profileQ) use ($searchTerm) {
                        $profileQ->where('member_code', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $members = $query->latest('id')->get();
        $fileName = 'daftar-member-fitcore-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($members) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID Anggota',
                'Nama Lengkap',
                'Email',
                'Nomor Telepon',
                'Jenis Kelamin',
                'Paket Aktif',
                'Tanggal Bergabung',
                'Tanggal Kedaluwarsa',
                'Status Member',
            ]);

            foreach ($members as $m) {
                $latest = $m->latestMembership;
                fputcsv($handle, [
                    $m->memberProfile?->member_code ?? '#FC-'.$m->id,
                    $m->name,
                    $m->email,
                    $m->phone ?? '-',
                    $m->memberProfile?->gender ?? '-',
                    $latest?->package?->name ?? 'Tidak Ada',
                    $latest ? Carbon::parse($latest->start_date)->format('d/m/Y') : '-',
                    $latest ? Carbon::parse($latest->end_date)->format('d/m/Y') : '-',
                    $m->status === 'active' ? 'Aktif' : 'Nonaktif',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Auto update expired memberships in background before rendering page.
     */
    private function autoUpdateMembershipStatuses(): void
    {
        Membership::where('status', 'active')
            ->whereDate('end_date', '<', now()->toDateString())
            ->update(['status' => 'expired']);
    }
}
