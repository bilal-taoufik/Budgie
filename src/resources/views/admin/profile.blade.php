<h1>Mon profil admin</h1>

<a href="{{ route('admin.dashboard') }}">Dashboard</a>
<br>
<a href="{{ route('admin.users.index') }}">Utilisateurs</a>
<br>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@if(session('error'))
    <div>{{ session('error') }}</div>
@endif

@if($errors->any())
    <div>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h2>Modifier mes informations</h2>

<form method="POST" action="{{ route('admin.profile.info') }}">
    @csrf
    @method('PUT')

    <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" placeholder="Prenom" required>
    <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" placeholder="Nom" required>
    <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" required>

    <button type="submit">Enregistrer</button>
</form>

<br>

<h2>Modifier mon mot de passe</h2>

<form method="POST" action="{{ route('admin.profile.password') }}">
    @csrf
    @method('PUT')

    <input type="password" name="current_password" placeholder="Mot de passe actuel" required>
    <input type="password" name="password" placeholder="Nouveau mot de passe" required>
    <input type="password" name="password_confirmation" placeholder="Confirmer le nouveau mot de passe" required>

    <button type="submit">Modifier le mot de passe</button>
</form>

<br>

<h2>Supprimer mon compte admin</h2>

<p>Attention : impossible de supprimer le dernier admin.</p>

<form method="POST"
      action="{{ route('admin.profile.delete') }}"
      onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte admin ?');">
    @csrf
    @method('DELETE')

    <input type="password" name="password" placeholder="Votre mot de passe" required>

    <button type="submit">Supprimer mon compte admin</button>
</form>
