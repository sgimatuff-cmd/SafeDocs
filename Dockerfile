FROM php:8.2-cli

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copiar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar ficheiros de dependências primeiro para aproveitar a cache do Docker
COPY composer.json composer.lock ./

# Instalar dependências sem correr scripts
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar o resto do código da aplicação
COPY . .

# Criar pastas essenciais do Laravel
RUN mkdir -p database \
    && mkdir -p storage/app/public \
    && mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p bootstrap/cache

# Gerar o autoloader final do Composer
RUN composer dump-autoload --optimize

# Dar permissões totais para evitar erros de escrita
RUN chmod -R 777 storage bootstrap/cache database

EXPOSE 10000

# O TRUQUE: Cria o ficheiro SQLite vazio, corre as migrações com os seeders e inicia o servidor
CMD touch database/database.sqlite \
    && php artisan migrate:fresh --seed --force \
    && php artisan config:clear \
    && php artisan view:clear \
    && php artisan serve --host=0.0.0.0 --port=10000