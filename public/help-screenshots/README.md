# Captures d'écran de l'aide

Déposez ici les captures d'écran utilisées par le centre d'aide (`/aide`).

## Nommage attendu

Pour que les mockups HTML soient automatiquement remplacés par vos vraies captures,
nommez les fichiers selon la convention suivante :

| Page de l'aide | Fichier attendu | Description |
|----------------|-----------------|-------------|
| Admin — Utilisateurs | `admin-users.png` | Liste des utilisateurs (`/admin/users`) |
| Admin — Programme show | `admin-program-show.png` | Fiche d'un programme avec blocs partenaires/membres |
| Admin — Form Builder | `admin-form-builder.png` | `/admin/programs/{slug}/fields` |
| Organisateur — Dashboard | `organizer-dashboard.png` | Cartes des programmes (`/organizer`) |
| Organisateur — Candidature | `organizer-application-show.png` | Récap complet + grille jury |
| Organisateur — Sélection | `organizer-selection.png` | Classement automatique |
| Jury — Programmes | `jury-programs.png` | `/jury/programs` |
| Jury — Évaluation | `jury-evaluation.png` | Écran 2 colonnes dossier + grille |
| Candidate — Dashboard | `candidate-dashboard.png` | Mon espace |
| Candidate — Formulaire | `candidate-application-edit.png` | Formulaire en 3 étapes |
| Candidate — Show | `candidate-application-show.png` | Récap d'une candidature |

## Référencement dans les vues

Dans les vues d'aide (`resources/views/help/role-*.blade.php`), les captures sont
référencées comme ceci :

```blade
<x-help.screen-mock title="Liste des utilisateurs" url="/admin/users" image="admin-users.png">
    {{-- Mockup HTML de secours, affiché si le fichier image n'existe pas --}}
    ...
</x-help.screen-mock>
```

Tant que le fichier n'est pas présent, le mockup HTML est affiché avec une mention
"Capture attendue dans public/help-screenshots/". Dès que vous déposez le fichier,
il remplace automatiquement le mockup au prochain chargement de la page.

## Format recommandé

- **PNG** ou **JPG**
- **Largeur** : 1200-1600 px (le composant fait `width: 100%`)
- **Qualité** : compressez avant dépôt (TinyPNG, Squoosh) pour rester sous 200 Ko/fichier
- **Cadrage** : capturez la zone utile seule, sans la barre d'adresse du navigateur (le composant ajoute déjà une fausse topbar)

## Outils de capture recommandés

- **Windows** : Outil Capture (Win+Maj+S) ou ShareX (gratuit, plus complet)
- **macOS** : Cmd+Maj+4 puis sélection, ou CleanShot X
- **Linux** : Flameshot ou Spectacle
