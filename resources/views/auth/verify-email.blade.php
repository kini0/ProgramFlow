@extends('layouts.guest')
@section('title', 'Vérification de votre email')
@section('content')
    <h1 class="text-xl font-semibold mb-4">Vérifiez votre adresse email</h1>
    <p class="text-sm text-slate-600 mb-4">
        Merci pour votre inscription ! Avant de commencer, pourriez-vous confirmer votre adresse email
        en cliquant sur le lien que nous venons de vous envoyer ?
    </p>

    @if (session('status') === 'verification-link-sent')
        <x-alert type="success" message="Un nouveau lien de vérification vient de vous être envoyé." />
    @endif

    <p class="text-sm text-slate-600 mb-4">Vous n'avez pas reçu l'email ?</p>

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn-primary">Renvoyer le lien</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-slate-500 hover:underline">Se déconnecter</button>
        </form>
    </div>
@endsection
