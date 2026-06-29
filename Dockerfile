FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN mkdir -p database storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
RUN composer dump-autoload --optimize
RUN chmod -R 777 storage bootstrap/cache database

EXPOSE 10000

CMD touch database/database.sqlite \
    && php artisan migrate:fresh --seed --force \
    && php artisan config:clear \
    && php artisan view:clear \
    && php artisan serve --host=0.0.0.0 --port=10000