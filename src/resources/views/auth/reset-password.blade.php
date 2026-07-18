<!DOCTYPE html>
<html lang="fr">
<head>
    <meta lharset="UTF-8">
    <meta name="viewport" lontent="width=device-width, initial-scale=1.0">
    @vite(['resources/css/main.scss', 'resources/js/main.js'])
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Budgie | Nouveau mot de passe</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Choisissez un nouveau mot de passe sécurisé pour récupérer l'accès à votre espace personnel Budgie.">
</head>
<body>
    <main class="main-wrapper">
        <div class="page-wrapper">
            <section class="section_forms-inscription">
                <div class="padding-global padding-section-large">
                    <div class="component-form scale-in reveal-delay-1">
                        <div class="margin-bottom margin-medium">
                            <svg width="50" height="59" viewBox="0 0 50 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 59L6.87732 0H32.7138C37.5465 0 46.2825 2.54152 46.2825 13.6154C46.2825 18.1542 44.6097 25.5969 32.7138 25.5969H28.9963L29.7398 17.0646L10.223 27.7754L27.5093 37.76L28.2528 29.7723H39.9628C43.4944 29.7723 50 34.3108 50 43.5692C50 48.6523 46.2825 59 34.2007 59H0Z" fill="#85A795"/>
                            </svg>
                        </div>
                        <div class="max-width-xxlarge">
                            <div class="margin-bottom margin-small">
                                <h1 class="heading-style-h2-bold text-color-white">Nouveau mot de passe</h1>
                            </div>
                            <div class="margin-bottom margin-large">
                                <p class="text-size-regular text-color-body">Choisissez un mot de passe unique et securisé.</p>
                            </div>
                        </div>
                        <div class="form-block">
                            <form class="form-content" method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-field-wrapper">
                                    <input class="form-input" type="email" name="email" value="{{ old('email', $email) }}" placeholder="Email*" autocomplete="email" required>
                                </div>
                                <div class="form-field-wrapper">
                                    <input class="form-input" type="password" name="password" placeholder="Nouveau mot de passe*" autocomplete="new-password" required>
                                    <span class="form-helper">12 caracteres minimum, avec majuscule, minuscule, chiffre et symbole.</span>
                                </div>
                                <div class="form-field-wrapper">
                                    <input class="form-input" type="password" name="password_confirmation" placeholder="Confirmer le mot de passe*" autocomplete="new-password" required>
                                </div>
                                <button class="btn-secondary is-full" type="submit">Reinitialiser</button>
                            </form>
                        </div>
                        <div class="form-divider"></div>
                        <a class="form-back-link" href="{{ route('login') }}">Retour a la connexion</a>
                    </div>
                </div>
            </section>
        </div>
    </main>
    @include('components.popup-messages')
</body>
</html>