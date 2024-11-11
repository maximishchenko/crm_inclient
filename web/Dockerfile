FROM php:7.1-apache

RUN apt update && apt upgrade -y && apt install -y libzip-dev zip
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install zip

RUN docker-php-ext-install mysqli pdo_mysql