# Documentation technique

## Conventions de code

- PHP 8.2+, **strict types** (`declare(strict_types=1)`) en tête de chaque fichier `app/`.
- PSR-12 (Pint configuré : `composer require laravel/pint --dev` + `vendor/bin/pint`).
- PHPDoc systématique sur les classes publiques, paramètres complexes, retours non triviaux.
- Casts forts dans les Models, **Enums backed strings** pour tous les statuts métier.

## Stack & dépendances clés

| Package | Usage |
|---------|-------|
| `spatie/laravel-permission` | Rôles + permissions |
| `spatie/laravel-activitylog` | Audit log automatique |
| `maatwebsite/excel` | Export Excel des classements |
| `barryvdh/laravel-dompdf` | Export PDF |

## Architecture des permissions

```
Gate::before (AuthServiceProvider) → Admin = tout autorisé
   │
   ├─ Spatie Role middleware (route-level)
   │     ├─ role:admin
   │     ├─ role:admin|organizer
   │     └─ etc.
   │
   └─ Policy (resource-level)
         ├─ ProgramPolicy::update
         ├─ ApplicationPolicy::view
         └─ EvaluationPolicy::update
```

Les Policies vérifient des règles fines (ex : un organisateur ne peut éditer que les programmes auxquels il est associé via `program_user`).

## Calcul du score pondéré

```php
weighted_score = Σ(score_critère × poids_critère) / Σ(poids_critère)
```

Calculé dans `EvaluationService::submit()` puis :
- `evaluations.weighted_score` mis à jour
- `applications.average_score` recalculé via `ApplicationService::recomputeScores()` = moyenne des `weighted_score` des évaluations soumises.

## Formulaire dynamique

Les champs sont stockés dans `application_fields` avec :
- `type` : détermine le widget Blade et les règles de validation
- `validation_rules` (JSON) : règles supplémentaires (ex. `['mimes:pdf','max:10240']`)
- `options` (JSON) : pour `select`, `radio`, `multiselect` au format `[{label, value}]`

À l'enregistrement, `ApplicationField::buildValidationRules()` reconstruit dynamiquement les rules pour Laravel Validation.

## Exports

- **Excel** : `App\Exports\ApplicationsExport` (Maatwebsite). Téléchargeable depuis la page Sélection.
- **PDF** : `resources/views/exports/ranking-pdf.blade.php` rendu via DomPDF. Format A4, branding fondation.

## Notifications (canaux)

Toutes les notifications héritent de `Illuminate\Notifications\Notification` et déclarent `via()` retournant `['mail', 'database']` :
- **mail** : SMTP/Postmark/Resend en production
- **database** : table `notifications`, exposable dans le topbar (cloche)

## Queues

- `QUEUE_CONNECTION=database` par défaut (table `jobs`).
- En production : `QUEUE_CONNECTION=redis` recommandé + `Supervisor` pour `php artisan queue:work`.

## Performance

- **Indexes** sur les colonnes filtrées/triées fréquemment (cf. DATABASE_SCHEMA.md).
- **Eager loading** systématique dans les Repositories (`with(['candidate', 'evaluations'])`).
- **Pagination** sur toutes les listes (15-20 items).
- **Cache** des permissions Spatie (24h par défaut, à invalider sur changement de rôle).

## Tests

- `php artisan test` lance unit + feature.
- Tests utilisent SQLite in-memory (cf. `phpunit.xml`).
- `RefreshDatabase` pour isolement des tests.
- Couverture : services métiers (Application, Evaluation, Selection), accès par rôle, authentification.

## Pistes d'évolution

- **Multi-tenancy SaaS** : ajouter un modèle `Foundation`, activer `teams` Spatie, isolation par tenant.
- **API** : exposer les ressources clés via Sanctum (déjà installé) + API Resources.
- **2FA** : Laravel Fortify ou Jetstream.
- **Recherche full-text** : Laravel Scout + Meilisearch/Algolia pour les candidatures.
- **Dashboard temps réel** : Laravel Echo + Reverb/Pusher.
- **Matching jury intelligent** : algorithme d'attribution équilibrée des candidatures aux jurys.
- **Anonymisation des dossiers** côté jury (option) pour réduire les biais.
