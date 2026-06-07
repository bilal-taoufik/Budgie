<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/main.js')
    <link rel="icon" type="image/ico" href="/favicon.ico">
    <title>@yield('title', 'Budgie | Authentification')</title>
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

                        <h1 class="heading-style-h2-bold text-color-white">@yield('heading')</h1>
                        <div class="margin-bottom margin-large text-color-body">@yield('intro')</div>

                        @if (session('status'))
                            <div class="w-form-done" style="display: block;">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="w-form-fail" style="display: block;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-block w-form">@yield('content')</div>

                        <div class="form-divider"></div>
                        <a href="{{ route('login') }}" class="form-back-link">Retour à la connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
