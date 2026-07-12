<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\VerifyMailController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\PrevisionController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home'); })->name('home');

// Routes pour la verification de l'e-mail
Route::get('/verify-email/{token}', [VerifyMailController::class, 'verifyEmail'])->name('verify.email');
Route::post('/resend-verify', [VerifyMailController::class, 'resendVerificationEmail'])->name('resend.verification');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'delete'])->name('admin.users.delete');

    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::put('/admin/profile/info', [AdminProfileController::class, 'updateInfo'])->name('admin.profile.info');
    Route::put('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::delete('/admin/profile', [AdminProfileController::class, 'delete'])->name('admin.profile.delete');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');

    Route::get('/customer/profile', [ProfileController::class, 'index'])->name('customer.profile.index');
    Route::put('/customer/profile/info', [ProfileController::class, 'updateInfo'])->name('customer.profile.info');
    Route::put('/customer/profile/password', [ProfileController::class, 'updatePassword'])->name('customer.profile.password');
    Route::delete('/customer/profile', [ProfileController::class, 'delete'])->name('customer.profile.delete');

    Route::get('/customer/prevision', [PrevisionController::class, 'index'])->name('customer.previsions.index');
    Route::get('/customer/prevision/calculer', [PrevisionController::class, 'calculer'])->name('customer.previsions.calculer');

    Route::get('/customer/account', [AccountController::class, 'index'])->name('customer.accounts.index');
    Route::post('/customer/account', [AccountController::class, 'store'])->name('customer.accounts.store');
    Route::put('/customer/account/{account}', [AccountController::class, 'update'])->name('customer.accounts.update');
    Route::delete('/customer/account/{account}', [AccountController::class, 'delete'])->name('customer.accounts.delete');

    Route::get('/customer/transactions', [TransactionController::class, 'index'])->name('customer.transactions.index');

    Route::get('/customer/depense', [TransactionController::class, 'depenses'])->name('customer.depenses.index');
    Route::post('/customer/depense', [TransactionController::class, 'store'])->name('customer.depenses.store');
    Route::put('/customer/depense/{transaction}', [TransactionController::class, 'update'])->name('customer.depenses.update');
    Route::delete('/customer/depense/{transaction}', [TransactionController::class, 'delete'])->name('customer.depenses.delete');

    Route::get('/customer/revenue', [TransactionController::class, 'revenus'])->name('customer.revenues.index');
    Route::post('/customer/revenue', [TransactionController::class, 'store'])->name('customer.revenues.store');
    Route::put('/customer/revenue/{transaction}', [TransactionController::class, 'update'])->name('customer.revenues.update');
    Route::delete('/customer/revenue/{transaction}', [TransactionController::class, 'delete'])->name('customer.revenues.delete');
});

require __DIR__.'/auth.php';