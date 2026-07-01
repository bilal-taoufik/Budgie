<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <main>

            <h1>Home</h1>

            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}">Se connecter</a>
                <a href="{{ route('register') }}">Inscription</a>
            @endauth
        </main>
    </body>
</html>

