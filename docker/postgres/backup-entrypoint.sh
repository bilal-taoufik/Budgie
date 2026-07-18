#!/bin/sh

set -eu

backup_cron="${DB_BACKUP_CRON:-0 3 * * 0}"

printf '%s\n' "${backup_cron} /bin/sh /usr/local/bin/backup-database.sh >> /proc/1/fd/1 2>&1" > /etc/crontabs/root

echo "Sauvegarde PostgreSQL planifiée avec l'expression cron : ${backup_cron}"

exec crond -f -l 2
