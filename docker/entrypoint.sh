#!/usr/bin/env sh
set -e

APP_DIR=/var/www/budgie
mkdir -p "$APP_DIR"
cd "$APP_DIR"

if [ "$1" = "php-fpm" ] && { [ ! -f artisan ] || [ ! -f composer.json ] || [ ! -f bootstrap/app.php ]; } && [ "${AUTO_INSTALL_LARAVEL:-false}" = "true" ]; then
  echo "Laravel not found, creating a new project..."
  rm -rf /tmp/laravel-bootstrap
  composer create-project laravel/laravel /tmp/laravel-bootstrap --no-interaction --prefer-dist --no-scripts

  for path in app bootstrap config database public resources routes tests; do
    if [ -e "/tmp/laravel-bootstrap/${path}" ]; then
      cp -Rf "/tmp/laravel-bootstrap/${path}" "$APP_DIR/"
    fi
  done

  for file in .editorconfig .gitattributes .gitignore .npmrc artisan composer.json composer.lock package.json package-lock.json phpunit.xml vite.config.js; do
    if [ -e "/tmp/laravel-bootstrap/${file}" ] && [ ! -e "$APP_DIR/${file}" ]; then
      cp -R "/tmp/laravel-bootstrap/${file}" "$APP_DIR/${file}"
    fi
  done

  rm -rf /tmp/laravel-bootstrap

  if [ -f composer.json ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
  fi

  if [ -f artisan ]; then
    php artisan key:generate --force --no-interaction || true
  fi
fi

if [ -f artisan ]; then
  if [ -d /opt/budgie-public ]; then
    cp -a /opt/budgie-public/. public/
  fi

  mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  chmod -R ug+rw storage bootstrap/cache

  if [ -f composer.json ] && [ ! -f vendor/autoload.php ]; then
    if [ "$1" = "php-fpm" ]; then
      mkdir -p vendor/composer
      find vendor/composer -name 'tmp-*.zip' -delete 2>/dev/null || true
      composer install --no-interaction --prefer-dist --optimize-autoloader
    else
      echo "Waiting for Composer dependencies..."
      until [ -f vendor/autoload.php ]; do
        sleep 2
      done
    fi
  fi

  if [ -f .env ] && grep -q '^APP_KEY=$' .env; then
    php artisan key:generate --force --no-interaction || true
  fi

  if [ -f .env ]; then
    APP_KEY_VALUE="$(grep '^APP_KEY=' .env | tail -n 1 | cut -d '=' -f 2-)"
    if [ -n "${APP_KEY_VALUE}" ]; then
      export APP_KEY="${APP_KEY_VALUE}"
    fi
  fi

  php artisan config:clear --no-interaction || true
  php artisan route:clear --no-interaction || true
  php artisan view:clear --no-interaction || true

  if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force --no-interaction
  fi

  if [ "${APP_ENV:-production}" = "production" ]; then
    php artisan config:cache --no-interaction || true
    php artisan route:cache --no-interaction || true
    php artisan view:cache --no-interaction || true
  fi
fi

exec "$@"
