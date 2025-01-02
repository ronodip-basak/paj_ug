FROM php:8.3-apache

RUN apt-get update -y && apt-get install -y openssl zip unzip git

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/laravel

COPY . .

RUN php composer.phar install

RUN php artisan storage:link

ENV APACHE_DOCUMENT_ROOT /var/www/laravel/public/

RUN chown -R www-data:www-data .

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN service apache2 restart