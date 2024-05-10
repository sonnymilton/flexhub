FROM node:21 as frontend-build
COPY /frontend /app
WORKDIR /app
RUN npm ci && npm run build

FROM php:8.2-fpm

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt update -y && \
    apt install -y zip libzip-dev libpq-dev libicu-dev nginx supervisor &&\
    docker-php-ext-install zip pdo pdo_pgsql intl opcache && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    rm -rf /var/lib/apt/lists/*

COPY /docker/app/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN rm /usr/local/etc/php-fpm.d/zz-docker.conf && \
    mkdir /var/run/php/ && chown www-data:www-data /var/run/php/  && \
    mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
    rm /etc/nginx/sites-enabled/default

COPY --chown=www-data:www-data /backend /application

COPY /docker/app/php/opcache.ini $PHP_INI_DIR/conf.d

COPY /docker/app/nginx/flex-server.conf /etc/nginx/conf.d/

COPY /docker/app/supervisor/messenger-worker.conf /etc/supervisor/conf.d/messenger-worker.conf

WORKDIR /application

ENV APP_ENV=prod
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=frontend-build /app/dist/ /application/public/

RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY /docker/app/entrypoint.sh /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
