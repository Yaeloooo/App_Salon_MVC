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

# Habilitar mod_rewrite para el Router
RUN a2dismod mpm_event || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Apuntar Apache a /public
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Build de assets con Gulp
RUN npm install && npm run build

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
