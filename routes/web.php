<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PreRegistrationController;
use App\Http\Controllers\RaffleDrawController;
use App\Http\Controllers\WinnerController;
use App\Http\Middleware\RoleMiddleware;


// Public Landing Page
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Role-Based Dashboard Router (Handles both 'user' and 'admin')
    // ✅ DO NOT put 'role:user' or 'role:admin' on this route!
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Default Breeze Profile / Account Settings Routes
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');

    /*
    |--------------------------------------------------------------------------
    | Specific User Routes (Protected by role:user)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
        // Example user-specific routes
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        // Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');

    });

    /*
    |--------------------------------------------------------------------------
    | Specific Admin Routes (Protected by role:admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Example admin management routes
        // Route::resource('events', AdminEventController::class);

    });
});


Route::get('/raffle-winners', [WinnerController::class, 'winners'])->name('public.winners');


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
require __DIR__ . '/admin.php';
