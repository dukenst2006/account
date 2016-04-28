FROM php:7-alpine

# add the application to the container
ADD . /var/www/html

# install extensions needed for Laravel
RUN apk update \
    && apk add \
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
    && rm /usr/local/bin/composer \
    && rm -rf /tmp/* \
    && rm -rf /tmp/src \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html