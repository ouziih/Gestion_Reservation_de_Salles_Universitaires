FROM composer:2.8 AS dependencies

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader

FROM php:8.3-fpm

WORKDIR /var/www

RUN docker-php-ext-install pdo_mysql \
    && echo 'clear_env = no' >> /usr/local/etc/php-fpm.d/www.conf

COPY --from=dependencies /app/vendor ./vendor
COPY composer.json composer.lock ./
COPY config ./config
COPY database ./database
COPY src ./src
COPY public ./public

CMD ["php-fpm", "-F"]
