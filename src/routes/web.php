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

    // Routes pour la gestion des comptes
    Route::get('/customer/account', [\App\Http\Controllers\Customer\AccountController::class, 'index'])->name('customer.accounts.index');
    Route::post('/customer/account', [\App\Http\Controllers\Customer\AccountController::class, 'store'])->name('customer.accounts.store');
    Route::put('/customer/account/{account}', [\App\Http\Controllers\Customer\AccountController::class, 'update'])->name('customer.accounts.update');
    Route::delete('/customer/account/{account}', [\App\Http\Controllers\Customer\AccountController::class, 'delete'])->name('customer.accounts.delete');

    // Routes pour la gestion des dépenses
    Route::get('/customer/depense', [\App\Http\Controllers\Customer\DepenseController::class, 'index'])->name('customer.depenses.index');
    Route::post('/customer/depense', [\App\Http\Controllers\Customer\DepenseController::class, 'store'])->name('customer.depenses.store');
    Route::put('/customer/depense/{depense}', [\App\Http\Controllers\Customer\DepenseController::class, 'update'])->name('customer.depenses.update');
    Route::delete('/customer/depense/{depense}', [\App\Http\Controllers\Customer\DepenseController::class, 'delete'])->name('customer.depenses.delete');

    // Routes pour la gestion des revenus
    Route::get('/customer/revenue', [\App\Http\Controllers\Customer\RevenuController::class, 'index'])->name('customer.revenues.index');
    Route::post('/customer/revenue', [\App\Http\Controllers\Customer\RevenuController::class, 'store'])->name('customer.revenues.store');
    Route::put('/customer/revenue/{revenue}', [\App\Http\Controllers\Customer\RevenuController::class, 'update'])->name('customer.revenues.update');
    Route::delete('/customer/revenue/{revenue}', [\App\Http\Controllers\Customer\RevenuController::class, 'delete'])->name('customer.revenues.delete');
});


require __DIR__.'/auth.php';
