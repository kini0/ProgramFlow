@extends('layouts.app')
@section('title', 'Candidature '.$application->reference)
@section('content')
    <a href="{{ route('organizer.programs.applications.index', $program) }}" class="text-sm text-slate-500 hover:underline">← Liste des candidatures</a>
    <div class="mt-2 flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $application->candidate?->full_name }}</h1>
            <p class="text-slate-500">Réf {{ $application->reference }} · {{ $application->candidate?->email }}</p>
            <div class="mt-2"><x-status-badge :label="$application->status->label()" :color="$application->status->color()" /></div>
        </div>
        <div>
            <p class="text-xs text-slate-400">Score moyen</p>
            <p class="text-3xl font-bold text-brand-700">{{ $application->average_score ?? '—' }}</p>
            <p class="text-xs text-slate-400">{{ $application->evaluations_count }} évaluations</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header"><h2 class="font-semibold">Réponses</h2></div>
            <div class="card-body space-y-4">
                @if($application->motivation)<div><p class="text-xs text-slate-400 uppercase">Motivation</p><p class="text-sm whitespace-pre-line">{{ $application->motivation }}</p></div>@endif
                @if($application->project_summary)<div><p class="text-xs text-slate-400 uppercase">Projet</p><p class="text-sm whitespace-pre-line">{{ $application->project_summary }}</p></div>@endif
                @foreach($application->responses as $r)
                    <div class="border-t border-slate-100 pt-3">
                        <p class="text-xs text-slate-400 uppercase">{{ $r->field?->label }}</p>
                        @if(in_array($r->field?->type, ['file', 'video']))
                            @php $doc = $application->documents->firstWhere('category', $r->field->key); @endphp
                            @if($doc)
                                <a href="{{ $doc->url() }}" target="_blank" class="text-brand-600 hover:underline">📎 {{ $doc->original_name }} ({{ $doc->humanSize() }})</a>
                            @endif
                        @else
                            <p class="text-sm whitespace-pre-line">{{ $r->value }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <div class="card">
                <div class="card-header"><h2 class="font-semibold">Évaluations</h2></div>
                <div class="card-body space-y-3 text-sm">
                    @forelse($application->evaluations as $eval)
                        <div class="border-b border-slate-100 pb-2">
                            <p class="font-medium">{{ $eval->jury?->full_name }}</p>
                            <p class="text-xs text-slate-400">{{ $eval->status->label() }}</p>
                            @if($eval->total_score)<p class="text-sm">Score : <b>{{ $eval->total_score }}</b> (pondéré : {{ $eval->weighted_score }})</p>@endif
                        </div>
                    @empty
                        <p class="text-slate-400">Aucune évaluation.</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2 class="font-semibold">Décision</h2></div>
                <form method="POST" action="{{ route('organizer.programs.applications.decide', [$program, $application]) }}" class="card-body space-y-3">
                    @csrf
                    <x-select name="status" label="Statut" :options="['shortlisted'=>'Présélectionner','accepted'=>'Accepter','rejected'=>'Refuser','waitlisted'=>'Liste d\'attente']" required />
                    <x-textarea name="decision_reason" label="Commentaire" rows="3" />
                    <button class="btn-primary w-full">Enregistrer la décision</button>
                </form>
            </div>
        </div>
    </div>
@endsection
