<h1>Admin Dashboard</h1>

<a href="{{ route('admin.users.index') }}">Utilisateurs</a>
<br>
<a href="{{ route('admin.profile.index') }}">Profil</a>
<br>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@if(session('error'))
    <div>{{ session('error') }}</div>
@endif

<h2>Statistiques</h2>

<div>
    <strong>Total utilisateurs :</strong> {{ $totalUsers }}
</div>

<div>
    <strong>Clients :</strong> {{ $totalCustomers }}
</div>

<div>
    <strong>Admins :</strong> {{ $totalAdmins }}
</div>

<div>
    <strong>Comptes crees :</strong> {{ $totalAccounts }}
</div>

<br>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Se deconnecter</button>
</form>
