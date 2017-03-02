FROM php:7.1-fpm-alpine

# Copy the application files to the container
ADD . /var/www/html

WORKDIR /var/www/html

# fix permissions in CI
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:1000:/' /etc/passwd \
    && sed -ri 's/^www-data:x:82:/www-data:x:1000:/' /etc/group

# Install Composer dependencies
RUN apk add --update --no-cache git zip unzip \

        # needed for gd
        libpng-dev libjpeg-turbo-dev \

    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install mbstring pdo_mysql gd

# Install Composer
RUN php /var/www/html/infrastructure/install_composer.php

# Ownership of the app dir for www-data
RUN chown -R www-data:www-data /var/www/html /home/www-data/

# Run composer as www-data
USER www-data

RUN mkdir -p /home/www-data/.composer/cache

# parallel dependency installation
RUN composer global require hirak/prestissimo \
    # production-ready dependencies
    && composer install  --no-interaction --optimize-autoloader --no-dev --prefer-dist

RUN rm -rf /home/www-data/.composer/cache

# Switch back to root
USER root

# Remove composer installation dependencies
RUN apk del git zip unzip

USER www-data