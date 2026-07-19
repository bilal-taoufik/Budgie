#!/bin/sh

# -e : arrête le script si une commande échoue
# -u : arrête le script si on utilise une variable non définie
set -eu

# Définit l'expression cron pour planifier les sauvegardes
backup_cron="${DB_BACKUP_CRON:-0 3 * * 0}" # tous les dimanches a 3h

# Crée la ligne de tâche cron et l'ajoute au fichier crontab root
printf '%s\n' "${backup_cron} /bin/sh /usr/local/bin/backup-database.sh >> /proc/1/fd/1 2>&1" > /etc/crontabs/root

echo "Sauvegarde PostgreSQL planifiée avec l'expression cron : ${backup_cron}"

# Lance le daemon cron en mode foreground (ne se met pas en arrière-plan)
# crond : le service cron (planificateur de tâches)
exec crond -f -l 2
