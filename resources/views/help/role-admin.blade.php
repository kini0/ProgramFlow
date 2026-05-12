@extends('layouts.help')
@section('content')
    <a href="{{ route('help.index') }}" class="text-sm text-slate-500 hover:text-brand-700 inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Centre d'aide
    </a>

    <h1 class="mt-2"><x-icon name="shield-star" weight="duotone" class="text-amber-500" /> Guide de l'administrateur</h1>

    <p>
        L'administrateur est le chef d'orchestre de ProgramFlow. Il configure la plateforme,
        crée les utilisateurs, les programmes et supervise l'ensemble des activités.
        Il a <strong>tous les droits</strong> sur toutes les ressources.
    </p>

    <h2><x-icon name="map-trifold" /> Vue d'ensemble du parcours</h2>

    <div class="grid md:grid-cols-3 gap-3 my-4 not-prose">
        @foreach([
            ['1','users-three','Gérer les utilisateurs','Créer les comptes pour chaque rôle.'],
            ['2','handshake','Gérer les partenaires','Référencer les organisations partenaires.'],
            ['3','briefcase','Créer un programme','Configurer dates, places, objectifs.'],
            ['4','user-plus','Associer membres & partenaires','Lier les jurys, organisateurs, partenaires.'],
            ['5','sliders-horizontal','Personnaliser le formulaire','Form builder pour la section dynamique.'],
            ['6','chart-bar','Superviser','Reporting global et par programme.'],
        ] as [$num, $icon, $title, $desc])
            <div class="step-card">
                <span class="num">{{ $num }}</span>
                <p class="font-semibold flex items-center gap-2"><x-icon :name="$icon" /> {{ $title }}</p>
                <p class="text-sm text-slate-600">{{ $desc }}</p>
            </div>
        @endforeach
    </div>

    {{-- ÉTAPE 1 --}}
    <h2 id="users"><x-icon name="users-three" /> 1. Gérer les utilisateurs</h2>

    <p>Avant de créer un programme, créez les comptes des intervenants : organisateurs, jurys, partenaires.</p>

    <ol>
        <li>Cliquez sur <strong>« Utilisateurs »</strong> dans le menu de gauche.</li>
        <li>Cliquez sur <strong>« Nouvel utilisateur »</strong> en haut à droite.</li>
        <li>Renseignez prénom, nom, email, téléphone (optionnel).</li>
        <li>Choisissez le <strong>rôle</strong> dans la liste : Organisateur, Jury, Candidate, Partenaire.</li>
        <li>Définissez un mot de passe temporaire que vous transmettrez à l'utilisateur.</li>
        <li>Cochez <strong>« Compte actif »</strong> et validez.</li>
    </ol>

    <x-help.screen-mock title="Liste des utilisateurs" url="/admin/users" image="admin-users.png">
        <div class="bg-white rounded-lg border border-slate-200">
            <div class="flex items-center justify-between p-4 border-b border-slate-100">
                <h3 class="font-bold !mt-0">Utilisateurs</h3>
                <span class="btn-primary text-sm pointer-events-none"><x-icon name="plus" /> Nouvel utilisateur</span>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr><th class="px-4 py-2 text-left">Nom</th><th class="px-4 py-2 text-left">Email</th><th class="px-4 py-2 text-left">Rôle</th><th class="px-4 py-2 text-left">Statut</th></tr>
                </thead>
                <tbody class="divide-y">
                    <tr><td class="px-4 py-2">Aïcha Koné</td><td class="px-4 py-2">aicha@…</td><td class="px-4 py-2">Organisateur</td><td class="px-4 py-2"><span class="badge bg-emerald-100 text-emerald-700">Actif</span></td></tr>
                    <tr><td class="px-4 py-2">Mariam Diabaté</td><td class="px-4 py-2">mariam@…</td><td class="px-4 py-2">Jury</td><td class="px-4 py-2"><span class="badge bg-emerald-100 text-emerald-700">Actif</span></td></tr>
                </tbody>
            </table>
        </div>
    </x-help.screen-mock>

    <div class="callout info">
        <p class="!mb-0"><strong>Astuce :</strong> Un utilisateur peut avoir un seul rôle global. Pour des cas particuliers (un jury qui est aussi mentor), créez un compte par fonction ou utilisez les associations par programme.</p>
    </div>

    {{-- ÉTAPE 2 --}}
    <h2 id="partners"><x-icon name="handshake" /> 2. Gérer les partenaires</h2>

    <ol>
        <li>Cliquez sur <strong>« Partenaires »</strong> dans le menu.</li>
        <li>Cliquez sur <strong>« Nouveau partenaire »</strong>.</li>
        <li>Renseignez nom, type (financier, technique, institutionnel, média), description, logo.</li>
        <li><strong>Optionnel :</strong> liez un compte utilisateur partenaire pour qu'il puisse accéder à son espace.</li>
    </ol>

    {{-- ÉTAPE 3 --}}
    <h2 id="programs"><x-icon name="briefcase" /> 3. Créer un programme</h2>

    <ol>
        <li>Cliquez sur <strong>« Programmes »</strong> → <strong>« Nouveau programme »</strong>.</li>
        <li>Saisissez le titre, la description courte, la description complète.</li>
        <li>Précisez les <strong>objectifs</strong> et les <strong>conditions d'éligibilité</strong>.</li>
        <li>Définissez les <strong>dates clés</strong> :
            <ul>
                <li>Ouverture / clôture des candidatures</li>
                <li>Date de début / fin du programme</li>
            </ul>
        </li>
        <li>Précisez le <strong>nombre de places</strong>.</li>
        <li>Téléversez une image de couverture (recommandé).</li>
        <li>Choisissez le statut initial : <code>draft</code> (brouillon) ou <code>published</code>.</li>
        <li>Validez. Le système crée automatiquement :
            <ul>
                <li>Les <strong>champs standard</strong> du formulaire de candidature (~50 champs)</li>
                <li>Les <strong>critères d'évaluation</strong> par défaut (4 critères pondérés)</li>
            </ul>
        </li>
    </ol>

    {{-- ÉTAPE 4 --}}
    <h2 id="members"><x-icon name="user-plus" /> 4. Associer membres et partenaires</h2>

    <p>Une fois le programme créé, ouvrez sa fiche : trois blocs en bas vous permettent de gérer l'équipe.</p>

    <h3>Ajouter des partenaires</h3>
    <ol>
        <li>Dans le bloc <strong>« Partenaires associés »</strong>, sélectionnez un ou plusieurs partenaires (Ctrl/Cmd+clic).</li>
        <li>Saisissez un rôle de partenariat (ex: « Sponsor principal »).</li>
        <li>Cliquez sur <strong>« Associer »</strong>.</li>
    </ol>

    <h3>Ajouter organisateurs, jurys, mentors</h3>
    <ol>
        <li>Dans le bloc <strong>« Équipe du programme »</strong>, choisissez d'abord le rôle (Organisateur / Jury / Mentor).</li>
        <li>Sélectionnez les utilisateurs concernés.</li>
        <li>Cliquez sur <strong>« Ajouter »</strong>. Le rôle global Spatie est appliqué automatiquement si l'utilisateur ne l'avait pas.</li>
    </ol>

    <div class="callout warning">
        <p class="!mb-0"><strong>Important :</strong> Sans jury associé, l'évaluation des candidatures sera impossible. Vérifiez toujours qu'au moins 1-2 jurys sont associés à un programme avant d'ouvrir les candidatures.</p>
    </div>

    {{-- ÉTAPE 5 --}}
    <h2 id="form"><x-icon name="sliders-horizontal" /> 5. Personnaliser le formulaire de candidature</h2>

    <p>
        Le formulaire est composé de <strong>3 étapes</strong> :
    </p>
    <ul>
        <li><strong>Étape 1 — Informations personnelles</strong> (8 sections fixes) : Identité, Coordonnées, Pièce d'identité, Parcours académique, Expérience, Santé.</li>
        <li><strong>Étape 2 — Parents & contact d'urgence</strong> (sections fixes).</li>
        <li><strong>Étape 3 — Spécifique au programme</strong> (entièrement configurable !) + Déclaration finale.</li>
    </ul>

    <p>Pour personnaliser l'étape 3 :</p>
    <ol>
        <li>Ouvrez la fiche du programme.</li>
        <li>Cliquez sur <strong>« Form Builder »</strong> en haut à droite.</li>
        <li>Ajoutez des champs via le formulaire de droite : choisissez le type (texte, date, fichier, etc.), libellé, obligatoire ou non.</li>
        <li>Réordonnez les champs en faisant glisser la poignée <x-icon name="dots-six-vertical" /> à gauche.</li>
        <li>Cliquez sur <strong>« Éditer »</strong> pour modifier un champ, <strong>« Supprimer »</strong> pour le retirer.</li>
    </ol>

    {{-- ÉTAPE 6 --}}
    <h2 id="reporting"><x-icon name="chart-bar" /> 6. Reporting et supervision</h2>

    <ol>
        <li>Menu <strong>« Reporting »</strong> : tableau de bord global avec stats agrégées.</li>
        <li>Cliquez sur un programme pour voir le <strong>rapport détaillé</strong> avec graphique de répartition des statuts.</li>
        <li>Sur la fiche d'un programme, accédez aux <strong>rapports d'activité</strong> pour documenter les sessions et événements.</li>
    </ol>

    <h2><x-icon name="archive" /> Archiver un programme</h2>
    <p>À la fin du cycle, ouvrez la fiche programme et cliquez sur <strong>« Archiver ce programme »</strong>. Les données restent consultables mais le programme n'apparaît plus dans les filtres actifs.</p>

    <div class="callout success">
        <p class="!mb-0"><strong>Bonnes pratiques :</strong>
        Configurez les champs et critères <em>avant</em> d'ouvrir les candidatures.
        Ne supprimez jamais un utilisateur ayant des évaluations soumises — désactivez-le plutôt (case "Compte actif" décochée).</p>
    </div>
@endsection
