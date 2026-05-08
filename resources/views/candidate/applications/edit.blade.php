@extends('layouts.app')
@section('title', 'Candidature — '.$application->program->title)
@section('content')
    <a href="{{ route('candidate.dashboard') }}" class="text-sm text-slate-500 hover:underline">← Mon espace</a>
    <h1 class="text-2xl font-bold mt-2">{{ $application->program->title }}</h1>
    <p class="text-slate-500 mb-6">Référence {{ $application->reference }} · <x-status-badge :label="$application->status->label()" :color="$application->status->color()" /></p>

    <form method="POST" action="{{ route('candidate.applications.update', $application) }}" enctype="multipart/form-data" class="space-y-8 max-w-3xl">
        @csrf @method('PATCH')

        <div class="card">
            <div class="card-header"><h2 class="font-semibold">Motivation & projet</h2></div>
            <div class="card-body space-y-4">
                <x-textarea name="motivation" label="Lettre de motivation" :value="$application->motivation" rows="6" />
                <x-textarea name="project_summary" label="Résumé de votre projet" :value="$application->project_summary" rows="5" />
            </div>
        </div>

        @php
            $sections = $application->program->applicationFields->groupBy('section');
            $responses = $application->responses->keyBy('application_field_id');
        @endphp

        @foreach($sections as $section => $fields)
            <div class="card">
                <div class="card-header"><h2 class="font-semibold uppercase tracking-wide text-sm">{{ str_replace('_', ' ', $section) }}</h2></div>
                <div class="card-body space-y-4">
                    @foreach($fields as $field)
                        @php $current = $responses[$field->id] ?? null; @endphp
                        @if(in_array($field->type, ['text', 'email', 'tel', 'url', 'number', 'date']))
                            <x-input :type="$field->type" :name="'responses['.$field->id.']'" :label="$field->label" :value="$current?->value" :required="$field->is_required" :help="$field->help_text" />
                        @elseif($field->type === 'textarea')
                            <x-textarea :name="'responses['.$field->id.']'" :label="$field->label" :value="$current?->value" :required="$field->is_required" :help="$field->help_text" rows="5" />
                        @elseif(in_array($field->type, ['select', 'radio']))
                            <x-select :name="'responses['.$field->id.']'" :label="$field->label" :options="collect($field->options ?? [])->mapWithKeys(fn($o) => [$o['value'] => $o['label']])->all()" :selected="$current?->value" :required="$field->is_required" placeholder="—" />
                        @elseif($field->type === 'checkbox' || $field->type === 'multiselect')
                            <div>
                                <label class="form-label">{{ $field->label }}</label>
                                @foreach(($field->options ?? []) as $opt)
                                    <label class="flex items-center gap-2 mt-1 text-sm">
                                        <input type="checkbox" name="responses[{{ $field->id }}][]" value="{{ $opt['value'] }}"
                                            @checked(in_array($opt['value'], (array)($current?->value_json ?? [])))
                                            class="rounded text-brand-600">
                                        {{ $opt['label'] }}
                                    </label>
                                @endforeach
                            </div>
                        @elseif(in_array($field->type, ['file', 'video']))
                            <div>
                                <label class="form-label">{{ $field->label }} @if($field->is_required)<span class="text-red-500">*</span>@endif</label>
                                <input type="file" name="responses[{{ $field->id }}]" class="form-input">
                                @if($current?->value)
                                    @php $doc = $application->documents->firstWhere('category', $field->key); @endphp
                                    @if($doc)
                                        <p class="text-xs mt-1">📎 {{ $doc->original_name }} ({{ $doc->humanSize() }})</p>
                                    @endif
                                @endif
                                @if($field->help_text)<p class="text-xs text-slate-500 mt-1">{{ $field->help_text }}</p>@endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex flex-wrap gap-3">
            <button type="submit" name="submit" value="0" class="btn-secondary">💾 Enregistrer le brouillon</button>
            <button type="submit" name="submit" value="1" class="btn-primary"
                    onclick="return confirm('Soumettre définitivement ? Vous ne pourrez plus modifier après envoi.')">
                ✓ Soumettre ma candidature
            </button>
        </div>
    </form>
@endsection
