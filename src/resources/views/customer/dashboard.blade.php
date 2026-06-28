<h1>Customer Dashboard</h1>


<a href="{{ route('customer.accounts.index') }}">Comptes</a>
<br>
<a href="{{ route('customer.depenses.index') }}">Depenses</a>
<br>
<a href="{{ route('customer.revenues.index') }}">Revenues</a>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
<br>
<a href="{{ route('customer.previsions.index') }}">Previsions</a>
