@extends('layouts.app')
@section('title', 'Évaluation — '.$evaluation->application->reference)
@section('content')
    <a href="{{ route('jury.dashboard') }}" class="text-sm text-slate-500 hover:underline">← Mes évaluations</a>
    <h1 class="text-2xl font-bold mt-2">{{ $evaluation->application->candidate?->full_name }}</h1>
    <p class="text-slate-500 mb-6">{{ $evaluation->application->program->title }} · Réf {{ $evaluation->application->reference }}</p>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Dossier de la candidate</h2></div>
            <div class="card-body space-y-4 max-h-[600px] overflow-y-auto">
                @if($evaluation->application->motivation)
                    <div><p class="text-xs text-slate-400 uppercase">Motivation</p><p class="text-sm whitespace-pre-line">{{ $evaluation->application->motivation }}</p></div>
                @endif
                @if($evaluation->application->project_summary)
                    <div><p class="text-xs text-slate-400 uppercase">Projet</p><p class="text-sm whitespace-pre-line">{{ $evaluation->application->project_summary }}</p></div>
                @endif
                @foreach($evaluation->application->responses as $r)
                    <div class="border-t border-slate-100 pt-3">
                        <p class="text-xs text-slate-400 uppercase">{{ $r->field?->label }}</p>
                        @if(in_array($r->field?->type, ['file', 'video']))
                            @php $doc = $evaluation->application->documents->firstWhere('category', $r->field->key); @endphp
                            @if($doc)<a href="{{ $doc->url() }}" target="_blank" class="text-brand-600 hover:underline">📎 {{ $doc->original_name }}</a>@endif
                        @else
                            <p class="text-sm whitespace-pre-line">{{ $r->value }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <form method="POST" action="{{ route('jury.evaluations.update', $evaluation) }}" class="card">
                @csrf @method('PATCH')
                <div class="card-header"><h2 class="font-semibold">Grille d'évaluation</h2></div>
                <div class="card-body space-y-4">
                    @foreach($evaluation->application->program->evaluationCriteria as $i => $crit)
                        @php $existing = $evaluation->scores->firstWhere('evaluation_criterion_id', $crit->id); @endphp
                        <div class="border-b border-slate-100 pb-4">
                            <div class="flex justify-between items-center">
                                <label class="form-label">{{ $crit->label }}</label>
                                <span class="text-xs text-slate-400">poids {{ $crit->weight }} · /{{ $crit->max_score }}</span>
                            </div>
                            @if($crit->description)<p class="text-xs text-slate-500 mb-1">{{ $crit->description }}</p>@endif
                            <input type="hidden" name="scores[{{ $i }}][criterion_id]" value="{{ $crit->id }}">
                            <input type="number" step="0.5" min="0" max="{{ $crit->max_score }}"
                                   name="scores[{{ $i }}][score]"
                                   value="{{ $existing?->score }}"
                                   class="form-input" required>
                            <textarea name="scores[{{ $i }}][comment]" placeholder="Commentaire (facultatif)" class="form-input mt-2" rows="2">{{ $existing?->comment }}</textarea>
                        </div>
                    @endforeach
                    <x-textarea name="comment" label="Commentaire global" :value="$evaluation->comment" rows="4" />
                </div>
                <div class="card-body border-t border-slate-100 flex gap-3">
                    <button class="btn-primary"
                            onclick="return confirm('Soumettre cette évaluation ? Action définitive.')">
                        Soumettre l'évaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
