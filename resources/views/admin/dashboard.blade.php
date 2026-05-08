@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('content')
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Vue d'ensemble</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-stat-card label="Programmes" :value="$stats['total']" tone="brand" />
        <x-stat-card label="Ouverts" :value="$stats['open']" tone="emerald" />
        <x-stat-card label="Candidatures" :value="$stats['total_applications']" tone="blue" />
        <x-stat-card label="Acceptées" :value="$stats['accepted_applications']" tone="amber" />
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mt-8">
        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Programmes par statut</h2></div>
            <div class="card-body space-y-2 text-sm">
                <p>📂 Brouillons / Publiés : {{ $stats['total'] - $stats['open'] - $stats['active'] - $stats['archived'] }}</p>
                <p>✅ En cours : {{ $stats['active'] }}</p>
                <p>🏁 Terminés : {{ $stats['completed'] }}</p>
                <p>📦 Archivés : {{ $stats['archived'] }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Actions rapides</h2></div>
            <div class="card-body grid grid-cols-2 gap-3">
                <a href="{{ route('admin.programs.create') }}" class="btn-primary">+ Nouveau programme</a>
                <a href="{{ route('admin.users.create') }}" class="btn-secondary">+ Utilisateur</a>
                <a href="{{ route('admin.partners.create') }}" class="btn-secondary">+ Partenaire</a>
                <a href="{{ route('admin.reports.index') }}" class="btn-secondary">Rapports</a>
            </div>
        </div>
    </div>
@endsection
