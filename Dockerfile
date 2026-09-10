FROM php:8.3-fpm

WORKDIR /var/www

COPY public ./public

CMD ["php-fpm", "-F"]
