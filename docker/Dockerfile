FROM php:8.3.3-cli-alpine

# This is a holding image for later

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY . /app

WORKDIR /app

RUN apk update && \
    apk add git zlib-dev zip unzip libxml2-dev libzip-dev poppler-utils yq && \
    docker-php-ext-install zip && \
    docker-php-ext-install xml && \
    docker-php-ext-install bcmath

RUN echo "memory_limit=1024M" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    php composer.phar install --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative  --no-interaction && \
    php composer.phar clear-cache && \
    rm -rf /usr/src/php

ENTRYPOINT ["php", "./screenjson"]