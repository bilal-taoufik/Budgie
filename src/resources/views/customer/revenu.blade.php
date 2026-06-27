<h1>Mes revenus</h1>
<a href="{{ route('customer.dashboard') }}">Dashboard</a><br>

<h2>Créer un revenu</h2>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@if(session('error'))
    <div>{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('customer.revenues.store') }}">
    @csrf
    <select name="account_id" required>
        <option value="">-- Choisir un compte --</option>
        @foreach($accounts as $account)
            <option value="{{ $account->id }}">{{ $account->name }}</option>
        @endforeach
    </select>
    <input type="text" name="revenue_nom" placeholder="Nom du revenu" required>
    <input type="text" name="revenue_description" placeholder="Description">
    <input type="number" step="0.01" name="revenue_montant" placeholder="Montant" required>
    <select name="revenue_fractionnement" required>
        <option value="">-- Choisir un fractionnement --</option>
        <option value="mensuel">Tous les 1 mois</option>
        <option value="semestriel">Tous les 6 mois</option>
        <option value="annuel">Tous les 12 mois</option>
        <option value="une_fois">Une fois</option>
    </select>
    <input type="date" name="revenue_date_effet" required>
    <button type="submit">Créer</button>

    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>

<br>
<h2>Liste des revenus</h2>

@foreach($revenues as $revenu)
<div>
    <strong>{{ $revenu->revenue_nom }}</strong><br>
    Compte : {{ $revenu->account->name }}<br>
    Description : {{ $revenu->revenue_description }}<br>
    Montant : {{ number_format($revenu->revenue_montant, 2, ',', ' ') }} €<br>
    Fractionnement : {{ $revenu->revenue_fractionnement }}<br>
    Date d'effet : {{ \Carbon\Carbon::parse($revenu->revenue_date_effet)->format('d/m/Y') }}<br>

    <button type="button" onclick="document.getElementById('edit-revenu-{{ $revenu->id }}').showModal()">
        Modifier
    </button>

    <dialog id="edit-revenu-{{ $revenu->id }}">
        <h3>Modifier {{ $revenu->revenue_nom }}</h3>
        <form method="POST" action="{{ route('customer.revenues.update', $revenu) }}">
            @csrf
            @method('PUT')
            <select name="account_id" required>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" {{ $revenu->account_id == $account->id ? 'selected' : '' }}>
                        {{ $account->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="revenue_nom" value="{{ $revenu->revenue_nom }}" required>
            <input type="text" name="revenue_description" value="{{ $revenu->revenue_description }}">
            <input type="number" step="0.01" name="revenue_montant" value="{{ $revenu->revenue_montant }}" required>
            <select name="revenue_fractionnement" required>
                <option value="mensuel" {{ $revenu->revenue_fractionnement === 'mensuel' ? 'selected' : '' }}>Tous les 1 mois</option>
                <option value="semestriel" {{ $revenu->revenue_fractionnement === 'semestriel' ? 'selected' : '' }}>Tous les 6 mois</option>
                <option value="annuel" {{ $revenu->revenue_fractionnement === 'annuel' ? 'selected' : '' }}>Tous les 12 mois</option>
                <option value="une_fois" {{ $revenu->revenue_fractionnement === 'une_fois' ? 'selected' : '' }}>Une fois</option>
            </select>
            <input type="date" name="revenue_date_effet" value="{{ $revenu->revenue_date_effet }}" required>
            <button type="submit">Enregistrer</button>
            <button type="button" onclick="document.getElementById('edit-revenu-{{ $revenu->id }}').close()">
                Annuler
            </button>
        </form>
    </dialog>

    <form method="POST"
          action="{{ route('customer.revenues.delete', $revenu) }}"
          onsubmit="return confirm('Voulez-vous vraiment supprimer ce revenu ?');">
        @csrf
        @method('DELETE')
        <button type="submit">Supprimer</button>
    </form>
    <hr>
</div>
@endforeach