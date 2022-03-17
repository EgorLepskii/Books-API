FROM php:7.4-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug-3.1.3 && docker-php-ext-enable xdebug


WORKDIR /var/www/laravel_docker

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

