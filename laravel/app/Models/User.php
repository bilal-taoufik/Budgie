<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\ClientController;
use PDO;
use PDOException ;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    private static $connexion = null ; 

    private function __construct(){
        self::$connexion = new PDO('mysql:host=localhost;dbname=Safar', 'boss', 'azerty');
    }

    private static function getConnexion(){
        if(self::$connexion == null){
            new User();
        }
        return self::$connexion ;
    }

    public static function programmerSejour( $ville_dep, $ville_arr, $tarif, $numHotel, $numResp){
        $bd = self::getConnexion() ; 

        $sql = 'insert into voyages values(:ville_depart, :ville_arriver, :tarif, :num_hotel, :num_responsable)';
        $st = $bd -> prepare($sql);
        $st -> execute( array(
                    ':ville_depart' => $ville_dep,
                    ':ville_arriver' => $ville_arr,
                    ':tarif' => $tarif,
                    ':num_hotel' => $numHotel,
                    ':num_responsable' => $numResp
            )
        );

        $st -> closeCursor() ; 

    }

    public static function getConnexionP($email, $mdp){
        $bd = self::getConnexion();

        $sql = "SELECT nom, prenom FROM client WHERE email = :email AND mdp = :mdp";

        $st = $bd->prepare($sql);

        $st->execute(array(':email' => $email, ':mdp' => $mdp));

        $client = $st ->fetch(PDO::FETCH_ASSOC);

        $st->closeCursor();

        return $client;
    }

    public static function enregistrerClient($nom, $prenom, $age, $email, $tel, $mdp)
    {
        $bd = self::getConnexion();

        $sql = "INSERT INTO client (nom, prenom, email, age, tel, mdp) VALUES (:nom, :prenom, :age, :email, :tel, :mdp)";
        $st = $bd->prepare($sql);
        $st->execute([
            ':nom'    => $nom,
            ':prenom' => $prenom,
            ':email'  => $email,
            ':age'    => $age,
            ':tel'    => $tel,
            ':mdp'    => $mdp,
        ]);

        $st->closeCursor();
    }

    


}