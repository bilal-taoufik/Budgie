<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function programmerSejour( $ville_dep, $ville_arr, $tarif, $numHotel, $numResp){
        DB::table('voyages')->insert([
            'ville_depart' => $ville_dep,
            'ville_arriver' => $ville_arr,
            'tarif' => $tarif,
            'num_hotel' => $numHotel,
            'num_responsable' => $numResp,
        ]);

    }

    public static function getConnexionP($email, $mdp){
        $client = DB::table('client')
            ->where('email', $email)
            ->first();

        if (! $client) {
            return false;
        }

        $passwordMatches = Hash::check($mdp, $client->mdp) || hash_equals((string) $client->mdp, $mdp);

        if (! $passwordMatches) {
            return false;
        }

        return [
            'nom' => $client->nom,
            'prenom' => $client->prenom,
        ];
    }

    public static function enregistrerClient($nom, $prenom, $age, $email, $tel, $mdp)
    {
        if (DB::table('client')->where('email', $email)->exists()) {
            throw new \RuntimeException('Email deja utilise.');
        }

        DB::table('client')->insert([
            'nom' => $nom,
            'prenom' => $prenom,
            'age' => $age,
            'email' => $email,
            'tel' => $tel,
            'mdp' => Hash::make($mdp),
        ]);
    }

}
