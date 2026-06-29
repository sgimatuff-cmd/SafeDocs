FROM php:8.2-cli

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar extensões PHP necessárias para Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar projeto
COPY . .

# Instalar dependências Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissões necessárias
RUN chmod -R 777 storage bootstrap/cache

# Gerar chave da aplicação (vai falhar se .env não estiver no deploy, mas Render resolve depois)
RUN php artisan config:clear

# Expor porta do Render
EXPOSE 10000

# Comando de arranque
CMD php artisan serve --host=0.0.0.0 --port=10000