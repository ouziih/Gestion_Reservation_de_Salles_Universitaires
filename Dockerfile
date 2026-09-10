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

COPY --from=dependencies /app/vendor ./vendor
COPY composer.json composer.lock ./
COPY src ./src
COPY public ./public

CMD ["php-fpm", "-F"]
