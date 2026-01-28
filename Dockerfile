FROM php:7.2.2-apache

# Instala la extensión mysqli
RUN docker-php-ext-install mysqli

# Habilita módulos necesarios
RUN a2enmod rewrite
RUN a2enmod headers

# Fuerza que Apache use .htaccess en la carpeta del sitio
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf && \
    sed -i '/<Directory \/var\/www\/html\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configurar cabeceras de seguridad
RUN echo '<IfModule mod_headers.c>' > /etc/apache2/conf-available/security-headers.conf && \
    echo '  Header always set X-Frame-Options "DENY"' >> /etc/apache2/conf-available/security-headers.conf && \
    echo '  Header always set Content-Security-Policy "frame-ancestors '\''none'\''"' >> /etc/apache2/conf-available/security-headers.conf && \
    echo '  Header always set X-Content-Type-Options "nosniff"' >> /etc/apache2/conf-available/security-headers.conf && \
    echo '  Header always set X-XSS-Protection "1; mode=block"' >> /etc/apache2/conf-available/security-headers.conf && \
    echo '</IfModule>' >> /etc/apache2/conf-available/security-headers.conf && \
    a2enconf security-headers
    
# (Opcional) Reinicia Apache al iniciar el contenedor
CMD ["apache2-foreground"]
