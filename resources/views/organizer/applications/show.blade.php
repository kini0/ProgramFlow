@extends('layouts.app')
@section('title', 'Candidature '.$application->reference)
@section('content')
    <a href="{{ route('organizer.programs.applications.index', $program) }}" class="text-sm text-slate-500 hover:underline">← Liste des candidatures</a>

    <div class="mt-2 flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $application->candidate?->full_name }}</h1>
            <p class="text-slate-500">
                Réf <code>{{ $application->reference }}</code> ·
                {{ $application->candidate?->email }} ·
                Soumise le {{ $application->submitted_at?->format('d/m/Y H:i') ?? '—' }}
            </p>
            <div class="mt-2"><x-status-badge :label="$application->status->label()" :color="$application->status->color()" /></div>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-400">Score moyen pondéré</p>
            <p class="text-3xl font-bold text-brand-700">{{ $application->average_score ?? '—' }}</p>
            <p class="text-xs text-slate-400">{{ $application->evaluations_count }} évaluation(s)</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- RÉCAPITULATIF COMPLET DES INFORMATIONS DU CANDIDAT           --}}
    {{-- ============================================================ --}}
    @php
        $application->load(['program.applicationFields', 'responses', 'documents', 'evaluations.jury', 'evaluations.scores.criterion']);
        $sections  = $application->program->applicationFields->groupBy('section');
        $responses = $application->responses->keyBy('application_field_id');

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
            'declaration' => 'Déclaration & engagement',
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

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- COLONNE GAUCHE : DOSSIER COMPLET --}}
        <div class="lg:col-span-2 space-y-4">
            @if($application->motivation || $application->project_summary)
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold">Synthèse libre</h2></div>
                    <div class="card-body space-y-3">
                        @if($application->motivation)
                            <div><p class="text-xs text-slate-400 uppercase">Motivation</p><p class="text-sm whitespace-pre-line">{{ $application->motivation }}</p></div>
                        @endif
                        @if($application->project_summary)
                            <div class="border-t border-slate-100 pt-3"><p class="text-xs text-slate-400 uppercase">Projet</p><p class="text-sm whitespace-pre-line">{{ $application->project_summary }}</p></div>
                        @endif
                    </div>
                </div>
            @endif

            @foreach($sectionOrder as $sectionKey)
                @if($sections->has($sectionKey))
                    @php
                        $fields = $sections[$sectionKey];
                        $isHealth = $sectionKey === 'health';
                    @endphp
                    <div class="card @if($isHealth) border-amber-200 @endif">
                        <div class="card-header @if($isHealth) bg-amber-50 @endif">
                            <h2 class="font-semibold">{{ $sectionLabels[$sectionKey] ?? $sectionKey }}</h2>
                            @if($isHealth)<span class="text-xs text-amber-700">⚠ Confidentiel</span>@endif
                        </div>
                        <div class="card-body">
                            <dl class="grid md:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                                @foreach($fields as $field)
                                    @php
                                        $resp  = $responses[$field->id] ?? null;
                                        $value = $renderValue($field, $resp);
                                    @endphp
                                    <div class="@if(in_array($field->type, ['textarea', 'file', 'video'])) md:col-span-2 @endif">
                                        <dt class="text-xs text-slate-400 uppercase">{{ $field->label }}</dt>
                                        <dd class="mt-1">
                                            @if(empty($value))
                                                <span class="text-slate-400 italic">Non renseigné</span>
                                            @elseif(in_array($field->type, ['file', 'video']) && is_object($value))
                                                @php
                                                    $isImage = str_starts_with($value->mime_type ?? '', 'image/');
                                                @endphp
                                                <div class="flex items-center gap-3">
                                                    @if($isImage)
                                                        <a href="{{ $value->url() }}" target="_blank">
                                                            <img src="{{ $value->url() }}" alt="" class="w-16 h-16 object-cover rounded border">
                                                        </a>
                                                    @else
                                                        <span class="text-2xl">📄</span>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium">{{ $value->original_name }}</p>
                                                        <p class="text-xs text-slate-500">{{ $value->humanSize() }}</p>
                                                        <a href="{{ $value->url() }}" target="_blank" class="text-xs text-brand-600 hover:underline">👁️ Consulter / Télécharger</a>
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

        {{-- COLONNE DROITE : ACTIONS ORGANIZER --}}
        <div class="space-y-6">

            {{-- ÉVALUATIONS DÉJÀ ENREGISTRÉES --}}
            <div class="card">
                <div class="card-header"><h2 class="font-semibold">Évaluations ({{ $application->evaluations->count() }})</h2></div>
                <div class="card-body space-y-3 text-sm">
                    @forelse($application->evaluations as $eval)
                        <div class="border-b border-slate-100 pb-2 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">{{ $eval->jury?->full_name }}</p>
                                    <x-status-badge :label="$eval->status->label()" :color="$eval->status->color()" />
                                </div>
                                @if($eval->total_score)
                                    <div class="text-right">
                                        <p class="text-xs text-slate-400">pondéré</p>
                                        <p class="font-bold text-brand-700">{{ $eval->weighted_score }}</p>
                                    </div>
                                @endif
                            </div>
                            @if($eval->comment)
                                <p class="mt-2 text-xs text-slate-600 italic">"{{ Str::limit($eval->comment, 200) }}"</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-slate-400">Aucune évaluation. Attribuez des jurys ci-dessous.</p>
                    @endforelse
                </div>
            </div>

            {{-- JURYS DU PROGRAMME (information seulement) --}}
            <div class="card">
                <div class="card-header"><h2 class="font-semibold">Jurys du programme</h2></div>
                <div class="card-body text-sm space-y-2">
                    @if($program->juries->isEmpty())
                        <p class="text-amber-600 bg-amber-50 rounded p-2 text-xs">
                            ⚠️ Aucun jury n'est associé à ce programme. Demandez à l'admin d'en ajouter
                            via la fiche du programme — sans jury, aucune évaluation n'est possible.
                        </p>
                    @else
                        <p class="text-xs text-slate-500 mb-2">
                            Tous les jurys ci-dessous évaluent automatiquement cette candidature.
                            Pas d'attribution manuelle nécessaire.
                        </p>
                        @php
                            $juriesById = $program->juries->keyBy('id');
                            $evalByJuryId = $application->evaluations->keyBy('jury_id');
                        @endphp
                        @foreach($juriesById as $jury)
                            @php $eval = $evalByJuryId[$jury->id] ?? null; @endphp
                            <div class="flex items-center justify-between py-1 border-b border-slate-100 last:border-0">
                                <span>⚖️ {{ $jury->full_name }}</span>
                                @if($eval && $eval->status->value === 'submitted')
                                    <span class="text-xs text-emerald-600">✓ {{ $eval->weighted_score }}</span>
                                @elseif($eval)
                                    <x-status-badge :label="$eval->status->label()" :color="$eval->status->color()" />
                                @else
                                    <span class="text-xs text-slate-400">En attente</span>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- DÉCISION FINALE --}}
            <div class="card">
                <div class="card-header"><h2 class="font-semibold">Décision finale</h2></div>
                <form method="POST" action="{{ route('organizer.programs.applications.decide', [$program, $application]) }}" class="card-body space-y-3">
                    @csrf
                    <x-select name="status" label="Statut"
                              :options="[
                                  'shortlisted' => 'Présélectionner',
                                  'accepted'    => 'Accepter',
                                  'rejected'    => 'Refuser',
                                  'waitlisted'  => 'Liste d\'attente',
                              ]"
                              :selected="$application->status->value === 'shortlisted' ? 'shortlisted' : null"
                              required />
                    <x-textarea name="decision_reason" label="Commentaire (envoyé à la candidate par email)" rows="3" />
                    <button class="btn-primary w-full">Enregistrer la décision</button>
                </form>
            </div>
        </div>
    </div>
@endsection
