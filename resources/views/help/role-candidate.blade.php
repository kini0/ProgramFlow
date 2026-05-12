@extends('layouts.help')
@section('content')
    <a href="{{ route('help.index') }}" class="text-sm text-slate-500 hover:text-brand-700 inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Centre d'aide
    </a>

    <h1 class="mt-2"><x-icon name="student" weight="duotone" class="text-emerald-500" /> Guide de la candidate</h1>

    <p>
        En tant que candidate, vous postulez à un programme de la fondation. Ce guide vous accompagne
        depuis la création de votre compte jusqu'à la réception de la décision finale.
    </p>

    <h2><x-icon name="map-trifold" /> Votre parcours en 7 étapes</h2>

    <ol>
        <li>Créer votre compte sur la plateforme.</li>
        <li>Vérifier votre email.</li>
        <li>Découvrir les programmes ouverts.</li>
        <li>Postuler à un programme (création d'un brouillon).</li>
        <li>Remplir le formulaire en 3 étapes.</li>
        <li>Soumettre votre candidature.</li>
        <li>Suivre le statut et recevoir la décision finale.</li>
    </ol>

    <h2 id="register"><x-icon name="user-plus" /> 1. Créer votre compte</h2>

    <ol>
        <li>Sur la page d'accueil, cliquez sur <strong>« Postuler »</strong> ou <strong>« S'inscrire »</strong>.</li>
        <li>Remplissez prénom, nom, email valide, mot de passe sécurisé.</li>
        <li>Validez. Vous êtes automatiquement connectée.</li>
    </ol>

    <h2 id="verify"><x-icon name="envelope-open" /> 2. Vérifier votre email</h2>

    <p>
        Vous recevez un email <strong>« Vérifiez votre adresse email »</strong> dans la minute qui suit.
        Cliquez sur le bouton dans l'email.
    </p>

    <div class="callout info">
        <p class="!mb-0">
            <strong>Vous n'avez pas reçu l'email ?</strong> Vérifiez vos spams. Vous pouvez aussi cliquer sur
            <strong>« Renvoyer le lien »</strong> depuis la page d'attente de vérification.
        </p>
    </div>

    <h2 id="discover"><x-icon name="compass" /> 3. Découvrir les programmes ouverts</h2>

    <p>Sur votre tableau de bord (<strong>« Mon espace »</strong>), vous voyez en bas la section <strong>« Programmes ouverts »</strong>.</p>
    <p>Cliquez sur un programme pour lire ses objectifs, conditions d'éligibilité, dates clés.</p>

    <h2 id="apply"><x-icon name="paper-plane-tilt" /> 4. Postuler</h2>

    <ol>
        <li>Sur la fiche d'un programme, cliquez sur <strong>« Postuler »</strong>.</li>
        <li>Un brouillon est créé. Vous arrivez sur le formulaire en 3 étapes.</li>
    </ol>

    <div class="callout success">
        <p class="!mb-0">
            <strong>Un seul dossier par programme :</strong> si vous cliquez à nouveau sur « Postuler »
            pour le même programme, le système vous redirige vers votre dossier existant — pas de doublon.
        </p>
    </div>

    <h2 id="fill"><x-icon name="note-pencil" /> 5. Remplir le formulaire en 3 étapes</h2>

    <h3>Étape 1 — Informations personnelles</h3>

    <p>6 sous-sections obligatoires :</p>
    <ul>
        <li><strong>1.1 Identité</strong> : nom, prénoms, date/lieu de naissance, nationalité, sexe, situation matrimoniale.</li>
        <li><strong>1.2 Coordonnées</strong> : adresse complète, ville, téléphones, email personnel.</li>
        <li><strong>1.3 Pièce d'identité</strong> : CNI ou Passeport, numéro, scan.</li>
        <li><strong>1.4 Parcours académique</strong> : dernier diplôme, Bac, université, domaine.</li>
        <li><strong>1.5 Expérience & engagement</strong> : expériences pro, associations, compétences, CV.</li>
        <li><strong>1.6 Santé & sécurité</strong> (confidentiel) : maladie chronique, allergies, traitements.</li>
    </ul>

    <div class="callout warning">
        <p class="!mb-0">
            <strong>Confidentialité des données santé :</strong> ces informations sont strictement confidentielles
            et ne servent qu'à la sécurité de la participante en cas d'urgence pendant le programme.
        </p>
    </div>

    <h3>Étape 2 — Parents & contact d'urgence</h3>
    <ul>
        <li><strong>2.1 Parent / tuteur principal</strong> : nom, lien, profession, téléphone, email.</li>
        <li><strong>2.2 Contact d'urgence</strong> : personne à joindre en cas de problème.</li>
    </ul>

    <h3>Étape 3 — Spécifique au programme + Déclaration</h3>
    <p>Cette section varie selon le programme. Elle peut contenir : lettre de motivation, lettres de recommandation, objectifs personnels…</p>
    <p>À la fin, vous devez cocher la <strong>déclaration finale</strong> : exactitude des informations, engagement à respecter le règlement, autorisation d'usage des données, autorisation médicale en cas d'urgence.</p>

    <h2 id="save"><x-icon name="floppy-disk" /> 6. Enregistrer et soumettre</h2>

    <p>
        À chaque étape, vous pouvez naviguer entre les 3 étapes via le stepper en haut.
        En bas de l'étape 3, deux boutons s'offrent à vous :
    </p>

    <ul>
        <li><strong>« Enregistrer le brouillon »</strong> : vos réponses sont sauvegardées, vous pourrez revenir plus tard.</li>
        <li><strong>« Soumettre ma candidature »</strong> : envoi définitif. Vous recevez un email de confirmation avec votre <strong>numéro de référence</strong> (ex : <code>PF-2026-A1B2C3</code>).</li>
    </ul>

    <div class="callout info">
        <p class="!mb-0">
            <strong>Modification après soumission :</strong> tant que les candidatures sont ouvertes (date de clôture non dépassée),
            vous pouvez encore <strong>modifier votre dossier soumis</strong>. Vos changements seront enregistrés
            sans nouvelle soumission.
        </p>
    </div>

    <h2 id="track"><x-icon name="binoculars" /> 7. Suivre votre candidature</h2>

    <p>Sur votre tableau de bord, votre candidature évolue à travers plusieurs <strong>statuts</strong> :</p>

    <div class="not-prose my-4 overflow-x-auto">
        <table class="w-full text-sm bg-white border border-slate-200 rounded-lg">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 text-left">Statut</th>
                    <th class="px-3 py-2 text-left">Signification</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr><td class="px-3 py-2"><span class="badge bg-slate-100 text-slate-700">Brouillon</span></td><td class="px-3 py-2">Dossier en cours de remplissage, non visible par la fondation.</td></tr>
                <tr><td class="px-3 py-2"><span class="badge bg-blue-100 text-blue-700">Soumise</span></td><td class="px-3 py-2">Dossier officiellement déposé, en attente d'évaluation.</td></tr>
                <tr><td class="px-3 py-2"><span class="badge bg-amber-100 text-amber-700">En évaluation</span></td><td class="px-3 py-2">Le jury examine votre dossier.</td></tr>
                <tr><td class="px-3 py-2"><span class="badge bg-purple-100 text-purple-700">Présélectionnée</span></td><td class="px-3 py-2">Bonne nouvelle : vous êtes dans les meilleurs profils. Décision finale à venir.</td></tr>
                <tr><td class="px-3 py-2"><span class="badge bg-emerald-100 text-emerald-700">Acceptée</span></td><td class="px-3 py-2">Félicitations ! Vous intégrez le programme.</td></tr>
                <tr><td class="px-3 py-2"><span class="badge bg-red-100 text-red-700">Refusée</span></td><td class="px-3 py-2">Votre candidature n'a pas été retenue cette fois-ci.</td></tr>
                <tr><td class="px-3 py-2"><span class="badge bg-orange-100 text-orange-700">Liste d'attente</span></td><td class="px-3 py-2">Vous pourriez être recontactée en cas de désistement.</td></tr>
            </tbody>
        </table>
    </div>

    <p>À chaque changement de statut, vous recevez un <strong>email automatique</strong> avec, si applicable, un commentaire de l'organisateur.</p>

    <h2><x-icon name="lightbulb" /> Conseils pour réussir votre candidature</h2>

    <ul>
        <li><strong>Soyez précise et concrète</strong> dans votre projet : objectifs mesurables, impact attendu.</li>
        <li><strong>Soignez la lettre de motivation</strong> — c'est souvent le critère qui fait la différence.</li>
        <li><strong>Téléversez un CV à jour</strong>, structuré et lisible.</li>
        <li><strong>Vérifiez 2x</strong> les informations avant de soumettre.</li>
        <li><strong>Postulez tôt</strong> pour ne pas être bloquée par un problème technique de dernière minute.</li>
    </ul>
@endsection
