FROM php:8.2-fpm-alpine

ARG USER
ARG USER_ID

WORKDIR /var/www/remindme

RUN apk --update add \
        libzip-dev \
        unzip \
        libpng-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        supervisor \
        bash \
    && rm -rf /var/cache/apk/* \
    && docker-php-ext-install zip pdo pdo_mysql bcmath \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j "$(nproc)" gd

RUN apk add --no-cache autoconf gcc g++ make pcre-dev $phpize_deps \
    && pecl install redis \
    && docker-php-ext-enable redis.so

RUN set -x ; \
    addgroup -g $USER_ID -S $USER ; \
    adduser -u $USER_ID -h /home/$USER -D $USER -S -G www-data www-data && exit 0 ; exit 1
RUN mkdir -p /home/$USER/.composer && \
    chown -R $USER:$USER /home/$USER

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/remindme

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --quiet

RUN chown -R $USER:www-data /var/www/remindme && \
    chmod -R 775 /var/www/remindme

COPY .docker/crontab/crontab /var/spool/cron/crontabs/root
COPY .docker/crontab/entrypoint.bash /usr/sbin
RUN chmod a+x /usr/sbin/entrypoint.bash

RUN touch /var/www/remindme/storage/logs/cron.log

ENTRYPOINT /usr/sbin/entrypoint.bash

USER $USER
