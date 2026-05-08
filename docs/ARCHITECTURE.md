# Architecture

## Vue d'ensemble

ProgramFlow suit une architecture MVC stricte, étendue par les patterns **Repository**, **Service Layer**, **Policy** et **Observer**.

```
┌────────────────────────────────────────────────────────────┐
│                    HTTP (Routes Web)                        │
└───────────────┬────────────────────────────────────────────┘
                │
┌───────────────▼────────────────┐
│      Controllers (par rôle)     │  ← FormRequest pour la validation
│   Admin/Organizer/Jury/...      │  ← Policy pour l'autorisation
└───────────────┬────────────────┘
                │
┌───────────────▼────────────────┐
│      Services (logique métier)  │  ← Transactions, événements, notifications
│  ProgramService, ApplicationSvc │
└───────────────┬────────────────┘
                │
┌───────────────▼────────────────┐
│     Repositories (Interface)    │  ← Abstrait l'accès aux données
│   ProgramRepositoryInterface    │
└───────────────┬────────────────┘
                │
┌───────────────▼────────────────┐
│   Eloquent Repositories          │
│  ProgramRepository (Eloquent)    │
└───────────────┬────────────────┘
                │
┌───────────────▼────────────────┐
│         Models Eloquent          │  ← Observers, Casts, Scopes
└───────────────┬────────────────┘
                │
┌───────────────▼────────────────┐
│        MySQL (utf8mb4)           │
└────────────────────────────────┘
```

## Découpage des responsabilités

### Controllers (`app/Http/Controllers`)
- **Strictement HTTP** : reçoivent la requête, déléguent au service approprié, retournent une réponse (vue ou redirection).
- Aucune logique métier directe ; aucun accès direct à la DB autre que via Repository ou via Eloquent simple côté lecture.
- Segmentés par rôle métier : `Admin/`, `Organizer/`, `Jury/`, `Candidate/`, `Partner/`.

### Form Requests (`app/Http/Requests`)
- Centralisent la validation et l'autorisation (`authorize()`).
- Évite la duplication de règles dans plusieurs contrôleurs.

### Services (`app/Services`)
- **Logique métier pure** : transactions, calculs, déclenchement de notifications, observateurs.
- `ProgramService`, `ApplicationService`, `EvaluationService`, `SelectionService`, `ReportService`.
- Idéaux pour les tests unitaires.

### Repositories (`app/Repositories`)
- Interface (`Contracts/`) + implémentation Eloquent (`Eloquent/`).
- Permet d'**inverser la dépendance** : on peut substituer une autre source de données (cache, API externe) sans toucher au reste du code.
- Liaison via `RepositoryServiceProvider`.

### Models (`app/Models`)
- Eloquent + Soft Deletes + Casts forts (Enums PHP 8).
- Relations explicites (1-N, N-N, polymorphiques pour `documents` et `comments`).
- Scopes : `scopePublic`, `scopeAcceptingApplications`, `scopeArchived`...
- Boot hooks : génération auto des `slug`/`reference`.

### Policies (`app/Policies`)
- Une policy par ressource sensible : `ProgramPolicy`, `ApplicationPolicy`, `EvaluationPolicy`.
- Un `Gate::before` global accorde tous les droits au super-admin (`AuthServiceProvider`).
- Combiné avec **Spatie Permission** pour l'attribution des rôles.

### Observers (`app/Observers`)
- `ApplicationObserver` log les transitions de statut.
- Permettrait facilement d'envoyer des notifications côté Slack/Webhook.

### Notifications (`app/Notifications`)
- Mail + base de données : `ApplicationSubmittedNotification`, `ApplicationDecisionNotification`, `EvaluationAssignedNotification`.

### Enums (`app/Enums`)
- Sécurité de typage forte : `UserRole`, `ProgramStatus`, `ApplicationStatus`, `EvaluationStatus`.
- Méthodes utilitaires (`label()`, `color()`) pour le rendu.

## Principes appliqués

### SOLID

- **S**ingle Responsibility : chaque service traite un domaine ; chaque policy une ressource.
- **O**pen/Closed : les services dépendent d'interfaces de repository → on étend sans modifier.
- **L**iskov : les enums respectent la même API ; les policies s'enchaînent via `Gate::before`.
- **I**nterface Segregation : chaque repository expose une interface ciblée (pas de "god interface").
- **D**ependency Inversion : Controllers et Services dépendent d'interfaces, pas d'implémentations Eloquent.

### DRY
- Composants Blade réutilisables (`x-input`, `x-select`, `x-status-badge`, `x-stat-card`).
- `BaseRepository` factorise les CRUD.
- Form Requests partagés entre store/update.

### KISS
- Pas d'over-engineering : la couche service reste légère, les controllers ne dépassent pas une centaine de lignes.

## Sécurité

- **CSRF** sur tous les formulaires (Laravel default).
- **Rate-limiting** sur le login (5 tentatives, lockout dynamique).
- **Storage privé** pour les documents sensibles (CV, pièces d'identité) → disque `documents` non public, accès via URL signée si exposition nécessaire.
- **Policies + middleware role** sur chaque route.
- **CSP recommandée** en production via header (à ajouter dans middleware si nécessaire).

## Multi-tenancy / SaaS (évolution)

L'architecture actuelle est mono-tenant mais "SaaS-ready" :
- Ajouter un modèle `Foundation` (= tenant) avec `belongsToMany(User)` et `hasMany(Program)`.
- Activer l'option `teams` de Spatie Permission.
- Préfixer les routes par `/{foundation:slug}` ou utiliser un sous-domaine.
- Migrations à étendre avec `foundation_id` sur `programs`, `partners`, `users` (via pivot).
