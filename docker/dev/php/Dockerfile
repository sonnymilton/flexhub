FROM php:8.2-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apk add zip libzip-dev libpq-dev icu-dev $PHPIZE_DEPS &&  \
    docker-php-ext-install zip pdo pdo_pgsql intl && \
    pecl install redis && docker-php-ext-enable redis

EXPOSE 9000

CMD ["php-fpm"]
