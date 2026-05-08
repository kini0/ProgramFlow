@extends('layouts.app')
@section('title', 'Mes évaluations')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Candidatures à évaluer</h1>
    @if($pending->isEmpty())
        <div class="card card-body text-center text-slate-500">Aucune candidature en attente. Merci pour votre travail !</div>
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
