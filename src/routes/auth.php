<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Inscription
    Route::get('register', [RegisterUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisterUserController::class, 'store']);

    // Se connecter
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // Se déconnecter
    Route::post('logout', [AuthenticatedSessionController::class, 'delete'])
        ->name('logout');
});
