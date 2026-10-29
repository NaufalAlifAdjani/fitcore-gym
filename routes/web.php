<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MemberManagementController;
use App\Http\Controllers\Admin\MembershipPackageController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\PtPackageController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\MembershipPackageController as MemberMembershipPackageController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PtPackagePurchaseController;
use App\Http\Controllers\PtSessionController;
use App\Http\Controllers\PtSessionSlotController;
use App\Http\Controllers\Trainer\DashboardController as TrainerDashboardController;
use App\Livewire\Trainer\Sessions\Index as TrainerSessionsIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', function (Request $request) {
    $role = auth()->user()?->role;

    $targetRoute = match ($role) {
        'admin' => 'admin.dashboard',
        'trainer' => 'trainer.dashboard',
        default => 'member.dashboard',
    };

    $redirect = redirect()->route($targetRoute, $request->query());

    if ($request->has('verified') || session('verified')) {
        $redirect->with('verified', true)
            ->with('success', session('success') ?? 'Email Anda berhasil diverifikasi! Selamat datang di FitCore Gym.');
    }

    return $redirect;
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

    // PT Session Booking routes
    Route::get('/pt-sessions/slots', [PtSessionSlotController::class, 'slots'])
        ->name('pt-sessions.slots');
    Route::get('/pt-sessions/availability', [PtSessionSlotController::class, 'availability'])
        ->name('pt-sessions.availability');

    Route::get('/pt-sessions', [PtSessionController::class, 'index'])
        ->name('pt-sessions.index');
    Route::get('/pt-sessions/create', [PtSessionController::class, 'create'])
        ->name('pt-sessions.create');
    Route::post('/pt-sessions', [PtSessionController::class, 'store'])
        ->name('pt-sessions.store');

    Route::get('/pt-sessions/{ptSession}/edit', [PtSessionController::class, 'edit'])
        ->name('pt-sessions.edit');
    Route::put('/pt-sessions/{ptSession}', [PtSessionController::class, 'update'])
        ->name('pt-sessions.update');
    Route::patch('/pt-sessions/{ptSession}/cancel', [PtSessionController::class, 'cancel'])
        ->name('pt-sessions.cancel');
});

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal-sesi', TrainerSessionsIndex::class)->name('sessions.index');
    Route::view('/klien-saya', 'trainer.placeholder', ['title' => 'Klien Saya'])->name('clients.index');
    Route::view('/ketersediaan-waktu', 'trainer.placeholder', ['title' => 'Ketersediaan Waktu'])->name('availability.index');
    Route::view('/pendapatan-komisi', 'trainer.placeholder', ['title' => 'Pendapatan / Komisi'])->name('commissions.index');
});

Route::middleware(['auth', 'can:manage-membership-packages'])->prefix('admin')->name('admin.')->group(function () {
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

    Route::get('/pt-packages', [PtPackageController::class, 'index'])->name('pt-packages.index');
    Route::post('/pt-packages', [PtPackageController::class, 'store'])->name('pt-packages.store');
    Route::put('/pt-packages/{ptPackage}', [PtPackageController::class, 'update'])->name('pt-packages.update');
    Route::patch('/pt-packages/{ptPackage}/toggle-status', [PtPackageController::class, 'toggleStatus'])->name('pt-packages.toggle-status');
    Route::get('/pt-sessions', [PtPackageController::class, 'index'])->name('pt-sessions.index');
    Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::prefix('member-management')->name('members.')->controller(MemberManagementController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/export', 'export')->name('export');
        Route::get('/{member}', 'show')->whereNumber('member')->name('show');
        Route::put('/{member}', 'update')->whereNumber('member')->name('update');
        Route::patch('/{member}/deactivate', 'deactivate')->whereNumber('member')->name('deactivate');
        Route::delete('/{member}', 'destroy')->whereNumber('member')->name('destroy');
    });
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
