@extends('layouts.auth-budgie')

@section('title', 'Budgie | Mot de passe oublié')
@section('heading', 'Mot de passe oublié ?')
@section('intro', 'Indiquez votre adresse email pour recevoir un lien de réinitialisation.')

@section('content')
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-field-wrapper">
            <input class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
        </div>
        <button type="submit" class="btn-secondary">Envoyer le lien</button>
    </form>
@endsection
