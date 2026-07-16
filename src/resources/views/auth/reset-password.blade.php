<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/main.scss', 'resources/js/main.js'])
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Budgie | Nouveau mot de passe</title>
</head>
<body>
    <main class="main-wrapper">
        <div class="page-wrapper">
            <section class="section_forms-inscription">
                <div class="padding-global padding-section-large">
                    <div class="component-form">
                        <div class="max-width-xxlarge">
                            <div class="margin-bottom margin-tiny">
                                <h1 class="heading-style-h2-bold text-color-white">Nouveau mot de passe</h1>
                            </div>
                            <div class="margin-bottom margin-large">
                                <p class="text-size-regular text-color-body">Choisissez un mot de passe unique et securise.</p>
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