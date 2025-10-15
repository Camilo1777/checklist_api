FROM php:8.1-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y libzip-dev unzip git zlib1g-dev && docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copiar código
COPY . /var/www/html

# Copiar composer desde la imagen oficial y ejecutar install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
