<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePtPackageRequest;
use App\Models\PtPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PtPackageController extends Controller
{
    /**
     * Display a listing of personal trainer packages.
     */
    public function index(Request $request): View
    {
        $query = PtPackage::query();

        if ($request->filled('search')) {
            $searchTerm = trim($request->string('search')->toString());
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        if ($request->input('status') === 'active') {
            $query->where('status', 'active');
        } elseif ($request->input('status') === 'inactive') {
            $query->where('status', 'inactive');
        }

        $packages = $query->latest('created_at')->latest('id')->paginate(10)->withQueryString();

        $totalPackages = PtPackage::count();
        $activePackages = PtPackage::where('status', 'active')->count();
        $inactivePackages = PtPackage::where('status', 'inactive')->count();
        $avgPrice = PtPackage::where('status', 'active')->avg('price') ?? 0;
        $formattedAvgPrice = 'Rp '.number_format($avgPrice, 0, ',', '.');

        return view('admin.pt-packages.index', compact(
            'packages',
            'totalPackages',
            'activePackages',
            'inactivePackages',
            'formattedAvgPrice'
        ));
    }

    /**
     * Store a newly created personal trainer package in storage.
     */
    public function store(StorePtPackageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $this->resolveStatus($request);

        PtPackage::create($data);

        return redirect()->route('admin.pt-packages.index')
            ->with('success', 'Paket sesi PT berhasil ditambahkan!');
    }

    /**
     * Update the specified personal trainer package in storage.
     */
    public function update(StorePtPackageRequest $request, PtPackage $ptPackage): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $this->resolveStatus($request);

        $ptPackage->update($data);

        return redirect()->route('admin.pt-packages.index')
            ->with('success', 'Paket sesi PT berhasil diperbarui!');
    }

    /**
     * Toggle the active/inactive status of a personal trainer package.
     */
    public function toggleStatus(Request $request, PtPackage $ptPackage): JsonResponse|RedirectResponse
    {
        $newStatus = $ptPackage->status === 'active' ? 'inactive' : 'active';
        $ptPackage->update(['status' => $newStatus]);

        $message = "Status paket \"{$ptPackage->name}\" berhasil diubah menjadi ".($newStatus === 'active' ? 'Aktif' : 'Nonaktif').'.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'status' => $newStatus,
                'is_active' => $newStatus === 'active',
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Resolve the package status from the request.
     */
    private function resolveStatus(Request $request): string
    {
        if ($request->has('is_active')) {
            return $request->boolean('is_active') ? 'active' : 'inactive';
        }

        if ($request->filled('status')) {
            return $request->input('status') === 'active' ? 'active' : 'inactive';
        }

        return 'active';
    }
}
