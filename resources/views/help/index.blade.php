@extends('layouts.help')
@section('content')
    <div class="bg-gradient-to-br from-brand-600 to-brand-800 text-white rounded-2xl px-8 py-12 mb-10">
        <h1 class="text-3xl md:text-4xl font-bold !text-white !mb-3">Centre d'aide ProgramFlow</h1>
        <p class="text-brand-100 max-w-2xl">
            Apprenez à utiliser efficacement la plateforme. Choisissez votre rôle ci-dessous
            pour accéder à un guide pas-à-pas adapté à vos besoins.
        </p>
    </div>

    <h2><x-icon name="user-circle" weight="duotone" class="text-brand-600" /> Choisissez votre profil</h2>

    <div class="grid md:grid-cols-2 gap-4 my-6 not-prose">
        @php
            $cards = [
                ['admin',     'Administrateur',  'Gérez les utilisateurs, programmes, partenaires et reporting global.', 'shield-star',  'amber'],
                ['organizer', 'Organisateur',    'Opérez le programme : candidatures, sélection, sessions, rapports.',   'briefcase',    'blue'],
                ['jury',      'Membre du jury',  'Évaluez les candidatures qui vous sont attribuées.',                    'scales',       'purple'],
                ['candidate', 'Candidate',       'Postulez à un programme, suivez votre dossier, recevez les décisions.',  'student',     'emerald'],
                ['partner',   'Partenaire',      'Consultez les programmes auxquels votre organisation contribue.',       'handshake',    'teal'],
            ];
        @endphp
        @foreach($cards as [$key, $title, $desc, $icon, $color])
            <a href="{{ route('help.role', $key) }}" class="block bg-white border border-slate-200 rounded-xl p-5 hover:border-brand-400 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-lg bg-{{ $color }}-100 text-{{ $color }}-600 inline-flex items-center justify-center text-2xl mb-3">
                    <x-icon :name="$icon" />
                </div>
                <h3 class="font-semibold text-slate-800 text-lg">{{ $title }}</h3>
                <p class="text-sm text-slate-600 mt-1">{{ $desc }}</p>
                <span class="text-brand-600 text-sm font-medium mt-2 inline-flex items-center gap-1">
                    Voir le guide <x-icon name="arrow-right" />
                </span>
            </a>
        @endforeach
    </div>

    <h2><x-icon name="lightbulb" weight="duotone" class="text-amber-500" /> Premiers pas</h2>

    <ol>
        <li><strong>Créer un compte</strong> sur la page d'inscription si vous êtes candidate. Pour les autres rôles, c'est l'administrateur qui crée votre compte.</li>
        <li><strong>Vérifier votre email</strong> en cliquant sur le lien reçu après inscription.</li>
        <li><strong>Vous connecter</strong> sur la page de connexion avec votre email et mot de passe.</li>
        <li><strong>Découvrir votre tableau de bord</strong> : il s'adapte automatiquement à votre rôle.</li>
        <li><strong>Consulter le guide</strong> spécifique à votre rôle ci-dessus pour aller plus loin.</li>
    </ol>

    <div class="callout info">
        <p class="!mb-0"><strong>Besoin d'aide rapide ?</strong> Consultez la <a href="{{ route('help.faq') }}" class="underline">FAQ</a>
        ou le <a href="{{ route('help.glossary') }}" class="underline">glossaire</a> pour comprendre la terminologie de la plateforme.</p>
    </div>

    <h2><x-icon name="info" weight="duotone" class="text-blue-500" /> Comprendre ProgramFlow</h2>

    <p>
        <strong>ProgramFlow</strong> est la plateforme de gestion des programmes de la
        {{ config('programflow.foundation_name') }}. Elle couvre le cycle de vie complet d'un programme :
    </p>

    <div class="grid md:grid-cols-4 gap-3 my-6 not-prose">
        @foreach([
            ['note-pencil', 'Création',     'Configuration du programme par l\'admin'],
            ['tray-arrow-down', 'Candidatures', 'Les candidates remplissent leur dossier en 3 étapes'],
            ['scales',      'Évaluation',   'Les jurys notent selon une grille pondérée'],
            ['trophy',      'Sélection',    'Classement automatique + décision finale'],
            ['graduation-cap', 'Programme actif', 'Sessions, présences, tâches'],
            ['newspaper',   'Rapports',     'Documentation des activités'],
            ['archive',     'Archivage',    'Historique consultable'],
            ['chart-bar',   'Reporting',    'Statistiques en temps réel'],
        ] as [$icon, $title, $desc])
            <div class="text-center p-4 border border-slate-200 rounded-lg bg-white">
                <x-icon :name="$icon" class="text-3xl text-brand-600" />
                <p class="font-semibold mt-2">{{ $title }}</p>
                <p class="text-xs text-slate-500">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
@endsection
