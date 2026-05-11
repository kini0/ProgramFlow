@php $r = $report ?? null; @endphp

<div class="card">
    <div class="card-header"><h2 class="font-semibold">Informations</h2></div>
    <div class="card-body space-y-4">
        <x-input name="title" label="Nom du rapport" :value="$r?->title" required />
        <x-textarea name="description" label="Description courte" :value="$r?->description" rows="2" />
        <x-input type="date" name="activity_date" label="Date de l'activité" :value="$r?->activity_date?->format('Y-m-d')" required />

        @if($sessions->isNotEmpty())
            <x-select name="program_session_id" label="Session liée (optionnel)"
                      :options="$sessions->pluck('title', 'id')->all()"
                      :selected="$r?->program_session_id"
                      placeholder="—" />
        @endif

        <x-textarea name="content" label="Contenu détaillé" :value="$r?->content" rows="10"
                    help="Compte rendu complet : déroulé, faits marquants, leçons apprises..." />
    </div>
</div>

<div class="card">
    <div class="card-header"><h2 class="font-semibold">Fichier principal</h2></div>
    <div class="card-body">
        <label class="form-label">Document téléchargeable (PDF, DOC)</label>
        <input type="file" name="report_file" accept=".pdf,.doc,.docx" class="form-input">
        <p class="text-xs text-slate-400 mt-1">Optionnel. Sera téléchargeable depuis la page de consultation.</p>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2 class="font-semibold">Galerie d'images</h2></div>
    <div class="card-body">
        <label class="form-label">Photos de l'activité</label>
        <input type="file" name="gallery_images[]" accept="image/*" multiple class="form-input">
        <p class="text-xs text-slate-400 mt-1">Plusieurs images possibles. Formats : JPG, PNG, WebP.</p>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2 class="font-semibold">Vidéos</h2></div>
    <div class="card-body">
        <label class="form-label">Vidéos de l'activité</label>
        <input type="file" name="gallery_videos[]" accept="video/*" multiple class="form-input">
        <p class="text-xs text-slate-400 mt-1">Formats : MP4, WebM, MOV. Pensez à compresser pour le web.</p>
    </div>
</div>
