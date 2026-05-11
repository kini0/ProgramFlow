@extends('layouts.app')
@section('title', 'Ma candidature')
@section('content')
    <a href="{{ route('candidate.applications.index') }}" class="text-sm text-slate-500 hover:underline">← Mes candidatures</a>
    <div class="flex items-start justify-between mt-2 mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $application->program->title }}</h1>
            <p class="text-slate-500">Réf {{ $application->reference }}</p>
        </div>
        @if($application->isEditable())
            <a href="{{ route('candidate.applications.edit', $application) }}" class="btn-primary">
                ✏️ {{ $application->isDraft() ? 'Continuer la candidature' : 'Modifier le dossier' }}
            </a>
        @endif
    </div>

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

    @if($application->isEditable() && ! $application->isDraft())
        <x-alert type="info">
            ✏️ <b>Vous pouvez encore modifier votre dossier</b> jusqu'à la clôture des candidatures, le
            <b>{{ $application->program->application_closes_at?->format('d/m/Y') ?? '—' }}</b>.
        </x-alert>
    @endif

    {{-- ============================================================ --}}
    {{-- RÉCAPITULATIF COMPLET — TOUS LES CHAMPS GROUPÉS PAR SECTION   --}}
    {{-- ============================================================ --}}
    @php
        // Charge la structure complète et indexe les réponses par field_id pour
        // un accès O(1).
        $application->load(['program.applicationFields', 'responses', 'documents']);
        $sections  = $application->program->applicationFields->groupBy('section');
        $responses = $application->responses->keyBy('application_field_id');

        // Mêmes libellés que dans la page d'édition pour rester cohérent.
        $sectionLabels = [
            'identity'    => '1.1 Identité du candidat',
            'address'     => '1.2 Coordonnées',
            'id_document' => '1.3 Pièce d\'identité',
            'academic'    => '1.4 Parcours académique',
            'experience'  => '1.5 Expérience & engagement',
            'health'      => '1.6 Santé & sécurité',
            'parents'     => '2.1 Parent / tuteur principal',
            'emergency'   => '2.2 Personne à contacter en cas d\'urgence',
            'dynamic'     => '3. Spécifique au programme',
            'declaration' => 'Déclaration & engagement',
        ];

        // Ordre d'affichage stable (suit l'ordre du formulaire).
        $sectionOrder = ['identity', 'address', 'id_document', 'academic',
                         'experience', 'health', 'parents', 'emergency',
                         'dynamic', 'declaration'];

        // Helper : produit la valeur affichable d'une réponse selon le type.
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
                    $labels = collect($field->options ?? [])
                        ->whereIn('value', $values)
                        ->pluck('label')
                        ->all();
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

    @if($application->motivation || $application->project_summary)
        <div class="card mb-4">
            <div class="card-header"><h2 class="font-semibold">Synthèse libre</h2></div>
            <div class="card-body space-y-3">
                @if($application->motivation)
                    <div>
                        <p class="text-xs text-slate-400 uppercase">Motivation</p>
                        <p class="text-sm whitespace-pre-line">{{ $application->motivation }}</p>
                    </div>
                @endif
                @if($application->project_summary)
                    <div class="border-t border-slate-100 pt-3">
                        <p class="text-xs text-slate-400 uppercase">Projet</p>
                        <p class="text-sm whitespace-pre-line">{{ $application->project_summary }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @foreach($sectionOrder as $sectionKey)
        @if($sections->has($sectionKey))
            @php
                $fields  = $sections[$sectionKey];
                $isHealth = $sectionKey === 'health';
            @endphp
            <div class="card mb-4 @if($isHealth) border-amber-200 @endif">
                <div class="card-header @if($isHealth) bg-amber-50 @endif">
                    <h2 class="font-semibold">{{ $sectionLabels[$sectionKey] ?? $sectionKey }}</h2>
                    @if($isHealth)
                        <span class="text-xs text-amber-700">⚠ Confidentiel</span>
                    @endif
                </div>
                <div class="card-body">
                    <dl class="grid md:grid-cols-2 gap-x-6 gap-y-4">
                        @foreach($fields as $field)
                            @php
                                $resp  = $responses[$field->id] ?? null;
                                $value = $renderValue($field, $resp);
                            @endphp
                            <div class="@if(in_array($field->type, ['textarea', 'file', 'video'])) md:col-span-2 @endif">
                                <dt class="text-xs text-slate-400 uppercase">{{ $field->label }}</dt>
                                <dd class="mt-1 text-sm text-slate-800">
                                    @if(empty($value))
                                        <span class="text-slate-400 italic">Non renseigné</span>

                                    @elseif(in_array($field->type, ['file', 'video']) && is_object($value))
                                        {{-- $value est un Document --}}
                                        @php
                                            $isImage = str_starts_with($value->mime_type ?? '', 'image/');
                                            $isVideo = str_starts_with($value->mime_type ?? '', 'video/');
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            @if($isImage)
                                                <a href="{{ $value->url() }}" target="_blank">
                                                    <img src="{{ $value->url() }}" alt="" class="w-20 h-20 object-cover rounded border">
                                                </a>
                                            @elseif($isVideo)
                                                <span class="text-2xl">🎥</span>
                                            @else
                                                <span class="text-2xl">📄</span>
                                            @endif
                                            <div>
                                                <p class="font-medium truncate">{{ $value->original_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $value->humanSize() }}</p>
                                                <a href="{{ $value->url() }}" target="_blank"
                                                   class="text-xs text-brand-600 hover:underline">👁️ Consulter</a>
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
@endsection
