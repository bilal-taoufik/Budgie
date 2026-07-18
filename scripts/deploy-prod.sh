#!/bin/sh

# Arrête immédiatement le déploiement si une commande échoue
# ou si une variable obligatoire n'est pas définie.
set -eu

# Fichiers utilisés pour le déploiement de production.
compose_file="docker-compose.prod.yml"
env_file="src/.env"

# Vérifie que le fichier contenant les variables de production existe.
if [ ! -f "$env_file" ]; then
    echo "Erreur : $env_file est introuvable."
    exit 1
fi

# Empêche de lancer accidentellement ce script avec la configuration locale.
if ! grep -Eq '^APP_ENV=production$' "$env_file"; then
    echo "Erreur : APP_ENV doit être défini à production dans $env_file."
    exit 1
fi

# Reconstruit les images et récupère les versions récentes des images de base.
echo "[1/9] Construction des images Docker"
docker compose -f "$compose_file" build --pull

# Démarre d'abord la base, car les migrations en auront besoin.
echo "[2/9] Démarrage de PostgreSQL"
docker compose -f "$compose_file" up -d postgres

# Attend jusqu'à 60 secondes que PostgreSQL accepte les connexions.
echo "[3/9] Attente de PostgreSQL"
attempt=0
until docker compose -f "$compose_file" exec -T postgres sh -c 'pg_isready -U "$POSTGRES_USER" -d "$POSTGRES_DB"' >/dev/null 2>&1; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge 30 ]; then
        echo "Erreur : PostgreSQL n'est pas prêt après 60 secondes."
        exit 1
    fi
    sleep 2
done

# Installe uniquement les dépendances PHP nécessaires en production
# et optimise l'autoload de Composer.
echo "[4/9] Installation des dépendances PHP de production"
docker compose -f "$compose_file" run --rm --no-deps app \
    composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

# npm ci utilise exactement les versions enregistrées dans package-lock.json.
echo "[5/9] Installation des dépendances front-end"
docker compose -f "$compose_file" run --rm --no-deps app npm ci

# Compile le SCSS et le JavaScript avec Vite pour la production.
echo "[6/9] Compilation des assets Vite"
docker compose -f "$compose_file" run --rm --no-deps app npm run build

# Supprime les anciens caches avant de mettre à jour la base de données.
echo "[7/9] Nettoyage des anciens caches et migration de la base"
docker compose -f "$compose_file" run --rm app php artisan optimize:clear

# Applique uniquement les migrations qui n'ont pas encore été exécutées.
# --force est obligatoire lorsque Laravel fonctionne en production.
docker compose -f "$compose_file" run --rm app php artisan migrate --force

# Reconstruit les caches Laravel pour accélérer l'application.
echo "[8/9] Création des caches Laravel de production"
docker compose -f "$compose_file" run --rm app php artisan config:cache
docker compose -f "$compose_file" run --rm app php artisan route:cache
docker compose -f "$compose_file" run --rm app php artisan view:cache

# Autorise PHP à écrire dans les dossiers de cache et de logs Laravel.
docker compose -f "$compose_file" run --rm --no-deps app chmod -R ug+rwX storage bootstrap/cache

# Redémarre tous les services avec le nouveau code : application, tâches
# planifiées, serveur web et sauvegarde hebdomadaire de PostgreSQL.
echo "[9/9] Démarrage des services de production"
docker compose -f "$compose_file" up -d --force-recreate --remove-orphans \
    app scheduler nginx postgres-backup

# Affiche l'état final des conteneurs pour vérifier le déploiement.
echo "Déploiement terminé."
docker compose -f "$compose_file" ps
