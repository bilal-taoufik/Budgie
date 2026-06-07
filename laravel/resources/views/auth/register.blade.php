<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/main.js')
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <title>Budgie | Inscription</title>
</head>

<body>
<main class="main-wrapper">
    <div class="page-wrapper">
        <div class="section_forms-inscription">
            <div class="padding-global padding-section-large">
                <div class="component-form">

                    <div class="margin-bottom margin-medium">
                        <svg width="50" height="59" viewBox="0 0 50 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 59L6.87732 0H32.7138C37.5465 0 46.2825 2.54152 46.2825 13.6154C46.2825 18.1542 44.6097 25.5969 32.7138 25.5969H28.9963L29.7398 17.0646L10.223 27.7754L27.5093 37.76L28.2528 29.7723H39.9628C43.4944 29.7723 50 34.3108 50 43.5692C50 48.6523 46.2825 59 34.2007 59H0Z" fill="#85A795"/>
                        </svg>
                    </div>

                    <div class="max-width-xxlarge">
                        <div class="margin-bottom margin-tiny">
                            <h1 class="heading-style-h2-bold text-color-white">
                                Bienvenue !
                            </h1>
                        </div>

                        <div class="margin-bottom margin-large">
                            <h2 class="text-size-regular text-color-body">
                                Rejoignez Budgie et gérez vos finances
                            </h2>
                        </div>
                    </div>

                    <div class="form-block w-form">
                        @if ($errors->any())
                            <div class="w-form-fail" style="display:block;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-field-wrapper">
                                <input
                                    type="text"
                                    class="form-input"
                                    name="nom"
                                    value="{{ old('nom') }}"
                                    placeholder="Nom*"
                                    required
                                    autofocus
                                    autocomplete="name"
                                >
                            </div>

                            <div class="form-field-wrapper">
                                <input
                                    type="text"
                                    class="form-input"
                                    name="prenom"
                                    value="{{ old('prenom') }}"
                                    placeholder="Prénom*"
                                    required
                                >
                            </div>

                            <div class="form-field-wrapper">
                                <input
                                    type="email"
                                    class="form-input"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Email*"
                                    required
                                    autocomplete="username"
                                >
                            </div>

                            <div class="form-field-wrapper">
                                <input
                                    type="tel"
                                    class="form-input"
                                    name="tel"
                                    value="{{ old('tel') }}"
                                    placeholder="Téléphone*"
                                    required
                                >
                            </div>

                            <div class="form-field-wrapper">
                                <input
                                    type="password"
                                    class="form-input"
                                    name="password"
                                    placeholder="Mot de passe*"
                                    required
                                    autocomplete="new-password"
                                >

                                <div class="form-helper">
                                    Minimum 8 caractères.
                                </div>
                            </div>

                            <div class="form-field-wrapper">
                                <input
                                    type="password"
                                    class="form-input"
                                    name="password_confirmation"
                                    placeholder="Confirmer le mot de passe*"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>

                            <button type="submit" class="btn-secondary">
                                S'inscrire
                            </button>
                        </form>
                    </div>

                    <div class="form-bottom">
                        <p>
                            Vous avez déjà un compte ?
                            <a href="{{ route('login') }}">
                                Se connecter
                            </a>
                        </p>
                    </div>

                    <div class="form-divider"></div>

                    <a href="{{ route('home') }}" class="form-back-link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M15 18L9 12L15 6"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <span>Retour à l'accueil</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
