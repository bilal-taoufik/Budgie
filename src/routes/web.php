<?php

use App\Http\Controllers\Auth\VerifyMailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home'); })->name('home');

// Routes pour la vérification de l'e-mail
Route::get('/verify-email/{token}', [VerifyMailController::class, 'verifyEmail'])->name('verify.email');
Route::post('/resend-verify', [VerifyMailController::class, 'resendVerificationEmail'])->name('resend.verification');

Route::middleware( ['auth','role:admin'] )->group(function () {
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
});

Route::middleware( ['auth','role:customer'] )->group(function () {
    Route::get('/customer/dashboard', function () { return view('customer.dashboard'); })->name('customer.dashboard');
});


require __DIR__.'/auth.php';
