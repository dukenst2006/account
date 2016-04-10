FROM php:7-alpine

# install extensions needed for Laravel
RUN apk update \
    && docker-php-ext-install mcrypt mbstring mysqli \
    && rm /var/cache/apk/*