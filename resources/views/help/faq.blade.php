@extends('layouts.help')
@section('content')
    <h1><x-icon name="question" weight="duotone" class="text-blue-500" /> Questions fréquentes</h1>

    <p>Vous ne trouvez pas la réponse à votre question ? Contactez l'administrateur de votre fondation.</p>

    <h2>Compte et connexion</h2>

    <details class="card card-body my-3" open>
        <summary class="font-semibold cursor-pointer">Je ne reçois pas l'email de vérification.</summary>
        <p class="mt-3">Vérifiez d'abord vos spams. Si rien : sur la page « Vérifiez votre email » après inscription, cliquez sur <strong>« Renvoyer le lien »</strong>. Si le problème persiste, contactez l'administrateur.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">J'ai oublié mon mot de passe.</summary>
        <p class="mt-3">Cliquez sur <strong>« Mot de passe oublié ? »</strong> sur la page de connexion. Saisissez votre email, vous recevrez un lien de réinitialisation valable 60 minutes.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Pourquoi suis-je bloqué après plusieurs tentatives de connexion ?</summary>
        <p class="mt-3">Après 5 tentatives échouées, votre adresse IP est temporairement bloquée pour des raisons de sécurité (anti-bruteforce). Attendez quelques minutes ou utilisez la fonction « Mot de passe oublié ».</p>
    </details>

    <h2>Candidatures</h2>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Puis-je modifier ma candidature après l'avoir soumise ?</summary>
        <p class="mt-3"><strong>Oui</strong>, tant que la période de candidature n'est pas fermée. Allez dans <em>Mes candidatures</em>, cliquez sur <strong>« Modifier »</strong>. Vos modifications sont enregistrées sans nouvelle soumission.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Puis-je postuler deux fois au même programme ?</summary>
        <p class="mt-3">Non. Le système n'autorise qu'<strong>une seule candidature par programme et par candidate</strong>. Si vous cliquez à nouveau sur « Postuler », vous êtes redirigée vers votre candidature existante.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Quelle est la taille maximale d'un fichier que je peux téléverser ?</summary>
        <p class="mt-3"><strong>64 Mo par fichier maximum</strong>. Pour le CV, restez si possible sous 10 Mo. Pour une vidéo de motivation, hébergez-la plutôt sur YouTube et collez le lien.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Quels formats de fichiers sont acceptés ?</summary>
        <p class="mt-3">CV et pièce d'identité : PDF, DOC, DOCX, JPG, PNG. Vidéos : MP4, WebM, MOV.</p>
    </details>

    <h2>Évaluation</h2>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Je suis jury — comment savoir quand commencer à évaluer ?</summary>
        <p class="mt-3">Les candidatures vous sont automatiquement attribuées <strong>dès que la période de candidature est fermée</strong>. Connectez-vous régulièrement à votre dashboard pour les voir apparaître. L'organisateur peut aussi déclencher la phase d'évaluation manuellement, auquel cas elles apparaîtront immédiatement.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Je veux modifier une note que j'ai soumise.</summary>
        <p class="mt-3">La soumission d'une évaluation est <strong>définitive</strong>. Pour modifier, contactez l'organisateur du programme qui pourra (en cas d'erreur avérée) demander une intervention admin.</p>
    </details>

    <h2>Sélection</h2>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Comment fonctionne le classement automatique ?</summary>
        <p class="mt-3">Les candidatures sont triées par <strong>score moyen pondéré décroissant</strong>. Le score pondéré d'un jury est calculé selon la formule : <code>Σ(note × poids du critère) / Σ(poids)</code>. La moyenne des scores pondérés de tous les jurys détermine le classement.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Peut-on accepter une candidate en liste d'attente ?</summary>
        <p class="mt-3">Oui. Sur la fiche de la candidature, l'organisateur peut changer la décision de <em>Liste d'attente</em> vers <em>Acceptée</em>. Un email automatique informe la candidate.</p>
    </details>

    <h2>Données et confidentialité</h2>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Mes données santé sont-elles accessibles par tous ?</summary>
        <p class="mt-3">Non. Les informations de santé sont marquées <strong>« Confidentiel »</strong> et ne sont consultées que par les organisateurs et jurys du programme. Les partenaires n'y ont jamais accès. Elles servent uniquement à la sécurité en cas d'urgence pendant le programme.</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Combien de temps mes données sont-elles conservées ?</summary>
        <p class="mt-3">Tant que le programme n'est pas archivé. Les programmes archivés restent consultables mais peuvent être purgés sur demande de l'administrateur, conformément à votre droit à l'oubli (RGPD).</p>
    </details>

    <details class="card card-body my-3">
        <summary class="font-semibold cursor-pointer">Comment supprimer mon compte ?</summary>
        <p class="mt-3">Depuis votre profil (clic sur votre avatar en haut à droite → Mon profil), une option de suppression est disponible. Attention : la suppression est définitive et entraîne la perte de toutes vos candidatures.</p>
    </details>
@endsection
