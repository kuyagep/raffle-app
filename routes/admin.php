<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\PrizeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WinnerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::delete('participants/bulk-delete', [ParticipantController::class, 'bulkDelete'])->name('participants.bulkDelete');
    Route::get('participants/all-ids', [ParticipantController::class, 'getAllIds'])
        ->name('participants.getAllIds');
    Route::get('participants/export', [ParticipantController::class, 'export'])->name('participants.export');
    Route::post('participants/import', [ParticipantController::class, 'import'])->name('participants.import');
    Route::get('participants/download-template', [ParticipantController::class, 'downloadTemplate'])
        ->name('participants.downloadTemplate');

    Route::resource('prizes', PrizeController::class)->except(['show']);
    Route::get('winners', [WinnerController::class, 'index'])->name('winners.index');
    Route::post('prizes/{prize}/draw', [PrizeController::class, 'draw'])->name('prizes.draw');
    Route::post('/admin/prizes/{prize}/pre-draw', [PrizeController::class, 'preDraw'])->name('prizes.preDraw');
    // routes/web.php
    Route::post('winners/update-selection', [WinnerController::class, 'updateSelection'])->name('winners.updateSelection');
    Route::get('winners/print', [WinnerController::class, 'print'])->name('winners.print');

    // web.php
    Route::post('/winners/clear-selection', function () {
        session()->forget('selected_winners');
        return response()->json(['status' => 'cleared']);
    })->name('winners.resetSelection');


    Route::resource('participants', ParticipantController::class)->only(['index', 'show', 'destroy']);

    Route::resource('users', UserController::class);

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    Route::get('/events/{id}/participants', [EventController::class, 'participants'])->name('events.participants');
});
