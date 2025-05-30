FROM composer:2.4 as build
COPY . /app/
RUN composer update --ignore-platform-req=ext-gd
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=ext-gd

FROM php:8.2.2-apache-buster as dev

ENV APP_ENV=dev
ENV APP_DEBUG=true
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y zip libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    ssl-cert

RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install pdo pdo_mysql zip gd

COPY docker/ssl/apache.crt /etc/apache2/ssl/apache.crt
COPY docker/ssl/apache.key /etc/apache2/ssl/apache.key

COPY . /var/www/html/
COPY --from=build /usr/bin/composer /usr/bin/composer
RUN composer install --prefer-dist --no-interaction

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .env.dev /var/www/html/.env

RUN php artisan config:cache && \
    php artisan route:cache && \
    chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite

RUN a2enmod rewrite ssl

FROM php:8.2.2-apache-buster as production

ENV APP_ENV=production
ENV APP_DEBUG=false

RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql gd
COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY docker/ssl/apache.crt /etc/apache2/ssl/apache.crt
COPY docker/ssl/apache.key /etc/apache2/ssl/apache.key

COPY --from=build /app /var/www/html
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .env.prod /var/www/html/.env

RUN php artisan config:cache && \
    php artisan route:cache && \
    chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite
RUN a2enmod rewrite ssl

ENV TZ="Asia/Jakarta"
