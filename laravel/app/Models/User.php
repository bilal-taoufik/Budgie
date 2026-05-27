<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    // La table dans la base de données
    protected $table = 'personne';

    // La clé primaire
    protected $primaryKey = 'prs_id';

    // Pas de created_at / updated_at
    public $timestamps = false;

    // Les champs qu'on peut remplir
    protected $fillable = [
    'prs_nom',
    'prs_prenom',
    'prs_email',
    'prs_password',
    'prs_adresse',
    'prs_age',
    'prs_tel',
    ];

    // Les champs cachés (jamais envoyés au navigateur)
    protected $hidden = [
        'prs_password',
    ];

    // Connexion : vérifie email + mot de passe et retourne l'utilisateur ou null
    public static function getConnexionP($email, $mdp)
    {
        $user = self::where('prs_email', $email)->first();

        if ($user && Hash::check($mdp, $user->prs_password)) {
            return $user;
        }

        return null;
    }

    // Inscription : crée un nouvel utilisateur dans la base
    public static function enregistrerClient($nom, $prenom, $age, $email, $tel, $mdp){
        return self::create([
            'prs_nom'      => $nom,
            'prs_prenom'   => $prenom,
            'prs_email'    => $email,
            'prs_password' => Hash::make($mdp),
            'prs_age'      => $age,
            'prs_tel'      => $tel,
        ]);
    }
}