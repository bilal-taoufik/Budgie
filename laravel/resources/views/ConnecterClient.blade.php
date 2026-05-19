<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bienvenue</title>

    </head>
    <body>
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if (session('client'))
            <h1>Bienvenue {{ session('client.prenom') }} {{ session('client.nom') }}</h1>
        @else
            <h1>Bienvenue</h1>
        @endif
    </body>
</html>
