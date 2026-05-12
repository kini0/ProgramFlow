@extends('layouts.help')
@section('content')
    <a href="{{ route('help.index') }}" class="text-sm text-slate-500 hover:text-brand-700 inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Centre d'aide
    </a>

    <h1 class="mt-2"><x-icon name="briefcase" weight="duotone" class="text-blue-500" /> Guide de l'organisateur</h1>

    <p>
        L'organisateur est le bras droit opérationnel de la fondation. Il prend en charge un ou plusieurs programmes
        et gère le quotidien : candidatures, sélection, sessions, suivi des participantes.
    </p>

    <h2><x-icon name="map-trifold" /> Étapes de votre parcours</h2>

    <ol>
        <li><strong>Préparer</strong> le programme avant l'ouverture (vérifier champs, critères, jurys).</li>
        <li><strong>Suivre</strong> l'arrivée des candidatures.</li>
        <li><strong>Clôturer</strong> les candidatures et démarrer l'évaluation.</li>
        <li><strong>Finaliser la sélection</strong> et notifier les candidates.</li>
        <li><strong>Animer</strong> le programme actif : sessions, présences, tâches.</li>
        <li><strong>Documenter</strong> via les rapports d'activité.</li>
    </ol>

    <h2 id="dashboard"><x-icon name="squares-four" /> Votre tableau de bord</h2>

    <p>À la connexion, votre dashboard <strong>« Mes programmes »</strong> affiche uniquement les programmes auxquels vous êtes associé.</p>

    <x-help.screen-mock title="Dashboard organisateur" url="/organizer" image="organizer-dashboard.png">
        <div class="grid md:grid-cols-2 gap-3">
            <div class="bg-white border border-slate-200 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <h4 class="font-bold">Leadership Féminin 2026</h4>
                    <span class="badge bg-emerald-100 text-emerald-700">Candidatures ouvertes</span>
                </div>
                <div class="grid grid-cols-3 gap-2 mt-3 text-center text-xs">
                    <div class="bg-slate-50 rounded p-2"><p>Candidatures</p><p class="font-bold text-lg">42</p></div>
                    <div class="bg-slate-50 rounded p-2"><p>Sessions</p><p class="font-bold text-lg">0</p></div>
                    <div class="bg-slate-50 rounded p-2"><p>Rapports</p><p class="font-bold text-lg">0</p></div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-3">
                    <span class="btn-secondary text-xs pointer-events-none"><x-icon name="tray" /> Candidatures</span>
                    <span class="btn-secondary text-xs pointer-events-none"><x-icon name="trophy" /> Sélection</span>
                </div>
            </div>
        </div>
    </x-help.screen-mock>

    <h2 id="applications"><x-icon name="tray-arrow-down" /> Suivre les candidatures</h2>

    <ol>
        <li>Sur la fiche d'un programme, cliquez sur <strong>« Candidatures »</strong>.</li>
        <li>Utilisez la <strong>recherche</strong> (référence, nom, email) et les <strong>filtres par statut</strong>.</li>
        <li>Consultez les <strong>statistiques en haut</strong> : Total, Soumises, Présélectionnées, Acceptées.</li>
        <li>Cliquez sur une référence ou « Ouvrir » pour consulter le dossier complet.</li>
    </ol>

    <h3>Consulter le dossier d'une candidate</h3>

    <p>La fiche candidature affiche le <strong>dossier complet</strong> regroupé par section (Identité, Coordonnées, Pièce d'identité, Parcours, Expérience, Santé, Parents, Urgence, Spécifique au programme).</p>

    <p>
        Pour chaque champ fichier (CV, pièce d'identité, vidéo), un bouton <strong>« Consulter / Télécharger »</strong>
        s'affiche. Les images apparaissent en miniature.
    </p>

    <h2 id="evaluation"><x-icon name="lock" /> Clôturer les candidatures et démarrer l'évaluation</h2>

    <p>Quand vous êtes prêt à passer à la phase d'évaluation :</p>

    <ol>
        <li>Sur la liste des candidatures, repérez le <strong>bandeau orange</strong> « Clôturer les candidatures et démarrer l'évaluation ».</li>
        <li>Vérifiez que des <strong>jurys sont associés</strong> au programme (sinon, demandez à l'admin d'en ajouter).</li>
        <li>Cliquez sur <strong>« Démarrer l'évaluation »</strong>.</li>
    </ol>

    <div class="callout info">
        <p class="!mb-0"><strong>Que se passe-t-il alors ?</strong> Le statut du programme passe en <code>review</code>.
        Tous les jurys du programme peuvent désormais voir et évaluer <strong>toutes</strong> les candidatures soumises.
        Aucune attribution manuelle n'est nécessaire — c'est entièrement automatique.</p>
    </div>

    <h2 id="selection"><x-icon name="trophy" /> Sélection finale</h2>

    <ol>
        <li>Quand les jurys ont (suffisamment) évalué, cliquez sur <strong>« Sélection »</strong>.</li>
        <li>Le système affiche un <strong>classement automatique pondéré</strong> (score moyen × poids des critères).</li>
        <li>Indiquez le <strong>nombre de places</strong> à présélectionner et cliquez sur <strong>« Pré-sélectionner top N »</strong>.</li>
        <li>Les N meilleures candidatures passent automatiquement en statut <code>shortlisted</code>.</li>
        <li>Allez ensuite individuellement sur chaque candidature présélectionnée pour <strong>valider la décision finale</strong> :
            Accepter / Refuser / Liste d'attente, avec un commentaire qui sera envoyé par email.</li>
        <li>Une fois toutes les décisions prises, cliquez sur <strong>« Verrouiller la sélection »</strong> pour basculer le programme en phase <code>active</code>.</li>
    </ol>

    <h3>Exporter les résultats</h3>
    <ul>
        <li><strong>Excel</strong> : liste complète des candidatures avec scores.</li>
        <li><strong>PDF</strong> : classement formaté, prêt à imprimer/partager.</li>
    </ul>

    <h2 id="sessions"><x-icon name="calendar" /> Gérer les sessions</h2>

    <ol>
        <li>Cliquez sur <strong>« Sessions »</strong> dans la barre de navigation du programme.</li>
        <li>Cliquez sur <strong>« Planifier une session »</strong>.</li>
        <li>Renseignez : titre, type (formation, atelier, mentoring, événement), date/heure, lieu (ou lien online), facilitateur.</li>
        <li>Le jour J, ouvrez la session et marquez les <strong>présences</strong> : Présent / Absent / Excusé / Retard.</li>
        <li>Rédigez le <strong>compte rendu</strong> pour conserver une trace.</li>
    </ol>

    <h2 id="reports"><x-icon name="newspaper" /> Rédiger un rapport d'activité</h2>

    <ol>
        <li>Cliquez sur <strong>« Rapports »</strong> dans la barre de navigation du programme.</li>
        <li>Cliquez sur <strong>« Nouveau rapport »</strong>.</li>
        <li>Renseignez titre, description, date de l'activité, contenu détaillé.</li>
        <li>Téléversez :
            <ul>
                <li><strong>Fichier principal</strong> (PDF ou DOC du rapport formel)</li>
                <li><strong>Galerie d'images</strong> (multi-upload, format JPG/PNG)</li>
                <li><strong>Vidéos</strong> (MP4, WebM, MOV)</li>
            </ul>
        </li>
        <li>Choisissez <strong>« Enregistrer en brouillon »</strong> ou <strong>« Publier »</strong>.</li>
    </ol>

    <div class="callout warning">
        <p class="!mb-0">
            <strong>Limite des fichiers :</strong> 64 Mo par fichier maximum. Pour les vidéos longues,
            préférez l'hébergement sur YouTube et collez le lien dans le contenu détaillé.
        </p>
    </div>

    <h2><x-icon name="check-circle" /> Clôturer le programme</h2>
    <p>
        Quand toutes les sessions sont terminées, demandez à l'administrateur de changer le statut en <code>completed</code>.
        Le programme reste consultable mais figé. Plus tard, il pourra être archivé.
    </p>
@endsection
