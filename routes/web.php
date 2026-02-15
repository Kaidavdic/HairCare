<?php

use App\Http\Controllers\Admin\SalonApprovalController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/salons', [SalonController::class, 'index'])->name('salons.index');

Route::get('/salons/{salon}', [SalonController::class, 'show'])
    ->name('salons.show');

Route::get('/contact', \App\Http\Controllers\ContactController::class)->name('contact');

// API endpoints
Route::get('/api/salon/{salon}/available-slots', [SalonController::class, 'getAvailableSlots']);

Route::middleware(['auth', 'verified'])->group(function () {
    // Shared client & salon owner routes
    Route::middleware('role:client,salon_owner')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');

        Route::post('/appointments/{appointment}/reviews', [ReviewController::class, 'store'])
            ->name('appointments.reviews.store');
    });

    // Only clients can create appointment requests for salon services
    Route::middleware('role:client')->group(function () {
        Route::post('/salons/{salon}/services/{service}/appointments', [SalonController::class, 'storeAppointment'])
            ->name('salons.appointments.store');
    });

    // Salon owner routes
    Route::middleware('role:salon_owner')->group(function () {
        Route::get('/owner/dashboard', [DashboardController::class, 'index'])
            ->name('owner.dashboard');

        Route::get('/owner/salon', [SalonController::class, 'edit'])
            ->name('owner.salon.edit');
        Route::post('/owner/salon', [SalonController::class, 'store'])
            ->name('owner.salon.store');
        Route::put('/owner/salon', [SalonController::class, 'update'])
            ->name('owner.salon.update');

        // Salon images
        Route::post('/owner/salon/images', [\App\Http\Controllers\Owner\SalonImageController::class, 'store'])
            ->name('owner.images.store');
        Route::delete('/owner/salon/images/{image}', [\App\Http\Controllers\Owner\SalonImageController::class, 'destroy'])
            ->name('owner.images.destroy');

        Route::get('/owner/appointments', [AppointmentController::class, 'ownerIndex'])
            ->name('owner.appointments.index');
        Route::post('/owner/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
            ->name('owner.appointments.update-status');

        // Owner service management (only for their salon)
        Route::get('/owner/salon/services', [\App\Http\Controllers\Owner\ServiceController::class, 'index'])
            ->name('owner.services.index');
        Route::get('/owner/salon/services/create', [\App\Http\Controllers\Owner\ServiceController::class, 'create'])
            ->name('owner.services.create');
        Route::post('/owner/salon/services', [\App\Http\Controllers\Owner\ServiceController::class, 'store'])
            ->name('owner.services.store');
        Route::get('/owner/salon/services/{service}/edit', [\App\Http\Controllers\Owner\ServiceController::class, 'edit'])
            ->name('owner.services.edit');
        Route::put('/owner/salon/services/{service}', [\App\Http\Controllers\Owner\ServiceController::class, 'update'])
            ->name('owner.services.update');
        Route::delete('/owner/salon/services/{service}', [\App\Http\Controllers\Owner\ServiceController::class, 'destroy'])
            ->name('owner.services.destroy');
    });

    // Admin routes
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/salons/pending', [SalonApprovalController::class, 'index'])
                ->name('salons.pending');
            Route::post('/salons/{salon}/approve', [\App\Http\Controllers\Admin\SalonApprovalController::class, 'approve'])->name('salons.approve');
            Route::post('/salons/{salon}/reject', [\App\Http\Controllers\Admin\SalonApprovalController::class, 'reject'])->name('salons.reject');
            Route::delete('/salons/{salon}', [\App\Http\Controllers\Admin\SalonApprovalController::class, 'destroy'])->name('salons.destroy');

            // Notifications
            Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class);

            // Users
            Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::post('/users/{user}/warn', [\App\Http\Controllers\Admin\UserController::class, 'warn'])->name('users.warn');
            Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'ban'])->name('users.ban');
            Route::post('/users/{user}/activate', [\App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
            Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

            // Settings (Contact)
            Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('settings.edit');
            Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        });

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');

    // Dashboard route removed
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    // Profile management (if app provides profile controller)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])
        ->name('messages.index');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])
        ->name('messages.show');
    Route::post('/messages/{user}', [App\Http\Controllers\MessageController::class, 'store'])
        ->name('messages.store');
});

require __DIR__ . '/auth.php';

