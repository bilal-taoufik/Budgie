installation de laravel : docker compose exec app composer create-projet laravel/laravel .

permission bootstrap : docker compose -f docker-compose.local.yml exec app chmod -R a+rwX storage bootstrap/cache

générer une key .env : docker-compose -f docker-compose.local.yml exec app php artisan key:generate


lancement des containeur en local : docker compose -f docker-compose.local.yml up -d --build

migrer des nouvelles tables : docker compose -f docker-compose.local.yml exec app php artisan migrate:fresh

inistaliser les seeds : docker compose -f docker-compose.local.yml exec app php artisan migrate:fresh --seed


Figma : [Lien du Figma](https://www.figma.com/design/tZfCaCvDEsaIeNFKGXN2fF/Budgie---Gestion-de-finance?node-id=2091-462&t=iGtsQHm5gZn9fwFC-1)