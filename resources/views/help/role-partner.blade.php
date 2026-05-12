@extends('layouts.help')
@section('content')
    <a href="{{ route('help.index') }}" class="text-sm text-slate-500 hover:text-brand-700 inline-flex items-center gap-1">
        <x-icon name="arrow-left" /> Centre d'aide
    </a>

    <h1 class="mt-2"><x-icon name="handshake" weight="duotone" class="text-teal-500" /> Guide du partenaire</h1>

    <p>
        En tant que partenaire de la fondation, vous accédez en lecture seule aux programmes auxquels votre
        organisation contribue (financièrement, techniquement, médiatiquement, etc.).
    </p>

    <h2><x-icon name="key" /> Accès à votre espace</h2>

    <ol>
        <li>L'administrateur a créé un compte pour votre organisation et l'a lié à votre fiche partenaire.</li>
        <li>Vous recevez vos identifiants par email.</li>
        <li>Connectez-vous via la page de connexion.</li>
        <li>Vous arrivez sur votre <strong>espace partenaire</strong>.</li>
    </ol>

    <h2 id="dashboard"><x-icon name="squares-four" /> Votre tableau de bord</h2>

    <p>Votre espace affiche :</p>
    <ul>
        <li><strong>La fiche de votre organisation</strong> : nom, type de partenariat, description, site web.</li>
        <li><strong>La liste des programmes</strong> auxquels vous êtes associés, avec leur statut et le nombre de candidatures.</li>
    </ul>

    <h2><x-icon name="eye" /> Ce que vous pouvez consulter</h2>

    <ul>
        <li>Le <strong>statut actuel</strong> de chaque programme (en cours, terminé, etc.).</li>
        <li>Le <strong>nombre de candidatures</strong> reçues, agrégées (pas de noms individuels).</li>
        <li>Les <strong>statistiques globales</strong> du programme.</li>
    </ul>

    <h2><x-icon name="lock" /> Ce que vous ne pouvez pas consulter</h2>

    <p>
        Pour des raisons de <strong>confidentialité (RGPD et respect des candidates)</strong>, vous n'avez pas accès :
    </p>
    <ul>
        <li>Aux dossiers individuels des candidates.</li>
        <li>Aux informations personnelles (nom, contact, santé).</li>
        <li>Aux notes des jurys et commentaires internes.</li>
        <li>Aux décisions de sélection nominatives.</li>
    </ul>

    <div class="callout info">
        <p class="!mb-0">
            <strong>Pourquoi ces restrictions ?</strong> Les candidates partagent des informations sensibles
            (parcours, santé, situation familiale) en confiance. Ces données ne peuvent être consultées que
            par les acteurs strictement nécessaires au processus de sélection.
        </p>
    </div>

    <h2><x-icon name="chart-line-up" /> Demander un reporting détaillé</h2>

    <p>
        Si vous avez besoin d'un rapport détaillé pour vos comptes-rendus à votre comité ou conseil
        d'administration, contactez directement l'administrateur de la fondation. Il pourra exporter
        des données agrégées et anonymisées adaptées à votre besoin.
    </p>

    <h2><x-icon name="question" /> Questions fréquentes</h2>

    <h3>Comment associer notre logo à un programme ?</h3>
    <p>L'administrateur peut téléverser votre logo sur votre fiche partenaire. Il apparaîtra sur les rapports d'activité publics.</p>

    <h3>Notre organisation n'est plus partenaire d'un programme. Comment le retirer ?</h3>
    <p>Contactez l'administrateur de la fondation. Lui seul peut modifier les associations partenaire ↔ programme.</p>

    <h3>Plusieurs personnes de notre organisation veulent accéder à l'espace partenaire.</h3>
    <p>Actuellement, un seul compte utilisateur est associé par partenaire. Pour partager l'accès, vous pouvez créer un email de groupe (ex : <code>contact@votre-org.com</code>) et partager les identifiants en interne.</p>
@endsection
