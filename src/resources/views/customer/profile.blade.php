<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/main.scss', 'resources/js/main.js'])
    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />
    <title>Budgie | Profil</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Consultez et modifiez les informations de votre profil Budgie afin de garder votre compte personnel à jour.">
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
                        <a href="{{ route('customer.dashboard') }}" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 4.16667V15.8333C2.5 16.2754 2.67559 16.6993 2.98816 17.0118C3.30072 17.3244 3.72464 17.5 4.16667 17.5H9.16667V2.5H4.16667C3.72464 2.5 3.30072 2.67559 2.98816 2.98816C2.67559 3.30072 2.5 3.72464 2.5 4.16667ZM15.8333 2.5H10.8333V9.16667H17.5V4.16667C17.5 3.25 16.75 2.5 15.8333 2.5ZM10.8333 17.5H15.8333C16.75 17.5 17.5 16.75 17.5 15.8333V10.8333H10.8333V17.5Z" fill="#85A795"/>
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                        <a href="{{ route('customer.accounts.index') }}" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 4.25C1.64843 3.76187 2.43838 3.49855 3.25 3.5H16.75C17.594 3.5 18.373 3.779 19 4.25C19 3.95453 18.9418 3.66194 18.8287 3.38896C18.7157 3.11598 18.5499 2.86794 18.341 2.65901C18.1321 2.45008 17.884 2.28434 17.611 2.17127C17.3381 2.0582 17.0455 2 16.75 2H3.25C2.65326 2 2.08097 2.23705 1.65901 2.65901C1.23705 3.08097 1 3.65326 1 4.25ZM1 7.25C1.64843 6.76187 2.43838 6.49855 3.25 6.5H16.75C17.594 6.5 18.373 6.779 19 7.25C19 6.95453 18.9418 6.66194 18.8287 6.38896C18.7157 6.11598 18.5499 5.86794 18.341 5.65901C18.1321 5.45008 17.884 5.28434 17.611 5.17127C17.3381 5.0582 17.0455 5 16.75 5H3.25C2.65326 5 2.08097 5.23705 1.65901 5.65901C1.23705 6.08097 1 6.65326 1 7.25ZM7 8C7.26522 8 7.51957 8.10536 7.70711 8.29289C7.89464 8.48043 8 8.73478 8 9C8 9.53043 8.21071 10.0391 8.58579 10.4142C8.96086 10.7893 9.46957 11 10 11C10.5304 11 11.0391 10.7893 11.4142 10.4142C11.7893 10.0391 12 9.53043 12 9C12 8.73478 12.1054 8.48043 12.2929 8.29289C12.4804 8.10536 12.7348 8 13 8H16.75C17.0455 8 17.3381 8.0582 17.611 8.17127C17.884 8.28434 18.1321 8.45008 18.341 8.65901C18.5499 8.86794 18.7157 9.11598 18.8287 9.38896C18.9418 9.66195 19 9.95453 19 10.25V15.75C19 16.0455 18.9418 16.3381 18.8287 16.611C18.7157 16.884 18.5499 17.1321 18.341 17.341C18.1321 17.5499 17.884 17.7157 17.611 17.8287C17.3381 17.9418 17.0455 18 16.75 18H3.25C2.65326 18 2.08097 17.7629 1.65901 17.341C1.23705 16.919 1 16.3467 1 15.75V10.25C1 9.65326 1.23705 9.08097 1.65901 8.65901C2.08097 8.23705 2.65326 8 3.25 8H7Z" fill="#85A795"/>
                            </svg>
                            <span>Comptes</span>
                        </a>
                        <a href="{{ route('customer.depenses.index') }}" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3333 14.1667L11.25 7.08332L7.08329 11.25L1.66663 5.83332" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.3334 14.1667H18.3334V9.16666" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Dépenses</span>
                        </a>
                        <a href="{{ route('customer.revenues.index') }}" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3333 5.83334L11.25 12.9167L7.08329 8.75001L1.66663 14.1667" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.3334 5.83334H18.3334V10.8333" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Revenus</span>
                        </a>
                        <a href="{{ route('customer.previsions.index') }}" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 2.5V15.8333C2.5 16.2754 2.67559 16.6993 2.98816 17.0118C3.30072 17.3244 3.72464 17.5 4.16667 17.5H17.5" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15 14.1667V7.5" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.8334 14.1667V4.16666" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.66663 14.1667V11.6667" stroke="#85A795" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Prévisions</span>
                        </a>
                    </nav>

                    <div class="dashboard-sidebar-bottom">
                        <a href="{{ route('customer.profile.index') }}" class="nav-link is-active">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.75 17.5C3.75 17.5 2.5 17.5 2.5 16.25C2.5 15 3.75 11.25 10 11.25C16.25 11.25 17.5 15 17.5 16.25C17.5 17.5 16.25 17.5 16.25 17.5H3.75ZM10 10C10.9946 10 11.9484 9.60491 12.6517 8.90165C13.3549 8.19839 13.75 7.24456 13.75 6.25C13.75 5.25544 13.3549 4.30161 12.6517 3.59835C11.9484 2.89509 10.9946 2.5 10 2.5C9.00544 2.5 8.05161 2.89509 7.34835 3.59835C6.64509 4.30161 6.25 5.25544 6.25 6.25C6.25 7.24456 6.64509 8.19839 7.34835 8.90165C8.05161 9.60491 9.00544 10 10 10Z" fill="#85A795"/>
                            </svg>
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

                <main class="dashboard-main">
                    <header class="dashboard-header fade-in"><div><h1 class="heading-style-h3-bold">Profil</h1><p class="text-color-body text-size-small">Gerer les informations de votre espace personnel.</p></div><a class="client-back-link" href="{{ route('customer.dashboard') }}">Retour au dashboard</a></header>
                    <section class="dashboard-content client-sections-content fade-up reveal-delay-1">


                        <section class="client-section">
                            <article class="dashboard-card entity-form-card">
                                <h3 class="heading-style-h5-bold">Mes informations</h3>
                                <form class="dashboard-form" method="POST" action="{{ route('customer.profile.info') }}">
                                    @csrf @method('PUT')
                                    <label>Prenom<input name="firstname" type="text" value="{{ old('firstname', $user->firstname) }}" required></label>
                                    <label>Nom<input name="lastname" type="text" value="{{ old('lastname', $user->lastname) }}" required></label>
                                    <label>Email<input name="email" type="email" value="{{ old('email', $user->email) }}" required></label>
                                    <button type="submit" class="dashboard-action-button">Mettre a jour</button>
                                </form>
                            </article>

                            <article class="dashboard-card entity-form-card">
                                <h3 class="heading-style-h5-bold">Mot de passe</h3>
                                <form class="dashboard-form" method="POST" action="{{ route('customer.profile.password') }}">
                                    @csrf @method('PUT')
                                    <label>Mot de passe actuel*<input name="current_password" type="password" required></label>
                                    <label>Nouveau mot de passe*<input name="password" type="password" required></label>
                                    <label>Confirmation*<input name="password_confirmation" type="password" required></label>
                                    <button type="submit" class="dashboard-action-button">Modifier</button>
                                </form>
                            </article>

                            <article class="dashboard-card entity-form-card">
                                <h3 class="heading-style-h5-bold">Supprimer mon compte</h3>
                                <form class="dashboard-form" method="POST" action="{{ route('customer.profile.delete') }}">
                                    @csrf @method('DELETE')
                                    <label>Mot de passe*<input name="password" type="password" required></label>
                                    <button type="submit" class="dashboard-action-button">Supprimer mon compte</button>
                                </form>
                            </article>
                        </section>
                    </section>
                </main>
            </div>
        </div>
    </main>
    @include('components.popup-messages')
</body>
</html>