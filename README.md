# Budgie

Budgie est une application web de gestion de finances personnelles. Elle permet à un utilisateur de suivre ses comptes, ses dépenses et ses revenus sans connecter directement l'application à un établissement bancaire.

L'application a été réalisée dans le cadre d'un projet ESGI. Les fonctionnalités bonus du sujet (exceptions, partage de comptes et abonnements Stripe) ne font pas partie du périmètre retenu.

## Liens du projet

- Application : [https://budgiefinance.fr](https://budgiefinance.fr)
- Dépôt GitHub : [github.com/bilal-taoufik/Budgie](https://github.com/bilal-taoufik/Budgie)
- Maquettes Figma : [Budgie - Gestion de finance](https://www.figma.com/design/tZfCaCvDEsaIeNFKGXN2fF/Budgie---Gestion-de-finance?node-id=2091-462&t=iGtsQHm5gZn9fwFC-1)
- Suivi Jira : [Board SCRUM Budgie](https://esgibudgie.atlassian.net/jira/software/projects/SCRUM/boards/1)

## Fonctionnalités

### Authentification

Un visiteur peut :

- créer un compte client ;
- confirmer son adresse e-mail ;
- demander un nouvel e-mail de vérification ;
- se connecter et se déconnecter ;
- demander un lien de réinitialisation de mot de passe ;
- choisir un nouveau mot de passe avec un token sécurisé.

Les mots de passe doivent contenir au minimum 12 caractères, avec une majuscule, une minuscule, un chiffre et un symbole. Ils sont stockés sous forme de hash et ne sont jamais enregistrés en clair.

### Comptes financiers

Chaque client peut créer, afficher, modifier et supprimer ses propres comptes. Un compte contient :

- un nom court ;
- une description ;
- un solde ;
- un taux annuel de rémunération ;
- un taux d'imposition ;
- une date de création.

La suppression d'un compte supprime également les transactions qui lui sont associées.

### Dépenses et revenus

Les dépenses et revenus sont rattachés à un compte appartenant à l'utilisateur connecté. Une transaction contient :

- un nom court et une description ;
- un montant ;
- un type : `depense` ou `revenu` ;
- une date d'effet ;
- une date de fin facultative ;
- une fréquence : ponctuelle, mensuelle, semestrielle ou annuelle.

À la création, à la modification ou à la suppression d'une transaction, le solde du compte est recalculé avec la différence réellement applicable jusqu'à aujourd'hui.

Les pages Dépenses et Revenus disposent d'un filtre par nom ou description. La recherche reste limitée aux transactions appartenant à l'utilisateur connecté.

### Prévisions

La page Prévisions calcule l'état des comptes à la fin du mois sélectionné.

Le calcul :

1. part du solde actuel du compte ;
2. ajoute ou retire uniquement les échéances futures, pour éviter un double comptage ;
3. applique les transactions récurrentes jusqu'à la date projetée ;
4. convertit le taux annuel de rémunération en taux mensuel ;
5. calcule chaque mois les intérêts bruts, l'imposition et les intérêts nets ;
6. capitalise les intérêts nets dans le solde du mois suivant.

Les prévisions sont strictement limitées aux comptes de l'utilisateur connecté.

### Tableau de bord client

Le tableau de bord présente :

- le solde total ;
- les revenus et dépenses du mois ;
- les derniers comptes ;
- les dernières échéances ;
- l'évolution du solde ;
- la répartition des dépenses ;
- une comparaison des revenus et dépenses.

### Administration

Un administrateur dispose d'un espace protégé par rôle. Il peut :

- consulter les statistiques générales ;
- afficher la liste des utilisateurs ;
- créer un autre administrateur ;
- supprimer un utilisateur, sauf son propre compte depuis la liste ;
- modifier ses informations et son mot de passe ;
- supprimer son compte si un autre administrateur existe.

## E-mails et durées de sécurité

| Élément | Durée | Fonctionnement |
|---|---:|---|
| Session utilisateur | 120 minutes | Expiration après 120 minutes d'inactivité. La fermeture du navigateur ne force pas immédiatement l'expiration. |
| Vérification d'e-mail | 24 heures | Un token aléatoire est créé à l'inscription. Un nouveau lien peut être demandé après expiration. |
| Réinitialisation du mot de passe | 60 minutes | Le token est stocké sous forme sécurisée dans `password_reset_tokens`. |
| Nouvelle demande de reset | 60 secondes | Limitation appliquée entre deux créations de token. |
| Confirmation sensible | 3 heures | Valeur Laravel `AUTH_PASSWORD_TIMEOUT` disponible ; Budgie ne possède actuellement pas de page `/confirm-password` dédiée. |

### Liens envoyés

- Vérification : `/verify-email/{token}`
- Mot de passe oublié : `/forgot-password`
- Réinitialisation : `/reset-password/{token}?email=adresse`

Les e-mails utilisent des templates Blade Budgie :

- `resources/views/mail/welcome.blade.php` ;
- `resources/views/mail/verify.blade.php` ;
- `resources/views/mail/reset-password.blade.php`.

En local, les messages sont consultables dans Mailpit à l'adresse [http://localhost:8025](http://localhost:8025).

## Sécurité

Les principales protections mises en place sont :

- protection CSRF sur les formulaires ;
- validation des données avec des `FormRequest` ;
- hash des mots de passe ;
- régénération de session après connexion ;
- invalidation de la session à la déconnexion ;
- contrôle des rôles `admin` et `customer` ;
- requêtes Eloquent paramétrées ;
- vérification de propriété avant modification ou suppression ;
- isolation des comptes, transactions et prévisions par utilisateur ;
- tokens d'e-mail aléatoires avec date d'expiration ;
- variables sensibles dans `.env`, exclu de Git ;
- blocage par Nginx de l'accès aux fichiers cachés ;
- HTTPS avec redirection automatique du port 80 vers le port 443.

## Conformité au sujet

Le tableau suivant concerne uniquement le périmètre obligatoire, hors bonus.

| Exigence | État | Implémentation |
|---|---|---|
| Identification | Conforme | Inscription, vérification e-mail, connexion, session et déconnexion. |
| CRUD comptes | Conforme | Création, liste, modification, suppression et affichage du solde. |
| CRUD dépenses | Conforme | Gestion complète, rattachement à un compte et filtre. |
| CRUD revenus | Conforme | Gestion complète, rattachement à un compte et filtre. |
| Prévisions | Conforme | Échéances futures, intérêts mensuels, imposition et capitalisation. |
| Figma et intégration | Conforme | Maquettes accessibles depuis le lien Figma. |
| Déploiement | Conforme dans la configuration | Docker, PHP-FPM, PostgreSQL, Nginx, domaine et HTTPS. |
| Gestion de projet | Conforme | Historique Git et board Jira SCRUM. |
| Sécurité | Conforme sur les contrôles principaux | CSRF, validation, rôles, isolation, hash et HTTPS. |
| Fréquence « tous les N mois » | Partiel | Les fréquences disponibles sont 1, 6 ou 12 mois et ponctuelle. Une valeur N libre reste à ajouter. |

## Architecture technique

- PHP 8.3
- Laravel
- PostgreSQL 16
- Blade
- SCSS et Vite
- JavaScript et Chart.js
- Docker Compose
- Nginx et PHP-FPM
- Mailpit en développement
- Certbot / Let's Encrypt en production

Organisation principale :

```text
Budgie/
├── docker/                     # Configuration Nginx
├── docker-compose.local.yml    # Environnement local
├── docker-compose.prod.yml     # Environnement de production
├── Dockerfile                  # PHP 8.3, Composer et Node.js
└── src/
    ├── app/                    # Modèles, contrôleurs, requêtes, mails
    ├── config/                 # Configuration Laravel
    ├── database/               # Migrations et factories
    ├── resources/              # Vues Blade, SCSS et JavaScript
    ├── routes/                 # Routes web, auth et console
    └── tests/                  # Tests Feature et Unit
```

## Installation locale

### Prérequis

- Docker Desktop avec Docker Compose ;
- Git ;
- ports `8000`, `5173`, `5432`, `8025` et `1025` disponibles.

### 1. Cloner et configurer

```bash
git clone https://github.com/bilal-taoufik/Budgie.git
cd Budgie
cp src/.env.example src/.env
```

Dans `src/.env`, configurer au minimum PostgreSQL :

```dotenv
APP_NAME=Budgie
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=budgie
DB_USERNAME=budgie
DB_PASSWORD=mot_de_passe_local

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS=noreply@budgie.fr
```

Les variables PostgreSQL sont également utilisées par le conteneur `postgres`. Elles doivent donc être renseignées.

### 2. Démarrer les conteneurs

```bash
docker compose -f docker-compose.local.yml up -d --build
```

### 3. Installer et initialiser l'application

```bash
docker compose -f docker-compose.local.yml exec app composer install
docker compose -f docker-compose.local.yml exec app npm install
docker compose -f docker-compose.local.yml exec app php artisan key:generate
docker compose -f docker-compose.local.yml exec app php artisan migrate
```

En cas de problème de permissions Linux :

```bash
docker compose -f docker-compose.local.yml exec app chmod -R a+rwX storage bootstrap/cache
```

### 4. Compiler les assets

Développement avec rechargement automatique :

```bash
docker compose -f docker-compose.local.yml exec app npm run dev -- --host 0.0.0.0
```

Compilation de production :

```bash
docker compose -f docker-compose.local.yml exec app npm run build
```

### 5. Accéder aux services

- Application : [http://localhost:8000](http://localhost:8000)
- Mailpit : [http://localhost:8025](http://localhost:8025)
- Vite : [http://localhost:5173](http://localhost:5173)
- PostgreSQL : `localhost:5432`

## Commandes utiles

```bash
# État des conteneurs
docker compose -f docker-compose.local.yml ps

# Appliquer les migrations
docker compose -f docker-compose.local.yml exec app php artisan migrate

# Recréer la base de développement
docker compose -f docker-compose.local.yml exec app php artisan migrate:fresh --seed

# Vider les caches Laravel
docker compose -f docker-compose.local.yml exec app php artisan optimize:clear

# Afficher les routes
docker compose -f docker-compose.local.yml exec app php artisan route:list

# Ouvrir un shell dans PHP
docker compose -f docker-compose.local.yml exec app bash
```

## Tests

Les tests métier couvrent notamment :

- le CRUD et les autorisations des comptes ;
- le CRUD des dépenses et revenus ;
- l'isolation entre utilisateurs ;
- les filtres nom/description ;
- les fréquences et dates de fin ;
- la sécurité et le calcul des prévisions ;
- le parcours de réinitialisation du mot de passe.

Commande ciblée sur les fonctionnalités Budgie maintenues :

```bash
docker compose -f docker-compose.local.yml exec -T app php artisan test \
  tests/Feature/Customer \
  tests/Feature/Auth/PasswordResetTest.php \
  tests/Unit/TransactionFrequencyTest.php
```

Les anciens tests Laravel générés à la création du projet peuvent encore référencer des routes Breeze non utilisées (`/profile`, `/confirm-password`) ou l'ancien champ `email_verified_at`. Ils doivent être adaptés au système d'authentification personnalisé avant d'utiliser `php artisan test` sans filtre.

## Déploiement

La production utilise `docker-compose.prod.yml` avec :

- PHP-FPM dans le conteneur `app` ;
- PostgreSQL dans le conteneur `postgres` ;
- Nginx sur les ports 80 et 443 ;
- redirection HTTP vers HTTPS ;
- certificats Let's Encrypt montés depuis `/etc/letsencrypt`.

Démarrage :

```bash
docker compose -f docker-compose.prod.yml up -d --build
```

Renouvellement du certificat et rechargement de Nginx :

```bash
sudo certbot renew --deploy-hook "docker exec budgie_nginx_prod nginx -s reload"
```

Vérification du renouvellement automatique :

```bash
systemctl status certbot.timer
```

## Données non incluses dans Git

Les éléments suivants ne doivent pas être ajoutés au dépôt :

- fichiers `.env` ;
- dépendances `vendor` et `node_modules` ;
- logs Laravel ;
- sessions et caches ;
- certificats et clés privées ;
- build Vite généré.

Utiliser uniquement `.env.example` pour documenter les variables nécessaires, sans valeur sensible.

## Équipe et méthode

Le projet est organisé avec Git/GitHub pour les versions et contributions, Jira pour le suivi SCRUM et Figma pour la conception graphique. L'historique complet des contributions est conservé dans le dossier `.git` demandé par le sujet.

## Sauvegardes automatiques

En production, le service `postgres-backup` sauvegarde PostgreSQL chaque dimanche à 03:00. Une seule sauvegarde compressée est conservée dans le dossier `backups/` du serveur : chaque nouvelle sauvegarde valide remplace la précédente.

La planification est configurable dans `src/.env` :

```dotenv
DB_BACKUP_CRON="0 3 * * 0"
```

Pour lancer une sauvegarde manuellement :

```bash
docker compose -f docker-compose.prod.yml run --rm --entrypoint /bin/sh postgres-backup /usr/local/bin/backup-database.sh
```

Pour restaurer une sauvegarde :

```bash
docker compose -f docker-compose.prod.yml exec -T postgres pg_restore --clean --if-exists -U "$DB_USERNAME" -d "$DB_DATABASE" < backups/Budgie_latest.dump
```
## Déploiement automatisé

Le script `scripts/deploy-prod.sh` exécute dans l'ordre :

1. construction et mise à jour des images Docker ;
2. démarrage et attente de PostgreSQL ;
3. `composer install` optimisé sans dépendances de développement ;
4. `npm ci` puis compilation Vite avec `npm run build` ;
5. nettoyage des anciens caches Laravel ;
6. migrations avec `php artisan migrate --force` ;
7. création des caches de configuration, routes et vues ;
8. démarrage de PHP-FPM, Nginx, du scheduler et de la sauvegarde PostgreSQL.

Avant le premier déploiement, vérifier que `APP_ENV=production` est défini dans `src/.env`. Depuis la racine du projet sur le serveur :

```bash
sh scripts/deploy-prod.sh
```

Le service `scheduler` exécute continuellement `php artisan schedule:work`. La commande `accounts:interet` est planifiée automatiquement le 31 décembre à 00:00, heure de Paris.
