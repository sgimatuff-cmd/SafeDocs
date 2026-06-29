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

# Instalar dependências sem correr scripts (evita tentar ligar à BD antes da hora)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar o resto do código da aplicação
COPY . .

# Criar rigorosamente TODAS as pastas de cache que o Laravel exige
RUN mkdir -p storage/app/public \
    && mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p bootstrap/cache

# Finalizar a instalação do Composer (gerar o autoloader definitivo)
RUN composer dump-autoload --optimize

# Dar permissões totais de leitura e escrita para o root e para o servidor web
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

# O truque: Limpar e gerar o cache APENAS quando o container iniciar, 
# garantindo que as pastas estão prontas no runtime
CMD php artisan config:clear && php artisan view:clear && php artisan serve --host=0.0.0.0 --port=10000