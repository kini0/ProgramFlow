@extends('layouts.app')
@section('title', 'Mon espace jury')
@section('content')
    <h1 class="text-2xl font-bold mb-2 flex items-center gap-2">
        Bonjour, {{ auth()->user()->first_name }} <x-icon name="hand-waving" weight="fill" class="text-amber-400" />
    </h1>
    <p class="text-slate-500 mb-6">Voici les candidatures qui vous sont actuellement attribuées.</p>

    <div class="grid md:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('jury.programs.index') }}" class="card card-body hover:bg-slate-50 transition">
            <p class="text-xs text-slate-400 uppercase">Voir l'ensemble</p>
            <p class="text-2xl font-bold mt-2 flex items-center gap-2">
                <x-icon name="books" class="text-brand-600" /> Mes programmes
            </p>
            <p class="text-sm text-slate-500 mt-1">Tous les programmes dont vous êtes membre du jury.</p>
        </a>
        <div class="card card-body">
            <p class="text-xs text-slate-400 uppercase">À évaluer</p>
            <p class="text-3xl font-bold text-amber-600 mt-2">{{ $pending->count() }}</p>
            <p class="text-sm text-slate-500 mt-1">Candidature(s) en attente de votre évaluation.</p>
        </div>
    </div>

    <h2 class="text-lg font-semibold text-slate-700 mb-3">Candidatures à évaluer</h2>
    @if($pending->isEmpty())
        <div class="card card-body text-center text-slate-500 flex flex-col items-center gap-2">
            <x-icon name="check-circle" weight="fill" class="text-emerald-500 text-3xl" />
            <p>Toutes vos évaluations sont à jour. Merci pour votre travail !</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($pending as $eval)
                <div class="card card-body flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-400 uppercase">{{ $eval->application->program->title }}</p>
                        <h3 class="font-semibold">{{ $eval->application->candidate?->full_name }}</h3>
                        <p class="text-xs text-slate-500">Réf {{ $eval->application->reference }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-status-badge :label="$eval->status->label()" :color="$eval->status->color()" />
                        <a href="{{ route('jury.evaluations.show', $eval) }}" class="btn-primary text-sm">Évaluer</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
