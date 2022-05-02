FROM php:7.4-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug-3.1.3 && docker-php-ext-enable xdebug


WORKDIR /var/www/laravel_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get -y update
RUN apt-get -y install git
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip


