FROM php:7-fpm-alpine

# install extensions needed for Laravel
RUN apk update \
    && apk add libmcrypt-dev \
    && docker-php-ext-install mcrypt pdo_mysql \
    && rm /var/cache/apk/*