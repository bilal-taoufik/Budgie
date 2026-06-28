<h1>Previsions</h1>

<a href="{{ route('customer.dashboard') }}">Dashboard</a><br>

<h2>Solde a une date</h2>
<form method="GET" action="{{ route('customer.previsions.index') }}">
    <label for="date_prevision">Date</label>
    <input
        type="date"
        id="date_prevision"
        name="date_prevision"
        value="{{ $selectedDate->toDateString() }}"
        required
    >
    <button type="submit">Calculer</button>
</form>

@if($selectedDate->isPast() && ! $selectedDate->isToday())
    <p>Choisis une date aujourd'hui ou dans le futur pour une prevision.</p>
@endif

@if($previsions->isEmpty())
    <p>Aucun compte disponible pour calculer une prevision.</p>
@endif

@foreach($previsions as $prevision)
    <h2>{{ $prevision['account']->name }}</h2>
    <p>Solde actuel : {{ number_format($prevision['account']->solde, 2, ',', ' ') }} €</p>

    <h3>Resultat au {{ $selectedDate->format('d/m/Y') }}</h3>
    <ul>
        <li>Revenus prevus : {{ number_format($prevision['date']['revenus'], 2, ',', ' ') }} €</li>
        <li>Depenses prevues : {{ number_format($prevision['date']['depenses'], 2, ',', ' ') }} €</li>
        <li>Variation : {{ number_format($prevision['date']['variation'], 2, ',', ' ') }} €</li>
        <li><strong>Solde prevu : {{ number_format($prevision['date']['solde'], 2, ',', ' ') }} €</strong></li>
    </ul>

    <br>
@endforeach
