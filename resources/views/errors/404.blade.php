@extends('layouts.guest')
@section('content')
    <h1 class="text-2xl font-bold text-slate-700 mb-2">404 — Page introuvable</h1>
    <p class="text-slate-500">La page demandée n'existe pas ou a été déplacée.</p>
    <a href="{{ url('/') }}" class="btn-primary mt-4">Retour à l'accueil</a>
@endsection
