<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/main.js')
    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />
    <title>Budgie | Votre partenaire financier</title>

    <!-- Finsweet Cookie Consent -->
    <script async src="https://cdn.jsdelivr.net/npm/@finsweet/cookie-consent@1/fs-cc.js"></script>

</head>
<body>
    <header class="header-wrapper">
        <div class="padding-global">

            <div class="header-component">

                <!-- LOGO MOBILE -->
                <a href="{{ url('/') }}" class="header-logo" aria-label="Accueil">
                    <svg width="32" height="38" viewBox="0 0 32 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 38L4.40149 0H20.9368C24.0297 0 29.6208 1.63691 29.6208 8.76923C29.6208 11.6925 28.5502 16.4861 20.9368 16.4861H18.5576L19.0335 10.9908L6.54275 17.8892L17.6059 24.32L18.0818 19.1754H25.5762C27.8364 19.1754 32 22.0985 32 28.0615C32 31.3354 29.6208 38 21.8885 38H0Z" fill="#85A795"/>
                    </svg>
                </a>

                <!-- TOGGLE -->
                <input type="checkbox" id="menu-toggle" class="menu-toggle">

                <!-- BURGER -->
                <label for="menu-toggle" class="burger-button" aria-label="Ouvrir le menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>

                <!-- DESKTOP NAV -->
                <nav class="header-nav" id="headerNav" aria-label="Navigation principale">
                    <ul class="header-nav-list">
                        <li class="header-nav-item">
                            <a href="#fonction" class="header-nav-link">
                                Fonctionnalités
                            </a>
                        </li>

                        <li class="header-nav-item">
                            <a href="#about" class="header-nav-link">
                                À propos
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- DESKTOP CTA -->
                <div class="wrapper_cta-header">
                    <a href="{{ route('login') }}" class="btn-secondary is-simple">
                        <span>CONNEXION</span>
                    </a>

                    <a href="{{ route('register') }}" class="btn-primary">
                        <span class="btn-primary__icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9 5l7 7-7 7" />
                            </svg>
                        </span>

                        <span class="btn-primary__text">
                            S'INSCRIRE
                        </span>
                    </a>
                </div>

                <!-- MOBILE MENU -->
                <div class="header-mobile-menu">

                    <nav class="header-mobile-nav">
                        <ul class="header-mobile-list">

                            <li>
                                <a href="#fonction" class="text-size-regular text-color-body">
                                    Fonctionnalités
                                </a>
                            </li>

                            <li>
                                <a href="#about" class="text-size-regular text-color-body">
                                    À propos
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('login') }}" class="text-size-regular text-color-body">
                                    Connexion
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('register') }}" class="text-size-regular text-color-body">
                                    Inscription
                                </a>
                            </li>

                        </ul>
                    </nav>

                </div>

            </div>

        </div>
    </header>

    <main class="main-wrapper">
        <div class="page-wrapper">

            <div class="section_header-home" id="about">
                <div class="hero-content">
                    <div class="padding-global padding-section-large">
                        <div class="hero-layout fade-left reveal-delay-2">
                            <div class="max-width-large hero-text-wrapper">
                                <div class="hero-title-group ">
                                    <h1 class="h1-hero">Votre partenaire financier personnel</h1>
                                </div>

                                <p class="text-size-medium text-color-body">
                                    Budgie vous aide à suivre vos dépenses, gérer vos comptes et planifier votre avenir financier.<br>
                                    Simple, sécurisé et sans connexion bancaire requise.
                                </p>

                                <div class="hero-button-group">
                                    <a href="{{ route('login') }}" class="btn-secondary">
                                        <span class="btn-primary__text">CONNEXION</span>
                                    </a>

                                    <a href="{{ route('register') }}" class="btn-primary">
                                        <span class="btn-primary__icon">
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>

                                        <span class="btn-primary__text">S'INSCRIRE</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section_fonction-home" id="fonction">
                <div class="padding-global padding-section-large">
                    <div class="fonction-layout">

                        <div class="fonction-content">
                            <div class="badge-section">
                                <span>Valeurs</span>
                            </div>

                            <div class="fonction-title-group">
                                <h2 class="heading-style-h2-bold text-color-white">
                                    Fonctionnalités essentielles
                                </h2>

                                <p class="text-size-regular text-color-body">
                                    Tout ce dont vous avez besoin pour gérer vos finances personnelles.
                                </p>
                            </div>
                        </div>

                        <div class="fonction-grid">
                            <div class="fonction-card">
                                <div class="fonction-icon">
                                    <img src="{{ asset('assets/icons/wallet.svg') }}" alt="">
                                </div>

                                <div class="fonction-card-content">
                                    <h3 class="heading-style-h3-bold text-color-white">Gestion des comptes</h3>
                                    <p class="text-size-regular text-color-body">Créez et gérez plusieurs comptes</p>
                                </div>
                            </div>

                            <div class="fonction-card">
                                <div class="fonction-icon">
                                    <img src="{{ asset('assets/icons/chart-down.svg') }}" alt="">
                                </div>

                                <div class="fonction-card-content">
                                    <h3 class="heading-style-h3-bold text-color-white">Suivi des Dépenses</h3>
                                    <p class="text-size-regular text-color-body">Enregistrez vos dépenses ponctuelles facilement</p>
                                </div>
                            </div>

                            <div class="fonction-card">
                                <div class="fonction-icon">
                                    <img src="{{ asset('assets/icons/chart-up.svg') }}" alt="">
                                </div>

                                <div class="fonction-card-content">
                                    <h3 class="heading-style-h3-bold text-color-white">Gestion des Revenus</h3>
                                    <p class="text-size-regular text-color-body">Enregistrez vos salaires, autres sources de revenus</p>
                                </div>
                            </div>

                            <div class="fonction-card">
                                <div class="fonction-icon">
                                    <img src="{{ asset('assets/icons/stats.svg') }}" alt="">
                                </div>

                                <div class="fonction-card-content">
                                    <h3 class="heading-style-h3-bold text-color-white">Prévisions</h3>
                                    <p class="text-size-regular text-color-body">Visualisez l'état de vos comptes à une date future</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section_cta-home">
                <div class="padding-global padding-section-large">

                    <div class="cta-home-wrapper">

                        <div class="cta-home-content">

                            <div class="cta-home-logo">
                                <svg width="74" height="88" viewBox="0 0 32 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 38L4.40149 0H20.9368C24.0297 0 29.6208 1.63691 29.6208 8.76923C29.6208 11.6925 28.5502 16.4861 20.9368 16.4861H18.5576L19.0335 10.9908L6.54275 17.8892L17.6059 24.32L18.0818 19.1754H25.5762C27.8364 19.1754 32 22.0985 32 28.0615C32 31.3354 29.6208 38 21.8885 38H0Z" fill="#A7B8AD"/>
                                </svg>
                            </div>

                            <div class="cta-home-text-group">
                                <h2 class="heading-style-h2-bold text-color-white">
                                    Prêt à prendre le contrôle de vos finances ?
                                </h2>

                                <p class="text-size-large text-weight-medium text-color-body">
                                    Rejoignez des milliers d'utilisateurs qui font confiance à Budgie pour gérer leurs finances
                                </p>
                            </div>

                            <div class="cta-home-button">
                                <a href="{{ route('register') }}" class="btn-primary">
                                    <span class="btn-primary__icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                    <span class="btn-primary__text">S'INSCRIRE</span>
                                </a>
                            </div>
                        </div>
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

    <footer class="footer-wrapper">
        <div class="footer_component">
            <div class="padding-global padding-section-large">

                <div class="footer-layout">

                    <div class="footer-brand">
                        <a href="{{ url('/') }}" class="footer-logo">
                            <svg width="32" height="38" viewBox="0 0 32 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 38L4.40149 0H20.9368C24.0297 0 29.6208 1.63691 29.6208 8.76923C29.6208 11.6925 28.5502 16.4861 20.9368 16.4861H18.5576L19.0335 10.9908L6.54275 17.8892L17.6059 24.32L18.0818 19.1754H25.5762C27.8364 19.1754 32 22.0985 32 28.0615C32 31.3354 29.6208 38 21.8885 38H0Z" fill="#A7B8AD"/>
                            </svg>
                        </a>

                        <p class="text-size-large text-color-body">
                            Votre partenaire financier personnel
                        </p>
                    </div>

                    <div class="footer-nav-wrapper">

                        <div class="footer-nav-group">
                            <h3 class="heading-style-h4-bold text-color-white">Liens rapides</h3>

                            <ul>
                                <li><a href="#fonction">Fonctionnalités</a></li>
                                <li><a href="{{ route('login') }}">Connexion</a></li>
                                <li><a href="{{ route('register') }}">Inscription</a></li>
                            </ul>
                        </div>

                        <div class="footer-nav-group">
                            <h3 class="heading-style-h4-bold text-color-white">Ressources</h3>

                            <ul>
                                <li><a fs-cc="open-preferences" role="button" tabindex="0" title="Cookie Preferences" aria-label="Cookie Preferences" href="#" class="fs-cc-manager2_button-footer">Cookies</a></li>
                                <li><a href="#about">À propos</a></li>
                                <li><a href="#">Mentions légales</a></li>
                            </ul>
                        </div>

                    </div>

                </div>

                <div class="footer-divider"></div>

                <div class="footer-bottom">
                    <p class="text-size-regular text-color-body">
                        © 2026 Budgie. Projet réaliser par des étudiants - ESGI
                    </p>
                </div>

            </div>
        </div>
    </footer>
</body>
</html>
