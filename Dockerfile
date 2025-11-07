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

#Ocultar versión y sistema operativo
RUN echo 'ServerTokens Prod' > /etc/apache2/conf-available/security.conf && \
    #Evita la firma de servidor en páginas de error
    echo 'ServerSignature Off' >> /etc/apache2/conf-available/security.conf && \
    #Configuración de cabeceras. La cabecera Server se escribe antes de tomar en cuenta el archivo .htacces
    #Por esta razón se pone esta configuración en el Dockerfile, y se copia a un archivo .conf
    #Oculta la información de cabecera 'Server' y pone 'SecureServer' en vez de eso.
    echo '<IfModule mod_headers.c>' >> /etc/apache2/conf-available/security.conf && \
    echo '  Header unset Server' >> /etc/apache2/conf-available/security.conf && \
    echo '  Header always set Server "SecureServer"' >> /etc/apache2/conf-available/security.conf && \
    echo '</IfModule>' >> /etc/apache2/conf-available/security.conf && \
    a2enconf security

# Reinicia Apache al iniciar el contenedor
CMD ["apache2-foreground"]
