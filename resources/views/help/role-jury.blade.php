@extends('layouts.help')
@section('content')
    <a href="{{ route('help.index') }}" class="text-sm text-slate-500 hover:text-brand-700 inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Centre d'aide
    </a>

    <h1 class="mt-2"><x-icon name="scales" weight="duotone" class="text-purple-500" /> Guide du membre du jury</h1>

    <p>
        En tant que membre du jury, vous évaluez les candidatures des programmes auxquels vous êtes associé.
        Le système vous attribue automatiquement <strong>toutes les candidatures soumises</strong> dès que la
        période de candidature est terminée.
    </p>

    <h2><x-icon name="map-trifold" /> Étapes de votre parcours</h2>

    <ol>
        <li>L'administrateur vous associe à un programme.</li>
        <li>Vous patientez pendant la période de candidatures.</li>
        <li>À la clôture, vous voyez automatiquement toutes les candidatures à évaluer.</li>
        <li>Vous évaluez chaque dossier selon une grille pondérée.</li>
        <li>Vous soumettez vos évaluations (action définitive).</li>
    </ol>

    <h2 id="dashboard"><x-icon name="squares-four" /> Votre tableau de bord</h2>

    <p>À la connexion, vous avez deux entrées principales :</p>
    <ul>
        <li><strong>« À évaluer »</strong> : la liste plate de toutes vos évaluations en attente, tous programmes confondus.</li>
        <li><strong>« Mes programmes »</strong> : vue par programme avec compteurs détaillés.</li>
    </ul>

    <x-help.screen-mock title="Mes programmes" url="/jury/programs" image="jury-programs.png">
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <h4 class="font-bold">Leadership Féminin 2026</h4>
                <span class="badge bg-amber-100 text-amber-700">Évaluation jury</span>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-3 text-center text-xs">
                <div class="bg-slate-50 rounded p-2"><p>Soumises</p><p class="font-bold text-lg">42</p></div>
                <div class="bg-amber-50 rounded p-2"><p class="text-amber-700">À évaluer</p><p class="font-bold text-lg text-amber-700">15</p></div>
                <div class="bg-emerald-50 rounded p-2"><p class="text-emerald-700">Faites</p><p class="font-bold text-lg text-emerald-700">27</p></div>
            </div>
        </div>
    </x-help.screen-mock>

    <div class="callout info">
        <p class="!mb-0">
            <strong>Quand sont attribuées les candidatures ?</strong> Dès que la <strong>période de candidature
            est fermée</strong> (date passée ou clôture manuelle par l'organisateur), toutes les candidatures soumises
            apparaissent automatiquement dans votre liste.
        </p>
    </div>

    <h2 id="evaluating"><x-icon name="pencil-simple" /> Évaluer une candidature</h2>

    <ol>
        <li>Cliquez sur <strong>« Évaluer »</strong> à côté d'une candidature dans votre dashboard.</li>
        <li>L'écran s'ouvre en <strong>2 colonnes</strong> :
            <ul>
                <li><strong>Gauche</strong> : le dossier complet de la candidate (toutes les sections : Identité, Coordonnées, Parcours, Projet, etc.). Scrollable indépendamment.</li>
                <li><strong>Droite</strong> : la <strong>grille d'évaluation</strong> avec un bloc par critère.</li>
            </ul>
        </li>
        <li>Lisez attentivement le dossier. Cliquez sur <strong>« Consulter »</strong> à côté de chaque fichier pour ouvrir le CV, la pièce d'identité, etc.</li>
        <li>Pour chaque critère, saisissez une <strong>note</strong> (entre 0 et la note maximale affichée).</li>
        <li>Ajoutez un <strong>commentaire</strong> optionnel par critère pour justifier votre note.</li>
        <li>Saisissez un <strong>commentaire global</strong> en bas.</li>
        <li>Cliquez sur <strong>« Soumettre l'évaluation »</strong>. Confirmez.</li>
    </ol>

    <div class="callout danger">
        <p class="!mb-0">
            <strong>Attention :</strong> La soumission est <strong>définitive</strong>. Vous ne pourrez plus
            modifier vos notes après. En cas d'erreur, contactez l'organisateur du programme.
        </p>
    </div>

    <h2><x-icon name="calculator" /> Comment le score final est-il calculé ?</h2>

    <p>
        Le système calcule un <strong>score pondéré</strong> à partir de vos notes et des poids définis pour chaque critère :
    </p>

    <pre class="bg-slate-900 text-white rounded-lg p-4 text-sm overflow-x-auto"><code>score pondéré = Σ(note × poids) / Σ(poids)</code></pre>

    <p>Exemple :</p>
    <ul>
        <li>Pertinence du parcours (poids 2) : note 15/20 → contribue 30 (15×2)</li>
        <li>Qualité du projet (poids 3) : note 17/20 → contribue 51 (17×3)</li>
        <li>Impact attendu (poids 3) : note 16/20 → contribue 48 (16×3)</li>
        <li>Motivation (poids 2) : note 18/20 → contribue 36 (18×2)</li>
    </ul>
    <p>Score pondéré = (30 + 51 + 48 + 36) / (2+3+3+2) = <strong>16.5 / 20</strong></p>

    <p>
        Le score moyen affiché sur la candidature est ensuite la <strong>moyenne des scores pondérés</strong>
        de tous les jurys ayant évalué cette candidature.
    </p>

    <h2 id="best-practices"><x-icon name="lightbulb" /> Bonnes pratiques d'évaluation</h2>

    <ul>
        <li><strong>Lisez l'intégralité du dossier</strong> avant de noter.</li>
        <li><strong>Soyez constant</strong> dans votre échelle de notation entre les candidatures.</li>
        <li><strong>Argumentez</strong> les notes inhabituelles (très haute ou très basse) en commentaire.</li>
        <li><strong>Évitez les biais</strong> : nom, photo, origine ne doivent pas influencer votre note.</li>
        <li><strong>Concentrez-vous</strong> sur les critères définis, pas sur des impressions générales.</li>
    </ul>

    <h2><x-icon name="envelope" /> Notifications</h2>

    <p>Vous recevez des notifications email dans plusieurs cas :</p>
    <ul>
        <li>Quand un organisateur vous attribue manuellement une candidature spécifique (cas exceptionnel).</li>
        <li>Quand un programme passe en phase d'évaluation (sur certaines fondations).</li>
    </ul>

    <p>
        Pour les attributions automatiques (cas standard), aucun email n'est envoyé — pour éviter de vous inonder
        de notifications. Connectez-vous régulièrement pour consulter votre dashboard.
    </p>
@endsection
