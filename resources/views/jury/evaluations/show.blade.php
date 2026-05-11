@extends('layouts.app')
@section('title', 'Évaluation — '.$evaluation->application->reference)
@section('content')
    <a href="{{ route('jury.dashboard') }}" class="text-sm text-slate-500 hover:underline inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Mes évaluations
    </a>
    <div class="mt-2 flex items-start justify-between mb-6">
        <div>
            <p class="text-xs text-slate-400 uppercase">{{ $evaluation->application->program->title }}</p>
            <h1 class="text-2xl font-bold">{{ $evaluation->application->candidate?->full_name }}</h1>
            <p class="text-slate-500">Réf <code>{{ $evaluation->application->reference }}</code></p>
        </div>
        <x-status-badge :label="$evaluation->status->label()" :color="$evaluation->status->color()" />
    </div>

    @php
        $application = $evaluation->application;
        $sections    = $application->program->applicationFields->groupBy('section');
        $responses   = $application->responses->keyBy('application_field_id');

        $sectionLabels = [
            'identity'    => '1.1 Identité',
            'address'     => '1.2 Coordonnées',
            'id_document' => '1.3 Pièce d\'identité',
            'academic'    => '1.4 Parcours académique',
            'experience'  => '1.5 Expérience & engagement',
            'health'      => '1.6 Santé & sécurité',
            'parents'     => '2.1 Parent / tuteur',
            'emergency'   => '2.2 Contact d\'urgence',
            'dynamic'     => '3. Spécifique au programme',
            'declaration' => 'Déclaration',
        ];
        $sectionOrder = ['identity', 'address', 'id_document', 'academic',
                         'experience', 'health', 'parents', 'emergency',
                         'dynamic', 'declaration'];

        $renderValue = function ($field, $response) use ($application) {
            if (! $response) return null;
            switch ($field->type) {
                case 'select':
                case 'radio':
                    $opt = collect($field->options ?? [])->firstWhere('value', $response->value);
                    return $opt['label'] ?? $response->value;
                case 'checkbox':
                case 'multiselect':
                    $values = (array)($response->value_json ?? []);
                    $labels = collect($field->options ?? [])->whereIn('value', $values)->pluck('label')->all();
                    return empty($labels) ? null : implode(', ', $labels);
                case 'file':
                case 'video':
                    return $application->documents->firstWhere('category', $field->key);
                case 'date':
                    return $response->value ? \Carbon\Carbon::parse($response->value)->format('d/m/Y') : null;
                default:
                    return $response->value;
            }
        };
    @endphp

    <div class="grid lg:grid-cols-5 gap-6">
        {{-- DOSSIER À CONSULTER --}}
        <div class="lg:col-span-3 space-y-3 max-h-[calc(100vh-180px)] overflow-y-auto pr-2">
            @if($application->motivation || $application->project_summary)
                <div class="card">
                    <div class="card-header sticky top-0 bg-white z-10"><h2 class="font-semibold">Synthèse libre</h2></div>
                    <div class="card-body space-y-3 text-sm">
                        @if($application->motivation)
                            <div><p class="text-xs text-slate-400 uppercase">Motivation</p><p class="whitespace-pre-line">{{ $application->motivation }}</p></div>
                        @endif
                        @if($application->project_summary)
                            <div class="border-t border-slate-100 pt-3"><p class="text-xs text-slate-400 uppercase">Projet</p><p class="whitespace-pre-line">{{ $application->project_summary }}</p></div>
                        @endif
                    </div>
                </div>
            @endif

            @foreach($sectionOrder as $sectionKey)
                @if($sections->has($sectionKey) && $sectionKey !== 'declaration')
                    @php
                        $fields = $sections[$sectionKey];
                        $isHealth = $sectionKey === 'health';
                    @endphp
                    <div class="card @if($isHealth) border-amber-200 @endif">
                        <div class="card-header sticky top-0 bg-white z-10 @if($isHealth) bg-amber-50 @endif">
                            <h2 class="font-semibold">{{ $sectionLabels[$sectionKey] ?? $sectionKey }}</h2>
                            @if($isHealth)<span class="text-xs text-amber-700 inline-flex items-center gap-1"><x-icon name="warning" weight="fill" /> Confidentiel</span>@endif
                        </div>
                        <div class="card-body">
                            <dl class="grid md:grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                @foreach($fields as $field)
                                    @php
                                        $resp  = $responses[$field->id] ?? null;
                                        $value = $renderValue($field, $resp);
                                    @endphp
                                    <div class="@if(in_array($field->type, ['textarea', 'file', 'video'])) md:col-span-2 @endif">
                                        <dt class="text-xs text-slate-400 uppercase">{{ $field->label }}</dt>
                                        <dd class="mt-1">
                                            @if(empty($value))
                                                <span class="text-slate-400 italic">—</span>
                                            @elseif(in_array($field->type, ['file', 'video']) && is_object($value))
                                                @php $isImage = str_starts_with($value->mime_type ?? '', 'image/'); @endphp
                                                <div class="flex items-center gap-3">
                                                    @if($isImage)
                                                        <a href="{{ $value->url() }}" target="_blank">
                                                            <img src="{{ $value->url() }}" alt="" class="w-16 h-16 object-cover rounded border">
                                                        </a>
                                                    @else
                                                        <x-icon name="file-text" class="text-2xl text-slate-400" />
                                                    @endif
                                                    <div>
                                                        <p class="font-medium">{{ $value->original_name }}</p>
                                                        <a href="{{ $value->url() }}" target="_blank" class="text-xs text-brand-600 hover:underline inline-flex items-center gap-1">
                                                            <x-icon name="eye" /> Consulter
                                                        </a>
                                                    </div>
                                                </div>
                                            @elseif($field->type === 'textarea')
                                                <p class="whitespace-pre-line">{{ $value }}</p>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- GRILLE D'ÉVALUATION --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('jury.evaluations.update', $evaluation) }}" class="card sticky top-4">
                @csrf @method('PATCH')
                <div class="card-header">
                    <h2 class="font-semibold">Grille d'évaluation</h2>
                    @if($evaluation->status?->value === 'submitted')
                        <span class="text-xs text-emerald-600 inline-flex items-center gap-1">
                            <x-icon name="check-circle" weight="fill" /> Déjà soumise — modification désactivée
                        </span>
                    @endif
                </div>
                <div class="card-body space-y-4 max-h-[calc(100vh-280px)] overflow-y-auto">
                    @foreach($application->program->evaluationCriteria as $i => $crit)
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
                                   {{ $evaluation->status?->value === 'submitted' ? 'disabled' : '' }}
                                   class="form-input" required>
                            <textarea name="scores[{{ $i }}][comment]" placeholder="Commentaire (facultatif)"
                                      {{ $evaluation->status?->value === 'submitted' ? 'disabled' : '' }}
                                      class="form-input mt-2" rows="2">{{ $existing?->comment }}</textarea>
                        </div>
                    @endforeach

                    <x-textarea name="comment" label="Commentaire global" :value="$evaluation->comment" rows="3"
                                :disabled="$evaluation->status?->value === 'submitted'" />
                </div>
                @if($evaluation->status?->value !== 'submitted')
                    <div class="card-body border-t border-slate-100">
                        <button class="btn-primary w-full"
                                onclick="return confirm('Soumettre cette évaluation ? Action définitive — vous ne pourrez plus modifier après.')">
                            <x-icon name="paper-plane-tilt" /> Soumettre l'évaluation
                        </button>
                    </div>
                @else
                    <div class="card-body border-t border-slate-100 text-center">
                        <p class="text-xs text-slate-400">Soumise le {{ $evaluation->submitted_at?->format('d/m/Y H:i') }}</p>
                        <p class="text-lg font-bold text-brand-700 mt-1">{{ $evaluation->weighted_score }} <span class="text-xs text-slate-400">(pondéré)</span></p>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
