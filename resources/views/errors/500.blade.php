@extends('layouts.guest')
@section('content')
    <h1 class="text-2xl font-bold text-red-600 mb-2">500 — Erreur serveur</h1>
    <p class="text-slate-600">Une erreur inattendue est survenue. L'équipe technique a été notifiée.</p>
    <a href="{{ url('/') }}" class="btn-primary mt-4">Retour à l'accueil</a>
@endsection
