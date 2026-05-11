@extends('layouts.app')
@section('title', 'Candidature — '.$application->program->title)
@section('content')
    <a href="{{ route('candidate.dashboard') }}" class="text-sm text-slate-500 hover:underline inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Mon espace
    </a>
    <h1 class="text-2xl font-bold mt-2">{{ $application->program->title }}</h1>
    <p class="text-slate-500 mb-6">Référence {{ $application->reference }} · <x-status-badge :label="$application->status->label()" :color="$application->status->color()" /></p>

    @if(! $application->isDraft())
        <x-alert type="info">
            <span class="inline-flex items-center gap-2"><x-icon name="pencil-simple" /> Votre candidature est <b>déjà soumise</b>, mais vous pouvez encore la mettre à jour</span>
            jusqu'à la clôture le <b>{{ $application->program->application_closes_at?->format('d/m/Y') ?? '—' }}</b>.
            Vos modifications seront enregistrées sans nouvelle soumission.
        </x-alert>
    @endif

    @php
        // Regroupe les champs par section, avec ordres et libellés.
        $sections = $application->program->applicationFields->groupBy('section');
        $responses = $application->responses->keyBy('application_field_id');

        $sectionLabels = [
            'identity'    => '1.1 Identité du candidat',
            'address'     => '1.2 Coordonnées',
            'id_document' => '1.3 Pièce d\'identité',
            'academic'    => '1.4 Parcours académique',
            'experience'  => '1.5 Expérience & engagement',
            'health'      => '1.6 Santé & sécurité (confidentiel)',
            'parents'     => '2.1 Parent / tuteur principal',
            'emergency'   => '2.2 Personne à contacter en cas d\'urgence',
            'dynamic'     => '3. Spécifique au programme',
            'declaration' => 'Déclaration & engagement',
        ];

        // 3 étapes regroupent les sections
        $steps = [
            'step1' => [
                'title' => 'Étape 1 — Informations personnelles',
                'sections' => ['identity', 'address', 'id_document', 'academic', 'experience', 'health'],
            ],
            'step2' => [
                'title' => 'Étape 2 — Parents & contact d\'urgence',
                'sections' => ['parents', 'emergency'],
            ],
            'step3' => [
                'title' => 'Étape 3 — Spécifique au programme & déclaration',
                'sections' => ['dynamic', 'declaration'],
            ],
        ];
    @endphp

    <div x-data="{ step: 1 }">
        {{-- Stepper --}}
        <nav class="flex items-center justify-between mb-6 text-sm">
            @foreach($steps as $i => $s)
                @php $num = $loop->iteration; @endphp
                <button type="button" x-on:click="step = {{ $num }}"
                        x-bind:class="step === {{ $num }} ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 border'"
                        class="flex-1 px-3 py-2 rounded-lg font-medium transition mx-1">
                    <span class="hidden md:inline">{{ $s['title'] }}</span>
                    <span class="md:hidden">{{ $num }}</span>
                </button>
            @endforeach
        </nav>

        <form method="POST" action="{{ route('candidate.applications.update', $application) }}" enctype="multipart/form-data" class="space-y-6 max-w-4xl">
            @csrf @method('PATCH')

            {{-- ÉTAPE 1 --}}
            <div x-show="step === 1" x-cloak>
                @foreach($steps['step1']['sections'] as $sectionKey)
                    @if($sections->has($sectionKey))
                        @include('candidate.applications._section', [
                            'section'   => $sectionKey,
                            'title'     => $sectionLabels[$sectionKey] ?? $sectionKey,
                            'fields'    => $sections[$sectionKey],
                            'responses' => $responses,
                            'application' => $application,
                        ])
                    @endif
                @endforeach
                <div class="flex justify-end">
                    <button type="button" x-on:click="step = 2; window.scrollTo({top:0, behavior:'smooth'})" class="btn-primary">Étape suivante <x-icon name="arrow-right" /></button>
                </div>
            </div>

            {{-- ÉTAPE 2 --}}
            <div x-show="step === 2" x-cloak>
                @foreach($steps['step2']['sections'] as $sectionKey)
                    @if($sections->has($sectionKey))
                        @include('candidate.applications._section', [
                            'section'   => $sectionKey,
                            'title'     => $sectionLabels[$sectionKey] ?? $sectionKey,
                            'fields'    => $sections[$sectionKey],
                            'responses' => $responses,
                            'application' => $application,
                        ])
                    @endif
                @endforeach
                <div class="flex justify-between">
                    <button type="button" x-on:click="step = 1; window.scrollTo({top:0, behavior:'smooth'})" class="btn-ghost"><x-icon name="arrow-left" /> Précédent</button>
                    <button type="button" x-on:click="step = 3; window.scrollTo({top:0, behavior:'smooth'})" class="btn-primary">Étape suivante <x-icon name="arrow-right" /></button>
                </div>
            </div>

            {{-- ÉTAPE 3 --}}
            <div x-show="step === 3" x-cloak>
                @foreach($steps['step3']['sections'] as $sectionKey)
                    @if($sections->has($sectionKey))
                        @include('candidate.applications._section', [
                            'section'   => $sectionKey,
                            'title'     => $sectionLabels[$sectionKey] ?? $sectionKey,
                            'fields'    => $sections[$sectionKey],
                            'responses' => $responses,
                            'application' => $application,
                        ])
                    @endif
                @endforeach

                <div class="flex flex-wrap items-center gap-3 justify-between">
                    <button type="button" x-on:click="step = 2; window.scrollTo({top:0, behavior:'smooth'})" class="btn-ghost"><x-icon name="arrow-left" /> Précédent</button>
                    <div class="flex flex-wrap gap-3">
                        @if($application->isDraft())
                            <button type="submit" name="submit" value="0" class="btn-secondary">
                                <x-icon name="floppy-disk" /> Enregistrer le brouillon
                            </button>
                            <button type="submit" name="submit" value="1" class="btn-primary"
                                    onclick="return confirm('Soumettre votre candidature ? Vous pourrez la mettre à jour tant que les candidatures sont ouvertes.')">
                                <x-icon name="paper-plane-tilt" /> Soumettre ma candidature
                            </button>
                        @else
                            {{-- Candidature déjà soumise mais encore éditable --}}
                            <button type="submit" name="submit" value="0" class="btn-primary">
                                <x-icon name="floppy-disk" /> Enregistrer les modifications
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>[x-cloak] { display: none !important; }</style>
@endsection
