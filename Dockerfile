FROM php:8.2
FROM composer:2.5.5

RUN docker-php-ext-install mysqli pdo pdo_mysql
