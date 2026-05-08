@extends('layouts.guest')
@section('title', 'Nouveau mot de passe')
@section('content')
    <h1 class="text-xl font-semibold mb-4">Définir un nouveau mot de passe</h1>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <x-input name="email" type="email" label="Email" :value="$request->email" required />
        <x-input name="password" type="password" label="Mot de passe" required autocomplete="new-password" />
        <x-input name="password_confirmation" type="password" label="Confirmer" required autocomplete="new-password" />
        <button type="submit" class="btn-primary w-full">Réinitialiser</button>
    </form>
@endsection
