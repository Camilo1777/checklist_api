FROM php:8.1-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Instala dependencias del sistema y extensiones PHP comunes
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
		libzip-dev zlib1g-dev libonig-dev libicu-dev unzip git \
	&& docker-php-ext-install pdo pdo_mysql mbstring zip intl \
	&& rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copiar código
COPY . /var/www/html

# Copiar composer desde la imagen oficial y ejecutar install (fallar si hay errores)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Copiar entrypoint que ajusta Apache al puerto indicado por $PORT
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exponer el puerto por defecto (Apache usa 80; entrypoint actualizará si RAILWAY asigna otro)
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
