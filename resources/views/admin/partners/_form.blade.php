<x-input name="name" label="Nom du partenaire" :value="$partner?->name" required />
<x-select name="type" label="Type" :options="['financier' => 'Financier', 'technique' => 'Technique', 'institutionnel' => 'Institutionnel', 'media' => 'Média', 'autre' => 'Autre']" :selected="$partner?->type" required />
<div class="grid md:grid-cols-2 gap-3">
    <x-input name="contact_name" label="Personne de contact" :value="$partner?->contact_name" />
    <x-input name="contact_email" type="email" label="Email contact" :value="$partner?->contact_email" />
    <x-input name="contact_phone" label="Téléphone" :value="$partner?->contact_phone" />
    <x-input name="website" type="url" label="Site web" :value="$partner?->website" />
</div>
<x-textarea name="description" label="Description" :value="$partner?->description" />
<x-select name="user_id" label="Compte utilisateur associé" :options="$users->pluck('full_name', 'id')->all()" :selected="$partner?->user_id" placeholder="Aucun" />
<div>
    <label class="form-label">Logo</label>
    <input type="file" name="logo" class="form-input">
    @if($partner?->logo_path)<img src="{{ Storage::url($partner->logo_path) }}" class="h-16 mt-2 rounded">@endif
</div>
<div class="flex items-center gap-2">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" class="rounded" {{ ($partner?->is_active ?? true) ? 'checked' : '' }}>
    <label class="text-sm">Actif</label>
</div>
