FROM php:7.2-fpm as production

COPY --from=composer:2.1.6 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y zip unzip

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && \
    apt-get install -y locales && \
    echo "ja_JP UTF-8" > /etc/locale.gen && \
    locale-gen && \
    echo "export LANG=ja_JP.UTF-8" >> ~/.bashrcÏ

WORKDIR /var/www

