FROM php:7-alpine

# add the application to the container
ADD . /var/www/html

WORKDIR /var/www/html

# fix permissions in CI
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1001:1001:/' /etc/passwd \
    && sed -ri 's/^www-data:x:82:/www-data:x:1001:/' /etc/group \

    && apk add --update --no-cache \
        # needed for mcrypt
        libmcrypt \

        # needed for composer
        git zip unzip \

    # php extensions the app will need
    && docker-php-ext-install mcrypt mbstring pdo_mysql \

    # install composer
    && wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer \

    # give fpm the keys
    && chown -R www-data:www-data /var/www/html /usr/local/bin/composer \

    # add queue
    && mkdir -p /etc/service/queue \
    && echo "#!/usr/bin/env bash \n php /var/www/artisan queue:listen --sleep=3 --timeout=60" >> /etc/service/queue/run \
    && chmod +x /etc/service/queue/run \

USER www-data

# install dependencies
RUN composer install --no-interaction --prefer-dist

# clean everything needed from building to maintain
# as light of a container as possible
USER root

RUN apk del libmcrypt git zip unzip \
    && rm /usr/local/bin/composer










# install extensions needed for Laravel
RUN apk add --update --no-cache \
            # needed for mcrypt
            libmcrypt \

            # needed for composer
            git zip unzip \

    && docker-php-ext-install mcrypt pdo_mysql \

    # fix permissions in CI
    && sed -ri 's/^www-data:x:82:82:/www-data:x:1001:1001:/' /etc/passwd \
    && sed -ri 's/^www-data:x:82:/www-data:x:1001:/' /etc/group \

    # install composer
    && wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer \

    # add queue
    && mkdir -p /etc/service/queue \
    && echo "#!/usr/bin/env bash \n php /var/www/artisan queue:listen --sleep=3 --timeout=60" >> /etc/service/queue/run \
    && chmod +x /etc/service/queue/run \

    && cd /var/www/html \
    && composer install --no-interaction --prefer-dist \

    # clean everything needed from building to maintain
    # as light of a container as possible
    && apk del libmcrypt git zip unzip \
    && rm /usr/local/bin/composer