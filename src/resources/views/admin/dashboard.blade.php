<h1>Admin Dashboard</h1>

<form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit">
        Se déconnecter
    </button>
</form>