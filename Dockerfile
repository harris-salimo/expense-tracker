FROM node:22-alpine AS builder

WORKDIR /app

COPY package.json pnpm-lock.yaml ./

RUN npm install -g pnpm && \
    pnpm install --frozen-lockfile

COPY public/ public/
COPY resources/ resources/
COPY vite.config.ts ./

RUN pnpm build

FROM richarvey/nginx-php-fpm:3.1.6

RUN apk update && \
    apk add --no-cache postgresql-dev && \
    docker-php-ext-configure pgsql pdo_pgsql && \
    docker-php-ext-install pgsql pdo_pgsql

COPY . .

# Image config
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Laravel config
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]
