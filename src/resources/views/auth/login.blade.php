<h1>Connexion</h1>

<form method="POST" action="{{ route('login') }}">
    @csrf

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    @if(session('info'))
        <p>{{ session('info') }}</p>
    @endif
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

    <button type="submit">
        Connexion
    </button>

    <a href="{{ route('register') }}">
        Pas encore de compte ? Inscrivez-vous !
    </a>

</form>

@if(session('btn_resend'))
    <form method='POST' action='{{ route('resend.verification') }}'>
        @csrf
        <input type='hidden' name='email' value='{{ session('email') }}'>

        <button type='submit'>
            Renvoyer l'e-mail de vérification
        </button>
    </form>
@endif
