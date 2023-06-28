FROM php:8-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --chown=www-data:www-data src /var/www/html
