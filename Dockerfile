FROM php:7.2.2-apache

# Instala la extensión mysqli
RUN docker-php-ext-install mysqli

# Habilita módulos necesarios
RUN a2enmod rewrite
RUN a2enmod headers

# Fuerza que Apache use .htaccess en la carpeta del sitio
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf && \
    sed -i '/<Directory \/var\/www\/html\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
# Desactivar la cabecera X-Powered-By de PHP
RUN echo 'expose_php = Off' > /usr/local/etc/php/conf.d/security.ini

# Reinicia Apache al iniciar el contenedor
CMD ["apache2-foreground"]

