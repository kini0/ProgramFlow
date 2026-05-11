@extends('layouts.app')
@section('title', $session->title)
@section('content')
    <a href="{{ route('organizer.programs.sessions.index', $program) }}" class="text-sm text-slate-500 hover:underline inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Sessions
    </a>
    <h1 class="text-2xl font-bold mt-2 mb-6">{{ $session->title }}</h1>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Informations</h2></div>
            <div class="card-body text-sm space-y-2">
                <p class="flex items-center gap-2"><x-icon name="calendar" class="text-slate-400" /> {{ $session->starts_at->format('d/m/Y H:i') }} <x-icon name="arrow-right" class="text-slate-300" /> {{ $session->ends_at?->format('d/m/Y H:i') ?? '—' }}</p>
                <p class="flex items-center gap-2"><x-icon name="map-pin" class="text-slate-400" /> {{ $session->location ?? '—' }}</p>
                <p class="flex items-center gap-2"><x-icon name="microphone" class="text-slate-400" /> Facilitateur : {{ $session->facilitator?->full_name ?? '—' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Compte rendu</h2></div>
            <form method="POST" action="{{ route('organizer.programs.sessions.update', [$program, $session]) }}" class="card-body">
                @csrf @method('PATCH')
                <input type="hidden" name="title" value="{{ $session->title }}">
                <input type="hidden" name="type" value="{{ $session->type }}">
                <input type="hidden" name="starts_at" value="{{ $session->starts_at->format('Y-m-d\TH:i') }}">
                <textarea name="report" class="form-input" rows="6">{{ $session->report }}</textarea>
                <button class="btn-primary mt-3">Enregistrer</button>
            </form>
        </div>
    </div>

    <div class="card mt-6">
        <div class="card-header"><h2 class="font-semibold">Présences</h2></div>
        <form method="POST" action="{{ route('organizer.programs.sessions.attendances', [$program, $session]) }}" class="card-body">
            @csrf
            <table class="table-app">
                <thead class="bg-slate-50"><tr><th>Participant</th><th>Statut</th><th>Note</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @foreach($program->participants as $i => $user)
                    @php $att = $session->attendances->firstWhere('user_id', $user->id); @endphp
                    <tr>
                        <td>{{ $user->full_name }}<input type="hidden" name="attendances[{{ $i }}][user_id]" value="{{ $user->id }}"></td>
                        <td>
                            <select name="attendances[{{ $i }}][status]" class="form-input">
                                @foreach(['present'=>'Présent','absent'=>'Absent','excused'=>'Excusé','late'=>'Retard'] as $v=>$l)
                                    <option value="{{ $v }}" @selected(($att?->status ?? 'absent') == $v)>{{ $l }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="attendances[{{ $i }}][note]" value="{{ $att?->note }}" class="form-input"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button class="btn-primary mt-3">Enregistrer les présences</button>
        </form>
    </div>
@endsection
