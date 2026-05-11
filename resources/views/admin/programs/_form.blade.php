@php $p = $program ?? null; @endphp
<x-input name="title" label="Titre du programme" :value="$p?->title" required />
<x-textarea name="short_description" label="Description courte" :value="$p?->short_description" rows="2" />
<x-textarea name="description"       label="Description complète" :value="$p?->description" rows="6" />
<x-textarea name="objectives"        label="Objectifs" :value="$p?->objectives" />
<x-textarea name="eligibility"       label="Conditions d'éligibilité" :value="$p?->eligibility" />

<div class="grid md:grid-cols-2 gap-4">
    <x-input type="number" name="seats" label="Nombre de places" :value="$p?->seats" />
    <x-select name="status" label="Statut" :options="collect($statuses)->mapWithKeys(fn($s) => [$s->value => $s->label()])->all()" :selected="$p?->status?->value" />
    <x-input type="date" name="application_opens_at" label="Ouverture des candidatures" :value="$p?->application_opens_at?->format('Y-m-d')" />
    <x-input type="date" name="application_closes_at" label="Clôture des candidatures" :value="$p?->application_closes_at?->format('Y-m-d')" />
    <x-input type="date" name="starts_at" label="Date de début" :value="$p?->starts_at?->format('Y-m-d')" />
    <x-input type="date" name="ends_at" label="Date de fin" :value="$p?->ends_at?->format('Y-m-d')" />
</div>

<div>
    <label class="form-label">Image de couverture</label>
    <input type="file" name="cover_image" class="form-input" accept="image/*">
    @if($p?->cover_image_path)
        <img src="{{ Storage::url($p->cover_image_path) }}" alt="" class="mt-2 h-24 rounded-lg">
    @endif
</div>

@unless($p)
    <x-alert type="info">
        💡 Après la création, vous pourrez sur la fiche programme :
        <ul class="list-disc list-inside mt-2 text-sm">
            <li>Associer des <b>partenaires</b> et leur attribuer un rôle</li>
            <li>Ajouter des <b>organisateurs et membres du jury</b></li>
            <li>Personnaliser le <b>formulaire de candidature</b> (Form Builder)</li>
            <li>Créer des <b>rapports d'activité</b> (textes, photos, vidéos)</li>
        </ul>
    </x-alert>
@endunless
