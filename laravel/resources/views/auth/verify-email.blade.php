@extends('layouts.auth-budgie')

@section('title', 'Budgie | Vérification email')
@section('heading', 'Vérifiez votre adresse email')
@section('intro', 'Un lien de vérification vous a été envoyé par email.')

@section('content')
    @if (session('status') === 'verification-link-sent')
        <div class="w-form-done" style="display: block;">Un nouveau lien vient de vous être envoyé.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-secondary">Renvoyer le lien</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="form-back-link">Se déconnecter</button>
    </form>
@endsection
