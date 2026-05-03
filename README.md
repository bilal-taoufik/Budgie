# Budgie

Configuration Docker pour deployer une application Laravel situee dans `laravel/` avec PHP-FPM, Nginx, PostgreSQL et Redis.

## Prerequis

- Docker
- Docker Compose
- Une application Laravel placee dans `laravel/`

## Demarrage

```bash
cp .env.docker.example laravel/.env
```

Renseigne ensuite `APP_KEY`, `APP_URL` et `DB_PASSWORD`.

Si ton projet Laravel existe deja, tu peux generer la cle avec:

```bash
docker compose run --rm app php artisan key:generate --show
```

Puis lance les services:

```bash
docker compose up -d --build
```

Application:

```text
http://localhost:8080
```

## Commandes utiles

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan optimize:clear
docker compose exec app composer install
docker compose logs -f app nginx
```

## Services

- `app`: PHP 8.3 FPM avec les extensions Laravel courantes
- `nginx`: serveur web expose sur `APP_PORT`
- `postgres`: base PostgreSQL 16 persistante
- `redis`: cache, sessions et queues
- `queue`: worker Laravel
- `scheduler`: execution de `schedule:run` chaque minute

## Migration automatique

En production, garde `RUN_MIGRATIONS=false` par defaut.

Pour lancer les migrations automatiquement au demarrage:

```env
RUN_MIGRATIONS=true
```
