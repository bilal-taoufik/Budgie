<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-7xl p-6">
        <header class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-slate-600">Vue globale de vos comptes</p>
            </div>

            <nav class="flex flex-wrap gap-3">
                <a class="rounded bg-white px-4 py-2 shadow" href="{{ route('customer.accounts.index') }}">Comptes</a>
                <a class="rounded bg-white px-4 py-2 shadow" href="{{ route('customer.depenses.index') }}">Depenses</a>
                <a class="rounded bg-white px-4 py-2 shadow" href="{{ route('customer.revenues.index') }}">Revenus</a>
                <a class="rounded bg-white px-4 py-2 shadow" href="{{ route('customer.previsions.index') }}">Previsions</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded bg-slate-900 px-4 py-2 text-white" type="submit">Logout</button>
                </form>
            </nav>
        </header>

        <section class="mb-6 grid gap-4 md:grid-cols-3">
            <article class="rounded bg-white p-5 shadow">
                <p class="text-sm text-slate-500">Solde total</p>
                <strong class="mt-2 block text-2xl">{{ number_format($soldeTotal, 2, ',', ' ') }} &euro;</strong>
            </article>

            <article class="rounded bg-white p-5 shadow">
                <p class="text-sm text-slate-500">Revenu ce mois</p>
                <strong class="mt-2 block text-2xl text-emerald-600">{{ number_format($revenuCeMois, 2, ',', ' ') }} &euro;</strong>
            </article>

            <article class="rounded bg-white p-5 shadow">
                <p class="text-sm text-slate-500">Depense ce mois</p>
                <strong class="mt-2 block text-2xl text-red-600">{{ number_format($depenseCeMois, 2, ',', ' ') }} &euro;</strong>
            </article>
        </section>

        <section class="mb-6 rounded bg-white p-5 shadow">
            <div class="mb-4 flex items-center justify-between gap-4">
                <h2 class="text-xl font-semibold">Comptes</h2>
                <a class="rounded bg-slate-900 px-4 py-2 text-sm text-white" href="{{ route('customer.accounts.index') }}">Gerer</a>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse($accounts as $account)
                    <article class="rounded border border-slate-200 p-4">
                        <h3 class="font-semibold">{{ $account->name }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $account->description }}</p>
                        <p class="mt-3 text-xl font-bold">{{ number_format($account->solde, 2, ',', ' ') }} &euro;</p>
                    </article>
                @empty
                    <p class="text-slate-500">Aucun compte pour le moment.</p>
                @endforelse
            </div>
        </section>

        <section class="mb-6 grid gap-6 lg:grid-cols-2">
            <article class="rounded bg-white p-5 shadow">
                <h2 class="mb-4 text-xl font-semibold">Evolution du solde</h2>
                <div class="h-72">
                    <canvas id="balanceChart"></canvas>
                </div>
            </article>

            <article class="rounded bg-white p-5 shadow">
                <h2 class="mb-4 text-xl font-semibold">Repartition des depenses</h2>
                <div class="h-72">
                    <canvas id="depensePieChart"></canvas>
                </div>
            </article>
        </section>

        <section class="mb-6 rounded bg-white p-5 shadow">
            <h2 class="mb-4 text-xl font-semibold">Revenus vs depenses</h2>
            <div class="h-80">
                <canvas id="revenuDepenseChart"></canvas>
            </div>
        </section>

        <section class="rounded bg-white p-5 shadow">
            <h2 class="mb-4 text-xl font-semibold">Transactions recentes</h2>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-left text-sm">
                    <thead>
                        <tr class="border-b text-slate-500">
                            <th class="py-3">Date</th>
                            <th class="py-3">Type</th>
                            <th class="py-3">Nom</th>
                            <th class="py-3">Compte</th>
                            <th class="py-3 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactionsRecentes as $transaction)
                            <tr class="border-b last:border-b-0">
                                <td class="py-3">{{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}</td>
                                <td class="py-3">{{ $transaction['type'] }}</td>
                                <td class="py-3">{{ $transaction['nom'] }}</td>
                                <td class="py-3">{{ $transaction['compte'] }}</td>
                                <td class="py-3 text-right {{ $transaction['montant'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ number_format($transaction['montant'], 2, ',', ' ') }} &euro;
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 text-slate-500" colspan="5">Aucune transaction recente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        window.dashboardCharts = @json($charts);
    </script>
</body>
</html>

