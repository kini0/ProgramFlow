@extends('layouts.guest')
@section('title', 'Connexion')
@section('content')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Connectez-vous</h1>
    @if(session('status'))<x-alert type="success" :message="session('status')" />@endif
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <x-input name="email" label="Email" type="email" required autofocus />
        <x-input name="password" label="Mot de passe" type="password" required autocomplete="current-password" />
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500"> Se souvenir de moi
        </label>
        <div class="flex items-center justify-between">
            <a href="{{ route('password.request') }}" class="text-sm text-brand-600 hover:underline">Mot de passe oublié ?</a>
            <button type="submit" class="btn-primary">Se connecter</button>
        </div>
    </form>
    <p class="mt-6 text-center text-sm text-slate-500">
        Pas encore de compte ? <a href="{{ route('register') }}" class="text-brand-600 hover:underline">Créer un compte</a>
    </p>
@endsection
