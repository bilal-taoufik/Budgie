<h1>Customer Dashboard</h1>


<a href="{{ route('customer.accounts.index') }}">Comptes</a>

<br>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>