@extends('layouts.auth-budgie')

@section('title', 'Budgie | Nouveau mot de passe')
@section('heading', 'Nouveau mot de passe')
@section('intro', 'Choisissez un nouveau mot de passe pour votre compte.')

@section('content')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="form-field-wrapper">
            <input class="form-input" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Email" required>
        </div>
        <div class="form-field-wrapper">
            <input class="form-input" type="password" name="password" placeholder="Nouveau mot de passe" required>
        </div>
        <div class="form-field-wrapper">
            <input class="form-input" type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
        </div>
        <button type="submit" class="btn-secondary">Réinitialiser</button>
    </form>
@endsection
