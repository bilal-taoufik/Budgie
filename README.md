<p align="center">
  <img src="docs/logo.svg" width="96" alt="Logo Budgie">
</p>

<p align="center">
  <strong>Votre partenaire financier personnel</strong><br>
  Gérez vos comptes, suivez vos revenus et dépenses, et anticipez votre situation financière.
</p>

<p align="center">
  <a href="https://budgiefinance.fr">Application</a> ·
  <a href="https://www.figma.com/design/tZfCaCvDEsaIeNFKGXN2fF/Budgie---Gestion-de-finance?node-id=2091-462&t=iGtsQHm5gZn9fwFC-1">Figma</a> ·
  <a href="https://esgibudgie.atlassian.net/jira/software/projects/SCRUM/boards/1">Jira</a>
</p>

## Présentation

Budgie est une application web de gestion de finances personnelles développée dans le cadre d'un projet ESGI. Elle fonctionne sans connexion directe à un établissement bancaire : l'utilisateur garde le contrôle sur les informations qu'il enregistre.

## Fonctionnalités

- inscription, vérification d'adresse e-mail, connexion et réinitialisation du mot de passe ;
- séparation des espaces client et administrateur ;
- gestion complète des comptes financiers ;
- gestion des revenus et dépenses ponctuels ou récurrents ;
- recherche par nom ou description ;
- mise à jour automatique des soldes ;
- prévisions avec intérêts, imposition et capitalisation ;
- tableau de bord avec statistiques et graphiques ;
- administration des utilisateurs ;
- sauvegarde hebdomadaire de PostgreSQL ;
- application annuelle automatisée des intérêts.

Les fonctionnalités bonus du sujet — partage de comptes, abonnements Stripe et exceptions — ne font pas partie du périmètre retenu.

## Technologies

- PHP 8.3 et Laravel ;
- PostgreSQL 16 ;
- Blade, SCSS, JavaScript et Chart.js ;
- Vite ;
- Docker Compose, PHP-FPM et Nginx ;
- Mailpit pour les e-mails en local ;
- Certbot et Let's Encrypt en production.

## Installation locale

### Prérequis

- Docker Desktop avec Docker Compose ;
- Git ;
- les ports `8000`, `5173`, `5432`, `8025` et `1025` disponibles.

### 1. Préparer le projet

```bash
git clone https://github.com/bilal-taoufik/Budgie.git
cd Budgie
cp src/.env.example src/.env
```

Configurer au minimum ces variables dans `src/.env` :

```dotenv
APP_NAME=Budgie
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=budgie
DB_USERNAME=budgie
DB_PASSWORD=mot_de_passe_local

POSTGRES_DB=budgie
POSTGRES_USER=budgie
POSTGRES_PASSWORD=mot_de_passe_local

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS=noreply@budgie.fr
```

### 2. Démarrer et initialiser l'application

```bash
docker compose -f docker-compose.local.yml up -d --build
docker compose -f docker-compose.local.yml exec app composer install
docker compose -f docker-compose.local.yml exec app npm install
docker compose -f docker-compose.local.yml exec app php artisan key:generate
docker compose -f docker-compose.local.yml exec app php artisan migrate --seed
```

### 3. Lancer Vite

```bash
docker compose -f docker-compose.local.yml exec app npm run dev -- --host 0.0.0.0
```

Services disponibles :

| Service | Adresse |
|---|---|
| Application | http://localhost:8000 |
| Mailpit | http://localhost:8025 |
| PostgreSQL | `localhost:5432` |
| Vite | http://localhost:5173 |

## Consulter la base avec HeidiSQL

En local, HeidiSQL est utilisé pour consulter et administrer la base PostgreSQL.

Créer une nouvelle session PostgreSQL avec les informations suivantes :

| Paramètre | Valeur |
|---|---|
| Hôte | `127.0.0.1` |
| Port | `5432` |
| Utilisateur | valeur de `POSTGRES_USER` |
| Mot de passe | valeur de `POSTGRES_PASSWORD` |
| Base | valeur de `POSTGRES_DB` |

Le conteneur PostgreSQL doit être démarré avant d'ouvrir la connexion :

```bash
docker compose -f docker-compose.local.yml up -d postgres
```

## Tests

La suite couvre notamment l'authentification, les rôles, les profils, l'administration, les comptes, les transactions, les soldes, les prévisions et l'isolation des données.

```bash
docker compose -f docker-compose.local.yml exec -T app php artisan test
```

État actuel : **52 tests réussis et 219 assertions**.

## Déploiement en production

Vérifier d'abord la configuration de production dans `src/.env` :

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://budgiefinance.fr
```

Le script de déploiement automatise la construction Docker, Composer, npm, Vite, les migrations, les caches Laravel et le démarrage des services :

```bash
sh scripts/deploy-prod.sh
```

Les commentaires présents dans le script expliquent chaque étape.

## Tâches automatiques

### Intérêts annuels

Le service Docker `scheduler` exécute le scheduler Laravel. La commande suivante est lancée automatiquement le 31 décembre à 00:00, heure de Paris :

```bash
php artisan accounts:interet
```

Pour vérifier la planification :

```bash
docker compose -f docker-compose.prod.yml exec -T app php artisan schedule:list
```

### Sauvegarde PostgreSQL

Le service `postgres-backup` crée une sauvegarde chaque dimanche à 03:00. Une seule sauvegarde valide est conservée : la nouvelle remplace l'ancienne dans `backups/`.

Configuration facultative dans `src/.env` :

```dotenv
DB_BACKUP_CRON="0 3 * * 0"
```

Sauvegarde manuelle :

```bash
docker compose -f docker-compose.prod.yml run --rm --entrypoint /bin/sh postgres-backup /usr/local/bin/backup-database.sh
```

Restauration :

```bash
docker compose -f docker-compose.prod.yml exec -T postgres pg_restore --clean --if-exists -U "$DB_USERNAME" -d "$DB_DATABASE" < backups/budgie_latest.dump
```

## Sécurité

- protection CSRF des formulaires ;
- mots de passe hachés et règles de complexité ;
- validation avec des `FormRequest` ;
- contrôle des rôles et de la propriété des données ;
- isolation stricte des comptes et transactions ;
- expiration des tokens de vérification et de réinitialisation ;
- HTTPS en production ;
- secrets stockés dans `.env`, exclu de Git.

## Structure du projet

```text
Budgie/
├── docker/                  # Nginx et sauvegardes PostgreSQL
├── docs/                    # Ressources de documentation
├── scripts/                 # Automatisation du déploiement
├── docker-compose.local.yml
├── docker-compose.prod.yml
├── Dockerfile
└── src/
    ├── app/                 # Logique Laravel
    ├── database/            # Migrations, factories et seeders
    ├── resources/           # Vues, SCSS et JavaScript
    ├── routes/              # Routes web et commandes
    └── tests/               # Tests unitaires et fonctionnels
```

## Équipe

Projet réalisé par **Bilal Taoufik** et **Zakaria Bouguera** avec GitHub, Jira, Figma.
