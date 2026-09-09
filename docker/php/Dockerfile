FROM php:8.4-apache

# Extensões PHP para conexão com MySQL
RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

# Permite o uso do .htaccess (front controller em public/index.php)
RUN { \
      echo '<Directory /var/www/html>'; \
      echo '    AllowOverride All'; \
      echo '</Directory>'; \
    } > /etc/apache2/conf-available/htaccess.conf \
 && a2enconf htaccess

WORKDIR /var/www/html

COPY public/ /var/www/html/
COPY src/ /var/www/src/

RUN chown -R www-data:www-data /var/www/html /var/www/src

EXPOSE 80