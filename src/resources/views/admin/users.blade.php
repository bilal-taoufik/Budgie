<h1>Gestion des utilisateurs</h1>

<a href="{{ route('admin.dashboard') }}">Dashboard</a>
<br>
<a href="{{ route('admin.profile.index') }}">Profil</a>
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

<h2>Ajouter un admin</h2>

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <input type="text" name="firstname" value="{{ old('firstname') }}" placeholder="Prenom" required>
    <input type="text" name="lastname" value="{{ old('lastname') }}" placeholder="Nom" required>
    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>

    <button type="submit">Ajouter un admin</button>
</form>

<br>

<h2>Liste des utilisateurs</h2>

@foreach($users as $user)
    <div>
        <strong>{{ $user->firstname }} {{ $user->lastname }}</strong><br>
        Email : {{ $user->email }}<br>
        Role : {{ $user->role }}<br>
        Email verifie : {{ $user->email_verified ? 'Oui' : 'Non' }}<br>
        Inscrit le : {{ $user->created_at->format('d/m/Y') }}<br>

        @if($user->id !== auth()->id())
            <form method="POST"
                  action="{{ route('admin.users.delete', $user) }}"
                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                @csrf
                @method('DELETE')

                <button type="submit">Supprimer</button>
            </form>
        @else
            <em>Votre compte</em>
        @endif

        <hr>
    </div>
@endforeach
