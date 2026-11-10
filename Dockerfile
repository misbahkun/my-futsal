# syntax=docker/dockerfile:1

FROM node:20 AS node_builder
WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

FROM php:8.3-fpm AS base

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        curl \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        unzip \
        mariadb-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

FROM base AS vendor_builder

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev --optimize-autoloader

FROM base

COPY . /var/www/html
COPY --from=vendor_builder /var/www/html/vendor /var/www/html/vendor
COPY --from=node_builder /app/public /var/www/html/public

COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN rm -f /usr/bin/composer \
    && rm -f /etc/nginx/sites-enabled/* \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && mkdir -p /var/log/supervisor /run

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
