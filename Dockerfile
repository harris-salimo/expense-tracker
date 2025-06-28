FROM composer:lts AS composer_deps

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts


FROM node:lts-alpine AS node_deps

WORKDIR /app

COPY . .

COPY --from=composer_deps /app/vendor /app/vendor

RUN npm install -g pnpm@10.6.2 && \
    pnpm install --frozen-lockfile

RUN pnpm run build


FROM dunglas/frankenphp:1-alpine AS production

RUN apk add --no-cache \
    bash \
    curl \
    curl-dev \
    file \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    zip \
    mbstring \
    curl \
    dom \
    fileinfo \
    bcmath \
    intl

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /app

COPY --from=node_deps /app /app

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache \
    && chmod -R ug+rwx /app/storage /app/bootstrap/cache

RUN cp .env.example .env \
    && php artisan optimize:clear \
    && php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache \
    && php artisan view:cache

RUN php artisan migrate --force

EXPOSE 80

COPY ./docker/caddy/Caddyfile /etc/caddy/Caddyfile
