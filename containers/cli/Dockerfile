FROM php:7.0-alpine
LABEL application="laravel"

# add the application to the container
ADD . /var/www/html

WORKDIR /var/www/html

# Fix permissions in CI
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:1000:/' /etc/passwd \
    && sed -ri 's/^www-data:x:82:/www-data:x:1000:/' /etc/group \

    &&  apk add --update --no-cache \

        # needed for composer
        git zip unzip \

        # needed for gd
        libpng-dev libjpeg-turbo-dev \

    # php extensions the app will need
    && docker-php-ext-install mbstring pdo_mysql gd \

        # needed for forking processes in laravel queues as of Laravel 5.3
        pcntl \

    # install composer
    && wget -O /usr/local/bin/composer http://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer \

    # give cli the keys
    && chown -R www-data:www-data /var/www/html /usr/local/bin/composer \

    # redirect logs to stdout
    && ln -sf /dev/stderr /var/www/html/storage/logs/laravel.log \

    # run laravel's scheduled commands as www-data
    && echo "*       *       *       *       *       php /var/www/html/artisan schedule:run" > /etc/crontabs/www-data

USER www-data

# install dependencies - including dev so we can run seeders
RUN composer install --no-interaction --prefer-dist

# clean everything needed from building to maintain
# as light of a container as possible
USER root

RUN apk del git zip unzip