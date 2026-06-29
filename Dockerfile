FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# criar pastas necessárias
RUN mkdir -p storage/framework/cache \
 && mkdir -p storage/framework/sessions \
 && mkdir -p storage/framework/views \
 && mkdir -p bootstrap/cache

# instalar dependências PRIMEIRO
RUN composer install --no-dev --optimize-autoloader

# permissões
RUN chmod -R 777 storage bootstrap/cache

# só limpar cache DEPOIS de tudo existir
RUN php artisan optimize:clear || true

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000