<?php

use App\Http\Controllers\Admin\MembershipPackageController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\MembershipPackageController as MemberMembershipPackageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = auth()->user()?->role;

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($role === 'trainer') {
        return redirect('/trainer/dashboard');
    }

    return redirect()->route('member.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member Dashboard
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])
        ->middleware('can:access-member-dashboard')
        ->name('member.dashboard');

    // Member Membership Package, Checkout, Upload Proof, and Payment Status routes
    Route::prefix('membership')->name('member.membership.')->controller(MemberMembershipPackageController::class)->group(function () {
        Route::get('/packages', 'index')->name('packages');
        Route::get('/checkout/{package}', 'checkout')->name('checkout');
        Route::get('/upload-proof/{package}', 'uploadProof')->name('upload-proof');
        Route::post('/upload-proof/{package}', 'processPayment')->name('upload-proof.store');
        Route::post('/checkout/{package}', 'processPayment')->name('process-payment');
        Route::get('/payment-status/{payment}', 'paymentStatus')->name('payment-status');
    });
});

Route::middleware(['auth', 'can:manage-membership-packages'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/packages', [MembershipPackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [MembershipPackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{package}', [MembershipPackageController::class, 'update'])->name('packages.update');
    Route::patch('/packages/{package}/toggle-status', [MembershipPackageController::class, 'toggleStatus'])->name('packages.toggle-status');

    Route::prefix('verifikasi-pembayaran')
        ->name('payments.')
        ->controller(PaymentVerificationController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->whereNumber('id')->name('show');
            Route::post('/{id}/approve', 'approve')->whereNumber('id')->name('approve');
            Route::post('/{id}/reject', 'reject')->whereNumber('id')->name('reject');
        });

    Route::view('/pt-sessions', 'admin.placeholder', ['title' => 'Paket Sesi & Jadwal PT'])->name('pt-sessions.index');
    Route::view('/check-in', 'admin.placeholder', ['title' => 'Scanner Check-in'])->name('check-in.index');
    Route::view('/members', 'admin.placeholder', ['title' => 'Manajemen Member'])->name('members.index');
    Route::view('/settings', 'admin.placeholder', ['title' => 'Pengaturan'])->name('settings.index');
    Route::view('/help', 'admin.placeholder', ['title' => 'Bantuan & SOP'])->name('help.index');
});

require __DIR__.'/auth.php';
