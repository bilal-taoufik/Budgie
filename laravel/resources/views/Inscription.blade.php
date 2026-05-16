<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h2>Formulaire d'inscription</h2>
<form action="/Client/Inscription" method="POST">
    @csrf 
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br>

    <label>Prénom :</label><br>
    <input type="text" name="prenom" required><br>

    <label>Adresse Mail :</label><br>
    <input type="text" name="email" required><br>

    <label> Age :</label><br>
    <input type="text" name="age" required><br>

    <label>tel :</label><br>
    <input type="text" name="tel" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mdp" required><br><br>

    <input type="submit" value="Valider">
</form>
<br>
@if(session('error'))
    <div class="error-message">
        {{ session('error') }}
    </div>
@endif
</body>
</html>