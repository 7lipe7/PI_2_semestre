
FROM php:8.4-apache

ENV TZ=America/Sao_Paulo

RUN docker-php-ext-install pdo pdo_mysql \
      && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
      && echo "$TZ" > /etc/timezone

# Erros somente nos logs
RUN printf 'display_errors=Off\nlog_errors=On\nerror_log=/dev/stderr\n' \
      > /usr/local/etc/php/conf.d/zz-app.ini

RUN a2enmod rewrite \
      && printf '<Directory /var/www/html>\n\
      Options -Indexes\n\
      AllowOverride All\n\
      Require all granted\n\
      </Directory>\n' > /etc/apache2/conf-available/app-security.conf \
      && a2enconf app-security

WORKDIR /var/www/html

COPY --chown=root:root public/ /var/www/html/
COPY --chown=root:root src/ /var/www/src/


RUN find /var/www/html /var/www/src -type d -exec chmod 755 {} \; \
      && find /var/www/html /var/www/src -type f -exec chmod 644 {} \;

EXPOSE 80
