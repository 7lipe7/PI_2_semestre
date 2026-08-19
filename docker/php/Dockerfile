FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY public/ /var/www/html/
COPY src/ /var/www/src/

RUN chown -R www-data:www-data /var/www/html /var/www/src

EXPOSE 80