FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-install zip xml

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 750 /var/www/html/Data \
    && chmod -R 750 /var/www/html/import \
    && chmod -R 750 /var/www/html/debug \
    && chmod -R 750 /var/www/html/include/Settings

RUN rm -f /var/www/html/android.php \
    /var/www/html/idtime.php \
    /var/www/html/stempelterminal.php \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80
# CMD ["apache2-foreground"]
