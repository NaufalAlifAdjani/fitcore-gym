<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipPackageRequest;
use App\Models\MembershipPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipPackageController extends Controller
{
    public function index(Request $request): View
    {
        $query = MembershipPackage::query();

        if ($request->filled('tier') && $request->tier !== 'Semua Tier') {
            $query->where('tier', $request->tier);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.trim($request->string('search')->toString()).'%');
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $packages = $query->latest()->paginate(10)->withQueryString();

        $totalPackages = MembershipPackage::count();
        $activePackages = MembershipPackage::where('is_active', true)->count();
        $avgPrice = MembershipPackage::where('is_active', true)->avg('price') ?? 0;
        $formattedAvgPrice = 'Rp '.number_format($avgPrice, 0, ',', '.');

        return view('admin.packages.index', compact(
            'packages',
            'totalPackages',
            'activePackages',
            'formattedAvgPrice',
        ));
    }

    public function store(StoreMembershipPackageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['pt_sessions'] = $data['pt_sessions'] ?? 0;

        MembershipPackage::create($data);

        return redirect()->route('admin.packages.index')->with('success', 'Paket membership berhasil ditambahkan!');
    }

    public function update(StoreMembershipPackageRequest $request, MembershipPackage $package): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['pt_sessions'] = $data['pt_sessions'] ?? 0;

        $package->update($data);

        return redirect()->route('admin.packages.index')->with('success', 'Paket membership berhasil diperbarui!');
    }

    public function toggleStatus(Request $request, MembershipPackage $package): JsonResponse|RedirectResponse
    {
        $package->update(['is_active' => ! $package->is_active]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Status paket berhasil diperbarui.',
                'is_active' => $package->is_active,
            ]);
        }

        return back()->with('success', 'Status paket berhasil diperbarui!');
    }
}
