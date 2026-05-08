# ProgramFlow — Plateforme de gestion des programmes de la Fondation Bénianh

ProgramFlow est une application web Laravel 11 conçue pour centraliser, gérer et automatiser l'ensemble du cycle de vie d'un programme d'une fondation : création, candidatures, évaluation par jury, sélection finale, suivi des sessions, archivage.

Le cas pilote est le programme **Leadership Féminin** de la Fondation Bénianh, mais l'architecture est multi-programme et conçue pour évoluer en SaaS multi-fondations.

## ✨ Fonctionnalités

- **Gestion des programmes** : CRUD complet, dates clés, objectifs, places, partenaires, intervenants
- **Formulaire de candidature dynamique** : champs configurables par programme (texte, fichiers, vidéo, etc.)
- **Espace candidate** : tableau de bord, candidatures en brouillon, suivi de statut
- **Module Jury** : attribution, grille d'évaluation pondérée, calcul automatique des scores
- **Sélection finale** : classement automatique, présélection, validation manuelle, exports PDF/Excel
- **Sessions du programme actif** : formations, ateliers, présences, comptes rendus, tâches
- **Espace partenaires** : accès restreint, consultation
- **Reporting** : tableaux de bord, taux de sélection, graphiques
- **Archivage** : historique consultable
- **Notifications** : emails (confirmation, décision) + notifications internes
- **Sécurité** : CSRF, validation côté serveur, fichiers uploadés protégés sur disque privé, rate-limiting login

## 🛠️ Stack

- **Backend** : Laravel 11 (PHP 8.2+)
- **Frontend** : Blade + Tailwind CSS + Alpine.js
- **DB** : MySQL 8 (utf8mb4)
- **Auth** : Laravel Breeze (Blade)
- **Permissions** : Spatie Laravel Permission
- **Logs** : Spatie Laravel Activity Log
- **Exports** : Maatwebsite Excel + DomPDF

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [docs/INSTALLATION.md](docs/INSTALLATION.md) | Guide d'installation pas-à-pas (Laragon + production) |
| [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) | Architecture, couches, design patterns |
| [docs/DATABASE_SCHEMA.md](docs/DATABASE_SCHEMA.md) | Schéma de base de données |
| [docs/USER_GUIDE.md](docs/USER_GUIDE.md) | Guide utilisateur par rôle |
| [docs/TECHNICAL.md](docs/TECHNICAL.md) | Notes techniques avancées (extensions SaaS, perf) |

## 🚀 Démarrage rapide

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate

# Renseigner DB_* dans .env, puis :
php artisan migrate --seed
php artisan storage:link

npm run dev          # en dev
php artisan serve
```

Comptes de démo (mot de passe `password`) :

| Rôle | Email |
|------|-------|
| Admin | admin@programflow.test |
| Organisateur | organizer@programflow.test |
| Jury | jury1@programflow.test |
| Candidate | candidate@programflow.test |
| Partenaire | partner@programflow.test |

## 🧪 Tests

```bash
php artisan test
```

## 📄 Licence

MIT — © {{ year }} Fondation Bénianh.
