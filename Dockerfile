FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli
ADD app/index.php /var/www/html/