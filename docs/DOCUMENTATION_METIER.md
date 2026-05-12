# Documentation Métier — ProgramFlow

**Plateforme de gestion des programmes de la Fondation Bénianh**

*Document destiné aux non-techniciens (administration, direction, partenaires, équipes opérationnelles)*

Version 1.0 — © {{ Fondation Bénianh }}

---

## Table des matières

1. [Introduction](#1-introduction)
2. [Vision et objectifs](#2-vision-et-objectifs)
3. [Glossaire essentiel](#3-glossaire-essentiel)
4. [Les acteurs du système](#4-les-acteurs-du-système)
5. [Le cycle de vie d'un programme](#5-le-cycle-de-vie-dun-programme)
6. [Processus métier détaillés](#6-processus-métier-détaillés)
7. [Gestion documentaire et confidentialité](#7-gestion-documentaire-et-confidentialité)
8. [Notifications et communications](#8-notifications-et-communications)
9. [Cas d'usage concrets](#9-cas-dusage-concrets)
10. [Questions fréquentes](#10-questions-fréquentes)
11. [Annexes](#11-annexes)

---

## 1. Introduction

### 1.1 Présentation du projet

ProgramFlow est l'application web professionnelle développée pour la **Fondation Bénianh** afin de centraliser, gérer et automatiser l'ensemble des activités de ses programmes d'accompagnement, en commençant par le programme phare **Leadership Féminin**.

L'application remplace les outils fragmentés (tableurs Excel, formulaires Google, échanges email) par une plateforme unique qui couvre le cycle complet d'un programme : de sa création par l'administrateur jusqu'à son archivage, en passant par la gestion des candidatures, l'évaluation par jury, la sélection finale et le suivi des participantes.

### 1.2 Pourquoi cette plateforme ?

**Avant ProgramFlow**, gérer un programme impliquait :
- Des candidatures reçues par email avec pièces jointes dispersées
- Des notations manuelles dans des tableurs partagés
- Aucun suivi structuré des présences ou des sessions
- Une communication peu traçable avec les candidates
- Aucune visibilité agrégée pour la direction et les partenaires

**Avec ProgramFlow**, tout est centralisé, traçable et automatisé :
- Une candidature = un dossier numérique unique avec sa référence
- L'évaluation suit une grille pondérée objective
- Les emails partent automatiquement à chaque étape
- Le reporting est en temps réel
- L'historique est consultable pour toutes les anciennes éditions

### 1.3 Pour qui ?

| Public | Bénéfice principal |
|--------|---------------------|
| Direction de la fondation | Vue d'ensemble, reporting, gouvernance |
| Équipe opérationnelle | Outils pour orchestrer les programmes |
| Partenaires | Transparence sur les programmes soutenus |
| Candidates | Parcours fluide et professionnel |
| Membres du jury | Outil structuré et impartial |

---

## 2. Vision et objectifs

### 2.1 Vision long terme

ProgramFlow est conçue pour évoluer en **produit SaaS** utilisable par plusieurs fondations et organisations. Son architecture multi-programme et multi-rôle est pensée dès le départ pour ce passage à l'échelle.

### 2.2 Principes fondateurs

1. **Centralisation** : toutes les données d'un programme au même endroit
2. **Automatisation** : les tâches répétitives sont déclenchées par le système (emails, attributions, calculs)
3. **Traçabilité** : chaque action est horodatée, chaque transition est journalisée
4. **Confidentialité** : les données sensibles (santé, identité) sont strictement protégées
5. **Accessibilité** : utilisable par des non-techniciens, sans formation lourde

### 2.3 Objectifs mesurables

- Réduire de 80% le temps consacré aux tâches administratives répétitives
- Garantir 100% de traçabilité des décisions
- Permettre la consultation de toute édition antérieure en moins de 30 secondes
- Offrir aux candidates un parcours en moins de 30 minutes de remplissage

---

## 3. Glossaire essentiel

| Terme | Définition |
|-------|------------|
| **Candidate** | Personne qui postule à un programme |
| **Candidature** | Dossier rempli pour postuler. Possède un statut unique à tout moment |
| **Programme** | Initiative de la fondation (formation, mentorat, financement) avec un début et une fin |
| **Statut** | État actuel d'une candidature ou d'un programme (8 statuts possibles, voir §5) |
| **Jury** | Personne désignée pour évaluer les candidatures d'un programme |
| **Organisateur** | Responsable opérationnel d'un ou plusieurs programmes |
| **Administrateur** | Gestionnaire de la plateforme, configure utilisateurs et programmes |
| **Partenaire** | Organisation soutenant un ou plusieurs programmes |
| **Référence** | Identifiant unique d'une candidature (ex: PF-2026-A1B2C3) |
| **Critère d'évaluation** | Axe de notation avec un poids (ex: "Qualité du projet" — poids 3) |
| **Score pondéré** | Note finale calculée par la formule Σ(note × poids) / Σ(poids) |
| **Champ standard** | Question fixe identique pour tous les programmes |
| **Champ dynamique** | Question spécifique à un programme, configurée par l'admin |
| **Form Builder** | Interface permettant de configurer les champs dynamiques |
| **Présélection** | Filtre des meilleurs profils avant la décision finale |
| **Session** | Activité planifiée d'un programme actif (formation, atelier) |
| **Rapport d'activité** | Document narratif et médias documentant une activité |

---

## 4. Les acteurs du système

ProgramFlow distingue **cinq rôles**, chacun avec des droits et des écrans dédiés.

### 4.1 Administrateur

**Qui ?** Membre de la direction ou responsable IT de la fondation.

**Rôle :** Chef d'orchestre du système. Premier à intervenir.

**Droits :** Tous (« super-utilisateur »).

**Responsabilités principales :**
- Créer et désactiver les comptes utilisateurs
- Créer, configurer, modifier et archiver les programmes
- Gérer le référentiel des partenaires
- Consulter le reporting global de la fondation
- Personnaliser les formulaires de candidature via le Form Builder

### 4.2 Organisateur

**Qui ?** Coordinateur ou chargé de projet d'un programme.

**Rôle :** Opère le programme au quotidien.

**Droits :** Limité aux programmes auxquels il est associé.

**Responsabilités principales :**
- Suivre l'arrivée des candidatures
- Clôturer la phase de candidature et déclencher l'évaluation
- Prendre les décisions finales (acceptée / refusée / liste d'attente)
- Planifier les sessions (formations, ateliers) et marquer les présences
- Rédiger les rapports d'activité

### 4.3 Membre du jury

**Qui ?** Expert externe ou interne désigné pour évaluer.

**Rôle :** Note les candidatures selon une grille pondérée.

**Droits :** Voit uniquement les programmes auxquels il est associé et ne peut évaluer que les candidatures de ces programmes.

**Responsabilités principales :**
- Consulter les dossiers complets des candidates
- Noter chaque critère de la grille
- Soumettre l'évaluation (action définitive)

### 4.4 Candidate

**Qui ?** Femme intéressée par un programme de la fondation.

**Rôle :** Postule et suit son dossier.

**Droits :** Uniquement sur ses propres candidatures.

**Responsabilités principales :**
- Créer son compte et vérifier son email
- Remplir le formulaire de candidature en 3 étapes
- Soumettre sa candidature avant la date de clôture
- Suivre l'évolution du statut sur son dashboard
- Recevoir les emails à chaque transition de statut

### 4.5 Partenaire

**Qui ?** Représentant d'une organisation soutenant un programme.

**Rôle :** Observateur en lecture seule.

**Droits :** Consulter les programmes auxquels son organisation contribue.

**Limites :** Pas d'accès aux dossiers individuels (RGPD).

**Responsabilités principales :**
- Suivre les statistiques agrégées
- Demander un reporting détaillé à l'administrateur si besoin

---

## 5. Le cycle de vie d'un programme

Un programme passe par **8 statuts** qui structurent l'activité de tous les acteurs.

### 5.1 Tableau des statuts

| # | Statut | Signification | Acteur principal |
|---|--------|---------------|-------------------|
| 1 | **Brouillon** (draft) | Programme en cours de création, invisible au public | Administrateur |
| 2 | **Publié** (published) | Visible mais candidatures pas encore ouvertes | Administrateur |
| 3 | **Candidatures ouvertes** (open) | Les candidates peuvent postuler | Candidates |
| 4 | **Évaluation jury** (review) | Les jurys notent les candidatures | Jurys |
| 5 | **Sélection finale** (selection) | Classement et décisions individuelles | Organisateur |
| 6 | **En cours** (active) | Programme se déroule : sessions, présences | Organisateur |
| 7 | **Terminé** (completed) | Programme achevé, données figées | — |
| 8 | **Archivé** (archived) | Lecture seule, historique consultable | Administrateur |

### 5.2 Transitions standard

```
draft → published → open → review → selection → active → completed → archived
```

Chaque transition est déclenchée par un acteur précis et a des conséquences sur ce que les autres peuvent faire.

### 5.3 Transitions possibles selon le statut

- **De `open`** : retour à `published` (admin), ou passage à `review` (clôture des candidatures)
- **De `review`** : passage à `selection` quand l'organisateur lance la sélection
- **De `selection`** : passage à `active` après verrouillage des décisions
- **De `active`** : passage à `completed` à la fin du programme
- **Tout statut** : passage à `archived` à tout moment par l'admin

---

## 6. Processus métier détaillés

### 6.1 Processus n°1 : Création d'un programme

**Déclencheur :** Décision de la fondation de lancer une nouvelle édition.

**Acteur :** Administrateur.

**Étapes :**

1. L'admin se connecte et accède à *Programmes → Nouveau programme*
2. Il saisit :
   - Titre (ex: "Leadership Féminin 2026")
   - Description courte (1-2 phrases d'accroche)
   - Description complète (présentation détaillée)
   - Objectifs du programme
   - Conditions d'éligibilité (qui peut postuler)
   - Nombre de places (ex: 30)
   - Dates clés : ouverture/clôture candidatures, début/fin programme
   - Image de couverture
3. À l'enregistrement, **le système crée automatiquement** :
   - 50+ champs de formulaire standard répartis en 9 sections fixes (Identité, Coordonnées, Pièce d'identité, Parcours, Expérience, Santé, Parents, Urgence, Déclaration)
   - 4 critères d'évaluation par défaut avec leurs poids :
     - Pertinence du parcours (poids 2)
     - Qualité du projet (poids 3)
     - Impact attendu (poids 3)
     - Motivation et engagement (poids 2)
4. L'admin associe ensuite :
   - Les organisateurs (qui pourront gérer le programme au quotidien)
   - Les membres du jury (qui évalueront les candidatures)
   - Les partenaires (qui auront accès en lecture seule)
5. **Optionnel** : l'admin personnalise le formulaire via le *Form Builder* pour ajouter des champs spécifiques à ce programme (section 3 du formulaire).

**Résultat :** Programme prêt à être publié et à recevoir des candidatures.

**Durée typique :** 15-30 minutes pour la première création.

---

### 6.2 Processus n°2 : Période de candidatures

**Déclencheur :** Date d'ouverture des candidatures atteinte (statut programme passe à *Open*).

**Acteurs :** Candidates (acteur principal), Organisateur (suivi).

**Étapes côté candidate :**

1. Découverte du programme via la page publique d'accueil (ou communication externe).
2. Création de compte : la candidate saisit prénom, nom, email, mot de passe.
3. Vérification de l'email : clic sur le lien reçu (sécurité).
4. Connexion à son espace personnel.
5. Sur la fiche du programme, clic sur *Postuler* → création d'un brouillon avec une référence unique (`PF-2026-A1B2C3`).
6. Remplissage du formulaire en 3 étapes :
   - **Étape 1 — Informations personnelles** (6 sections : Identité, Coordonnées, Pièce, Parcours académique, Expérience, Santé)
   - **Étape 2 — Parents & contact d'urgence** (2 sections)
   - **Étape 3 — Spécifique au programme + déclaration finale** (obligatoire de cocher 4 cases d'engagement)
7. La candidate peut **enregistrer en brouillon** autant de fois qu'elle veut, et revenir plus tard.
8. Soumission finale : la candidature change de statut (Brouillon → Soumise) et un email de confirmation est envoyé.
9. **Modification après soumission :** tant que la date de clôture n'est pas dépassée, la candidate peut encore modifier son dossier déjà soumis (les changements sont enregistrés sans nouvelle soumission).

**Étapes côté organisateur :**

1. Consultation régulière de la liste des candidatures sur son dashboard.
2. Statistiques en temps réel : total, soumises, présélectionnées, acceptées.
3. Possibilité de consulter chaque dossier dans les moindres détails (toutes les sections, tous les fichiers).

**Règles métier strictes :**

- **Une seule candidature par programme et par personne.** Si la candidate clique deux fois sur "Postuler", elle est redirigée vers son dossier existant.
- **Aucune création possible** si le programme n'est pas en statut *open* ou si la date de clôture est dépassée.

---

### 6.3 Processus n°3 : Clôture et lancement de l'évaluation

**Déclencheur :**
- Soit la date de clôture est atteinte (clôture automatique)
- Soit l'organisateur clique sur *Démarrer l'évaluation* (clôture manuelle anticipée)

**Acteur principal :** Organisateur.

**Étapes :**

1. L'organisateur accède à la liste des candidatures du programme.
2. Un bandeau orange apparaît : *"Clôturer les candidatures et démarrer l'évaluation"*.
3. Le système vérifie :
   - Qu'au moins un jury est associé au programme (sinon, message bloquant).
   - Qu'au moins une candidature soumise existe.
4. L'organisateur clique sur *Démarrer l'évaluation*.
5. **Effets automatiques :**
   - Le statut du programme passe à *review*
   - Toutes les candidatures *soumises* deviennent *en évaluation* (under_review)
   - Pour chaque candidature × chaque jury, une **évaluation est automatiquement créée** en statut *À traiter* (assigned)
   - Aucun email n'est envoyé aux jurys (pour éviter le spam) — ils découvrent les candidatures lors de leur prochaine connexion

**Note importante :** Avant cette mise à jour métier, l'organisateur devait manuellement attribuer chaque candidature à chaque jury. C'était fastidieux (50 candidatures × 3 jurys = 150 clics). Désormais, **l'attribution est automatique** : chaque jury membre du programme évalue toutes les candidatures.

---

### 6.4 Processus n°4 : Évaluation par le jury

**Déclencheur :** Les candidatures apparaissent dans le dashboard du jury.

**Acteur :** Membre du jury.

**Étapes :**

1. Le jury se connecte à son espace.
2. Sur son dashboard, il voit :
   - Le compteur "À évaluer" (X candidatures attendent sa note)
   - L'entrée *Mes programmes* pour une vue par programme avec compteurs détaillés
3. Il clique sur *Évaluer* à côté d'une candidature.
4. L'écran d'évaluation s'ouvre en **2 colonnes** :
   - **Gauche** : le dossier complet de la candidate (toutes les sections, tous les fichiers téléchargeables)
   - **Droite** : la grille d'évaluation
5. Le jury lit attentivement le dossier (CV, projet, motivation, parcours).
6. Pour chaque critère, il saisit :
   - Une note entre 0 et la note maximale (typiquement 0-20)
   - Un commentaire optionnel pour justifier
7. Il rédige un commentaire global en bas.
8. Il clique sur *Soumettre l'évaluation* → confirmation → action irréversible.

**Calcul automatique :**
- Score brut = somme des notes
- **Score pondéré** = Σ(note × poids du critère) / Σ(poids)
- La candidature reçoit automatiquement la moyenne des scores pondérés de tous les jurys ayant soumis

**Exemple :**

| Critère | Poids | Note (jury 1) | Note × poids |
|---------|-------|---------------|---------------|
| Pertinence du parcours | 2 | 15 | 30 |
| Qualité du projet | 3 | 17 | 51 |
| Impact attendu | 3 | 16 | 48 |
| Motivation | 2 | 18 | 36 |

Score pondéré du jury 1 = (30 + 51 + 48 + 36) / (2 + 3 + 3 + 2) = 165 / 10 = **16,5 / 20**

Si trois jurys ont noté la candidature respectivement 16,5 — 15,8 — 17,2, la moyenne (16,5) est stockée sur la candidature pour le classement final.

---

### 6.5 Processus n°5 : Sélection finale

**Déclencheur :** Suffisamment d'évaluations sont soumises (typiquement toutes).

**Acteur :** Organisateur.

**Étapes :**

1. L'organisateur accède à la page *Sélection* du programme.
2. Le système affiche un **classement automatique pondéré décroissant** :
   - Position 1 : candidate avec le meilleur score pondéré
   - Position 2 : suivante, etc.
3. L'organisateur saisit le nombre de places à présélectionner (typiquement = nombre de places du programme).
4. Il clique sur *Pré-sélectionner top N* → les N premières candidatures passent automatiquement en statut *Présélectionnée*.
5. Pour chaque candidature présélectionnée, l'organisateur ouvre le dossier individuellement et prend la décision finale :
   - **Acceptée** : la candidate rejoint le programme
   - **Refusée** : décision motivée par un commentaire
   - **Liste d'attente** : recontact si désistement
6. Chaque décision déclenche un **email automatique** à la candidate avec le commentaire de l'organisateur.
7. Quand toutes les décisions sont prises, l'organisateur clique sur *Verrouiller la sélection* → le programme passe en statut *Actif*.

**Exports :**

- **Excel** : tableau complet des candidatures avec scores, à des fins de comité de sélection
- **PDF** : classement formaté avec le branding de la fondation, prêt à imprimer ou diffuser au conseil d'administration

---

### 6.6 Processus n°6 : Programme actif

**Déclencheur :** Statut du programme passe à *Active*.

**Acteur principal :** Organisateur.

**Étapes :**

1. Planification des sessions :
   - L'organisateur crée chaque session (formation, atelier, mentoring, événement)
   - Titre, type, date/heure, lieu (physique ou lien online), facilitateur désigné
2. Le jour J de chaque session :
   - L'organisateur ouvre la session
   - Marque les présences de chaque participante (présent / absent / excusé / retard) avec note optionnelle
   - Rédige le compte rendu de la session
3. Assignation de tâches :
   - L'organisateur peut créer des tâches (devoirs, livrables) et les assigner à des participantes
   - Statut : à faire / en cours / fait / annulée
   - Priorité : basse / moyenne / haute

**Reporting en continu :**
- Tableau de bord avec les statistiques du programme actif
- Possibilité d'exporter à tout moment

---

### 6.7 Processus n°7 : Rapports d'activité

**Déclencheur :** Une activité notable a eu lieu et doit être documentée pour la communication interne et externe.

**Acteurs :** Organisateur ou Administrateur.

**Étapes :**

1. Accès au module *Rapports d'activité* d'un programme.
2. Clic sur *Nouveau rapport*.
3. Saisie :
   - Titre (ex: "Atelier inaugural — Lancement du programme")
   - Description courte (1-2 phrases)
   - Date de l'activité
   - Session liée (optionnel)
   - Contenu détaillé (compte rendu narratif, faits marquants, témoignages)
4. Téléversement des médias :
   - Fichier principal (PDF formel, 64 Mo max)
   - Galerie d'images (JPG, PNG, multi-upload)
   - Vidéos (MP4, WebM, MOV)
5. Choix du statut :
   - *Brouillon* : visible uniquement par l'équipe interne
   - *Publié* : éventuellement consultable par les partenaires (sur évolution future)

**Cas d'usage :**
- Documenter chaque temps fort pour le rapport annuel
- Constituer une banque de témoignages et photos
- Partager visuellement avec les partenaires l'usage de leur financement

---

### 6.8 Processus n°8 : Archivage

**Déclencheur :** Le programme est terminé depuis suffisamment longtemps OU la direction décide de l'archiver.

**Acteur :** Administrateur.

**Étapes :**

1. Accès à la fiche du programme.
2. Clic sur *Archiver ce programme*.
3. Le statut passe à *Archivé*.

**Effets :**
- Le programme disparaît des filtres actifs
- Toutes les données restent consultables en lecture seule
- Les candidatures, évaluations, sessions, rapports demeurent accessibles
- Aucune modification n'est plus possible (sauf désarchivage par l'admin)

**Bonne pratique :** Archiver les programmes 6 à 12 mois après la fin pour conserver une trace exploitable sans encombrer l'interface.

---

## 7. Gestion documentaire et confidentialité

### 7.1 Types de documents stockés

| Type | Exemples | Disque de stockage |
|------|----------|---------------------|
| **Avatars** | Photos de profil | Public |
| **Logos partenaires** | Images d'organisations | Public |
| **Images de couverture programmes** | Bannières | Public |
| **CV des candidates** | PDF, DOCX | Privé (protégé par token) |
| **Pièces d'identité** | CNI, passeport scannés | Privé (protégé par token) |
| **Vidéos de motivation** | MP4, MOV | Privé (protégé par token) |
| **Galeries de rapports** | JPG, PNG | Public |

### 7.2 Sécurité des fichiers sensibles

Les documents sensibles (CV, pièces d'identité, données santé) sont stockés sur un **disque privé** non accessible directement par URL. Pour les consulter, le système génère une URL signée et vérifie à chaque téléchargement :

1. L'utilisateur est authentifié
2. Il a le rôle approprié (admin, organisateur du programme, jury assigné)
3. Il n'est pas en train de demander un document hors de son périmètre

### 7.3 Confidentialité des données santé

La section **Santé & sécurité** du formulaire de candidature contient des informations particulièrement sensibles :

- Maladies chroniques
- Allergies
- Traitements en cours
- Groupe sanguin
- Contact médical

Ces données :
- Sont marquées **« Confidentiel »** dans l'interface
- Apparaissent dans un encadré jaune distinctif
- Ne sont accessibles qu'à l'admin, l'organisateur et le jury
- Ne sont JAMAIS visibles par les partenaires
- Servent exclusivement à la sécurité de la participante en cas d'urgence pendant le programme

### 7.4 RGPD et droit à l'oubli

- Conservation des données pendant la durée du programme + 5 ans (sauf demande contraire)
- Droit à l'oubli : la candidate peut demander la suppression de son compte et ses données via son espace
- Anonymisation possible des programmes archivés sur demande

---

## 8. Notifications et communications

### 8.1 Emails automatiques

Le système envoie automatiquement des emails aux moments clés :

| Événement | Destinataire | Contenu |
|-----------|--------------|---------|
| Inscription | Candidate | Email de vérification |
| Soumission de candidature | Candidate | Confirmation avec référence |
| Décision (acceptée/refusée/liste d'attente) | Candidate | Notification du statut + commentaire |
| Réinitialisation de mot de passe | Tout utilisateur | Lien de réinitialisation (60 min) |
| Attribution manuelle (cas exceptionnel) | Membre du jury | Notification d'attribution |

### 8.2 Notifications internes

En complément des emails, des notifications sont stockées dans la base de données et seront affichées dans l'interface (cloche dans le bandeau supérieur — fonctionnalité prévue).

### 8.3 Configuration

Tous les emails sont envoyés via un service transactionnel professionnel (Mailtrap en dev, Postmark/Mailgun/SES en production). Les expéditeurs et signatures sont personnalisables par fondation.

---

## 9. Cas d'usage concrets

### 9.1 Scénario complet : Leadership Féminin 2026

Voici comment se déroule un programme du début à la fin sur ProgramFlow.

| Jour | Acteur | Action |
|------|--------|--------|
| J1 | Admin | Crée le programme en statut Brouillon |
| J1 | Admin | Configure dates, places, objectifs |
| J2 | Admin | Associe 3 organisateurs, 5 jurys, 4 partenaires |
| J2 | Admin | Personnalise via Form Builder (champ "Lettre de motivation") |
| J3 | Admin | Statut → Publié, puis → Candidatures ouvertes |
| J3-J30 | Candidates (~150) | Inscriptions, candidatures, soumissions |
| J30 | Système | Date de clôture atteinte, candidatures fermées automatiquement |
| J31 | Organisateur | Clique "Démarrer l'évaluation" |
| J31 | Système | Crée 750 évaluations (150 candidatures × 5 jurys) |
| J31-J45 | Jurys | Évaluent leurs candidatures attribuées |
| J46 | Organisateur | Accède à la page Sélection |
| J46 | Organisateur | Pré-sélectionne top 30 |
| J46-J48 | Organisateur | Prend décisions finales individuelles |
| J48 | Système | Envoie 150 emails (30 acceptées, 100 refusées, 20 liste d'attente) |
| J48 | Organisateur | Verrouille la sélection → Programme actif |
| J50-M6 | Organisateur | Planifie 12 sessions, marque présences, rédige rapports |
| M6 | Admin | Statut → Terminé |
| M12 | Admin | Archive le programme |

### 9.2 Cas particulier : un désistement

Une candidate acceptée se désiste après la sélection :

1. L'organisateur ouvre la candidature et change son statut en *Retirée*.
2. Sur la liste d'attente, il identifie la prochaine candidate à reclasser.
3. Il ouvre son dossier et change son statut de *Liste d'attente* → *Acceptée*.
4. Un email de bonne nouvelle est envoyé automatiquement.

### 9.3 Cas particulier : un jury supplémentaire ajouté en cours

Un jury de dernière minute est ajouté à un programme en cours d'évaluation :

1. L'admin l'associe au programme via la fiche.
2. Lors de sa prochaine connexion, le jury voit automatiquement toutes les candidatures soumises (auto-création des évaluations).
3. Il peut commencer à évaluer immédiatement.

---

## 10. Questions fréquentes

### 10.1 Pour la candidate

**Q : Puis-je modifier ma candidature après soumission ?**
R : Oui, tant que la période de candidature n'est pas fermée.

**Q : Mes informations santé sont-elles vues par tout le monde ?**
R : Non. Seuls l'admin, l'organisateur et le jury y ont accès, et uniquement pour des raisons de sécurité.

**Q : Combien de temps prend le remplissage du formulaire ?**
R : Environ 20-30 minutes, plus le temps de scanner vos documents.

### 10.2 Pour l'organisateur

**Q : Combien de jurys minimum pour démarrer l'évaluation ?**
R : Au moins 1, mais 2-3 est recommandé pour la robustesse de la notation.

**Q : Que se passe-t-il si un jury ne soumet pas son évaluation à temps ?**
R : Le système n'oblige pas la complétude. La moyenne se calcule sur les évaluations soumises. À vous de relancer le jury si nécessaire.

**Q : Puis-je annuler une décision d'acceptation par erreur ?**
R : Oui, ouvrez la candidature et changez la décision. Un nouvel email sera envoyé.

### 10.3 Pour l'administrateur

**Q : Comment migrer un ancien programme depuis Excel ?**
R : Pas d'import direct prévu (à développer si besoin). Vous pouvez créer manuellement le programme archivé et y associer les candidates.

**Q : Comment exporter toutes les données d'un programme pour une sauvegarde externe ?**
R : Les exports Excel et PDF couvrent les candidatures. Pour une sauvegarde complète, demandez un dump de la base à votre équipe technique.

---

## 11. Annexes

### 11.1 Annexe A — Droits par rôle (matrice)

| Action | Admin | Organisateur | Jury | Candidate | Partenaire |
|--------|-------|--------------|------|-----------|------------|
| Créer un utilisateur | ✓ | — | — | — | — |
| Créer un programme | ✓ | — | — | — | — |
| Modifier un programme | ✓ | Si associé | — | — | — |
| Archiver un programme | ✓ | — | — | — | — |
| Personnaliser le formulaire | ✓ | Si associé | — | — | — |
| Voir les candidatures soumises | ✓ | Si associé | Si associé (siennes) | Sa propre | — |
| Modifier une candidature | ✓ | — | — | Sa propre (avant clôture) | — |
| Évaluer une candidature | — | — | Si assignée | — | — |
| Prendre une décision finale | ✓ | Si associé | — | — | — |
| Planifier une session | ✓ | Si associé | — | — | — |
| Rédiger un rapport d'activité | ✓ | Si associé | — | — | — |
| Voir le reporting global | ✓ | — | — | — | — |
| Voir un rapport partenaire | ✓ | Si associé | — | — | ✓ (lecture) |

### 11.2 Annexe B — Champs du formulaire standard

Le formulaire est structuré en 9 sections fixes générées automatiquement pour chaque programme :

**Section 1.1 — Identité (7 champs)**
Nom, Prénoms, Date de naissance, Lieu de naissance, Nationalité, Sexe, Situation matrimoniale.

**Section 1.2 — Coordonnées (6 champs)**
Adresse complète, Ville, Commune/Quartier, Téléphone principal, Téléphone secondaire, Email personnel.

**Section 1.3 — Pièce d'identité (4 champs)**
Type, Numéro, Date d'expiration, Scan.

**Section 1.4 — Parcours académique (8 champs)**
Dernier diplôme, Série du Bac, Année du Bac, Établissement du Bac, Niveau actuel, Université, Domaine, Autres formations.

**Section 1.5 — Expérience & engagement (4 champs)**
Expériences pro, Associations, Compétences particulières, CV.

**Section 1.6 — Santé & sécurité (11 champs, confidentiels)**
Maladie chronique, Précision, Allergies, Précision, Traitement médical, Précision, Contre-indications, Restrictions alimentaires, Situations particulières, Groupe sanguin, Contact médical.

**Section 2.1 — Parent / tuteur principal (6 champs)**
Nom et prénom, Lien, Profession, Téléphone, Email, Adresse.

**Section 2.2 — Contact d'urgence (5 champs)**
Nom et prénom, Lien, Téléphone principal, Téléphone secondaire, Adresse.

**Section 3 — Spécifique au programme (configurable)**
Au minimum : Lettre de motivation. Plus tous les champs ajoutés par l'admin via le Form Builder.

**Section finale — Déclaration & engagement (4 cases obligatoires)**
- Je certifie l'exactitude des informations
- Je m'engage à respecter le règlement
- J'autorise l'usage administratif de mes données
- J'autorise les mesures d'urgence médicale

### 11.3 Annexe C — Critères d'évaluation par défaut

| Critère | Poids | Note max | Description suggérée |
|---------|-------|----------|----------------------|
| Pertinence du parcours | 2 | 20 | Cohérence entre formation/expérience et le programme |
| Qualité du projet | 3 | 20 | Clarté, réalisme, ambition du projet |
| Impact attendu | 3 | 20 | Bénéfice social/économique du projet |
| Motivation et engagement | 2 | 20 | Solidité de la lettre de motivation, parcours associatif |

Ces critères peuvent être modifiés / complétés par l'administrateur via la fiche du programme.

### 11.4 Annexe D — Glossaire des statuts (rappel)

**Candidatures :**
- Brouillon (draft)
- Soumise (submitted)
- En évaluation (under_review)
- Présélectionnée (shortlisted)
- Acceptée (accepted)
- Refusée (rejected)
- Liste d'attente (waitlisted)
- Retirée (withdrawn)

**Programmes :**
- Brouillon (draft)
- Publié (published)
- Candidatures ouvertes (open)
- Évaluation jury (review)
- Sélection finale (selection)
- En cours (active)
- Terminé (completed)
- Archivé (archived)

**Évaluations :**
- À traiter (assigned)
- En cours (in_progress)
- Soumise (submitted)

---

*Fin du document.*

*Pour toute question, contactez l'administrateur de votre fondation.*
*Version 1.0 — Document interne {{ Fondation Bénianh }}.*
