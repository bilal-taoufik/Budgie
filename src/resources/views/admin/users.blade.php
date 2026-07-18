<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        @vite(['resources/css/main.scss','resources/js/main.js'])
        <title>Budgie | Utilisateurs</title>
    <meta name="robots" content="noindex, nofollow">
        <meta name="description" content="Consultez et gérez les utilisateurs de Budgie depuis l'espace d'administration de la plateforme.">
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
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.5 4.16667V15.8333C2.5 16.2754 2.67559 16.6993 2.98816 17.0118C3.30072 17.3244 3.72464 17.5 4.16667 17.5H9.16667V2.5H4.16667C3.72464 2.5 3.30072 2.67559 2.98816 2.98816C2.67559 3.30072 2.5 3.72464 2.5 4.16667ZM15.8333 2.5H10.8333V9.16667H17.5V4.16667C17.5 3.25 16.75 2.5 15.8333 2.5ZM10.8333 17.5H15.8333C16.75 17.5 17.5 16.75 17.5 15.8333V10.8333H10.8333V17.5Z" fill="#85A795"/>
                                </svg>
                                <span>
                                    Tableau de bord
                                </span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="nav-link is-active">
                                <span class="nav-icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.75 17.5C3.75 17.5 2.5 17.5 2.5 16.25C2.5 15 3.75 11.25 10 11.25C16.25 11.25 17.5 15 17.5 16.25C17.5 17.5 16.25 17.5 16.25 17.5H3.75ZM10 10C10.9946 10 11.9484 9.60491 12.6517 8.90165C13.3549 8.19839 13.75 7.24456 13.75 6.25C13.75 5.25544 13.3549 4.30161 12.6517 3.59835C11.9484 2.89509 10.9946 2.5 10 2.5C9.00544 2.5 8.05161 2.89509 7.34835 3.59835C6.64509 4.30161 6.25 5.25544 6.25 6.25C6.25 7.24456 6.64509 8.19839 7.34835 8.90165C8.05161 9.60491 9.00544 10 10 10Z" fill="#85A795"/>
                                    </svg>
                                </span>
                                <span>
                                    Utilisateurs
                                </span>
                            </a>
                        </nav>
                        <div class="dashboard-sidebar-bottom">
                            <a href="{{ route('admin.profile.index') }}" class="nav-link">
                                <span class="nav-icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.75 17.5C3.75 17.5 2.5 17.5 2.5 16.25C2.5 15 3.75 11.25 10 11.25C16.25 11.25 17.5 15 17.5 16.25C17.5 17.5 16.25 17.5 16.25 17.5H3.75ZM10 10C10.9946 10 11.9484 9.60491 12.6517 8.90165C13.3549 8.19839 13.75 7.24456 13.75 6.25C13.75 5.25544 13.3549 4.30161 12.6517 3.59835C11.9484 2.89509 10.9946 2.5 10 2.5C9.00544 2.5 8.05161 2.89509 7.34835 3.59835C6.64509 4.30161 6.25 5.25544 6.25 6.25C6.25 7.24456 6.64509 8.19839 7.34835 8.90165C8.05161 9.60491 9.00544 10 10 10Z" fill="#85A795"/>
                                    </svg>
                                </span>
                                <span>Profil</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link logout-button">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.8333 2.5H9.16667V10.8333H10.8333V2.5ZM14.8583 4.30833L13.675 5.49167C14.3499 6.03307 14.8944 6.71941 15.268 7.49984C15.6416 8.28026 15.8348 9.13475 15.8333 10C15.8333 13.225 13.225 15.8333 10 15.8333C8.79602 15.8341 7.62135 15.4619 6.63738 14.7681C5.65341 14.0743 4.90841 13.0929 4.50474 11.9586C4.10107 10.8243 4.05854 9.59281 4.38298 8.43337C4.70742 7.27392 5.38292 6.24338 6.31667 5.48333L5.14167 4.30833C4.31343 5.00743 3.6479 5.87901 3.1916 6.86212C2.73531 7.84524 2.49928 8.91616 2.5 10C2.5 11.9891 3.29018 13.8968 4.6967 15.3033C6.10322 16.7098 8.01088 17.5 10 17.5C11.9891 17.5 13.8968 16.7098 15.3033 15.3033C16.7098 13.8968 17.5 11.9891 17.5 10C17.5 7.71667 16.475 5.68333 14.8583 4.30833Z" fill="#85A795"/>
                                    </svg>
                                    <span>Deconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="dashboard-main">
                        <div class="dashboard-header fade-in">
                            <div>
                                <h1 class="heading-style-h3-bold">
                                    Gestion des utilisateurs
                                </h1>
                                <p class="text-color-body text-size-small">
                                    Gerez les clients et administrateurs Budgie.
                                </p>
                            </div>
                            <a class="client-back-link" href="{{ route('admin.dashboard') }}">
                                Retour au dashboard
                            </a>
                        </div>
                        <div class="dashboard-content client-sections-content fade-up reveal-delay-1">
                            <section class="client-section">
                                <div class="client-section-heading">
                                    <div>
                                        <span class="section-kicker">
                                            Nouvel accès
                                        </span>
                                        <h2 class="heading-style-h4-bold">
                                            Ajouter un administrateur
                                        </h2>
                                    </div>
                                </div>
                                <article class="dashboard-card entity-form-card">
                                    <form class="dashboard-form" method="POST" action="{{ route('admin.users.store') }}">
                                        @csrf
                                        <label>
                                            Prenom
                                            <input name="firstname" value="{{ old('firstname') }}" required>
                                        </label>
                                        <label>
                                            Nom
                                            <input name="lastname" value="{{ old('lastname') }}" required>
                                        </label>
                                        <label>
                                            Email
                                            <input type="email" name="email" value="{{ old('email') }}" required>
                                        </label>
                                        <label>
                                            Mot de passe
                                            <input type="password" name="password" required>
                                        </label>
                                        <label>
                                            Confirmation
                                            <input type="password" name="password_confirmation" required>
                                        </label>
                                        <button class="dashboard-action-button">
                                            Ajouter un administrateur
                                        </button>
                                    </form>
                                </article>
                            </section>
                            <section class="client-section">
                                <div class="client-section-heading">
                                    <div>
                                        <span class="section-kicker">
                                            Annuaire
                                        </span>
                                        <h2 class="heading-style-h4-bold">
                                            Liste des utilisateurs
                                        </h2>
                                    </div>
                                    <span class="text-color-body text-size-small">
                                        {{ $users->count() }} utilisateur(s)
                                    </span>
                                </div>
                                <article class="dashboard-card dashboard-table-card">
                                    <div class="dashboard-table-wrapper">
                                        <table class="dashboard-table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Utilisateur
                                                    </th>
                                                    <th>
                                                        Role
                                                    </th>
                                                    <th>
                                                        Verification
                                                    </th>
                                                    <th>
                                                        Inscription
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($users as $user)
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            {{ $user->firstname }} {{ $user->lastname }}
                                                        </strong>
                                                        <br>
                                                        <span class="text-color-body text-size-tiny">
                                                            {{ $user->email }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="entity-badge {{ $user->
                                                            role==='customer'?'is-once':'' }}">{{ $user->role }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $user->email_verified?'Oui':'Non' }}
                                                    </td>
                                                    <td>
                                                        {{ $user->created_at->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        @if($user->id!==auth()->id())
                                                        <form method="POST" action="{{ route('admin.users.delete',$user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="icon-action icon-action-danger" title="Supprimer" aria-label="Supprimer">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M8.25 8.25C8.44891 8.25 8.63968 8.32902 8.78033 8.46967C8.92098 8.61032 9 8.80109 9 9V18C9 18.1989 8.92098 18.3897 8.78033 18.5303C8.63968 18.671 8.44891 18.75 8.25 18.75C8.05109 18.75 7.86032 18.671 7.71967 18.5303C7.57902 18.3897 7.5 18.1989 7.5 18V9C7.5 8.80109 7.57902 8.61032 7.71967 8.46967C7.86032 8.32902 8.05109 8.25 8.25 8.25ZM12 8.25C12.1989 8.25 12.3897 8.32902 12.5303 8.46967C12.671 8.61032 12.75 8.80109 12.75 9V18C12.75 18.1989 12.671 18.3897 12.5303 18.5303C12.3897 18.671 12.1989 18.75 12 18.75C11.8011 18.75 11.6103 18.671 11.4697 18.5303C11.329 18.3897 11.25 18.1989 11.25 18V9C11.25 8.80109 11.329 8.61032 11.4697 8.46967C11.6103 8.32902 11.8011 8.25 12 8.25ZM16.5 9C16.5 8.80109 16.421 8.61032 16.2803 8.46967C16.1397 8.32902 15.9489 8.25 15.75 8.25C15.5511 8.25 15.3603 8.32902 15.2197 8.46967C15.079 8.61032 15 8.80109 15 9V18C15 18.1989 15.079 18.3897 15.2197 18.5303C15.3603 18.671 15.5511 18.75 15.75 18.75C15.9489 18.75 16.1397 18.671 16.2803 18.5303C16.421 18.3897 16.5 18.1989 16.5 18V9Z" fill="#E7000B"/>
                                                                    <path d="M21.75 4.5C21.75 4.89782 21.592 5.27936 21.3107 5.56066C21.0294 5.84196 20.6478 6 20.25 6H19.5V19.5C19.5 20.2956 19.1839 21.0587 18.6213 21.6213C18.0587 22.1839 17.2956 22.5 16.5 22.5H7.5C6.70435 22.5 5.94129 22.1839 5.37868 21.6213C4.81607 21.0587 4.5 20.2956 4.5 19.5V6H3.75C3.35218 6 2.97064 5.84196 2.68934 5.56066C2.40804 5.27936 2.25 4.89782 2.25 4.5V3C2.25 2.60218 2.40804 2.22064 2.68934 1.93934C2.97064 1.65804 3.35218 1.5 3.75 1.5H9C9 1.10218 9.15804 0.720644 9.43934 0.43934C9.72064 0.158035 10.1022 0 10.5 0L13.5 0C13.8978 0 14.2794 0.158035 14.5607 0.43934C14.842 0.720644 15 1.10218 15 1.5H20.25C20.6478 1.5 21.0294 1.65804 21.3107 1.93934C21.592 2.22064 21.75 2.60218 21.75 3V4.5ZM6.177 6L6 6.0885V19.5C6 19.8978 6.15804 20.2794 6.43934 20.5607C6.72064 20.842 7.10218 21 7.5 21H16.5C16.8978 21 17.2794 20.842 17.5607 20.5607C17.842 20.2794 18 19.8978 18 19.5V6.0885L17.823 6H6.177ZM3.75 4.5H20.25V3H3.75V4.5Z" fill="#E7000B"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        @else
                                                        <span class="text-color-body">
                                                            Votre compte
                                                        </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5">
                                                        Aucun utilisateur.
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </article>
                            </section>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        @include('components.popup-messages')
    </body>
</html>
