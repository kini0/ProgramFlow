# Guide d'installation

## Prérequis

- **PHP 8.2+** (extensions : pdo_mysql, mbstring, openssl, gd, zip, fileinfo, xml)
- **Composer 2.x**
- **MySQL 8.x** (ou MariaDB 10.6+)
- **Node.js 18+** et **npm**
- **Laragon** (Windows, recommandé) ou tout serveur LAMP/LEMP équivalent

## Installation locale (Laragon)

1. **Cloner le projet** dans `C:\laragon\www\ProgramFlow` (déjà fait dans votre cas).

2. **Installer les dépendances PHP** :
   ```bash
   composer install
   ```

3. **Installer les dépendances JS** :
   ```bash
   npm install
   ```

4. **Configurer l'environnement** :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Éditer `.env` :
   ```
   DB_DATABASE=programflow
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Créer la base** dans phpMyAdmin (ou en CLI) :
   ```sql
   CREATE DATABASE programflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

6. **Lancer les migrations + seeders** :
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

7. **Compiler les assets** :
   ```bash
   npm run dev      # en dev (hot-reload)
   # ou
   npm run build    # production
   ```

8. **Accéder à l'application** : Laragon configure automatiquement `http://programflow.test` (Auto Virtual Hosts). Sinon, lancer :
   ```bash
   php artisan serve
   ```
   et ouvrir `http://localhost:8000`.

## Comptes de démonstration

Tous les comptes générés ont pour mot de passe **`password`**.

| Rôle | Email |
|------|-------|
| Administrateur | admin@programflow.test |
| Organisateur | organizer@programflow.test |
| Jury | jury1@programflow.test, jury2@programflow.test |
| Candidate | candidate@programflow.test (+ 15 candidates fictives) |
| Partenaire | partner@programflow.test |

## Mise en production

1. Configurer un serveur web (Nginx/Apache) qui pointe sur le dossier `public/`.
2. `.env` :
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```
3. Optimisations :
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   npm run build
   ```
4. Configurer un mailer transactionnel (`MAIL_MAILER=smtp` ou Postmark/Resend dans `.env`).
5. Configurer un worker queue :
   ```bash
   php artisan queue:work
   ```
   (utilisez Supervisor pour le maintenir actif).
6. Configurer le scheduler dans cron :
   ```
   * * * * * cd /var/www/programflow && php artisan schedule:run >> /dev/null 2>&1
   ```

## Dépannage courant

- **Erreur "Personal access client not found"** → ignorer (Sanctum non utilisé en mode session).
- **Storage permissions** : `chmod -R 775 storage bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache`.
- **Vite manifest not found** : exécuter `npm run build` ou `npm run dev`.
