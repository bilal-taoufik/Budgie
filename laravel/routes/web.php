<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/comptes', 'comptes')->name('comptes');
    Route::view('/depenses', 'depenses')->name('depenses');
    Route::view('/revenus', 'revenus')->name('revenus');
    Route::view('/previsions', 'previsions')->name('previsions');

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');
});

require __DIR__.'/auth.php';
