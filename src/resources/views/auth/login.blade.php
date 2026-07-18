<!DOCTYPE html>
<html lang="fr">
<head>
    <meta lharset="UTF-8">
    <meta name="viewport" lontent="width=device-width, initial-scale=1.0">
    @vite(['resources/css/main.scss', 'resources/js/main.js'])
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <title>Budgie | Connexion</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Connectez-vous à votre espace Budgie pour consulter vos comptes, suivre votre budget et gérer vos finances personnelles.">

    <!-- Finsweet Cookie Consent -->
    <script async fs-cc-mode="opt-in" src="https://cdn.jsdelivr.net/npm/@finsweet/cookie-consent@1/fs-cc.js"></script>

</head>
<body>

    <main class="main-wrapper">
        <div class="page-wrapper">


            <div class="section_forms-inscription">
                <div class="padding-global padding-section-large">
                    <div class="component-form scale-in reveal-delay-1">

                        <div class="margin-bottom margin-medium">
                            <svg width="50" height="59" viewBox="0 0 50 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 59L6.87732 0H32.7138C37.5465 0 46.2825 2.54152 46.2825 13.6154C46.2825 18.1542 44.6097 25.5969 32.7138 25.5969H28.9963L29.7398 17.0646L10.223 27.7754L27.5093 37.76L28.2528 29.7723H39.9628C43.4944 29.7723 50 34.3108 50 43.5692C50 48.6523 46.2825 59 34.2007 59H0Z" fill="#85A795"/>
                            </svg>
                        </div>

                        <div class="max-width-xxlarge">
                            <div class="margin-bottom margin-small">
                                <h1 class="heading-style-h2-bold text-color-white">
                                    Connectez-vous !
                                </h1>
                            </div>

                            <div class="margin-bottom margin-large">
                                <h2 class="text-size-regular text-color-body">
                                    Accédez à votre espace personnel
                                </h2>
                            </div>
                        </div>

                        <div class="form-block">
                            <form class="form-content" id="login-form" method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-field-wrapper">
                                    <input 
                                        type="email" 
                                        class="form-input"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="Email*"
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
                                    >
                                </div>


                                <button type="submit" class="btn-secondary is-full">
                                    Se connecter
                                </button>

                            </form>
                        </div>


                            @if(session('btn_resend'))
                                <form method="POST" action="{{ route('resend.verification') }}">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ session('email') }}">
                                    <button type="submit" class="btn-secondary is-full">
                                        Renvoyer l'e-mail de verification
                                    </button>
                                </form>
                            @endif

                        <div class="form-bottom">
                            <a class="text-size-small text-color-brand" href="{{ route('password.request') }}">Mot de passe oublie ?</a>

                            <p class="text-size-regular text-color-body">
                                Pas encore de compte ?
                                <a class="text-size-regular text-color-brand" href="{{ route('register') }}">
                                S'inscrire
                                </a>
                            </p>
                        </div>

                        <div class="form-divider"></div>

                        <a href="{{ route('home') }}" class="form-back-link">
                            <span>Retour à l'accueil</span>
                        </a>

                    </div>
                </div>
            </div>
            
            <div class="cookie-component">
                <div fs-cc="banner" class="fs-cc-banner2_component">
                    <div class="fs-cc-banner2_container">
                    <div class="fs-cc-banner2_text">
                        En cliquant sur « Accepter les cookies », vous consentez au stockage de cookies sur votre appareil afin d'améliorer la navigation sur le site, d'analyser son utilisation et de soutenir nos actions marketing.
                    </div>

                    <div class="fs-cc-banner2_buttons-wrapper">
                        <button fs-cc="open-preferences" type="button" class="button fs-cc-banner2_button is-secondary">
                        Paramètres
                        </button>

                        <button fs-cc="allow" type="button" class="button fs-cc-banner2_button">
                        Accepter
                        </button>
                    </div>
                    </div>
                </div>

                <div fs-cc="preferences" fs-cc-scroll="disable" class="fs-cc-prefs2_component">
                    <div class="fs-cc-prefs2_overlay" fs-cc="close"></div>

                    <div class="fs-cc-prefs2_form-wrapper">
                    <form id="cookie-preferences" class="fs-cc-prefs2_form">
                        <button fs-cc="close" type="button" class="fs-cc-prefs2_close" aria-label="Close cookie preferences">
                        <svg fill="currentColor" aria-hidden="true" focusable="false" viewBox="0 0 16 16">
                            <path d="M9.414 8l4.293-4.293-1.414-1.414L8 6.586 3.707 2.293 2.293 3.707 6.586 8l-4.293 4.293 1.414 1.414L8 9.414l4.293 4.293 1.414-1.414L9.414 8z"></path>
                        </svg>
                        </button>

                        <div class="fs-cc-prefs2_content">
                        <div class="fs-cc-prefs2_header">
                            <h3 class="fs-cc-prefs2_title">Préférences des cookies</h3>
                            <p class="fs-cc-prefs2_text">
                            Choisissez les cookies que vous souhaitez autoriser. Les cookies essentiels sont nécessaires au bon fonctionnement du site web.
                            </p>
                        </div>

                        <div class="fs-cc-prefs2_option">
                            <div class="fs-cc-prefs2_toggle-wrapper">
                            <div>
                                <div class="fs-cc-prefs2_label">Essentiels</div>
                                <div class="fs-cc-prefs2_description">
                                Nécessaires au bon fonctionnement du site web.
                                </div>
                            </div>
                            <div class="fs-cc-prefs2_required">Obligatoires</div>
                            </div>
                        </div>

                        <div class="fs-cc-prefs2_option">
                            <div class="fs-cc-prefs2_toggle-wrapper">
                            <div>
                                <div class="fs-cc-prefs2_label">Marketing</div>
                                <div class="fs-cc-prefs2_description">
                                Utilisés pour proposer des publicités et des campagnes marketing pertinentes.
                                </div>
                            </div>

                            <label class="fs-cc-prefs2_checkbox-field" for="marketing-2">
                                <input
                                type="checkbox"
                                name="marketing-2"
                                id="marketing-2"
                                fs-cc-checkbox="marketing"
                                class="fs-cc-prefs2_checkbox-input"
                                >
                                <span class="fs-cc-prefs2_checkbox-ui"></span>
                            </label>
                            </div>
                        </div>

                        <div class="fs-cc-prefs2_option">
                            <div class="fs-cc-prefs2_toggle-wrapper">
                            <div>
                                <div class="fs-cc-prefs2_label">Personnalisation</div>
                                <div class="fs-cc-prefs2_description">
                                Permet au site de mémoriser vos préférences et de proposer des fonctionnalités améliorées.
                                </div>
                            </div>

                            <label class="fs-cc-prefs2_checkbox-field" for="personalization-2">
                                <input
                                type="checkbox"
                                name="personalization-2"
                                id="personalization-2"
                                fs-cc-checkbox="personalization"
                                class="fs-cc-prefs2_checkbox-input"
                                >
                                <span class="fs-cc-prefs2_checkbox-ui"></span>
                            </label>
                            </div>
                        </div>

                        <div class="fs-cc-prefs2_option">
                            <div class="fs-cc-prefs2_toggle-wrapper">
                            <div>
                                <div class="fs-cc-prefs2_label">Analytics</div>
                                <div class="fs-cc-prefs2_description">
                                Nous aide à comprendre comment les visiteurs interagissent avec le site web.
                                </div>
                            </div>

                            <label class="fs-cc-prefs2_checkbox-field" for="analytics-2">
                                <input
                                type="checkbox"
                                name="analytics-2"
                                id="analytics-2"
                                fs-cc-checkbox="analytics"
                                class="fs-cc-prefs2_checkbox-input"
                                >
                                <span class="fs-cc-prefs2_checkbox-ui"></span>
                            </label>
                            </div>
                        </div>

                        <div class="fs-cc-prefs2_buttons-wrapper">
                            <div class="fs-cc-prefs2_buttons-container">
                            <button fs-cc="deny" type="button" class="button fs-cc-banner2_button">
                                Refuser tous les cookies
                            </button>

                            <button fs-cc="allow" type="button" class="button fs-cc-banner2_button">
                                Autoriser tous les cookies
                            </button>
                            </div>

                            <button fs-cc="submit" type="submit" class="button fs-cc-banner2_button">
                            Enregistrer les préférences
                            </button>
                        </div>
                        </div>
                    </form>

                    <div class="w-form-done" tabindex="-1" role="region" aria-label="Cookie Preferences success">
                        Vos préférences ont été enregistrées.
                    </div>

                    <div class="w-form-fail" tabindex="-1" role="region" aria-label="Cookie Preferences failure">
                        Une erreur est survenue. Veuillez réessayer.
                    </div>
                    </div>
                </div>

                <!-- <button fs-cc="open-preferences" type="button" class="cookie-settings-trigger button is-secondary">
                    Manage cookies
                </button> -->
            </div>
        </div>
    </main>
    @php
        $popupMessages = collect();

        foreach (['success', 'error', 'info'] as $type) {
            if (session($type)) {
                $popupMessages->push([
                    'type' => $type,
                    'message' => session($type),
                ]);
            }
        }

        foreach ($errors->all() as $error) {
            $popupMessages->push([
                'type' => 'error',
                'message' => $error,
            ]);
        }
    @endphp

    @if($popupMessages->isNotEmpty())
        <div class="popup-wrapper" id="popup-wrapper">
            @foreach($popupMessages as $popup)
                <div class="popup-message popup-{{ $popup['type'] }}">
                    {{ $popup['message'] }}
                </div>
            @endforeach
        </div>

        <script>
            setTimeout(() => {
                const popupWrapper = document.getElementById('popup-wrapper');

                if (popupWrapper) {
                    popupWrapper.style.display = 'none';
                }
            }, 8000); // 8 seconds
        </script>
    @endif

</body>
</html>








