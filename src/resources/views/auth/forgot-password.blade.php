<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/main.scss', 'resources/js/main.js'])
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Budgie | Mot de passe oublie</title>
</head>
<body>
    <main class="main-wrapper">
        <div class="page-wrapper">
            <section class="section_forms-inscription">
                <div class="padding-global padding-section-large">
                    <div class="component-form">
                        <div class="max-width-xxlarge">
                            <div class="margin-bottom margin-tiny">
                                <h1 class="heading-style-h2-bold text-color-white">Mot de passe oublie</h1>
                            </div>
                            <div class="margin-bottom margin-large">
                                <p class="text-size-regular text-color-body">Recevez un lien securise pour choisir un nouveau mot de passe.</p>
                            </div>
                        </div>
                        <div class="form-block">
                            <form class="form-content" method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-field-wrapper">
                                    <input class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder="Email*" autocomplete="email" required autofocus>
                                </div>
                                <button class="btn-secondary is-full" type="submit">Envoyer le lien</button>
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