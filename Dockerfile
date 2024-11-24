FROM php:7.2.2-apache
RUN echo "ErrorDocument 404 /404.html" >> /etc/apache2/apache2.conf
RUN echo "ServerSignature Off" >> /etc/apache2/apache2.conf
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf
RUN echo "expose_php = Off" >> /usr/local/etc/php/php.ini
RUN docker-php-ext-install mysqli
RUN a2enmod headers
RUN echo "Header set X-FRAME-OPTIONS \"SAMEORIGIN\"" >> /etc/apache2/conf-enabled/security.conf
RUN echo "Header unset X-Powered-By" >> /etc/apache2/conf-enabled/security.conf
RUN echo "Header set X-XSS-Protection \"1; mode=block\"" >> /etc/apache2/conf-enabled/security.conf
RUN echo "Header set X-Content-Type-Options nosniff" >> /etc/apache2/conf-enabled/security.conf
#RUN echo "Header always set Content-Security-Policy \"default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self';\"" >> /etc/apache2/conf-enabled/security.conf