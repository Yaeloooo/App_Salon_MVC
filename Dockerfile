FROM php:8.1-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    libjpeg62-turbo \
    libpng-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev

# Instalar dependencias Node y hacer build de assets
RUN npm install && npm run build

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
