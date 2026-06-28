<?php

use App\Http\Controllers\Auth\VerifyMailController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\DepenseController;
use App\Http\Controllers\Customer\PrevisionController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\RevenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home'); })->name('home');

// Routes pour la vérification de l'e-mail
Route::get('/verify-email/{token}', [VerifyMailController::class, 'verifyEmail'])->name('verify.email');
Route::post('/resend-verify', [VerifyMailController::class, 'resendVerificationEmail'])->name('resend.verification');

Route::middleware( ['auth','role:admin'] )->group(function () {
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
});

Route::middleware( ['auth','role:customer'] )->group(function () {
    // Routes pour les prévisions
    Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');

    // Routes pour le profil
    Route::get('/customer/profile', [ProfileController::class, 'index'])->name('customer.profile.index');
    Route::put('/customer/profile/info', [ProfileController::class, 'updateInfo'])->name('customer.profile.info');
    Route::put('/customer/profile/password', [ProfileController::class, 'updatePassword'])->name('customer.profile.password');
    Route::delete('/customer/profile', [ProfileController::class, 'delete'])->name('customer.profile.delete');
    Route::get('/customer/prevision', [PrevisionController::class, 'index'])->name('customer.previsions.index');

    // Routes pour la gestion des comptes
    Route::get('/customer/account', [AccountController::class, 'index'])->name('customer.accounts.index');
    Route::post('/customer/account', [AccountController::class, 'store'])->name('customer.accounts.store');
    Route::put('/customer/account/{account}', [AccountController::class, 'update'])->name('customer.accounts.update');
    Route::delete('/customer/account/{account}', [AccountController::class, 'delete'])->name('customer.accounts.delete');

    // Routes pour la gestion des dépenses
    Route::get('/customer/depense', [DepenseController::class, 'index'])->name('customer.depenses.index');
    Route::post('/customer/depense', [DepenseController::class, 'store'])->name('customer.depenses.store');
    Route::put('/customer/depense/{depense}', [DepenseController::class, 'update'])->name('customer.depenses.update');
    Route::delete('/customer/depense/{depense}', [DepenseController::class, 'delete'])->name('customer.depenses.delete');

    // Routes pour la gestion des revenus
    Route::get('/customer/revenue', [RevenuController::class, 'index'])->name('customer.revenues.index');
    Route::post('/customer/revenue', [RevenuController::class, 'store'])->name('customer.revenues.store');
    Route::put('/customer/revenue/{revenue}', [RevenuController::class, 'update'])->name('customer.revenues.update');
    Route::delete('/customer/revenue/{revenue}', [RevenuController::class, 'delete'])->name('customer.revenues.delete');
});


require __DIR__.'/auth.php';



