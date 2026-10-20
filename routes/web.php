<?php

use App\Http\Controllers\CheckInController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PtPackagePurchaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
