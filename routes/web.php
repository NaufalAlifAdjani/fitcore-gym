<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PtSessionController;
use App\Http\Controllers\PtSessionSlotController;
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

    // 1. JSON Endpoints (Statis)
    Route::get('/pt-sessions/slots', [PtSessionSlotController::class, 'slots'])
        ->name('pt-sessions.slots');
    Route::get('/pt-sessions/availability', [PtSessionSlotController::class, 'availability'])
        ->name('pt-sessions.availability');

    // 2. Resource & Action Routes
    Route::get('/pt-sessions', [PtSessionController::class, 'index'])
        ->name('pt-sessions.index');
    Route::get('/pt-sessions/create', [PtSessionController::class, 'create'])
        ->name('pt-sessions.create');
    Route::post('/pt-sessions', [PtSessionController::class, 'store'])
        ->name('pt-sessions.store');

    // 3. Parameterized Routes
    Route::get('/pt-sessions/{ptSession}/edit', [PtSessionController::class, 'edit'])
        ->name('pt-sessions.edit');
    Route::put('/pt-sessions/{ptSession}', [PtSessionController::class, 'update'])
        ->name('pt-sessions.update');
    Route::patch('/pt-sessions/{ptSession}/cancel', [PtSessionController::class, 'cancel'])
        ->name('pt-sessions.cancel');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/jadwal-sesi', \App\Livewire\Trainer\Sessions\Index::class)->name('sessions.index');
});
