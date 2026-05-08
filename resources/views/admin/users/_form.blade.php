<div class="grid md:grid-cols-2 gap-3">
    <x-input name="first_name" label="Prénom" :value="$user?->first_name" required />
    <x-input name="last_name" label="Nom" :value="$user?->last_name" required />
</div>
<x-input name="email" label="Email" type="email" :value="$user?->email" required />
<x-input name="phone" label="Téléphone" :value="$user?->phone" />
<x-select name="role" label="Rôle" :options="collect($roles)->mapWithKeys(fn($r) => [$r->value => $r->label()])->all()" :selected="$user?->roles->first()?->name" required />

<div class="flex items-center gap-2">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded text-brand-600" {{ ($user?->is_active ?? true) ? 'checked' : '' }}>
    <label for="is_active" class="text-sm">Compte actif</label>
</div>

<div class="grid md:grid-cols-2 gap-3">
    <x-input type="password" name="password" label="Mot de passe @if($user) (laisser vide = inchangé)@endif" />
    <x-input type="password" name="password_confirmation" label="Confirmer" />
</div>
