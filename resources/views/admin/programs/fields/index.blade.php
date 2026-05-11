@extends('layouts.app')
@section('title', 'Form Builder — '.$program->title)
@section('content')
    <a href="{{ route('admin.programs.show', $program) }}" class="text-sm text-slate-500 hover:underline inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Retour au programme
    </a>
    <div class="flex items-start justify-between mt-2 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Form Builder</h1>
            <p class="text-slate-500">Section dynamique du formulaire — {{ $program->title }}</p>
        </div>
    </div>

    <x-alert type="info">
        Les sections <b>Identité</b>, <b>Coordonnées</b>, <b>Pièce d'identité</b>, <b>Parcours</b>, <b>Expérience</b>,
        <b>Santé</b>, <b>Parents</b>, <b>Contact d'urgence</b> et <b>Déclaration finale</b> sont fixes et identiques pour
        tous les programmes. Vous configurez ici uniquement les <b>champs spécifiques à ce programme</b>.
    </x-alert>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header flex items-center justify-between">
                <h2 class="font-semibold">Champs dynamiques ({{ $fields->count() }})</h2>
            </div>
            <div class="card-body">
                @if($fields->isEmpty())
                    <p class="text-slate-400 text-center py-8">Aucun champ dynamique. Ajoutez-en via le formulaire à droite.</p>
                @else
                    <ul id="fields-list" class="space-y-2">
                        @foreach($fields as $field)
                            <li data-id="{{ $field->id }}" class="border border-slate-200 rounded-lg p-3 bg-white flex items-start gap-3">
                                <span class="cursor-grab text-slate-400 select-none mt-1" title="Glisser pour réordonner">
                                    <x-icon name="dots-six-vertical" class="text-lg" />
                                </span>
                                <div class="flex-1">
                                    <p class="font-medium">
                                        {{ $field->label }}
                                        @if($field->is_required)<span class="text-red-500 text-xs">*</span>@endif
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        Type : <b>{{ $field->type }}</b> · clé : <code>{{ $field->key }}</code>
                                        @if($field->help_text)· <i>{{ $field->help_text }}</i>@endif
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" class="text-xs text-brand-600 hover:underline"
                                            x-on:click="$dispatch('edit-field', {{ $field->toJson() }})">Éditer</button>
                                    <form method="POST" action="{{ route('admin.programs.fields.destroy', [$program, $field]) }}"
                                          onsubmit="return confirm('Supprimer ce champ ?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-500 hover:underline">Supprimer</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- FORMULAIRE D'AJOUT / ÉDITION --}}
        <div class="card" x-data="{
                editing: null,
                init() {
                    window.addEventListener('edit-field', e => {
                        this.editing = e.detail;
                        this.$refs.label.value = e.detail.label;
                        this.$refs.type.value = e.detail.type;
                        this.$refs.help.value = e.detail.help_text || '';
                        this.$refs.required.checked = !!e.detail.is_required;
                        this.$el.scrollIntoView({behavior:'smooth'});
                    });
                }
             }">
            <div class="card-header"><h2 class="font-semibold" x-text="editing ? 'Modifier le champ' : 'Ajouter un champ'"></h2></div>
            <form method="POST"
                  x-bind:action="editing ? '{{ route('admin.programs.fields.index', $program) }}/' + editing.id : '{{ route('admin.programs.fields.store', $program) }}'"
                  class="card-body space-y-3">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PATCH"></template>

                <x-input name="label" label="Libellé" x-ref="label" required />
                <x-input name="key" label="Clé technique (auto si vide)" help="Ex: motivation_projet (lettres, chiffres, _ -)" />
                <div>
                    <label class="form-label">Type de champ</label>
                    <select name="type" x-ref="type" class="form-input" required>
                        <option value="text">Texte court</option>
                        <option value="textarea">Texte long</option>
                        <option value="email">Email</option>
                        <option value="tel">Téléphone</option>
                        <option value="url">URL</option>
                        <option value="number">Nombre</option>
                        <option value="date">Date</option>
                        <option value="select">Menu déroulant</option>
                        <option value="radio">Choix unique</option>
                        <option value="checkbox">Case(s) à cocher</option>
                        <option value="file">Fichier</option>
                        <option value="video">Vidéo</option>
                    </select>
                </div>
                <x-textarea name="help_text" label="Texte d'aide (optionnel)" x-ref="help" rows="2" />

                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_required" value="0">
                    <input type="checkbox" id="is_required" name="is_required" value="1" x-ref="required" class="rounded">
                    <label for="is_required" class="text-sm">Champ obligatoire</label>
                </div>

                <div class="flex gap-2">
                    <button class="btn-primary flex-1" x-text="editing ? 'Enregistrer' : 'Ajouter'"></button>
                    <button type="button" class="btn-ghost" x-show="editing"
                            x-on:click="editing = null; $el.closest('form').reset()">Annuler</button>
                </div>

                <p class="text-xs text-slate-400">
                    Pour les types <i>select</i>, <i>radio</i>, <i>checkbox</i>, vous pourrez ajouter des options
                    après création (édition avancée).
                </p>
            </form>
        </div>
    </div>

    {{-- Drag & drop pour réordonner --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const list = document.getElementById('fields-list');
        if (list) {
            new Sortable(list, {
                handle: '.cursor-grab',
                animation: 150,
                onEnd: () => {
                    const ids = Array.from(list.children).map(li => li.dataset.id);
                    fetch('{{ route('admin.programs.fields.reorder', $program) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ fields: ids }),
                    });
                },
            });
        }
    </script>
@endsection
