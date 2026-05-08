# Guide utilisateur

## Rôles et accès

| Rôle | Accès |
|------|-------|
| **Administrateur** | Tout (dashboard `/admin`, utilisateurs, programmes, partenaires, reporting) |
| **Organisateur** | Programmes assignés, gestion des candidatures et sélection, sessions |
| **Jury** | Évaluations qui lui sont assignées |
| **Candidate** | Postuler, suivre ses candidatures, modifier ses brouillons |
| **Partenaire** | Consultation des programmes auxquels il est associé |

## Parcours candidate

1. **Inscription** sur `/register` → un compte avec rôle "candidate" est créé automatiquement.
2. **Découverte des programmes** sur la page d'accueil ou dans son tableau de bord.
3. **Postuler** : clic sur "Postuler" → un brouillon est créé.
4. **Remplir le formulaire dynamique** : champs configurés par l'organisateur (texte, choix, fichier, vidéo).
5. **Sauvegarder** régulièrement (bouton "💾 Enregistrer le brouillon").
6. **Soumettre** définitivement quand prêt : un email de confirmation est envoyé avec la référence (ex. `PF-2026-A1B2C3`).
7. **Suivre le statut** : Soumise → En évaluation → Présélectionnée / Acceptée / Refusée / Liste d'attente.
8. **Recevoir la décision** par email + dans son tableau de bord.

## Parcours organisateur

1. **Tableau de bord** `/organizer` : liste des programmes assignés.
2. **Voir les candidatures** d'un programme : recherche, filtres par statut, statistiques.
3. **Ouvrir un dossier candidate** : voir toutes les réponses, documents, évaluations.
4. **Attribuer des jurys** à une candidature.
5. **Sélection finale** : voir le classement automatique pondéré, présélectionner les N meilleurs, valider manuellement.
6. **Exporter** : Excel ou PDF des résultats.
7. **Verrouiller** la sélection → le programme passe en phase "active".
8. **Gérer les sessions** : planifier formations/ateliers, marquer les présences, rédiger les comptes rendus, assigner des tâches.

## Parcours jury

1. **Tableau de bord** `/jury` : liste des candidatures à évaluer.
2. **Ouvrir un dossier** : consulter toutes les réponses et documents.
3. **Remplir la grille d'évaluation** : note + commentaire par critère, score pondéré calculé automatiquement.
4. **Soumettre** : action définitive ; le score moyen de la candidature est recalculé automatiquement.

## Parcours partenaire

1. **Tableau de bord** `/partner` : informations du partenaire et programmes associés.
2. **Consultation en lecture seule** des programmes (pas d'accès aux dossiers candidates).

## Parcours administrateur

1. **Tableau de bord** `/admin` : statistiques globales, raccourcis.
2. **Utilisateurs** : créer/modifier/supprimer, assigner les rôles.
3. **Programmes** : CRUD complet, archivage en un clic.
4. **Partenaires** : CRUD complet.
5. **Reporting** : vue d'ensemble + rapport détaillé par programme avec graphique.

## Notifications

- **Email + interne** : confirmation de candidature, décision, attribution d'évaluation.
- Configurable dans `app/Notifications/`.

## Bonnes pratiques

- Configurer **les critères d'évaluation et les champs du formulaire AVANT** d'ouvrir les candidatures.
- Toujours **archiver** un programme terminé pour conserver une trace consultable.
- **Ne pas supprimer** un utilisateur ayant des évaluations soumises (préférer le désactiver via `is_active = false`).
