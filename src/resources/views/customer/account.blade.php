<h1>Mes comptes</h1>

<a href="{{ route('customer.dashboard') }}">Dashboard</a>

<br>

<h2>Créer un compte</h2>

@if(session('success'))
    <div>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div>
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('customer.accounts.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Nom du compte" required>
    <input type="text" name="description" placeholder="Description" required>
    <input type="number" step="0.01" name="solde" placeholder="Solde" required>
    <input type="number" step="0.01" name="interest_rate" placeholder="Taux intérêt" required>
    <input type="number" step="0.01" name="tax_rate" placeholder="Taux impôt" required>

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

<h2>Liste des comptes</h2>

@foreach($accounts as $account)
<div>
    <strong>{{ $account->name }}</strong><br>
    Description : {{ $account->description }}<br>
    Solde : {{ number_format($account->solde, 2, ',', ' ') }} €<br>
    Intérêt : {{ number_format($account->interest_rate, 2, ',', ' ') }} %<br>
    Impôt : {{ number_format($account->tax_rate, 2, ',', ' ') }} %

    <br>

    <button type="button" onclick="document.getElementById('edit-account-{{ $account->id }}').showModal()">
        Modifier
    </button>

    <dialog id="edit-account-{{ $account->id }}">
        <h3>Modifier {{ $account->name }}</h3>

        <form method="POST" action="{{ route('customer.accounts.update', $account) }}">
            @csrf
            @method('PUT')

            <input type="text" name="name" value="{{ $account->name }}" required>
            <input type="text" name="description" value="{{ $account->description }}" required>
            <input type="number" step="0.01" name="solde" value="{{ $account->solde }}" required>
            <input type="number" step="0.01" name="interest_rate" value="{{ $account->interest_rate }}" required>
            <input type="number" step="0.01" name="tax_rate" value="{{ $account->tax_rate }}" required>

            <button type="submit">Enregistrer</button>
            <button type="button" onclick="document.getElementById('edit-account-{{ $account->id }}').close()">
                Annuler
            </button>
        </form>
    </dialog>

    <form method="POST"
          action="{{ route('customer.accounts.delete', $account) }}"
          onsubmit="return confirm('Voulez-vous vraiment supprimer ce compte ?');">
        @csrf
        @method('DELETE')

        <button type="submit">Supprimer</button>
    </form>

    <hr>
</div>
@endforeach