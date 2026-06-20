<h1>Inscription</h1>

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div>
        <label for="firstname">Prénom</label>
        <input
            type="text"
            id="firstname"
            name="firstname"
            value="{{ old('firstname') }}"
            required
        >

        @error('firstname')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label for="lastname">Nom</label>
        <input
            type="text"
            id="lastname"
            name="lastname"
            value="{{ old('lastname') }}"
            required
        >

        @error('lastname')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
        >

        @error('email')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label for="password">Mot de passe</label>
        <input
            type="password"
            id="password"
            name="password"
            required
        >

        @error('password')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label for="password_confirmation">
            Confirmation du mot de passe
        </label>

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            required
        >

        @error('password_confirmation')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <a href="{{ route('login') }}">
        Déjà inscrit ?
    </a>

    <br><br>

    <button type="submit">
        S'inscrire
    </button>

</form>
