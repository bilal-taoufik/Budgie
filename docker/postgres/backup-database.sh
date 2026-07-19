#!/bin/sh

# -e : arrête le script si une commande échoue
# -u : arrête le script si on utilise une variable non définie
set -eu

# Définit le répertoire de sauvegarde
# Utilise DB_BACKUP_PATH si elle existe, sinon utilise /backups par défaut
backup_directory="${DB_BACKUP_PATH:-/backups}"
backup_file="${backup_directory}/${DB_DATABASE}_latest.dump"
temporary_file="${backup_file}.tmp"

mkdir -p "$backup_directory"
trap 'rm -f "$temporary_file"' EXIT

echo "Démarrage de la sauvegarde PostgreSQL vers ${backup_file}"

# Lance pg_dump pour exporter la base de données PostgreSQL
# PGPASSWORD : définit le mot de passe de connexion (variable d'environnement)
PGPASSWORD="$DB_PASSWORD" pg_dump \
    --host="$DB_HOST" \
    --port="${DB_PORT:-5432}" \
    --username="$DB_USERNAME" \
    --dbname="$DB_DATABASE" \
    --no-password \
    --format=custom \
    --clean \
    --if-exists \
    --file="$temporary_file"

test -s "$temporary_file"
mv -f "$temporary_file" "$backup_file"

echo "Sauvegarde PostgreSQL terminée : ${backup_file}"
