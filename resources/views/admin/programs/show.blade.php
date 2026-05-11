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
            <a href="{{ route('organizer.programs.applications.index', $program) }}" class="btn-secondary"><x-icon name="tray" /> Candidatures</a>
            <a href="{{ route('admin.programs.fields.index', $program) }}" class="btn-secondary"><x-icon name="sliders-horizontal" /> Form Builder</a>
            <a href="{{ route('admin.programs.activityReports.index', $program) }}" class="btn-secondary"><x-icon name="newspaper" /> Rapports</a>
            <a href="{{ route('admin.programs.edit', $program) }}" class="btn-primary"><x-icon name="pencil-simple" /> Modifier</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Informations --}}
        <div class="card lg:col-span-2">
            <div class="card-header"><h2 class="font-semibold">Informations</h2></div>
            <div class="card-body space-y-3 text-sm">
                <p><b>Période :</b> {{ $program->starts_at?->format('d/m/Y') }} <x-icon name="arrow-right" class="text-slate-300" /> {{ $program->ends_at?->format('d/m/Y') }}</p>
                <p><b>Places :</b> {{ $program->seats }}</p>
                <p><b>Candidatures :</b> {{ $program->application_opens_at?->format('d/m/Y') }} <x-icon name="arrow-right" class="text-slate-300" /> {{ $program->application_closes_at?->format('d/m/Y') }}</p>
                @if($program->objectives)<p class="pt-2 border-t border-slate-100"><b>Objectifs :</b><br><span class="whitespace-pre-line">{{ $program->objectives }}</span></p>@endif
                @if($program->eligibility)<p><b>Éligibilité :</b><br><span class="whitespace-pre-line">{{ $program->eligibility }}</span></p>@endif
            </div>
        </div>

        {{-- Critères d'évaluation --}}
        <div class="card">
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

        {{-- PARTENAIRES --}}
        <div class="card lg:col-span-2">
            <div class="card-header flex items-center justify-between">
                <h2 class="font-semibold">Partenaires associés</h2>
                <span class="text-xs text-slate-400">{{ $program->partners->count() }} partenaire(s)</span>
            </div>
            <div class="card-body">
                @if($program->partners->isEmpty())
                    <p class="text-sm text-slate-400 mb-4">Aucun partenaire pour le moment.</p>
                @else
                    <table class="w-full text-sm mb-4">
                        <thead class="text-xs text-slate-400 uppercase">
                            <tr><th class="text-left pb-2">Nom</th><th class="text-left pb-2">Type</th><th class="text-left pb-2">Rôle</th><th></th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($program->partners as $partner)
                                <tr>
                                    <td class="py-2 font-medium">{{ $partner->name }}</td>
                                    <td class="py-2 text-slate-500">{{ $partner->type }}</td>
                                    <td class="py-2">
                                        <form method="POST" action="{{ route('admin.programs.partners.update', [$program, $partner]) }}" class="flex gap-2">
                                            @csrf @method('PATCH')
                                            <input type="text" name="role" value="{{ $partner->pivot->role }}" placeholder="Rôle" class="form-input text-xs py-1">
                                            <button class="text-xs text-brand-600 hover:underline">OK</button>
                                        </form>
                                    </td>
                                    <td class="py-2 text-right">
                                        <form method="POST" action="{{ route('admin.programs.partners.destroy', [$program, $partner]) }}"
                                              onsubmit="return confirm('Retirer ce partenaire ?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-red-500 hover:underline">Retirer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Formulaire d'ajout --}}
                <form method="POST" action="{{ route('admin.programs.partners.store', $program) }}" class="border-t border-slate-100 pt-4">
                    @csrf
                    <p class="text-xs uppercase text-slate-400 mb-2">Ajouter des partenaires</p>
                    <div class="grid md:grid-cols-3 gap-2">
                        <select name="partner_ids[]" multiple required class="form-input md:col-span-2 h-32" size="6">
                            @foreach(\App\Models\Partner::orderBy('name')->get() as $p)
                                @if(! $program->partners->contains($p->id))
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->type }})</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="space-y-2">
                            <input type="text" name="role" placeholder="Rôle (optionnel)" class="form-input">
                            <button class="btn-primary w-full">Associer</button>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Maintenez Ctrl/Cmd pour sélectionner plusieurs partenaires.</p>
                </form>
            </div>
        </div>

        {{-- MEMBRES (Organisateurs / Jurys) --}}
        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Équipe du programme</h2></div>
            <div class="card-body space-y-3 text-sm">
                @php
                    $byRole = $program->members->groupBy('pivot.role');
                @endphp

                @foreach(['organizer' => 'Organisateurs', 'jury' => 'Jury', 'mentor' => 'Mentors'] as $roleKey => $label)
                    @if(($byRole[$roleKey] ?? collect())->isNotEmpty())
                        <div>
                            <p class="text-xs text-slate-400 uppercase mb-1">{{ $label }}</p>
                            @foreach($byRole[$roleKey] as $u)
                                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                                    <span>{{ $u->full_name }}</span>
                                    <form method="POST" action="{{ route('admin.programs.members.destroy', [$program, $u]) }}"
                                          onsubmit="return confirm('Retirer ce membre ?')">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="role" value="{{ $roleKey }}">
                                        <button class="text-xs text-red-500 hover:underline" title="Retirer"><x-icon name="x" /></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                {{-- Formulaire d'ajout --}}
                <form method="POST" action="{{ route('admin.programs.members.store', $program) }}" class="border-t border-slate-100 pt-3">
                    @csrf
                    <p class="text-xs uppercase text-slate-400 mb-2">Ajouter des membres</p>
                    <select name="role" required class="form-input mb-2">
                        <option value="organizer">Organisateur</option>
                        <option value="jury">Jury</option>
                        <option value="mentor">Mentor</option>
                        <option value="speaker">Intervenant</option>
                    </select>
                    <select name="user_ids[]" multiple required class="form-input h-32" size="5">
                        @foreach(\App\Models\User::orderBy('last_name')->get() as $u)
                            <option value="{{ $u->id }}">{{ $u->full_name }} <span>({{ $u->email }})</span></option>
                        @endforeach
                    </select>
                    <button class="btn-primary w-full mt-2">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.programs.archive', $program) }}" class="mt-6">
        @csrf
        <button class="btn-secondary text-sm">Archiver ce programme</button>
    </form>
@endsection
