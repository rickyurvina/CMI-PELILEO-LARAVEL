# ----------------------
# Composer install step
# ----------------------
FROM composer:2.0 as build

WORKDIR /app

COPY composer.json composer.json
COPY database/ database/

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# ----------------------
# Assets install step
# ----------------------
FROM node:8-alpine as assets

WORKDIR /app

RUN mkdir resources
RUN mkdir public

COPY webpack.mix.js /app
COPY package.json /app
COPY resources /app/resources

# Install dependencies and compile assets
RUN npm install && npm run production

# ----------------------
# The FPM container
# ----------------------
FROM php:7.4-fpm

WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    zlib1g-dev \
    libicu-dev \
    g++ \
    libzip-dev \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY ./docker/www.conf /usr/local/etc/php-fpm.d/www.conf
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/php.ini "$PHP_INI_DIR/conf.d/php.ini"
COPY . /app
COPY --from=build /app/vendor/ /app/vendor/
COPY --from=assets /app/public/ /app/public/

RUN php artisan vendor:publish --force --tag=livewire:assets --ansi

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /app

# Change current user to www
USER www-data
