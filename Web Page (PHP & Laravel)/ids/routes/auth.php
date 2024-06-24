<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Packet_log;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Alert_log;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {


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

Route::middleware('auth')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('packets_log', [Packet_log::class, 'create'])
    ->name('packets_log');
    Route::get('alert_logs', [Alert_log::class, 'create'])
    ->name('alert_logs');

    Route::get('/show_log/{packet_id}', [Packet_log::class, 'show'])->name('show_log');
    
    Route::get('/show_alert/{alert_id}', [Alert_log::class, 'show'])->name('show1_log');

    Route::get('signatures', [SignatureController::class, 'create'])
    ->name('signatures');
    Route::get('signatures/add', [SignatureController::class, 'create1'])
    ->name('signatures.add');
    Route::post('/signatures/store', [SignatureController::class, 'store'])->name('signatures.store');
    Route::get('packet_alert_log/{alert_id}', [Packet_log::class, 'more'])
    ->name('more_packets_log');


    Route::get('signatures/edit/{id}', [SignatureController::class, 'edit'])
    ->name('signatures.edit');

    Route::put('signatures/{signature}', [SignatureController::class, 'update'])
    ->name('signatures.update');


    Route::delete('signatures/{signature}', [SignatureController::class, 'destroy'])
    ->name('signatures.destroy');




    Route::get('/search', [Packet_log::class, 'search'])->name('search');







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


    Route::get('reports', function () {
        return view('admin.reports');
       
       })->name("reports");
       
       Route::post('/generate-report', [ReportController::class, 'generateReport'])->name('generate.report');       
       Route::get('/view-report/{id}', [ReportController::class, 'view'])->name('view.report');
       Route::delete('/reports/{id}', [ReportController::class, 'delete'])->name('delete.report');
       Route::get('/clear-packet-logs', [Packet_log::class, 'clearLogs'])->name('clear_packet_logs');


       
});
