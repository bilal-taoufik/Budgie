# Budgie Finance

Application web développée avec Laravel, PostgreSQL, Nginx et Docker.

## Prérequis

- Docker Desktop
- Docker Compose
- Git

## Cloner le projet

```bash
git clone <url-du-repo>
cd Budgie
```


## Configuration

```bash
cp laravel/.env.example laravel/.env
```

Puis :

```bash
docker compose -f docker-compose.local.yml up -d --build
docker compose -f docker-compose.local.yml exec app php artisan key:generate
```

## Lancement du projet

```bash
docker compose -f docker-compose.local.yml up -d --build
```

## Base de données

```bash
docker compose -f docker-compose.local.yml exec app php artisan migrate
```
## Installation complète

Copiez le fichier d'environnement :

```bash
cp laravel/.env.example laravel/.env
```

Construisez et démarrez les conteneurs :

```bash
docker compose -f docker-compose.local.yml up -d --build
```

Installez les dépendances PHP :

```bash
docker compose -f docker-compose.local.yml exec app composer install
```

Générez la clé Laravel :

```bash
docker compose -f docker-compose.local.yml exec app php artisan key:generate
```

Exécutez les migrations :

```bash
docker compose -f docker-compose.local.yml exec app php artisan migrate
```


## Accès

Application :

http://localhost

## Commandes utiles

```bash
docker compose -f docker-compose.local.yml exec app php artisan --version
docker compose -f docker-compose.local.yml exec app php artisan optimize:clear
```

## Arrêt

```bash
docker compose -f docker-compose.local.yml down
```

Suppression des volumes :

```bash
docker compose -f docker-compose.local.yml down -v
```

## Architecture

```text
Budgie
├── docker
├── laravel
├── Dockerfile
├── docker-compose.local.yml
└── README.md
```

## Stack

- Laravel
- PHP 8.3
- PostgreSQL 16
- Nginx
- Docker
