FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libonig-dev \
    && docker-php-ext-install curl fileinfo gettext mbstring exif mysqli pdo_mysql  

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
