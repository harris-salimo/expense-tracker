FROM composer:lts AS composer_deps

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction


FROM node:lts-alpine AS node_deps

WORKDIR /app

COPY package.json pnpm-lock.yaml pnpm-workspace.yaml ./

RUN npm install -g pnpm@10.6.2 && \
    pnpm install --frozen-lockfile

COPY . .

RUN pnpm run build


FROM dunglas/frankenphp:1-alpine AS production

RUN apk add --no-cache \
    bash \
    curl \
    file \
    libcurl \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    && rm -rf /var/cache/apk/* && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    zip \
    mbstring \
    curl \
    dom \
    fileinfo \
    bcmath \
    intl

COPY --from=composer_deps /app /app
COPY --from=node_deps /app/public /app/public

RUN chown -R frankenphp:frankenphp /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache \
    && chmod -R ug+rwx /app/storage /app/bootstrap/cache && \
    php artisan optimize:clear \
    && php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache \
    && php artisan view:cache

WORKDIR /app

EXPOSE 80

COPY ./docker/caddy/Caddyfile /etc/caddy/Caddyfile
