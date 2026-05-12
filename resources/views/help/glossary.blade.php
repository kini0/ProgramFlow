@extends('layouts.help')
@section('content')
    <h1><x-icon name="book-open" weight="duotone" class="text-amber-500" /> Glossaire</h1>

    <p>Définitions des termes utilisés sur la plateforme ProgramFlow.</p>

    <div class="space-y-4 mt-6">
        @php
            $terms = [
                ['Brouillon', 'Statut d\'une candidature pas encore soumise. Visible uniquement par la candidate.'],
                ['Candidate', 'Personne (souvent au féminin pour le cas Leadership Féminin) qui postule à un programme.'],
                ['Candidature', 'Dossier rempli par une candidate pour postuler à un programme. Possède un statut unique à tout moment.'],
                ['Champ dynamique', 'Question ajoutée par l\'organisateur dans la section « Spécifique au programme » du formulaire. Configurable via le Form Builder.'],
                ['Champ standard', 'Question fixe et identique pour tous les programmes (Identité, Coordonnées, Pièce, Parcours, Expérience, Santé, Parents, Urgence, Déclaration).'],
                ['Clôture', 'Date à partir de laquelle les candidatures ne sont plus acceptées. Déclenche automatiquement la phase d\'évaluation.'],
                ['Critère d\'évaluation', 'Axe sur lequel un jury note une candidature (ex: « Qualité du projet »). Possède un poids et une note maximale.'],
                ['Décision', 'Action finale de l\'organisateur sur une candidature : Acceptée, Refusée, Présélectionnée, Liste d\'attente.'],
                ['Évaluation', 'Notation d\'une candidature par un membre du jury. Donne lieu à un score pondéré.'],
                ['Form Builder', 'Interface admin qui permet d\'ajouter/modifier/supprimer les champs dynamiques d\'un programme.'],
                ['Jury', 'Personne désignée pour évaluer les candidatures d\'un programme. Membre du collège de jurys.'],
                ['Liste d\'attente', 'Statut d\'une candidature qui n\'est pas retenue mais qui pourrait l\'être si une place se libère.'],
                ['Organisateur', 'Utilisateur opérationnel responsable d\'un ou plusieurs programmes (configuration, suivi, sessions, rapports).'],
                ['Participante', 'Candidate dont la candidature a été acceptée. Devient membre du programme actif.'],
                ['Partenaire', 'Organisation (entreprise, ONG, institution) qui soutient un ou plusieurs programmes.'],
                ['Phase d\'évaluation', 'Période pendant laquelle les jurys évaluent les candidatures (statut programme : review).'],
                ['Présélection', 'Filtre initial des meilleurs profils avant la décision finale (statut shortlisted).'],
                ['Programme', 'Initiative de la fondation accueillant des candidates (formation, mentorat, financement…). Possède un cycle de vie complet.'],
                ['Rapport d\'activité', 'Document narratif + médias (photos, vidéos, PDF) documentant une activité d\'un programme.'],
                ['Référence', 'Identifiant unique d\'une candidature au format PF-AAAA-XXXXXX. Sert de référent dans tous les emails et exports.'],
                ['Score pondéré', 'Note finale d\'une évaluation, calculée par Σ(note × poids du critère) / Σ(poids).'],
                ['Section', 'Regroupement logique de champs dans le formulaire de candidature (ex: « 1.4 Parcours académique »).'],
                ['Session', 'Activité planifiée d\'un programme actif : formation, atelier, mentoring, événement.'],
                ['Snapshot', 'Copie figée des réponses au moment de la soumission. Garantit qu\'une candidature ne change pas même si le profil utilisateur évolue.'],
                ['Statut', 'État actuel d\'une candidature ou d\'un programme. Voir le tableau des statuts dans le guide de chaque rôle.'],
            ];
        @endphp

        @foreach($terms as [$term, $def])
            <div class="border-l-4 border-brand-200 pl-4 py-1">
                <p class="font-semibold text-slate-800">{{ $term }}</p>
                <p class="text-slate-600 text-sm">{{ $def }}</p>
            </div>
        @endforeach
    </div>
@endsection
