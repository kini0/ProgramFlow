# Schéma de base de données

## Vue d'ensemble

```
users ──────────┬─────────── applications ─── application_responses ─── application_fields
                │                  │                                         │
                │                  ├─── evaluations ─── evaluation_scores ── evaluation_criteria
                │                  │                                         │
                ├──── programs ────┴─── program_sessions ─── attendances     │
                │       │                  │                                 │
                │       │                  └────── tasks                    │
                │       └─── partner_program ─── partners                    │
                │       └─── program_user (organizer/jury/participant)       │
                │                                                            │
                └─── documents (polymorphique)                                │
                └─── comments  (polymorphique)
```

## Tables principales

### `users`
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint pk | |
| first_name, last_name | string | |
| email | string unique | |
| password | string | hashé |
| phone, gender, date_of_birth, country, city | divers | |
| avatar_path | string nullable | |
| is_active | boolean | indexé |
| bio, preferences (json) | | |
| last_login_at | timestamp | |
| timestamps + softDeletes | | |

### `programs`
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint pk | |
| title, slug (unique) | string | |
| short_description, description, objectives, eligibility | text | |
| seats | smallint | nb de participantes |
| application_opens_at, application_closes_at | date | indexés ensemble |
| starts_at, ends_at | date | |
| status | enum | indexé · cf. `ProgramStatus` |
| is_featured | boolean | |
| settings | json | extensions |
| created_by | fk users | |
| timestamps + softDeletes | | |

### `applications`
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint pk | |
| reference | string unique | format `PF-2026-XXXXXX` |
| program_id, user_id | fk + unique pair | une seule candidature par programme |
| status | enum indexé | |
| motivation, project_summary | text | |
| average_score, evaluations_count | decimal/int | mis à jour par `EvaluationService` |
| submitted_at, reviewed_at, decided_at | timestamps | |
| decided_by | fk users | |
| decision_reason | text | |
| meta | json | extensions |

### `application_fields`
Définition dynamique des champs du formulaire candidate par programme (one-to-many).
Type : `text`, `textarea`, `email`, `tel`, `url`, `date`, `number`, `select`, `multiselect`, `checkbox`, `radio`, `file`, `video`.
`options` (json), `validation_rules` (json), `is_required` (bool), `order_column`.

### `application_responses`
Une réponse par champ et par candidature (`unique [application_id, application_field_id]`).
Stocke `value` (string) ou `value_json` (array sérialisé).

### `evaluation_criteria`
Critères de notation par programme : `label`, `description`, `weight` (coefficient), `max_score`.

### `evaluations`
Affectation d'un membre du jury à une candidature (`unique [application_id, jury_id]`).
Statut : `assigned`, `in_progress`, `submitted`. Score final `total_score` + `weighted_score`.

### `evaluation_scores`
Note par critère pour une évaluation.

### `program_sessions`
Sessions du programme actif (formation, atelier, mentoring, événement). Lieu/online, facilitateur, compte rendu.

### `attendances`
Présence par session : `present`, `absent`, `excused`, `late`. Marquée par un organisateur.

### `tasks`
Tâches assignables par programme/session : `todo`, `in_progress`, `done`, `cancelled`. Priorité `low/medium/high`.

### `partners`
Partenaires institutionnels/financiers/techniques/médias. Association optionnelle à un compte utilisateur (`user_id`).

### Tables pivot
- `partner_program` (many-to-many programme ↔ partenaire avec rôle)
- `program_user` (many-to-many programme ↔ user avec rôle : organizer/jury/mentor/speaker/participant)

### `documents` (polymorphique)
Stocke tout fichier attaché à une candidature, un programme, etc.
`disk` = `documents` (privé) par défaut.

### `comments` (polymorphique)
Commentaires internes (jury/organisateur) ou publics, sur n'importe quelle ressource.

### Tables système
- `notifications` (database channel Laravel)
- `password_reset_tokens`, `sessions`, `cache`, `cache_locks`
- `jobs`, `job_batches`, `failed_jobs` (queues)
- `activity_log` (Spatie Activity Log)
- `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` (Spatie Permission)

## Indexes critiques

- `users.email` (unique)
- `programs.slug` (unique), `programs.status`, composite `(application_opens_at, application_closes_at)`
- `applications.reference` (unique), composite `[program_id, user_id]` unique, `applications.status`, `[program_id, status]`, `submitted_at`
- `evaluations` unique `[application_id, jury_id]`, `status`
- `attendances` unique `[program_session_id, user_id]`
- `application_fields` unique `[program_id, key]`
