<?php

use Illuminate\Support\Facades\Route;
use Isotope\Metronic\Http\Controllers\RoleController;
use Isotope\Metronic\Http\Controllers\UserController;
use Isotope\Metronic\Http\Controllers\ModuleController;
use Isotope\Metronic\Http\Controllers\Auth\PasswordController;
use Isotope\Metronic\Http\Controllers\Auth\NewPasswordController;
use Isotope\Metronic\Http\Controllers\Auth\VerifyEmailController;
use Isotope\Metronic\Http\Controllers\Auth\RegisteredUserController;
use Isotope\Metronic\Http\Controllers\Auth\PasswordResetLinkController;
use Isotope\Metronic\Http\Controllers\Auth\ConfirmablePasswordController;
use Isotope\Metronic\Http\Controllers\Auth\AuthenticatedSessionController;
use Isotope\Metronic\Http\Controllers\Auth\EmailVerificationPromptController;
use Isotope\Metronic\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware(['guest'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

Route::as('authorization.')->prefix('authorization')->middleware(['auth', 'authorization'])->group(function () {
    Route::get('/', [UserController::class, 'welcome'])->name('welcome');
    Route::resource('/roles', RoleController::class)->except('show');
    Route::resource('/users', UserController::class)->except('show');
    Route::get('/app-modules', [ModuleController::class, 'getModules'])->name('app-modules');
    Route::post('/app-modules', [ModuleController::class, 'update'])->name('app-modules.update');
});