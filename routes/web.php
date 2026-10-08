<?php

use App\Http\Controllers\Admin\MembershipPackageController;
use App\Http\Controllers\ProfileController;
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

Route::middleware(['auth', 'can:manage-membership-packages'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/packages', [MembershipPackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [MembershipPackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{package}', [MembershipPackageController::class, 'update'])->name('packages.update');
    Route::patch('/packages/{package}/toggle-status', [MembershipPackageController::class, 'toggleStatus'])->name('packages.toggle-status');

    Route::view('/pt-sessions', 'admin.placeholder', ['title' => 'Paket Sesi & Jadwal PT'])->name('pt-sessions.index');
    Route::view('/payments', 'admin.placeholder', ['title' => 'Verifikasi Pembayaran'])->name('payments.index');
    Route::view('/check-in', 'admin.placeholder', ['title' => 'Scanner Check-in'])->name('check-in.index');
    Route::view('/members', 'admin.placeholder', ['title' => 'Manajemen Member'])->name('members.index');
    Route::view('/settings', 'admin.placeholder', ['title' => 'Pengaturan'])->name('settings.index');
    Route::view('/help', 'admin.placeholder', ['title' => 'Bantuan & SOP'])->name('help.index');
});

require __DIR__.'/auth.php';
