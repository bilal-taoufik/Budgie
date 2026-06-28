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
    <input type="text" name="revenu_nom" placeholder="Nom du revenu" required>
    <input type="text" name="revenu_description" placeholder="Description">
    <input type="number" step="0.01" name="revenu_montant" placeholder="Montant" required>
    <select name="revenu_fractionnement" required>
        <option value="">-- Choisir un fractionnement --</option>
        <option value="mensuel">Tous les 1 mois</option>
        <option value="semestriel">Tous les 6 mois</option>
        <option value="annuel">Tous les 12 mois</option>
        <option value="unique">Unique</option>
    </select>
    <input type="date" name="revenu_date_effet" required>
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
    <strong>{{ $revenu->revenu_nom }}</strong><br>
    Compte : {{ $revenu->account->name }}<br>
    Description : {{ $revenu->revenu_description }}<br>
    Montant : {{ number_format($revenu->revenu_montant, 2, ',', ' ') }} €<br>
    Fractionnement : {{ $revenu->revenu_fractionnement }}<br>
    Date d'effet : {{ \Carbon\Carbon::parse($revenu->revenu_date_effet)->format('d/m/Y') }}<br>

    <button type="button" onclick="document.getElementById('edit-revenu-{{ $revenu->id }}').showModal()">
        Modifier
    </button>

    <dialog id="edit-revenu-{{ $revenu->id }}">
        <h3>Modifier {{ $revenu->revenu_nom }}</h3>
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
            <input type="text" name="revenu_nom" value="{{ $revenu->revenu_nom }}" required>
            <input type="text" name="revenu_description" value="{{ $revenu->revenu_description }}">
            <input type="number" step="0.01" name="revenu_montant" value="{{ $revenu->revenu_montant }}" required>
            <select name="revenu_fractionnement" required>
                <option value="mensuel" {{ $revenu->revenu_fractionnement === 'mensuel' ? 'selected' : '' }}>Tous les 1 mois</option>
                <option value="semestriel" {{ $revenu->revenu_fractionnement === 'semestriel' ? 'selected' : '' }}>Tous les 6 mois</option>
                <option value="annuel" {{ $revenu->revenu_fractionnement === 'annuel' ? 'selected' : '' }}>Tous les 12 mois</option>
                <option value="unique" {{ $revenu->revenu_fractionnement === 'unique' ? 'selected' : '' }}>Unique</option>
            </select>
            <input type="date" name="revenu_date_effet" value="{{ $revenu->revenu_date_effet }}" required>
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