<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MembershipPackageController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\MembershipPackageController as MemberMembershipPackageController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Trainer\DashboardController as TrainerDashboardController;
use App\Http\Controllers\PtPackagePurchaseController;
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
        return redirect()->route('trainer.dashboard');
    }

    return redirect()->route('member.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member Dashboard
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])
        ->middleware('role:member')
        ->name('member.dashboard');

    // Member Membership Package, Checkout, Upload Proof, and Payment Status routes
    Route::prefix('membership')->name('member.membership.')->middleware('role:member')->controller(MemberMembershipPackageController::class)->group(function () {
        Route::get('/packages', 'index')->name('packages');
        Route::get('/checkout/{package}', 'checkout')->name('checkout');
        Route::get('/upload-proof/{package}', 'uploadProof')->name('upload-proof');
        Route::post('/upload-proof/{package}', 'processPayment')->name('upload-proof.store');
        Route::post('/checkout/{package}', 'processPayment')->name('process-payment');
        Route::get('/payment-status/{payment}', 'paymentStatus')->name('payment-status');
    });
});

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
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
    Route::redirect('/check-in', '/admin/checkin')->name('check-in.index');
    Route::view('/members', 'admin.placeholder', ['title' => 'Manajemen Member'])->name('members.index');
    Route::view('/settings', 'admin.placeholder', ['title' => 'Pengaturan'])->name('settings.index');
    Route::view('/help', 'admin.placeholder', ['title' => 'Bantuan & SOP'])->name('help.index');
});

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
| The Digital Member Card and PT Package Purchase are accessible by members.
*/
Route::middleware(['auth', 'role:member'])->name('member.')->group(function () {
    Route::get('/member/card', [MemberCardController::class, 'show'])->name('card');
    Route::get('/member/pt-packages', [PtPackagePurchaseController::class, 'index'])->name('pt-packages.index');
    Route::get('/member/pt-packages/select-trainer', [PtPackagePurchaseController::class, 'selectTrainer'])->name('pt-packages.select-trainer');
    Route::get('/member/pt-packages/checkout', [PtPackagePurchaseController::class, 'checkout'])->name('pt-packages.checkout');
    Route::post('/member/pt-packages/checkout', [PtPackagePurchaseController::class, 'processCheckout'])->name('pt-packages.process-checkout');
    Route::get('/member/pt-packages/upload-payment/{payment?}', [PtPackagePurchaseController::class, 'uploadPayment'])->name('pt-packages.upload-payment');
    Route::post('/member/pt-packages/upload-payment/{payment?}', [PtPackagePurchaseController::class, 'submitPaymentProof'])->name('pt-packages.submit-proof');
    Route::post('/member/pt-packages/buy', [PtPackagePurchaseController::class, 'buy'])->name('pt-packages.buy');
});

/*
|--------------------------------------------------------------------------
| Admin / Staff Routes
|--------------------------------------------------------------------------
| Protected by auth + role middleware. Only admin and trainer (petugas)
| can access the QR scanner page and submit check-in requests.
*/
Route::middleware(['auth', 'role:admin,trainer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
    Route::post('/checkin', [CheckInController::class, 'store'])->name('checkin.store');
});

require __DIR__.'/auth.php';
