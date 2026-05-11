@extends('layouts.app')
@section('title', 'Évaluations — '.$program->title)
@section('content')
    <a href="{{ route('jury.programs.index') }}" class="text-sm text-slate-500 hover:underline inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Mes programmes
    </a>
    <div class="flex items-start justify-between mt-2 mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $program->title }}</h1>
            <p class="text-slate-500">Candidatures à évaluer</p>
        </div>
        <x-status-badge :label="$program->status->label()" :color="$program->status->color()" />
    </div>

    @if(! $isOpenForEval)
        <x-alert type="warning">
            <span class="inline-flex items-center gap-2"><x-icon name="hourglass" /> <b>L'évaluation n'est pas encore ouverte.</b></span>
            Les candidatures sont actuellement en cours de soumission jusqu'au
            <b>{{ $program->application_closes_at?->format('d/m/Y') ?? '—' }}</b>.
            Vous pourrez consulter et évaluer les dossiers à partir du lendemain.
        </x-alert>
    @endif

    @if($createdCount > 0)
        <x-alert type="info" message="{{ $createdCount }} nouvelle(s) candidature(s) à évaluer ont été ajoutée(s) à votre liste." />
    @endif

    @if($evaluations->isEmpty())
        <div class="card card-body text-center text-slate-500">
            @if(! $isOpenForEval)
                <p>Patientez : les candidatures vous seront automatiquement attribuées à la clôture.</p>
            @else
                <p>Aucune candidature soumise pour ce programme pour le moment.</p>
            @endif
        </div>
    @else
        @php
            $pending = $evaluations->filter(fn($e) => in_array($e->status->value, ['assigned', 'in_progress']));
            $done    = $evaluations->filter(fn($e) => $e->status->value === 'submitted');
        @endphp

        @if($pending->isNotEmpty())
            <div class="flex items-center justify-between mt-2 mb-3">
                <h2 class="text-lg font-semibold text-slate-700">À évaluer ({{ $pending->count() }})</h2>
                <p class="text-xs text-slate-500">Triées par date d'attribution</p>
            </div>
            <div class="space-y-2">
                @foreach($pending as $eval)
                    <div class="card card-body flex items-center justify-between border-l-4 border-amber-400">
                        <div>
                            <h3 class="font-semibold">{{ $eval->application->candidate?->full_name }}</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                <span class="font-mono">{{ $eval->application->reference }}</span> ·
                                Soumise le {{ $eval->application->submitted_at?->format('d/m/Y') ?? '—' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-status-badge :label="$eval->status->label()" :color="$eval->status->color()" />
                            <a href="{{ route('jury.evaluations.show', $eval) }}" class="btn-primary text-sm">
                                {{ $eval->status->value === 'assigned' ? 'Évaluer' : 'Continuer' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($done->isNotEmpty())
            <h2 class="text-lg font-semibold text-slate-700 mt-8 mb-3">Déjà évaluées ({{ $done->count() }})</h2>
            <div class="space-y-2">
                @foreach($done as $eval)
                    <div class="card card-body flex items-center justify-between opacity-90">
                        <div>
                            <p class="font-medium">{{ $eval->application->candidate?->full_name }}</p>
                            <p class="text-xs text-slate-500">
                                <span class="font-mono">{{ $eval->application->reference }}</span> ·
                                Évaluée le {{ $eval->submitted_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-xs text-slate-400">Score pondéré</p>
                                <p class="font-bold text-brand-700">{{ $eval->weighted_score }}</p>
                            </div>
                            <a href="{{ route('jury.evaluations.show', $eval) }}" class="btn-ghost text-sm">Voir</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
@endsection
