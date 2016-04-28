FROM php:7-fpm-alpine

# add the application to the container
COPY . /var/www/html

    # fix permissions on a mac
    #RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:50:/' /etc/passwd \
    #&& sed -ri 's/^www-data:x:82:/www-data:x:50:/' /etc/group \

    # fix permissions in CI
    RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1001:1001:/' /etc/passwd \
    && sed -ri 's/^www-data:x:82:/www-data:x:1001:/' /etc/group \
    && apk update \
        && apk add \
            # needed for mcrypt
            libmcrypt \

            # needed for composer
            git zip unzip \

        && docker-php-ext-install mcrypt mbstring pdo_mysql \
        # install composer
        && wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar \
        && chmod +x /usr/local/bin/composer \

        && cd /var/www/html \
        && composer install --no-interaction --prefer-dist \

        # give the keys to fpm
        && chown -R www-data:www-data /home/www-data /var/www/html \

        # clean everything needed from building to maintain
        # as light of a container as possible
        && apk del libmcrypt git zip unzip \
        && rm /usr/local/bin/composer \
        && rm -rf /tmp/* \
        && rm -rf /tmp/src \
        && rm -rf /var/cache/apk/*

WORKDIR /var/www/html