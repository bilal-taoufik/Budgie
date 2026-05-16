<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
//require "Models/User.php";

Class ClientController extends Controller {
    
    public function ConnexionClient(Request $request) {
        $login = $request->input('email'); 
        $mdp = $request->input('mdp');
    

        return view('ConnexionClient');
    }
    

    public function ConnecterClient (){
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $client = User::getConnexionP($email, $mdp);

        
        if($client != FALSE){
            session_start();

            $_SESSION["nom"] = $client["nom"] ; 
            $_SESSION["prenom"] = $client["prenom"];

            //header("Location: /");
            return view('ConnecterClient');

        }
        else{
            printf('Identfiant ou mot de passe incorrect.');
            return view('ConnexionClient');
        }
    }


    public function AfficherAccueil() {
        return view('ConnecterClient');
    }

    public function Inscription(Request $request)
    {
        try {
            \App\Models\User::enregistrerClient(
                $request->input('nom'),
                $request->input('prenom'),
                $request->input('email'),
                $request->input('age'),
                $request->input('tel'),
                $request->input('mdp')
            );
    
            return redirect('Client/Connexion')->with('success', 'Inscription réussie !');
    
        } catch (\Exception $e) {
            dd("Erreur : " . $e->getMessage()); 
        }
    }

    public function Formulaire() {
        return view('Inscription');
    }

    public function ConsulterProfil (){
        return view ('ConsulterProfil');
    }
}
?>