<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/Client/Connexion', [ClientController::class, 'ConnexionClient'])->name('client.connexion');
Route::post('/Client/Connecter', [ClientController::class, 'ConnecterClient'])->name('client.connecter');
Route::get('/Client/Connecter', [ClientController::class, 'AfficherAccueil'])->name('client.accueil');
Route::post('/Client/Inscription', [ClientController::class, 'Inscription'])->name('client.inscription.store');
Route::get('/Client/Inscription', [ClientController::class, 'Formulaire'])->name('client.inscription');
Route::get('/Client/Profil', [ClientController::class, 'ConsulterProfil'])->name('client.profil');

Route::redirect('/connexion', '/Client/Connexion');
Route::redirect('/inscription', '/Client/Inscription');
