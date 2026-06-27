<h1>Mes dépenses</h1>
<a href="{{ route('customer.dashboard') }}">Dashboard</a><br>

<h2>Créer une dépense</h2>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@if(session('error'))
    <div>{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('customer.depenses.store') }}">
    @csrf
    <select name="account_id" required>
        <option value="">-- Choisir un compte --</option>
        @foreach($accounts as $account)
            <option value="{{ $account->id }}">{{ $account->name }}</option>
        @endforeach
    </select>
    <input type="text" name="nom" placeholder="Nom de la dépense" required>
    <input type="text" name="description" placeholder="Description">
    <input type="number" step="0.01" name="montant" placeholder="Montant" required>
    <select name="fractionnement" required>
        <option value="">-- Choisir un fractionnement --</option>
        <option value="mensuel">Tous les 1 mois</option>
        <option value="semestriel">Tous les 6 mois</option>
        <option value="annuel">Tous les 12 mois</option>
        <option value="une_fois">Une fois</option>
    </select>
    <input type="date" name="date_effet" required>
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
<h2>Liste des dépenses</h2>

@foreach($depenses as $depense)
<div>
    <strong>{{ $depense->nom }}</strong><br>
    Compte : {{ $depense->account->name }}<br>
    Description : {{ $depense->description }}<br>
    Montant : {{ number_format($depense->montant, 2, ',', ' ') }} €<br>
    Fractionnement : {{ $depense->fractionnement }}<br>
    Date d'effet : {{ \Carbon\Carbon::parse($depense->date_effet)->format('d/m/Y') }}<br>

    <button type="button" onclick="document.getElementById('edit-depense-{{ $depense->id }}').showModal()">
        Modifier
    </button>

    <dialog id="edit-depense-{{ $depense->id }}">
        <h3>Modifier {{ $depense->nom }}</h3>
        <form method="POST" action="{{ route('customer.depenses.update', $depense) }}">
            @csrf
            @method('PUT')
            <select name="account_id" required>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" {{ $depense->account_id == $account->id ? 'selected' : '' }}>
                        {{ $account->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="nom" value="{{ $depense->nom }}" required>
            <input type="text" name="description" value="{{ $depense->description }}">
            <input type="number" step="0.01" name="montant" value="{{ $depense->montant }}" required>
            <select name="fractionnement" required>
                <option value="mensuel" {{ $depense->fractionnement === 'mensuel' ? 'selected' : '' }}>Tous les 1 mois</option>
                <option value="semestriel" {{ $depense->fractionnement === 'semestriel' ? 'selected' : '' }}>Tous les 6 mois</option>
                <option value="annuel" {{ $depense->fractionnement === 'annuel' ? 'selected' : '' }}>Tous les 12 mois</option>
                <option value="une_fois" {{ $depense->fractionnement === 'une_fois' ? 'selected' : '' }}>Une fois</option>
            </select>
            <input type="date" name="date_effet" value="{{ $depense->date_effet }}" required>
            <button type="submit">Enregistrer</button>
            <button type="button" onclick="document.getElementById('edit-depense-{{ $depense->id }}').close()">
                Annuler
            </button>
        </form>
    </dialog>

    <form method="POST"
          action="{{ route('customer.depenses.delete', $depense) }}"
          onsubmit="return confirm('Voulez-vous vraiment supprimer cette dépense ?');">
        @csrf
        @method('DELETE')
        <button type="submit">Supprimer</button>
    </form>
    <hr>
</div>
@endforeach