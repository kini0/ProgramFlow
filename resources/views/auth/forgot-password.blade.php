@extends('layouts.guest')
@section('title', 'Mot de passe oublié')
@section('content')
    <h1 class="text-xl font-semibold mb-4">Réinitialiser le mot de passe</h1>
    <p class="text-sm text-slate-600 mb-4">Saisissez votre email, nous vous enverrons un lien de réinitialisation.</p>
    @if(session('status'))<x-alert type="success" :message="session('status')" />@endif
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <x-input name="email" type="email" label="Email" required />
        <button type="submit" class="btn-primary w-full">Envoyer le lien</button>
    </form>
@endsection
