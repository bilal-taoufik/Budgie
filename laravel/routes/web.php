<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ResponsableController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/Client/Connexion',[ClientController::class,'ConnexionClient']);
Route::post('/Client/Connecter',[ClientController::class,'ConnecterClient']);
Route::get('/Client/Connecter', [ClientController::class, 'AfficherAccueil']);
Route::post('/Client/Inscription', [ClientController::class, 'Inscription']);
Route::get('/Client/Inscription', [ClientController::class, 'Formulaire']);
Route::get('/Client/Profil',[ClientController::class,'ConsulterProfil']);

?>