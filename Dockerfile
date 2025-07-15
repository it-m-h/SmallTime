FROM php:8.2-apache

WORKDIR /var/www/html

COPY . /var/www/html/

RUN apt-get update && apt-get install -y \
       libzip-dev \
       libxml2-dev \
    && docker-php-ext-install zip xml \
    && a2enmod rewrite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 750 /var/www/html/Data \
       /var/www/html/import \
       /var/www/html/debug \
       /var/www/html/include/Settings \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80
# CMD ["apache2-foreground"]
