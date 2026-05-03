FROM node:22-alpine AS assets

WORKDIR /app

COPY laravel/package*.json ./

RUN if [ -f package.json ]; then \
      if [ -f package-lock.json ]; then npm ci; else npm install; fi; \
    fi

COPY laravel .

RUN mkdir -p public/build \
  && if [ -f package.json ]; then npm run build; fi

FROM php:8.3-fpm-alpine

ARG APP_ENV=production

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libpng-dev \
    libpq-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-client \
    shadow \
    unzip \
    zip \
  && docker-php-ext-configure intl \
  && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pdo_pgsql \
    zip \
  && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del .phpize-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/99-opcache.ini

COPY . .
COPY --from=assets /app/public/build ./laravel/public/build

RUN mkdir -p laravel/storage laravel/bootstrap/cache \
  && chown -R www-data:www-data laravel/storage laravel/bootstrap/cache \
  && chmod -R ug+rw laravel/storage laravel/bootstrap/cache \
  && if [ -f laravel/composer.json ] && [ -f laravel/bootstrap/app.php ]; then \
      (cd laravel && \
      composer install \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        $(if [ "$APP_ENV" = "production" ]; then echo "--no-dev"; fi)); \
    fi \
  && chown -R www-data:www-data laravel/storage laravel/bootstrap/cache \
  && chmod -R ug+rw laravel/storage laravel/bootstrap/cache \
  && chmod +x /var/www/html/docker/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["sh", "/var/www/html/docker/entrypoint.sh"]
CMD ["php-fpm"]
