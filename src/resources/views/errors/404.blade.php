<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/main.scss', 'resources/js/main.js'])
    <link rel="icon" type="image/ico" href="/favicon.ico">
    <title>Page introuvable | Budgie</title>
    <meta name="robots" content="noindex">
</head>
<body>
    <main class="error-page">
        <a href="{{ route('home') }}" class="error-page__logo" aria-label="Retour à l'accueil">
            <svg width="40" height="48" viewBox="0 0 32 38" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M0 38L4.40149 0H20.9368C24.0297 0 29.6208 1.63691 29.6208 8.76923C29.6208 11.6925 28.5502 16.4861 20.9368 16.4861H18.5576L19.0335 10.9908L6.54275 17.8892L17.6059 24.32L18.0818 19.1754H25.5762C27.8364 19.1754 32 22.0985 32 28.0615C32 31.3354 29.6208 38 21.8885 38H0Z" fill="currentColor"/>
            </svg>
        </a>

        <section class="error-page__content fade-up">
            <p class="error-page__code">Erreur 404</p>
            <h1>Oupss... Cette page n'existe plus !</h1>
            <p class="error-page__description">La page que vous recherchez a peut-être été déplacée ou supprimée.</p>
            <a href="{{ route('home') }}" class="btn-primary">
                <span class="btn-primary__icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
                </span>
                <span class="btn-primary__text">RETOUR À L'ACCUEIL</span>
            </a>
        </section>

        <p class="error-page__brand">BUDGIE</p>
    </main>
</body>
</html>
