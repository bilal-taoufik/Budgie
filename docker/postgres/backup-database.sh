#!/bin/sh

set -eu

backup_directory="${DB_BACKUP_PATH:-/backups}"
backup_file="${backup_directory}/${DB_DATABASE}_latest.dump"
temporary_file="${backup_file}.tmp"

mkdir -p "$backup_directory"
trap 'rm -f "$temporary_file"' EXIT

echo "Démarrage de la sauvegarde PostgreSQL vers ${backup_file}"

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
