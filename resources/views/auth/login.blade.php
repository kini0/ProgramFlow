@extends('layouts.guest')
@section('title', 'Connexion')
@section('content')
    <h1 class="text-xl font-semibold text-slate-800 mb-6">Connectez-vous</h1>

    {{-- Messages contextuels selon le flux d'inscription / vérification --}}
    @php $status = session('status'); @endphp

    @if($status === 'verification-sent')
        <x-alert type="success" title="Compte créé !">
            Un email de vérification a été envoyé à <b>{{ session('verification_email') }}</b>.
            Cliquez sur le lien dans l'email pour activer votre compte, puis revenez ici pour vous connecter.
        </x-alert>
    @elseif($status === 'verified')
        <x-alert type="success" title="Email vérifié !">
            Votre adresse <b>{{ session('verification_email') }}</b> est désormais validée.
            Vous pouvez maintenant vous connecter.
        </x-alert>
    @elseif($status === 'already-verified')
        <x-alert type="info" message="Cette adresse a déjà été vérifiée. Vous pouvez vous connecter." />
    @elseif($status === 'verification-resent')
        <x-alert type="success" title="Lien renvoyé">
            Si un compte existe pour <b>{{ session('verification_email') }}</b>, un nouveau lien de vérification vient d'être envoyé.
        </x-alert>
    @elseif($status === 'password-reset')
        <x-alert type="success" message="Votre mot de passe a été réinitialisé. Vous pouvez vous connecter." />
    @elseif($status)
        <x-alert type="success" :message="$status" />
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <x-input name="email" label="Email" type="email" :value="old('email', session('verification_email'))" required autofocus />
        <x-input name="password" label="Mot de passe" type="password" required autocomplete="current-password" />
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500"> Se souvenir de moi
        </label>
        <div class="flex items-center justify-between">
            <a href="{{ route('password.request') }}" class="text-sm text-brand-600 hover:underline">Mot de passe oublié ?</a>
            <button type="submit" class="btn-primary">Se connecter</button>
        </div>
    </form>

    {{-- Formulaire de renvoi du lien de vérification --}}
    @if($errors->has('email') && session('verification_email'))
        <div class="mt-6 border-t border-slate-200 pt-4">
            <p class="text-sm text-slate-600 mb-2">Vous n'avez pas reçu l'email de vérification ?</p>
            <form method="POST" action="{{ route('verification.send') }}" class="flex gap-2">
                @csrf
                <input type="hidden" name="email" value="{{ session('verification_email') }}">
                <button class="btn-secondary text-sm w-full">Renvoyer le lien de vérification</button>
            </form>
        </div>
    @endif

    <p class="mt-6 text-center text-sm text-slate-500">
        Pas encore de compte ? <a href="{{ route('register') }}" class="text-brand-600 hover:underline">Créer un compte</a>
    </p>
@endsection
