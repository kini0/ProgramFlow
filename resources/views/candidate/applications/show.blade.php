@extends('layouts.app')
@section('title', 'Ma candidature')
@section('content')
    <a href="{{ route('candidate.applications.index') }}" class="text-sm text-slate-500 hover:underline">← Mes candidatures</a>
    <h1 class="text-2xl font-bold mt-2">{{ $application->program->title }}</h1>
    <p class="text-slate-500 mb-6">Réf {{ $application->reference }}</p>

    <div class="card mb-6">
        <div class="card-body flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 uppercase">Statut</p>
                <div class="mt-1"><x-status-badge :label="$application->status->label()" :color="$application->status->color()" /></div>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-400">Soumise le</p>
                <p class="font-medium">{{ $application->submitted_at?->format('d/m/Y H:i') ?? '—' }}</p>
            </div>
        </div>
    </div>

    @if($application->decision_reason)
        <x-alert type="info" title="Message de l'organisateur" :message="$application->decision_reason" />
    @endif

    <div class="card">
        <div class="card-header"><h2 class="font-semibold">Récapitulatif</h2></div>
        <div class="card-body space-y-4">
            @if($application->motivation)
                <div><p class="text-xs text-slate-400 uppercase">Motivation</p><p class="text-sm whitespace-pre-line">{{ $application->motivation }}</p></div>
            @endif
            @if($application->project_summary)
                <div><p class="text-xs text-slate-400 uppercase">Projet</p><p class="text-sm whitespace-pre-line">{{ $application->project_summary }}</p></div>
            @endif
            @foreach($application->responses as $r)
                <div class="border-t border-slate-100 pt-3">
                    <p class="text-xs text-slate-400 uppercase">{{ $r->field?->label }}</p>
                    <p class="text-sm whitespace-pre-line">{{ $r->value }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
