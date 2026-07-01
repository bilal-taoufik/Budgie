installation de laravel : docker compose exec app composer create-projet laravel/laravel .

permission bootstrap : docker compose -f docker-compose.local.yml exec app chmod -R a+rwX storage bootstrap/cache

générer une key .env : docker-compose -f docker-compose.local.yml exec app php artisan key:generate


lancement des containeur en local : docker compose -f docker-compose.local.yml up -d --build

migrer des nouvelles tables : docker compose -f docker-compose.local.yml exec app php artisan migrate:fresh

inistaliser les seeds : docker compose -f docker-compose.local.yml exec app php artisan migrate:fresh --seed


Figma : [Lien du Figma](https://www.figma.com/design/tZfCaCvDEsaIeNFKGXN2fF/Budgie---Gestion-de-finance?node-id=2091-462&t=iGtsQHm5gZn9fwFC-1)

Jira : [Lien du Jira](https://esgibudgie.atlassian.net/jira/software/projects/SCRUM/boards/1atlOrigin=eyJpIjoiMmYyNThiOTYzNGY2NDRhZTg4NDYyMmIyODVhNmE5YzkiLCJwIjoiaiJ9)


certificat utiliser : Certbot - delai 90 jours
hook utiliser pour le renouveller automatiquement : sudo certbot renew --deploy-hook "docker exec budgie_nginx_prod nginx -s reload"
voir si il ets active ou pas : systemctl status certbot.timer