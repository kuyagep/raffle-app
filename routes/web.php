<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParticipantController as AdminParticipantController;
use App\Http\Controllers\Admin\PrizeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PreRegistrationController;
use App\Http\Controllers\RaffleDrawController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WinnerController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // return view('landing');
    if (!Auth::check()) {
        return redirect("/login");
    } else {
        return redirect("/admin/dashboard");
    }
});
Route::get('/raffle-winners', [WinnerController::class, 'winners'])->name('public.winners');

Route::middleware(['auth'])->group(function () {

    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');


    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::delete('participants/bulk-delete', [AdminParticipantController::class, 'bulkDelete'])->name('participants.bulkDelete');
        Route::get('participants/all-ids', [AdminParticipantController::class, 'getAllIds'])
            ->name('participants.getAllIds');
        Route::get('participants/export', [AdminParticipantController::class, 'export'])->name('participants.export');
        Route::post('participants/import', [AdminParticipantController::class, 'import'])->name('participants.import');
        Route::get('participants/download-template', [AdminParticipantController::class, 'downloadTemplate'])
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


        Route::resource('participants', AdminParticipantController::class)->only(['index', 'show', 'destroy']);

        Route::resource('users', UserController::class);

        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::post('/events/{id}/toggle-join', [EventController::class, 'toggleJoin'])
            ->name('events.toggle-join');
        Route::get('/events/{id}/participants', [EventController::class, 'participants'])->name('events.participants');
    });
})->middleware(RoleMiddleware::class);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/api/departments/{id}/offices', function ($id) {
    return \App\Models\Office::where('department_id', $id)->get(['id', 'name']);
});
Route::get('/events/join/{code}', [EventController::class, 'joinByLink'])
    ->name('events.join-by-link');

Route::post('/events/join/{code}/pre-register', [EventController::class, 'storePreRegistration'])
    ->name('events.pre-register.store');


Route::get('/preregistration', [PreRegistrationController::class, 'create'])->name('preregistration.create');

// Process Pre-Registration Form Submission (AJAX)
Route::post('/preregistration', [PreRegistrationController::class, 'store'])->name('preregistration.store');


// public routes for participants
Route::get('/join', [ParticipantController::class, 'join'])->name('join');
Route::get('/registration', [ParticipantController::class, 'create'])->name('registration.create');
Route::post('/registration', [ParticipantController::class, 'store'])->name('registrations.store');
Route::get('/registration/{id}', [ParticipantController::class, 'show'])->name('registrations.show');
Route::get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');

// Staff can assist raffle draws
Route::middleware(['auth'])->group(function () {
    Route::get('/raffle', [RaffleDrawController::class, 'showDrawPage'])->name('raffle.draw');
    Route::post('/raffle-draw/start', [RaffleDrawController::class, 'startDraw'])->name('raffle.start');
    Route::post('/raffle/redraw', [RaffleDrawController::class, 'redraw'])->name('raffle.redraw');
    Route::get('/raffle-draw/recent-winners', [RaffleDrawController::class, 'recentWinners'])->name('raffle.recentWinners');
    Route::get('participants/list', [RaffleDrawController::class, 'list'])
        ->name('participants.list');
    Route::get('/raffle/check-prize', [RaffleDrawController::class, 'checkPrize'])->name('raffle.checkPrize');
    Route::get('/raffle/prizes-remaining', [RaffleDrawController::class, 'prizesRemaining'])->name('raffle.prizesRemaining');

    Route::get('scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/certificate/{attendance}', [CertificateController::class, 'generate'])->name('certificate.generate');
});


require __DIR__ . '/auth.php';
