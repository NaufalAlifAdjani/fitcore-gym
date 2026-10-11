<?php

use App\Http\Controllers\CheckInController;
use App\Http\Controllers\MemberCardController;
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

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
| The Digital Member Card is accessible by any authenticated user with
| a member profile (role: member). It shows the QR Code for check-in.
*/
Route::middleware(['auth', 'role:member'])->name('member.')->group(function () {
    Route::get('/member/card', [MemberCardController::class, 'show'])->name('card');
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
