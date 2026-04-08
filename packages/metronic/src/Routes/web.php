<?php

use Illuminate\Support\Facades\Route;
use Isotope\Metronic\Http\Controllers\NotificationController;
use Isotope\Metronic\Http\Controllers\Auth\PasswordController;
use Isotope\Metronic\Http\Controllers\Settings\SettingController;
use Isotope\Metronic\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['web'])->group(function () {
    require __DIR__ . '/auth.php';

    Route::middleware(['auth', 'authorization'])->group(function () {
        Route::resource('settings', SettingController::class)->only('index', 'store');
        Route::post('settings/slider/upload', [SettingController::class, 'sliderUpload'])->name('settings.slider.upload');
        Route::delete('settings/slider/delete', [SettingController::class, 'sliderDelete'])->name('settings.slider.delete');
        Route::post('settings/slider/reorder', [SettingController::class, 'sliderReorder'])->name('settings.slider.reorder');
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/mark-read-all-notification', [NotificationController::class, 'markReadAllNotification'])->name('notifications.markAllAsRead');
        Route::post('notifications/clear-all-notification', [NotificationController::class, 'clearAllNotification'])->name('notifications.clearAll');
        Route::patch('notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/locale-change/{locale?}', [AuthenticatedSessionController::class, 'changeLocale']);
        Route::get('profile', [AuthenticatedSessionController::class, 'profile'])->name('profile');
        Route::get('profile-edit', [AuthenticatedSessionController::class, 'profileEdit'])->name('profile.edit');
        Route::put('profile-update', [AuthenticatedSessionController::class, 'profileUpdate'])->name('profile.update');
        Route::get('change-password', [PasswordController::class, 'ShowChangePasswordForm'])->name('showChangePasswordForm');
        Route::post('updatePassword', [PasswordController::class, 'updatePassword'])->name('updatePassword');
    });
});
