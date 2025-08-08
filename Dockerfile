# dockerfile pour héberger le projet sinfed sur render
FROM php:8.2-apache

# définir le répertoire de travail
WORKDIR /var/www/html

# copier le projet dans le container
COPY . /var/www/html/

# installer extensions mysql/php
RUN docker-php-ext-install mysqli pdo pdo_mysql

# permissions (au cas où)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
