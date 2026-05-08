@extends('layouts.guest')
@section('title', 'Inscription')
@section('content')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Créer un compte candidate</h1>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-3">
            <x-input name="first_name" label="Prénom" required />
            <x-input name="last_name"  label="Nom"    required />
        </div>
        <x-input name="email" label="Email" type="email" required />
        <x-input name="password" label="Mot de passe" type="password" required autocomplete="new-password" />
        <x-input name="password_confirmation" label="Confirmer le mot de passe" type="password" required autocomplete="new-password" />
        <button type="submit" class="btn-primary w-full">Créer mon compte</button>
    </form>
    <p class="mt-6 text-center text-sm text-slate-500">
        Déjà inscrite ? <a href="{{ route('login') }}" class="text-brand-600 hover:underline">Se connecter</a>
    </p>
@endsection
