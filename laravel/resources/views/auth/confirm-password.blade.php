@extends('layouts.auth-budgie')

@section('title', 'Budgie | Confirmation')
@section('heading', 'Confirmez votre mot de passe')
@section('intro', 'Cette partie de votre espace est sécurisée.')

@section('content')
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="form-field-wrapper">
            <input class="form-input" type="password" name="password" placeholder="Mot de passe" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn-secondary">Confirmer</button>
    </form>
@endsection
