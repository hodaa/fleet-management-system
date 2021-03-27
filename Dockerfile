FROM php:7.4-fpm

RUN apt-get update && apt-get install -y  nano
RUN docker-php-ext-install -j$(nproc) pdo_mysql

ADD . /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod 777 /var/www/html/storage
