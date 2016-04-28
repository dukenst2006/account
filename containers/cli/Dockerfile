FROM php:7-alpine

# add the application to the container
ADD . /var/www/html

# install extensions needed for Laravel
RUN apk update \
    && apk add libmcrypt-dev \
    && docker-php-ext-install mcrypt pdo_mysql \
    && rm /var/cache/apk/* \
    && touch /etc/crontabs/www-data

# fix permissions in CI
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1001:1001:/' /etc/passwd \
    && sed -ri 's/^www-data:x:82:/www-data:x:1001:/' /etc/group \
    && chown -R www-data:www-data /home/www-data /var/www/html /etc/crontabs/www-data \

    # install composer
    && wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer

USER www-data

    RUN cd /var/www/html \
        && composer install --no-interaction --prefer-dist \

        # setup crontab
        && echo "*       *       *       *       *       php /var/www/html/artisan schedule:run" >> /etc/crontabs/www-data

WORKDIR /var/www/html