@extends('layouts.app')
@section('title', $program->title)
@section('content')
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $program->title }}</h1>
            <p class="text-slate-500 text-sm">{{ $program->short_description }}</p>
            <div class="mt-2"><x-status-badge :label="$program->status->label()" :color="$program->status->color()" /></div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('organizer.programs.applications.index', $program) }}" class="btn-secondary">Candidatures</a>
            <a href="{{ route('admin.programs.edit', $program) }}" class="btn-primary">Modifier</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header"><h2 class="font-semibold">Informations</h2></div>
            <div class="card-body space-y-3 text-sm">
                <p><b>Période :</b> {{ $program->starts_at?->format('d/m/Y') }} → {{ $program->ends_at?->format('d/m/Y') }}</p>
                <p><b>Places :</b> {{ $program->seats }}</p>
                <p><b>Candidatures :</b> {{ $program->application_opens_at?->format('d/m/Y') }} → {{ $program->application_closes_at?->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Partenaires</h2></div>
            <div class="card-body text-sm space-y-1">
                @forelse($program->partners as $partner)
                    <p>🤝 {{ $partner->name }} <span class="text-xs text-slate-400">({{ $partner->type }})</span></p>
                @empty
                    <p class="text-slate-400">Aucun partenaire.</p>
                @endforelse
            </div>
        </div>

        <div class="card lg:col-span-2">
            <div class="card-header"><h2 class="font-semibold">Critères d'évaluation</h2></div>
            <div class="card-body">
                <ul class="space-y-2 text-sm">
                    @foreach($program->evaluationCriteria as $c)
                        <li class="flex justify-between border-b border-slate-100 pb-2">
                            <span>{{ $c->label }}</span>
                            <span class="text-xs text-slate-500">poids {{ $c->weight }} · /{{ $c->max_score }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Membres</h2></div>
            <div class="card-body text-sm space-y-2">
                @if($program->organizers->isNotEmpty())
                    <p class="text-xs text-slate-400 uppercase">Organisateurs</p>
                    @foreach($program->organizers as $o)<p>👤 {{ $o->full_name }}</p>@endforeach
                @endif
                @if($program->juries->isNotEmpty())
                    <p class="text-xs text-slate-400 uppercase mt-3">Jury</p>
                    @foreach($program->juries as $j)<p>⚖️ {{ $j->full_name }}</p>@endforeach
                @endif
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.programs.archive', $program) }}" class="mt-6">
        @csrf
        <button class="btn-secondary text-sm">Archiver ce programme</button>
    </form>
@endsection
