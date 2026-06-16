installation de laravel : docker compose exec app composer create-projet laravel/laravel .

permission bootstrap : docker compose -f docker-compose.local.yml exec app chmod -R a+rwX storage bootstrap/cache

générer une key .env : docker-compose -f docker-compose.local.yml exec app php artisan key:generate


lancement des containeur en local : docker compose -f docker-compose.local.yml up -d --build
