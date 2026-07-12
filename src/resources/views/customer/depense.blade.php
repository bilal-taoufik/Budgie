<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/main.scss', 'resources/js/main.js'])
    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />
    <title>Budgie | Depenses</title>
</head>
<body>
    <main class="main-wrapper">
        <div class="page-wrapper">
            <div class="dashboard-page">
                <div class="dashboard-sidebar">
                    <a href="{{ route('home') }}" class="dashboard-logo" aria-label="Accueil">
                        <svg width="32" height="38" viewBox="0 0 32 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 38L4.40149 0H20.9368C24.0297 0 29.6208 1.63691 29.6208 8.76923C29.6208 11.6925 28.5502 16.4861 20.9368 16.4861H18.5576L19.0335 10.9908L6.54275 17.8892L17.6059 24.32L18.0818 19.1754H25.5762C27.8364 19.1754 32 22.0985 32 28.0615C32 31.3354 29.6208 38 21.8885 38H0Z" fill="#85A795"/>
                        </svg>
                    </a>

                    <input type="checkbox" id="dashboard-menu-toggle" class="dashboard-menu-toggle">

                    <label for="dashboard-menu-toggle" class="dashboard-burger" aria-label="Ouvrir le menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </label>

                    <nav class="dashboard-nav">
                        <a href="{{ route('customer.dashboard') }}" class="nav-link"><span>Tableau de bord</span></a>
                        <a href="{{ route('customer.accounts.index') }}" class="nav-link"><span>Comptes</span></a>
                        <a href="{{ route('customer.depenses.index') }}" class="nav-link is-active"><span>Depenses</span></a>
                        <a href="{{ route('customer.revenues.index') }}" class="nav-link"><span>Revenus</span></a>
                        <a href="{{ route('customer.previsions.index') }}" class="nav-link"><span>Previsions</span></a>
                    </nav>

                    <div class="dashboard-sidebar-bottom">
                        <a href="{{ route('customer.profile.index') }}" class="nav-link"><span>Profil</span></a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link logout-button"><span>Deconnexion</span></button>
                        </form>
                    </div>
                </div>

                <div class="dashboard-main">
                    <div class="dashboard-header">
                        <div>
                            <h1 class="heading-style-h3-bold">Depenses</h1>
                            <p class="text-color-body text-size-small">Creer, modifier et supprimer vos depenses.</p>
                        </div>
                        <a class="client-back-link" href="{{ route('customer.dashboard') }}">Retour au dashboard</a>
                    </div>

                    <section class="dashboard-content client-sections-content">


                        <section class="client-section">
                            <article class="dashboard-card entity-form-card">
                                <h3 class="heading-style-h5-bold">Creer</h3>

                                <form class="dashboard-form" method="POST" action="{{ route('customer.depenses.store') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="depense">
                                    <label>Nom*<input name="nom" type="text" value="{{ old('nom') }}" required></label>
                                    <label>Description*<input name="description" type="text" value="{{ old('description') }}"></label>
                                    <label>Compte*
                                        <select name="account_id" required>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>{{ $account->nom }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label>Montant*<input name="montant" type="number" step="0.01" min="0" value="{{ old('montant') }}" required></label>
                                    <label>Fractionnement*
                                        <select name="fractionnement" required>
                                            <option value="mensuel" @selected(old('fractionnement') === 'mensuel')>Mensuel</option>
                                            <option value="semestriel" @selected(old('fractionnement') === 'semestriel')>Semestriel</option>
                                            <option value="annuel" @selected(old('fractionnement') === 'annuel')>Annuel</option>
                                            <option value="unique" @selected(old('fractionnement') === 'unique')>Unique</option>
                                        </select>
                                    </label>
                                    <label>Date d'effet*<input name="date_effet" type="date" value="{{ old('date_effet', now()->format('Y-m-d')) }}" required></label>
                                    <label>Date de fin<input name="date_fin" type="date" value="{{ old('date_fin') }}"></label>
                                    <button type="submit" class="dashboard-action-button">Enregistrer</button>
                                </form>
                            </article>

                            <div class="entity-grid">
                                @forelse($transactions as $transaction)
                                    <article class="dashboard-card entity-card">
                                        <span class="entity-badge">{{ ucfirst($transaction->fractionnement) }}</span>
                                        <h3 class="heading-style-h5-bold">{{ $transaction->nom }}</h3>
                                        <p class="text-color-body">{{ $transaction->description ?? 'Aucune description' }}</p>

                                        <dl class="entity-details">
                                            <div><dt>Compte</dt><dd>{{ $transaction->account?->nom ?? '-' }}</dd></div>
                                            <div><dt>Montant</dt><dd>{{ number_format($transaction->montant, 2, ',', ' ') }} €</dd></div>
                                            <div><dt>Date d'effet</dt><dd>{{ $transaction->date_effet->format('d/m/Y') }}</dd></div>
                                            <div><dt>Date de fin</dt><dd>{{ $transaction->date_fin?->format('d/m/Y') ?? '-' }}</dd></div>
                                        </dl>

                                        <details class="entity-edit">
                                            <summary class="icon-action" title="Modifier" aria-label="Modifier"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.5 3.5C17.3284 2.67157 18.6716 2.67157 19.5 3.5C20.3284 4.32843 20.3284 5.67157 19.5 6.5L7 19L3 20L4 16L16.5 3.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></summary>

                                            <form class="dashboard-form" method="POST" action="{{ route('customer.depenses.update', $transaction) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="type" value="depense">
                                                <label>Nom<input name="nom" type="text" value="{{ $transaction->nom }}" required></label>
                                                <label>Description<input name="description" type="text" value="{{ $transaction->description }}"></label>
                                                <label>Compte
                                                    <select name="account_id" required>
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}" @selected($transaction->account_id == $account->id)>{{ $account->nom }}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                                <label>Montant<input name="montant" type="number" step="0.01" min="0" value="{{ $transaction->montant }}" required></label>
                                                <label>Date d'effet<input name="date_effet" type="date" value="{{ $transaction->date_effet->format('Y-m-d') }}" required></label>
                                                <label>Date de fin<input name="date_fin" type="date" value="{{ $transaction->date_fin?->format('Y-m-d') }}"></label>
                                                <label>Fractionnement
                                                    <select name="fractionnement" required>
                                                        <option value="mensuel" @selected($transaction->fractionnement === 'mensuel')>Mensuel</option>
                                                        <option value="semestriel" @selected($transaction->fractionnement === 'semestriel')>Semestriel</option>
                                                        <option value="annuel" @selected($transaction->fractionnement === 'annuel')>Annuel</option>
                                                        <option value="unique" @selected($transaction->fractionnement === 'unique')>Unique</option>
                                                    </select>
                                                </label>
                                                <button type="submit" class="dashboard-action-button">Modifier</button>
                                            </form>
                                        </details>

                                        <div class="entity-actions">
                                            <form method="POST" action="{{ route('customer.depenses.delete', $transaction) }}" data-confirm="Supprimer cette transaction ?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="icon-action icon-action-danger" title="Supprimer" aria-label="Supprimer"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 6V4C8 3.44772 8.44772 3 9 3H15C15.5523 3 16 3.44772 16 4V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 6L18.25 20C18.1968 20.9915 17.3767 21.75 16.3838 21.75H7.61616C6.62334 21.75 5.8032 20.9915 5.75 20L5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                                            </form>
                                        </div>
                                    </article>
                                @empty
                                    <article class="dashboard-card entity-card"><p class="text-color-body">Aucune depense pour le moment.</p></article>
                                @endforelse
                            </div>
                        </section>
                    </section>
                </div>
            </div>
        </div>
    </main>
    @include('components.popup-messages')
</body>
</html>
