FROM php:7.2.2-apache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install mysqli

# Copiar una configuración de Apache personalizada que permite .htaccess
# y establece permisos adecuados para el documento raíz
COPY apache2.conf /etc/apache2/apache2.conf

# Asegurar permisos correctos en /var/www/html
RUN chown -R www-data:www-data /var/www/html \
	&& chmod -R 755 /var/www/html