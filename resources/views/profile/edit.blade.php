@extends('layouts.app')
@section('title', 'Mon profil')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Mon profil</h1>

    <div class="grid lg:grid-cols-2 gap-6 max-w-5xl">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="card">
            @csrf @method('PATCH')
            <div class="card-header"><h2 class="font-semibold">Informations personnelles</h2></div>
            <div class="card-body space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <x-input name="first_name" label="Prénom" :value="$user->first_name" required />
                    <x-input name="last_name" label="Nom" :value="$user->last_name" required />
                </div>
                <x-input name="email" label="Email" type="email" :value="$user->email" required />
                <x-input name="phone" label="Téléphone" :value="$user->phone" />
                <div class="grid grid-cols-2 gap-3">
                    <x-input name="city" label="Ville" :value="$user->city" />
                    <x-input name="country" label="Pays" :value="$user->country" />
                </div>
                <x-textarea name="bio" label="Bio" :value="$user->bio" rows="3" />
                <div>
                    <label class="form-label">Avatar</label>
                    <input type="file" name="avatar" accept="image/*" class="form-input">
                </div>
                <button class="btn-primary">Enregistrer</button>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.password') }}" class="card">
            @csrf @method('PATCH')
            <div class="card-header"><h2 class="font-semibold">Mot de passe</h2></div>
            <div class="card-body space-y-3">
                <x-input name="current_password" type="password" label="Mot de passe actuel" required autocomplete="current-password" />
                <x-input name="password" type="password" label="Nouveau mot de passe" required autocomplete="new-password" />
                <x-input name="password_confirmation" type="password" label="Confirmer" required autocomplete="new-password" />
                <button class="btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
@endsection
